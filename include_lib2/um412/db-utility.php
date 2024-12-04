<?php
include_once "cekadmin.php";
function bajDB($ref,$func=""){
	return "href=#x onClick=\"bukaAjax('content','content1.php?ref=$ref',0,'scriptAdmin();$func;');return false\" "; 
	}
cekVar("op");
if ($op=='lock') {
	//mysql_query2("update tbconfig set lockdb='1'");
	lockDB(1);
	}
elseif ($op=='unlock') {
//	mysql_query2("update tbconfig set lockdb='0'");
	lockDB(0);
}
$stat=cekLockDB();
?>
Status DB : <?=$stat?>
<p><a <?=bajDB("lockdb&op=lock")?> >Lock DB</a><br />
<a <?=bajDB("lockdb&op=unlock")?> >Unlock DB</a></p>
