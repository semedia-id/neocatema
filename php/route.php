<?php

function get_content($what,$echo=true) {
	$string = file_get_contents($what);
	if ($echo) {
		echo $string;
	} else {
		return string
	}
}