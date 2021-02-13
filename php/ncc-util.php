<?php

function minify($_src) {
 // Buffer output
 ob_start();
 $_time=microtime(TRUE);
 $_ptr=0;
 while ($_ptr<=strlen($_src)) {
  if ($_src[$_ptr]=='/') {
   // Let's presume it's a regex pattern
   $_regex=TRUE;
   if ($_ptr>0) {
    // Backtrack and validate
    $_ofs=$_ptr;
    while ($_ofs>0) {
     $_ofs--;
     // Regex pattern should be preceded by parenthesis, colon or assignment operator
     if ($_src[$_ofs]=='(' || $_src[$_ofs]==':' || $_src[$_ofs]=='=') {
       while ($_ptr<=strlen($_src)) {
       $_str=strstr(substr($_src,$_ptr+1),'/',TRUE);
       if (!strlen($_str) && $_src[$_ptr-1]!='/' || strpos($_str,"\n")) {
        // Not a regex pattern
        $_regex=FALSE;
        break;
       }
       echo '/'.$_str;
       $_ptr+=strlen($_str)+1;
       // Continue pattern matching if / is preceded by a \
       if ($_src[$_ptr-1]!='\\' || $_src[$_ptr-2]=='\\') {
         echo '/';
         $_ptr++;
         break;
       }
      }
      break;
     }
     elseif ($_src[$_ofs]!="\t" && $_src[$_ofs]!=' ') {
      // Not a regex pattern
      $_regex=FALSE;
      break;
     }
    }
    if ($_regex && _ofs<1)
     $_regex=FALSE;
   }
   if (!$_regex || $_ptr<1) {
    if (substr($_src,$_ptr+1,2)=='*@') {
     // JS conditional block statement
     $_str=strstr(substr($_src,$_ptr+3),'@*/',TRUE);
     echo '/*@'.$_str.$_src[$_ptr].'@*/';
     $_ptr+=strlen($_str)+6;
    }
    elseif ($_src[$_ptr+1]=='*') {
     // Multiline comment
     $_str=strstr(substr($_src,$_ptr+2),'*/',TRUE);
     $_ptr+=strlen($_str)+4;
    }
    elseif ($_src[$_ptr+1]=='/') {
     // Multiline comment
     $_str=strstr(substr($_src,$_ptr+2),"\n",TRUE);
     $_ptr+=strlen($_str)+2;
    }
    else {
     // Division operator
     echo $_src[$_ptr];
     $_ptr++;
    }
   }
   continue;
  }
  elseif ($_src[$_ptr]=='\'' || $_src[$_ptr]=='"') {
   $_match=$_src[$_ptr];
   // String literal
   while ($_ptr<=strlen($_src)) {
    $_str=strstr(substr($_src,$_ptr+1),$_src[$_ptr],TRUE);
    echo $_match.$_str;
    $_ptr+=strlen($_str)+1;
    if ($_src[$_ptr-1]!='\\' || $_src[$_ptr-2]=='\\') {
     echo $_match;
     $_ptr++;
     break;
    }
   }
   continue;
  }
  if ($_src[$_ptr]!="\r" && $_src[$_ptr]!="\n" && ($_src[$_ptr]!="\t" && $_src[$_ptr]!=' ' ||
   preg_match('/[\w\$]/',$_src[$_ptr-1]) && preg_match('/[\w\$]/',$_src[$_ptr+1])))
    // Ignore whitespaces
    echo str_replace("\t",' ',$_src[$_ptr]);
  $_ptr++;
 }
 echo '/* Compressed in '.round(microtime(TRUE)-$_time,4).' secs */';
 $_out=ob_get_contents();
 ob_end_clean();
 return $_out;
}

	function rglob($pattern, $flags = 0) {
		$files = glob($pattern, $flags);
		foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR) as $dir) {
			$files = array_merge($files, rglob($dir.'/'.basename($pattern), $flags));
		}
		return $files;
	}

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

	function ncc_compact($html) {
		$tmp = explode("\n", $html);
		$tmp = preg_replace('#[ \t]+(\r?$)#', '$1', $tmp );
		$tmp = preg_replace('/\r/', '', $tmp );
		$tmp = preg_replace('#\s+$|^\s+#', '', $tmp );
		$tmp = preg_replace('/\s+$/', '', $tmp);
		$tmp = preg_replace('/if\s+/', 'if', $tmp);
		$tmp = array_filter($tmp);
		$html = implode("\n", $tmp);
		$html = preg_replace('/\s+(=|,|>|<|:)/si', '$1', $html);
		$html = preg_replace('/(=|,|>|<|:)\s+/si', '$1', $html);
		$html = preg_replace('/(}|{|\+|\-|\*|;) /si', '$1', $html);
		$html = preg_replace('/ (}|{|\+|\-|\*|;)/si', '$1', $html);
		return $html;
	}

	function ncc_tidyup($html,$strip=false) {

		$html = preg_replace('/[\r\n\s+\t]+(?=(?:[^<])*>)/', ' ', $html);
		$html = preg_replace('/\t/', ' ', $html);

		$tmp = explode("\n", $html);
		$tmp = preg_replace('/^(\<|\>)\s+$/', '$1', $tmp);
		$tmp = preg_replace('/^\s+(\>|\<)/', '$1', $tmp);
		$tmp = preg_replace('/\s+$/', '', $tmp);
		$tmp = preg_replace('/\s+\">/', '">', $tmp );
		$tmp = preg_replace("/\s+\'>/", "'>", $tmp );
		$tmp = preg_replace('/"\s+>/', '">', $tmp );
		$tmp = preg_replace("/'\s+>/", "'>", $tmp );
		$tmp = preg_replace('/(>)\s+(<)/', '$1$2', $tmp);

		$tmp = array_filter($tmp);
		$html = implode("\n", $tmp);
		$html = preg_replace('/\s+">/', '">', $html );
		$html = preg_replace("/\s+'>/", "'>", $html );
		$html = preg_replace('/\n<\/(li|div)>/', "</$1>", $html);
		$html = preg_replace('/\/(div|span|a|i|p|button)>\n/', "/$1>", $html);
		$html = preg_replace('/<(section|main|footer|aside|script|svg)>/', "\n<$1", $html);

		return $html;
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

	function ncc_filedir($path,$pattern="*") {

		$res=[]; $i=0;
		$files = rglob("$path/".$pattern);
		//$files = preg_replace('#'.GRAV_ROOT.'#', '', $files);
		foreach ($files as $f) {
			$inf = pathinfo($f);
			$res[$i]['mtime'] = date ("Y/m/d - H:i:s", filemtime($f) );
			$res[$i]['file'] = $f;
			$res[$i]['path'] = $inf['dirname'];
			$res[$i]['name'] = $inf['filename'];
			$res[$i]['ext'] = $inf['extension'];
			$res[$i]['base'] = $inf['basename'];

			$i++;
		}
		return $res;
	}
?>