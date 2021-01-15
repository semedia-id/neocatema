<?php
namespace Grav\Theme;

use \Grav\Common\Grav;
use Grav\Common\Theme;
use Grav\Common\Utils;

class GaskenTwigExtension extends \Twig_Extension
{

	/**
	 * Returns extension name.
	 *
	 * @return string
	 */
	public function getName()
	{
		return 'GaskenTwigExtension';
	}



	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('gasvara', [$this, 'gasvar_array_func']),
			new \Twig_SimpleFunction('gasvara_string', [$this, 'gasvara_print']),
			new \Twig_SimpleFunction('filedir', [$this, 'filedir']),


		];
	}





	public function gasvara_print($var,$delim='') {
		$coba = Grav::instance();
		$gas = $coba['config']['gas'];
		$array = $gas[$var];
		array_filter($array);
		sort($array);
		$res = join($delim,array_unique($array));
		$res = preg_replace('#\n|\r#', '', $res);
		$res = preg_replace('#;\s+|;;#', ';', $res);
		return trim(trim($res,' '),$delim);
	}

	public function gasvar_array_func($var,$value)
	{
		$coba = Grav::instance();
		$gas = $coba['config']['gas'];

		if (!isset($gas[$var])) {
			$gas[$var]=[];
			array_push($gas[$var],$value);
		} else {
			array_push($gas[$var],$value);
		}

		$coba['config']['gas'] = $gas;

	}



	public function filedir($path='',$pattern='*')
	{

		$path = preg_replace("#".GRAV_ROOT."#","",$path);

		if (Utils::startsWith($path, '/')) {
			$path = GRAV_ROOT . $path.'/';
		} else {
			$path = Grav::instance()['page']->path() . '/' . $path.'/';
		}

		$path = preg_replace('#//#', '/', $path);

		if (file_exists($path)) {
			$files = glob($path.$pattern);
			$files = preg_replace('#'.GRAV_ROOT.'#', '', $files);
		} else {
			$files =  [];
		}

		return $files;

	}

}