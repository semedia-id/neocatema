<?php include('php/function.php'); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<link href="/css/neoca.css?<?php seed(); ?>" type="text/css" rel="stylesheet">
<link href="/css/neoca-dev.css?<?php seed(); ?>" type="text/css" rel="stylesheet">
<script src="/js/w3color.js?"></script> 
<script src="/js/_gas.js?<?php seed() ?>"></script> 

<title>neocatema</title>
<body>
<div id='nc_dashboard' class='w-panel'>
<header>

	<div>
	
	<strong style='display:inline-block; width: 155px;'>neocatema</strong>

	<button naked border 
		title='Variables'
		onclick="javascript:nc_toggle('#nc_dashboard','w-panel');">
		<i class='ncico-settings'></i></button>

	<button naked border 
		title='Menus'
		onclick="javascript:nc_toggle('#nc_dashboard','w-menu');">
		<i class='ncico-menu'></i></button>
	</div>
	
	<div>

	<a class='button toggle' 
		title='Variables'
		href="/">
		<i class='ncico-home'></i></a>

	<button naked  
		title='More'
		onclick="javascript:nc_toggle('#nc-option','open');">
		<i class='ncico-more-vertical'></i></button>
	</div>
	
</header>

<div id='nc-option' class=''>

<ul class='menu'>
<li><a href='javascript:localStorage.clear();location.reload();'>Reset Theme</a></li>
<li><a axhr href='javascript:nc_variables()'>Generate</a></li>
</ul>

</div>

<nav>
<?php include('php/menu.php'); ?>
</nav>

<aside>
<?php include('php/inputs.php'); ?>
</aside>
<main>
<?php include('php/welcome.php'); ?>


</main>

</div>

<script src="/neoca-css.js?<?php seed() ?>"></script> 
</body>
</html>