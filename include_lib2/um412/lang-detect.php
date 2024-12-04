<?php
if(isset($_GET['bahasa']))  {
	$lang = $_GET['bahasa'];
	// daftar sesion dan set cookie.
	$_SESSION['lang'] = $lang;
	setcookie('lang', $lang, time() + (3600 * 24 * 30));
}
else if(isset($_SESSION['lang'])) {
	$lang= $_SESSION['lang'];
} else if(isset($_COOKIE['lang'])) {
	$lang= $_COOKIE['lang'];
} else if(isset($defLang)) {
	$lang= $defLang;
} else {
	$lang= 'id';
}

//echo "lang $lang";
//include_once 'bahasa/'.$lang_file;
?>
