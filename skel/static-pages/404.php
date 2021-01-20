<?php
$req = $_SERVER['REQUEST_URI'];
$req = preg_replace('#static-page/#','',$req);
header("Location: $req");
die();
?>
