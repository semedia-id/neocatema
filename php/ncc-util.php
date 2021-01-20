<?php

	function ncc_tidyup($html)
	{
		$tmp = explode("\n", $html);
		$tmp = preg_replace('/^(\<|\>)\s+$/', '$1', $tmp);
		$tmp = preg_replace('/^\s+(\>|\<)/', '$1', $tmp);
		$tmp = preg_replace('/\s+$/', '', $tmp);
		$tmp = preg_replace('/^\s+$/', '', $tmp);
		$tmp = preg_replace('/\s+">/', '">', $tmp );
		$tmp = preg_replace('/"\s+>/', '">', $tmp );
		$tmp = preg_replace('/(>)\s+(<)/', '$1$2', $tmp);
		$tmp = array_filter($tmp);
		$st_content = implode("\n", $tmp);
		$tmp = preg_replace('/\s+">/', '">', $st_content );
		$tmp = preg_replace('/"\s+>/', '">', $tmp );
		$tmp = preg_replace('/\r/', '', $tmp );
		$tmp = preg_replace('/[\r\n\s+\t]+(?=(?:[^<])*>)/', ' ', $tmp);
		$tmp = preg_replace('/[\r\n](\<\/(li|label|i>|b>|button|a|span|div))/mi', '$1', $tmp );
		return $tmp;
	}

	function create_ifnotexists($pdir,$cmd=false,$src=false)
	{

		if (! file_exists($pdir) ) {

			switch($cmd) {
				case "touch":
					touch($pdir);
					break;
				case "copy":
					if ($src) { copy($src,$pdir); }
					break;
				case "mkdir":
				default:
					mkdir($pdir,0755,true);
					break;
			}
		}
	}

	function passme($var) {
		return $var;
	}
?>