<?php

$t="";
$t.='
<ul id="menu" class="collapse">
	<li class="panel active">
		<a href="index.php"  
			data-parent=\"#$dtparent\" 
		>
			<i class="icon-th-large"></i> Halaman Utama
		</a>                   
	</li>

';
$i=0;
foreach ($aMenuLevel1 as $mnLv1) {
	$smn=explode("|",$mnLv1."||||||||");
	$jdl=$smn[0];
	$href=$smn[1];
	$dtparent=$smn[2];
	$collp=$smn[3];
	$cls=$smn[4];
	$dttarget='dttg'.$i;//$smn[5];
	$icn=$smn[6];
	$pull=$smn[7];
	//href=\"index.php?det=$mnLv1[1]\"
	$sm="";
	
	if (is_array($aSubMenuLevel1[$i])) {
		$sm.="<ul class=\"collapse\" id=\"$dttarget\">";
		$asmm=$aSubMenuLevel1[$i];
		foreach ($asmm as $wsmx){
			$smx=explode("|",$wsmx."||||");
			$link="input.php?det=$smx[2]&contentOnly=1&newrnd=$rnd";
			$onc=" onclick=\"bukaAjax('content','$link',0,'$smx[4]');;return false;\" ";
			$sm.="<li class=\"$smx[1]\">
					<a href=\"index.php?det=$smx[2]\" $onc ><i class='icon-caret-right'></i> $smx[0] </a>
				</li>";
			
		}
		$sm.="</ul>";
	}
	
	$link="";
	$onc=" onclick=\"return false;\" ";
	$t.="
	<li class=\"panel \">
		<a 
		 href=\"$href\" $onc data-parent=\"#$dtparent\" 
		 data-toggle=\"$collp\" class=\"$cls\" data-target=\"#$dttarget\"
		>
			<i class=\"$icn\"></i> $jdl 
		</a>
		$sm	
	</li>
	";
	$i++;
}
/*
$t.='
	<li class="panel">
		<a href="index2.php?det=login&op=logout"  
			data-parent=\"#$dtparent\" 
		>
			<i class="icon-signout"></i> Logout
		</a>                   
	</li>

</ul>
';
*/

echo "<br>".$t;
?>