<?php
session_start();
$ranStr = md5(microtime());
$ranStr = substr($ranStr, 0, 6);
$_SESSION['cap_code_'] = $ranStr;
$newImage = imagecreatefromjpeg("cap_bg.jpg");
$txtColor = imagecolorallocate($newImage, 0, 0, 0);
imagestring($newImage, 5, 5, 5, $ranStr, $txtColor);
header("Content-type: image/jpeg");
imagejpeg($newImage);

/*

$string = '';

for ($i = 0; $i < 5; $i++) {
	// this numbers refer to numbers of the ascii table (lower case)
	$string .= chr(rand(97, 122));
}

$_SESSION['rand_code'] = $string;

$dir = 'fonts/';

$image = imagecreatetruecolor(170, 60);
$black = imagecolorallocate($image, 0, 0, 0);
$color = imagecolorallocate($image, 200, 100, 90); // red
$white = imagecolorallocate($image, 255, 255, 255);

imagefilledrectangle($image,0,0,399,99,$white);
imagettftext ($newImage, 30, 0, 10, 40, $color, $dir."arial.ttf", $_SESSION['cap_code']);
header("Content-type: image/png");
imagepng($newImage);
*/

?>