<?
$path = "../images/";

$_GET['img']="awan_biru.jpg";
if (! isset($_GET['img'])) {
    die("Invalid URL");
}

$finalPath = $path.$_GET['img'];
/*
$imageName = filter_var($_GET['img'], FILTER_SANITIZE_STRING);
$finalPath = $path.$imageName;
header('Content-type: octet/stream');
header('Content-Type: image/jpg');
header("Content-Disposition: attachment; filename=$finalPath;");
readfile($finalPath);
*/

header('Content-Type: image/jpg');
include $finalPath;
?>