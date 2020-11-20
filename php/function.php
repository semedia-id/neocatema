<?php

function e($string) { echo $string; }

function color_input($what,$prefix='nc') {?>
	<input type='color' 
		id='<?php e($what) ?>-color' 
		class='<?php e($what) ?>-color'
		data-color='<?php e($what) ?>'
		name='<?php e($what) ?>-color' value='#ffffff'
		onchange="javascript:change_color(this);">
<?php }

	
function colorchart($what) {?>

<div class='color-chart'>
<div class='label'><?php e($what); ?></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-b' data-color='b'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-d3' data-color='d3'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-d2' data-color='d2'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-d1' data-color='d1'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-d' data-color='d'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg' data-color='n'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-l' data-color='l'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-l1' data-color='l3'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-l2' data-color='l2'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-l3' data-color='l1'></div></div>
<div><div class='boxcolor ncc-<?php e($what) ?> bg-w' data-color='w'></div></div>
</div>

<?php }

function sample() {?>



<?php }
	
function randomwords($n=10) {
	if ($n > 100) { $n=100; }
	$w = '';
	$words = [ 
	'prona','errand','augue','a','cras','hamburger','labor',
	'pulvinar','est','belian','ac','cubi','hendrerit','lacinia',
	'purus','et','berk','accio','cum','hotel','lacus',
	'quam','etiam','biak','accumsan','curabitur','hub','laoreet',
	'quis','eu','bibendum','acrandom','cursus','hyper','lectus',
	'quisque','euismod','bis','adipiscing','dapibus','iaculis','leo',
	'rak','ex','black','aenean','deco','id','lex',
	'ran','facilisis','blandit','agust','diam','iku','libero',
	'random','falo','blum','ajax','dictum','imperdiet','ligula',
	'randomi','fames','bonaqua','aliquam','dictumst','in','list',
	'randomize','faucibus','brother','aliquet','dignissim','integer','lobortis',
	'rhoncus','faucibus','aenean','bull','amet','dolor','interdum','loop',
	'risus','felis','bullet','and','dom','ipsum','lopa',
	'rutrum','fermentum','cafe','angel','domi','james','lorem',
	'sagittis','feugiat','coma','ango','donec','juka','luca',
	'sapien','finibus','commodo','ante','dot','jump','lucent',
	'scelerisque','five','condimentum','apa','dui','justo','luctus',
	'seco','fork','congue','araha','duis','kob','lujem',
	'sed','four','consectetur','arcu','dummy','kodo','lumen',
	'sem','fringilla','consequat','assio','efficitur','kramp','lux',
	'semper','fusce','convallis','at','egestas','maecenas','netus',
	'senectus','git','corona','auctor','eget','magna','nibh',
	'seven','gravida','eleifend','man','eight','malesuada','nine',
	'sister','habitant','elementum','mandom','mi','eros','nisi',
	'sit','habitasse','elit','massa','ola-ola','papa','nisl',
	'six','hac','enema','matrix','ona','paul','trand',
	'sodales','maximus','enim','mattis','one','pellentesque','tortor',
	'sollicitudin','metus','erat','mauris','orci','peni','vel',
	'spoon','suscipit','moko','porta','ornare','petite','nunc',
	'turpis','suspendisse','molestie','porttitor','nola','pharetra','odio',
	'two','teenage','mollis','posuere','non','phasellus','vestibulum',
	'ullamcorper','tellus','momento','praesent','nulla','placerat','vitae',
	'ultrices','tempor','morbi','pretium','nullam','platea','vivamus',
	'ultricies','tempus','nec','primis','vaksa','viverra','was',
	'union','ten','nec','fusce','proin','varius','volutpat','were',
	'united','thomas','neque','poom','vehicula','vulputate','white',
	'urna','three','tristique','tincidunt','ut','thum','venenatis','velit',
	'none'];
	shuffle($words); 
	for ($x = 0; $x <= $n; $x++) { $w .= $words[$x]. ' '; }
	return rtrim($w);
}
	
function rw($n=9) {
	echo ucfirst(randomwords($n)).".";
}

function seed() { echo time(); }


function g_grid_html($n) {
	$ps = (100/$n);
	echo "<div class='g-grid'>\n";
	for ($x= 1; $x <= $n; $x++) {
		$p = str_replace(".",'-',"".round($ps,1));
		
		echo "<div class='g-block size-$p'><div class='g-content'>".$p."</div></div>\n";
	}
	echo "</div>\n";
}