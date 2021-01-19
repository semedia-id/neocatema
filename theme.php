<?php
namespace Grav\Theme;

use Gantry\Framework\Gantry;
use Gantry\Framework\Theme as GantryTheme;
use Grav\Common\Theme;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;

class Neocatema extends Theme
{
	public $gantry = '5.4.0';

	/**
	 * @var GantryTheme
	 */
	protected $theme;

	/**
	 * @return array
	 */
	public static function getSubscribedEvents()
	{
		return [
			'onThemeInitialized' => ['onThemeInitialized', 0],
			'onAdminMenu' => ['onAdminMenu', 0],
			'onGetPageTemplates' => ['onGetPageTemplates', 0],
			'onGetPageBlueprints' => ['onGetPageBlueprints', 0],
			'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
			'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
			'onTwigExtensions' => ['onTwigExtensions', 0],
			'onOutputGenerated' => ['onOutputGenerated', 0],
//			'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
//			 'onFormProcessed' => ['onFormProcessed', 0],
		];
	}

	public function onAdminMenu()
	{

		$theme_name = $this->name;

		$this->grav['assets']->add("user://themes/$theme_name/admin/poko.css",1);
		$this->grav['assets']->add("user://themes/$theme_name/js/w3color/w3color.js");
		$this->grav['assets']->add("user://themes/$theme_name/admin/poko.js");

	}

	public function onThemeInitialized()
	{
		if (defined('GRAV_CLI') && GRAV_CLI) {
			return;
		}

		$locator = $this->grav['locator'];
		$path = $locator('theme://');
		$name = $this->name;

		if (!class_exists('\Gantry5\Loader')) {
			if ($this->isAdmin()) {
				$messages = $this->grav['messages'];
				$messages->add('Please enable Gantry 5 plugin in order to use current theme!', 'error');
				return;
			} else {
				throw new \LogicException('Please install and enable Gantry 5 Framework plugin!');
			}
		}

		// Setup Gantry 5 Framework or throw exception.
		\Gantry5\Loader::setup();

		// Get Gantry instance.
		$gantry = Gantry::instance();

		// Set the theme path from Grav variable.
		$gantry['theme.path'] = $path;
		$gantry['theme.name'] = $name;

		// Define the template.
		require $locator('theme://includes/theme.php');

		// Define Gantry services.

		$gantry['theme'] = function ($c) {
			return new \Gantry\Theme\Neoca($c['theme.path'], $c['theme.name']);
		};
	}

	/*
	public function onShortcodeHandlers()
	{
		$theme_name = $this->name;
		$this->grav['shortcode']->registerAllShortcodes("user://themes/$theme_name/php/shortcodes");
	}
	*/

	public function onTwigSiteVariables()
	{
		$locator = $this->grav['locator'];
		$adminCookieSuffix = '-admin';
		$this->adminCookie = session_name() . $adminCookieSuffix;

		$this->config->set('buf',[]);

		/* Provide {{ config.gas }} */

		if (isset($_COOKIE[$this->adminCookie]) === true) {
			$this->config->set('gas.admin',true);
		}

		$log_path = $locator('log://popularity');

		if (file_exists($log_path . '/daily.json')) {
			$val = array_values((array)json_decode(file_get_contents($log_path . '/daily.json')))[0];
			$this->config->set('gas.stat.daily',$val);
		}

		if (file_exists($log_path . '/monthly.json')) {
			$this->config->set('gas.stat.monthly',array_values((array)json_decode(file_get_contents($log_path . '/monthly.json')))[0]);
		}

		if (file_exists($log_path . '/totals.json')) {
			$this->config->set('gas.stat.total',array_values((array)json_decode(file_get_contents($log_path . '/totals.json')))[0]);
		}

	}

	public function onTwigExtensions()
	{
		require_once(__DIR__.'/php/GaskenTwigExtension.php');
		$this->grav['twig']->twig->addExtension(new GaskenTwigExtension());
		
		require_once(__DIR__.'/php/ncc-TwigExtension.php');
		$this->grav['twig']->twig->addExtension(new nccTwigExtension());
		
		//require_once(__DIR__.'/php/ColorMixerTwigExtension.php');
		//$this->grav['twig']->twig->addExtension(new ColorMixerTwigExtension());
	}


	/*
	 * So you can make templae outside the theme for a client needs
	 */

