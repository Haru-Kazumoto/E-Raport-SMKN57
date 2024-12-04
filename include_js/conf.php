<?php
@session_start();
$pt="../";
		$docroot=$_SESSION['docroot'];
if (!isset($toroot)) {
	$toroot="../".$docroot;
}

$nf1=$toroot."conf2.php";
$nf2=$toroot."content/conf2.php";
if (file_exists($nf1))
	include_once $nf1;
	
elseif (file_exists($nf2))
	include_once $nf2;
	
		
//include "../registration-wcim/conf2.php";



?>

