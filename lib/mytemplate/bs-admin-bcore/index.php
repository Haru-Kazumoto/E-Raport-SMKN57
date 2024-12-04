<?php


include $toroot."content.php";


if ($contentOnly==1) {
	include $nfx;
	exit;
} else {
	include $tppath."header.php";
	include $nfx;
	include $tppath."footer.php";
	
	
}