	public function onTwigTemplatePaths()
	{

		require_once(__DIR__.'/php/ncc-util.php');

		$locator = $this->grav['locator'];
		$theme_name = $this->name;

		create_ifnotexists($locator('user://').'/templates');
		create_ifnotexists($locator('user://').'/blueprints');
		create_ifnotexists($locator('user://').'/workspace/css');
		create_ifnotexists($locator('user://').'/workspace/scss');
		create_ifnotexists($locator('user://').'/workspace/js');
		create_ifnotexists($locator('user://')."/data/gantry5/themes/$theme_name/particles");
		create_ifnotexists($locator('user://').'/workspace/js/script.js','touch');
		create_ifnotexists($locator('user://').'/workspace/css/custom.css','touch');
		create_ifnotexists($locator('user://').'/workspace/scss/custom.scss',
			'copy',__DIR__.'/_userspace/scss/custom.scss');
		create_ifnotexists($locator('user://').'/workspace/scss/_grav-dependency.scss',
			'copy',__DIR__.'/_userspace/scss/_grav-dependency.scss');
		create_ifnotexists($locator('user://').'/workspace/scss-watch.sh',
			'copy',__DIR__.'/_userspace/scss-watch.sh');
			
		$this->grav['twig']->twig_paths[] = $locator('user://templates');
	}

	public function onGetPageTemplates(Event $event)
	{
		$types = $event->types;
		$types->scanTemplates('user://templates');
	}

	public function onGetPageBlueprints(Event $event)
	{
		$types = $event->types;
		$types->scanBlueprints('user://blueprints');
	}

	public function onOutputGenerated()
	{

		require_once(__DIR__.'/php/ncc-util.php');
		
		function createPath($path) {
			if (is_dir($path)) return true;
			$prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
			$return = createPath($prev_path);
			return ($return && is_writable($prev_path)) ? mkdir($path) : false;
		}

		if (! $this->isAdmin()) {

			if (isset($this->config['theme']['tidy_output'])) {
				$this->grav->output = ncc_tidyup($this->grav->output."");
			}

			if (isset($this->config['theme']['static_path'])) {
				
				$st_content = $this->grav->output;
				
				$locator = $this->grav['locator'];
				$tdir = $_SERVER['DOCUMENT_ROOT'].'/'. $this->config['theme']['static_path'];

				if (! file_exists($tdir)) { mkdir($tdir); }

				$file = $tdir.$this->grav['uri']->path();
				createPath($file);
				$filepath = $file."/index.html";
				
				$tmp = explode('\n',$st_content);

				$st = $this->config['theme']['static_path'];

				$tmp = preg_replace('#href="\/#','href="/'.$st.'/',$tmp);
				$tmp = preg_replace('#link href="\/'.$st.'#','link href="',$tmp);
				
				function copy_asset($src,$tdir) {
					$name = preg_replace('#assets/#','',$src);
					//$name = preg_replace('#^(.+?)(\..+?$)#','ncc-static$2',$src);
					$sdir = preg_replace('#'.GRAV_ROOT.'#','',$tdir);
					copy($src,$tdir.'/'.$name);
					return $sdir."/".$name;
				}
				
				if ($this->grav['assets']['css_pipeline']) {

					$tmp = preg_replace_callback('/\"\/(assets(?=\/)(.*?)(.css)(?="))/', 
						function($matches) use ($tdir) { 
						return '"'.copy_asset($matches[1],$tdir);
						}, $tmp);

				}
				
				if ($this->grav['assets']['js_pipeline']) {
				
					$tmp = preg_replace_callback('/\"\/(assets(?=\/)(.*?)(.js)(?="))/', 
						function($matches) use ($tdir) { 
						return '"'.copy_asset($matches[1],$tdir);
						}, $tmp);

				}					

				$st_content = implode('\n', $tmp);
				$st_content = preg_replace('#</(div|li|ul)>\n#','</$1>',$st_content);
				$tmp = explode('\n', $st_content);
				$tmp = preg_replace('#^\s+#','',$tmp);
				$st_content = implode('\n',$tmp);
				
				if ($this->config['theme']['rewrite']) {
					file_put_contents($filepath, $st_content);
				} else {
					if (! file_exists($filepath) ) {
						file_put_contents($filepath, $st_content);
					}
				}

			}
		}
	}
}
