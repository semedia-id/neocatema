<?php
namespace Grav\Theme;

use Grav\Common\Grav;
use Grav\Common\Theme;
use Grav\Common\Utils;
use Grav\Common\Plugin;

class nccTwigExtension extends \Twig_Extension
{

	protected $theme;

	/**
	 * Returns extension name.
	 *
	 * @return string
	 */

	public function getName() { return 'nccTwigExtension'; }

	public function getFilters() {
		return [
			new \Twig_SimpleFilter('stripper', [$this, 'stripper_func']),
			new \Twig_SimpleFilter('nodupe', [$this, 'unique_array']),
			new \Twig_SimpleFilter('safetxt', [$this, 'safetxt_func']),
		];
	}

	public function getFunctions() {
		return [
			new \Twig_SimpleFunction('arrayken', [$this, 'ncc_arrayken']),
			new \Twig_SimpleFunction('file_as_array', [$this, 'file_as_array']),
			new \Twig_SimpleFunction('file_is_exist', [$this, 'file_is_exist']),

			new \Twig_SimpleFunction('filedir', [$this, 'filedir']),
			new \Twig_SimpleFunction('fileget', [$this, 'fileget']),
			new \Twig_SimpleFunction('gasvara', [$this, 'gasvar_array_func']),
			new \Twig_SimpleFunction('gasvara_print', [$this, 'gasvara_print']),

			new \Twig_SimpleFunction('popularity', [$this, 'popularity']),
			new \Twig_SimpleFunction('randomwords', [$this, 'randomwords']),
			new \Twig_SimpleFunction('stringken', [$this, 'ncc_stringken']),
			new \Twig_SimpleFunction('to_array', [$this, 'ncc_obj_to_array']),
		];
	}

	public function ncc_obj_to_array($stdClassObject) {
		$response = array();
		foreach ($stdClassObject as $key => $value) {
			$response[$key]= $value;
		}
		ksort($response);
		return $response;
	}

	public function unique_array($array,$delim=' ') {
		if (is_array($array)) {
			array_filter($array);
			sort($array);
			return array_unique($array);
		} else {
			$array = explode($delim,trim($array));
			$array = array_unique($array);
			array_filter($array);
			sort($array);
			return implode($delim,$array);
		}
	}

	public function ncc_arrayken($string,$delim,$unique=false) {
		$array = explode($delim, $string);
		array_filter($array);

		if ($unique) {
			return array_unique($array);
		} else {
			return $array;
		}
	}

	public function ncc_stringken($array, $delim=' ') {

		if (! is_array($array) ) { $array = explode($delim,$array); }
		array_filter($array);
		sort($array);
		return trim(join($delim,array_unique($array)),$delim);
	}

	public function file_is_exist($path,$fromroot=false) {

		if ($fromroot) {
			$wpath = $path;
		} else {
			if (Utils::startsWith($path, '/')) {
				$wpath = GRAV_ROOT . $path;
			} else {
				$wpath = Grav::instance()['page']->path() . '/' . $path;
			}
		}



		if (file_exists($wpath)) {
			return true;
		} else {
			return false;
		}
	}

	public function fileget($file) {
		if (Utils::startsWith($file, '/')) {
			$file = GRAV_ROOT . $file;
		} else {
			$file = Grav::instance()['page']->path() . '/' . $file;
		}

		return file_get_contents($file);
	}

/* verified ------------------------------------------------ */

	public function file_as_array($file) {
		if (Utils::startsWith($file, '/')) {
			$file = GRAV_ROOT . $file;
		} else {
			$file = Grav::instance()['page']->path() . '/' . $file;
		}
		$array = file($file);
		$array = preg_replace('#\r|\n|\s+$#','',$array);
		return array_filter($array);
	}

	public function stripper_func($string,$compress=false) {
		$tmp = explode("\n", $string);
		$tmp = preg_replace('/^\s+$/', '', $tmp);
		$tmp = preg_replace('/^\s+/', '', $tmp);
		$tmp = preg_replace('/\t|\n|\r/', '', $tmp);
		$tmp = array_filter($tmp);
		$string = implode("\n", $tmp);
		$string = preg_replace('/;;/', ';', $string);
		$string = preg_replace('/\t|\n|\r/', '', $string);
		$string = preg_replace('/\s+(=|:|;|\+|<|>|\(|\)|\}|\{|\}|,)/', '$1', $string);
		$string = preg_replace('/(=|:|;|\+|<|>|\(|\)|\}|\{|\}|,)\s+/', '$1', $string);
		return trim($string);
	}

	public function gasvara_print($var,$delim='',$compact=false) {
		$coba = Grav::instance();
		$gas = $coba['config']['gas'];
		$array = $gas[$var];
		array_filter($array);
		sort($array);
		$res = join($delim,array_unique($array));
		$res = preg_replace('#\n|\r#', '', $res);
		$res = preg_replace('#;\s+|;;#', ';', $res);
		if (!$compact) { $res = preg_replace('#;#', ";\n", $res); }
		return "\n".trim($res,' ');
	}

	public function gasvar_array_func($var,$value) {
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

	public function safetxt_func($string,$compress=false) {
		$string = preg_replace('/\W|\s/', '', $string);
		return strtolower($string);
	}
	public function filedir($path,$pattern="*") {

		require_once(__DIR__.'/ncc-util.php');

		$path = preg_replace("#".GRAV_ROOT."#","",$path);

		if (Utils::startsWith($path, '/')) {
			$path = GRAV_ROOT . $path.'/';
		} else {
			$path = Grav::instance()['page']->path() . '/' . $path.'/';
		}

		return ncc_filedir($path,$pattern);


	}

	public function popularity($what) {

		$log_path = GRAV_ROOT.'/logs/popularity';

		switch($what) {

			case 'daily':
				$file = $log_path . '/daily.json';
				break;
			case 'monthly':
				$file = $log_path . '/monthly.json';
				break;
			case 'total':
				$file = $log_path . '/totals.json';
				break;
			case 'visitor':
			default:
				$file = $log_path . '/visitors.json';
				break;
		}

		if (file_exists($file)) {
			$array = (array)json_decode(file_get_contents($file));
			$val = array_values($array)[0];
			return $val;
		} else {
			return "N/A";
		}
	}

	public function randomwords($n=10) {
		if ($n > 100) { $n=100; }
		$w = '';
		$words = ['lorem','ipsum','dolor','sit','amet','consectetur','adipiscing','elit','suspendisse','turpis','ligula','commodo','at','vehicula','scelerisque','tincidunt','ac','ante','duis','a','scelerisque','metus','a','congue','felis','mauris','mattis','risus','id','finibus','rhoncus','in','sit','amet','aliquet','nisi','vivamus','vestibulum','lectus','ipsum','eu','porta','elit','laoreet','et','aliquam','porttitor','nisl','eu','elit','viverra','pulvinar','vivamus','ex','enim','lacinia','molestie','efficitur','et','mattis','non','metus','morbi','sit','amet','dictum','diam','imperdiet','vulputate','arcu','maecenas','magna','sapien','facilisis','vitae','posuere','tincidunt','faucibus','non','tortor','praesent','quis','eleifend','dolor','ut','congue','ut','justo','vitae','suscipit','suspendisse','non','dictum','nisl','aenean','semper','eget','sapien','nec','dignissim'];
		shuffle($words);
		for ($x = 0; $x < $n; $x++) { $w .= $words[$x]. ' '; }
		return rtrim($w);
	}

} /* eof class */