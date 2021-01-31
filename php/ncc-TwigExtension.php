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
	public function getName()
	{
		return 'nccTwigExtension';
	}

	public function getFilters()
	{
		return [
			new \Twig_SimpleFilter('stripper', [$this, 'stripper_func']),
			new \Twig_SimpleFilter('nodupe', [$this, 'unique_array']),
		];
	}

	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('randomwords', [$this, 'randomwords']),
			new \Twig_SimpleFunction('popularity', [$this, 'popularity']),
			new \Twig_SimpleFunction('fileget', [$this, 'fileget']),
			new \Twig_SimpleFunction('filegeta', [$this, 'filegeta']),
			new \Twig_SimpleFunction('filedira', [$this, 'filedira']),
			new \Twig_SimpleFunction('file_is_exists', [$this, 'file_is_exist']),
			new \Twig_SimpleFunction('file_is_exist', [$this, 'file_is_exist']),
			new \Twig_SimpleFunction('stringken', [$this, 'ncc_stringken']),
			new \Twig_SimpleFunction('arrayken', [$this, 'ncc_arrayken']),
			new \Twig_SimpleFunction('to_array', [$this, 'ncc_obj_to_array']),
		];
	}

	public function ncc_obj_to_array($stdClassObject)
	{
		$response = array();
		foreach ($stdClassObject as $key => $value) {
			$response[$key]= $value;
		}
		ksort($response);
		return $response;
	}

	public function unique_array($array)
	{
		array_filter($array);
		sort($array);
		return array_unique($array);

	}

	public function ncc_arrayken($string,$delim,$unique=false)
	{
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

	public function randomwords($n=10) {
		if ($n > 100) { $n=100; }
		$w = '';
		$words = ['lorem','ipsum','dolor','sit','amet','consectetur','adipiscing','elit','suspendisse','turpis','ligula','commodo','at','vehicula','scelerisque','tincidunt','ac','ante','duis','a','scelerisque','metus','a','congue','felis','mauris','mattis','risus','id','finibus','rhoncus','in','sit','amet','aliquet','nisi','vivamus','vestibulum','lectus','ipsum','eu','porta','elit','laoreet','et','aliquam','porttitor','nisl','eu','elit','viverra','pulvinar','vivamus','ex','enim','lacinia','molestie','efficitur','et','mattis','non','metus','morbi','sit','amet','dictum','diam','imperdiet','vulputate','arcu','maecenas','magna','sapien','facilisis','vitae','posuere','tincidunt','faucibus','non','tortor','praesent','quis','eleifend','dolor','ut','congue','ut','justo','vitae','suscipit','suspendisse','non','dictum','nisl','aenean','semper','eget','sapien','nec','dignissim'];
		shuffle($words);
		for ($x = 0; $x < $n; $x++) { $w .= $words[$x]. ' '; }
		return rtrim($w);
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
			echo 'masuk:'.$wpath;
			return false;
		}
	}

	public function filedira($path,$pattern="*") {

		function rglob($pattern, $flags = 0) {
			$files = glob($pattern, $flags);
			foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR) as $dir) {
				$files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
			}
			return $files;
		}

		$res=[]; $i=0;
		$files = rglob(GRAV_ROOT."$path/".$pattern);
		$files = preg_replace('#'.GRAV_ROOT.'#', '', $files);
		foreach ($files as $f) {
			$inf = pathinfo($f);
			$res[$i]['mtime'] = date ("Y/m/d - H:i:s", filemtime(GRAV_ROOT.$f) );
			$res[$i]['file'] = preg_replace('#'.GRAV_ROOT.'#', '', $f);
			$res[$i]['path'] = $inf['dirname'];
			$res[$i]['name'] = $inf['filename'];
			$res[$i]['ext'] = $inf['extension'];
			$res[$i]['base'] = $inf['basename'];

			$i++;
		}
		return $res;
	}

	public function filegeta($file) {
		return file_get_contents(GRAV_ROOT.$file);
	}

	public function fileget($file) {
		return file_get_contents($file);
	}

	public function popularity($what)
	{

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


	public function stripper_func($string,$compress=false)
	{

		$tmp = explode("\n", $string);
		$tmp = preg_replace('/^\s+$/', '', $tmp);
		$tmp = preg_replace('/^\s+/', '', $tmp);
		$tmp = preg_replace('/\s+$/', '', $tmp);
		$tmp = array_filter($tmp);
		$string = implode("\n", $tmp);
		$string = preg_replace('/;;/', ';', $string);

		if ($compress) {
			$string = preg_replace('/\t/', '', $string);
			$string = preg_replace('/\n/', '', $string);
			$string = preg_replace('/\s+(=|:|<|>|\(|\)|\}|\{|,)/', '$1', $string);
			$string = preg_replace('/(=|:|<|>|\(|\)|\}|\{|,)\s+/', '$1', $string);
		}

		return (trim($string));
	}


}