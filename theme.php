<?php
namespace Grav\Theme;

use Gantry\Framework\Gantry;
use Gantry\Framework\Theme as GantryTheme;
use Grav\Common\Theme;
use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

use RocketTheme\Toolbox\ResourceLocator\UniformResourceLocator;

class Neoca extends Theme
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
			'onShortcodeHandlers' => ['onShortcodeHandlers', 0],
			'onTwigSiteVariables' => ['onTwigSiteVariables', 0],
			'onTwigExtensions' => ['onTwigExtensions', 0],
			'onOutputGenerated' => ['onOutputGenerated', 0],

//             'onFormProcessed' => ['onFormProcessed', 0],
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

    public function onShortcodeHandlers()
    {
		$theme_name = $this->name;
        $this->grav['shortcode']->registerAllShortcodes("user://themes/$theme_name/php/shortcodes");
    }


	public function onTwigSiteVariables()
    {
        $locator = $this->grav['locator'];
		$adminCookieSuffix = '-admin';
		$this->adminCookie = session_name() . $adminCookieSuffix;

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
        //require_once(__DIR__.'/php/ColorMixerTwigExtension.php');
        //$this->grav['twig']->twig->addExtension(new ColorMixerTwigExtension());
    }


	/*
	 * So you can make templae outside the theme for a client needs
	 */

    public function onTwigTemplatePaths()
    {
		$locator = $this->grav['locator'];
		$theme_name = $this->name;
		
		$tdir = $locator('user://').'/templates';
		if (! file_exists($tdir) ) { mkdir($tdir); }
		
		$bdir = $locator('user://').'/blueprints';
		if (! file_exists($tdir) ) { mkdir($bdir); }
		
		$pdir = $locator('user://')."/data/gantry5/themes/$theme_name/particles";
		if (! file_exists($pdir) ) { mkdir($pdir); }

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

		function createPath($path) {
			if (is_dir($path)) return true;
			$prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
			$return = createPath($prev_path);
			return ($return && is_writable($prev_path)) ? mkdir($path) : false;
		}

        if (! $this->isAdmin()) {

			$st_content = $this->grav->output."";

			if (isset($this->config['theme']['tidy_output'])) {

				$tmp = explode("\n", $st_content);
				$tmp = preg_replace('/^(\W)\s+$/', '$1', $tmp);
				$tmp = preg_replace('/^\s+(\W)/', '$1', $tmp);
				$tmp = preg_replace('/(\r|\t)/', '', $tmp);
				$tmp = preg_replace('/(\W)\s+(\W)/', '$1$2', $tmp);

				$tmp = preg_replace('/\s+$/', '', $tmp);
				$tmp = array_filter($tmp);
				$st_content = implode("\n", $tmp);
			
				$st_content  = preg_replace('/\s+\">/', '">', $st_content );
				$st_content  = preg_replace('/"\s+>/', '">', $st_content );
				$st_content = preg_replace('/[\r\n\s+\t]+(?=(?:[^<])*>)/', ' ', $st_content);
				$st_content = preg_replace('/\n(>|\}|\)|\")/', '$1', $st_content);
		
				$this->grav->output = $st_content;
			
			}
			
			if (isset($this->config['theme']['static_path'])) {

				$locator = $this->grav['locator'];
				$tdir = $_SERVER['DOCUMENT_ROOT'].'/'. $this->config['theme']['static_path'];

				if (! file_exists($tdir)) { mkdir($tdir); }

				$file = $tdir.$this->grav['uri']->path();
				createPath($file);
				$filepath = $file."/index.html";

				$st_content = preg_replace('#href="\/#','href="/static/',$st_content);
				$st_content = preg_replace('#link href="\/static#','link href="',$st_content);
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

