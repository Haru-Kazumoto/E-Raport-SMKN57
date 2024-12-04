<?php
// Code to process uploads
@session_start();

$js_path="../";
$idup=strip_tags($_REQUEST['idup']);

if (!isset($_SESSION[$idup])) {
	echo "invalid";
	exit;
} else {
	
}
$sup=$_SESSION[$idup];

if($_FILES && !$_FILES['ajax_file']['error']){
    $allowed_types = ["audio","image","video"];
    $type = substr($_FILES['ajax_file']['type'],0,5);
    if(in_array($type,$allowed_types)){
        move_uploaded_file($_FILES['ajax_file']['tmp_name'],"uploaded-files/".$_FILES['ajax_file']['name']);
    }
}
?>