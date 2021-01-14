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

	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('randomwords', [$this, 'randomwords']),
			new \Twig_SimpleFunction('popularity', [$this, 'popularity']),
			new \Twig_SimpleFunction('fileget', [$this, 'fileget']),
			new \Twig_SimpleFunction('filedira', [$this, 'filedira']),
			new \Twig_SimpleFunction('file_is_exists', [$this, 'file_is_exist']),			
		];
	}

	public function randomwords($n=10) {
		if ($n > 100) { $n=100; }
		$w = '';
		$words = ['lorem','ipsum','dolor','sit','amet','consectetur','adipiscing','elit','suspendisse','turpis','ligula','commodo','at','vehicula','scelerisque','tincidunt','ac','ante','duis','a','scelerisque','metus','a','congue','felis','mauris','mattis','risus','id','finibus','rhoncus','in','sit','amet','aliquet','nisi','vivamus','vestibulum','lectus','ipsum','eu','porta','elit','laoreet','et','aliquam','porttitor','nisl','eu','elit','viverra','pulvinar','vivamus','ex','enim','lacinia','molestie','efficitur','et','mattis','non','metus','morbi','sit','amet','dictum','diam','imperdiet','vulputate','arcu','maecenas','magna','sapien','facilisis','vitae','posuere','tincidunt','faucibus','non','tortor','praesent','quis','eleifend','dolor','ut','congue','ut','justo','vitae','suscipit','suspendisse','non','dictum','nisl','aenean','semper','eget','sapien','nec','dignissim'];
		shuffle($words);
		for ($x = 0; $x < $n; $x++) { $w .= $words[$x]. ' '; }
		return rtrim($w);
	}

	public function file_is_exist($path) {
		
		if (Utils::startsWith($path, '/')) {
			$wpath = GRAV_ROOT . $path;
		} else {
			$wpath = Grav::instance()['page']->path() . '/' . $path;
		}		
		
		
		if (file_exists($wpath)) {
			return true;
		} else {
			return false;
		}
	}

	public function filedira($path,$pattern="*") {
		$res=[]; $i=0;
		$files = glob("$path/".$pattern);
		//$files = preg_replace('#'.GRAV_ROOT.'#', '', $files);
		foreach ($files as $f) {
			$res[$i]['name'] = preg_replace('#'.$path.'/#', '', $f);
			$res[$i]['file'] = preg_replace('#'.GRAV_ROOT.'#', '', $f);
			$i++;
		}
		return $res;
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
	


}