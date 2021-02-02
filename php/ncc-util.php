<?php

	function createPath($path) {
		if (is_dir($path)) return true;
		$prev_path = substr($path, 0, strrpos($path, '/', -2) + 1 );
		$return = createPath($prev_path);
		return ($return && is_writable($prev_path)) ? mkdir($path) : false;
	}

	function copy_asset($src,$tdir) {
		$name = preg_replace('#assets/#','',$src);
		//$name = preg_replace('#^(.+?)(\..+?$)#','ncc-static$2',$src);
		$sdir = preg_replace('#'.GRAV_ROOT.'#','',$tdir);
		copy($src,$tdir.'/'.$name);
		return $sdir."/".$name;
	}
			
	function ncc_generate_staticpages(
		$st_content,
		$file,
		$skelthemedir,
		$tdir,
		$st,
		$rw,
		$jsp,
		$csp) 
	{

		//if (! file_exists($tdir)) { mkdir($tdir); }

		create_ifnotexists("$tdir");		
		create_ifnotexists("$tdir/404.php","copy","$skelthemedir/static-pages/404.php");		
		create_ifnotexists("$tdir/.htaccess","copy","$skelthemedir/static-pages/.htaccess");		
			
		createPath($file);

		$filepath = $file."/index.html";		
		$tmp = explode('\n',$st_content);
		
		$tmp = preg_replace('#href="\/#','href="/'.$st.'/',$tmp);
		$tmp = preg_replace('#link href="\/'.$st.'#','link href="',$tmp);

		if ($csp) {
			$tmp = preg_replace_callback('/\"\/(assets(?=\/)(.*?)(.css)(?="))/',
				function($matches) use ($tdir) {
					return '"'.copy_asset($matches[1],$tdir);
			}, $tmp);
		}

		if ($jsp) {

			$tmp = preg_replace_callback('/\"\/(assets(?=\/)(.*?)(.js)(?="))/',
				function($matches) use ($tdir) {
					return '"'.copy_asset($matches[1],$tdir);
				}, $tmp);
		}

		$tmp = preg_replace('#</(div|li|ul])>\n#','</$1>',$tmp);
		$tmp = preg_replace('#<head>#',"<head>\n<meta http-equiv='cache-control' content='public' />",$tmp);
		
		# remove querystring?
		$tmp = preg_replace('#\?\w+#','',$tmp);

		$st_content = implode('',$tmp);
				
		if ($rw) {
			file_put_contents($filepath, $st_content);
		} else {
			if (! file_exists($filepath) ) {
				file_put_contents($filepath, $st_content);
			}
		}
		
	}
	
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