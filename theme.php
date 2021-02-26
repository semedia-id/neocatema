<?php
namespace Grav\Theme;

use Grav\Common\Plugin;
use Grav\Common\Theme;
use Grav\Plugin\Admin\Admin;
use Gantry\Framework\Gantry;
use Gantry\Framework\Theme as GantryTheme;
use Grav\Common\Grav;
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
			'onAdminMenu' => ['onAdminMenu', 0],
			'onBuildPagesInitialized' => ['propertiesPrepare',0],
			'onGetPageBlueprints' => ['onGetPageBlueprints', 0],
			'onGetPageTemplates' => ['onGetPageTemplates', 0],
			'onOutputGenerated' => ['onOutputGenerated', 0],
			'onThemeInitialized' => ['onThemeInitialized', 0],
			'onTwigExtensions' => ['onTwigExtensions', 0],
			'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
			'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
		];
	}

	public function getgantry_outline() {
		$gantry = Gantry::instance();
		$array = $gantry['outlines']->all()->toArray();
		return $array;
	}
	public function propertiesPrepare()
	{
		require_once(__DIR__.'/php/ncc-util.php');

		$cores = ncc_filedir(__DIR__.'/js/core/','*.js');
		$modules = ncc_filedir(__DIR__.'/js/modules/','*.js');
		$str = "/* to recompile this file, clear your grav cache\n";
		$str .= "   => ". __DIR__;
		$str .= "\n*/\n";
		foreach ($cores as $m) {
			//$str .= "/* = ".$m['file'] ." */";
			$str .= ncc_compact( file_get_contents($m['rfile']) );
		}

		foreach ($modules as $m) {
			//$str .= "/* = ".$m['file'] ." */";
			$str .= "\n/*- ".$m['base']." -*/\n";
			$str .= ncc_compact( file_get_contents($m['rfile']) );
		}

		file_put_contents(__DIR__.'/js/module.min.js',$str);
	}

	public function onAdminMenu()
	{

		$theme_name = $this->name;

		$this->grav['assets']->add("user://themes/$theme_name/admin/css/poko.css",1);
		$this->grav['assets']->add("user://themes/$theme_name/js/core/w3color.js");
		$this->grav['assets']->add("user://themes/$theme_name/admin/js/poko.js");

	}

	public function webcheck() {
		

		$array=[];
		$barray=[];
		$array[] = "<tr><td>DOCUMENT_ROOT</td><td><input type='text' value='". $_SERVER['DOCUMENT_ROOT']. "' ></td></tr>";
		$array[] = "<tr><td>GRAV_ROOT</td><td><input type='text' value='". GRAV_ROOT ."' ></td></tr>";
		$array[] = "<tr><td>DIR</td><td><input type='text' value='". __DIR__ ."' ></td></tr>";
		return "<table class='ncc_enviro'>".join("",$array)."</table>";
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
		require_once(__DIR__.'/php/ncc-util.php');

		/* check and create the workspace's path */

		create_ifnotexists($locator('user://').'/workspace/templates');
		create_ifnotexists($locator('user://').'/workspace/blueprints');
		create_ifnotexists($locator('user://').'/workspace/css');
		create_ifnotexists($locator('user://').'/workspace/scss');
		create_ifnotexists($locator('user://').'/workspace/js');
		create_ifnotexists($locator('user://').'/workspace/assets');

		/* touch the needed files */

		create_ifnotexists($locator('user://').'/workspace/js/script.js','touch');
		create_ifnotexists($locator('user://').'/workspace/css/grav.css','touch');

		/* copy file from theme/skeleton */

		create_ifnotexists($locator('user://').'/workspace/scss/grav.scss',
			'copy',__DIR__.'/skel/workspace/scss/grav.scss');
		create_ifnotexists($locator('user://').'/workspace/scss/gantry.scss',
			'copy',__DIR__.'/skel/workspace/scss/gantry.scss');
		create_ifnotexists($locator('user://').'/workspace/scss/_custom.scss',
			'copy',__DIR__.'/skel/workspace/scss/_custom.scss');
		create_ifnotexists($locator('user://').'/workspace/scss-watch.sh',
			'copy',__DIR__.'/skel/workspace/scss-watch.sh');

		/* for particles */
		// create_ifnotexists($locator('user://')."/data/gantry5/themes/$name/particles");

		// Define Gantry services.

		$gantry['theme'] = function ($c) {
			return new \Gantry\Theme\Neoca($c['theme.path'], $c['theme.name']);
		};
	}

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

	}

	public function onTwigExtensions()
	{
		require_once(__DIR__.'/php/ncc-TwigExtension.php');
		$this->grav['twig']->twig->addExtension(new nccTwigExtension());

	}


	public function onTwigTemplatePaths()
	{

		$locator = $this->grav['locator'];
		$this->grav['twig']->twig_paths[] = $locator('user://workspace/templates');
	}

	public function onGetPageTemplates(Event $event)
	{
		$types = $event->types;
		$types->scanTemplates('user://workspace/templates');
	}

	public function onGetPageBlueprints(Event $event)
	{
		$types = $event->types;
		$types->scanBlueprints('user://workspace/blueprints');
	}

	public function onOutputGenerated()
	{

		require_once(__DIR__.'/php/ncc-util.php');

		if (! $this->isAdmin()) {

			if ($this->config['theme']['tidy_output']) {
				$this->grav->output = ncc_tidyup($this->grav->output."");
			}

			if (isset($this->config['plugins']['neocaprima']['static_path'])) {

				/* require neocaprima plugins */

				$tdir = GRAV_ROOT.'/'. $this->config['plugins']['neocaprima']['static_path'];

				ncc_generate_staticpages(
					$this->grav->output,
					$tdir.$this->grav['uri']->path(),
					__DIR__."/skel",
					$tdir,
					$this->config['plugins']['neocaprima']['static_path'],
					$this->config['plugins']['neocaprima']['rewrite'],
					$this->grav['assets']['js_pipeline'],
					$this->grav['assets']['css_pipeline']
				);
			}

		}
	}



}
