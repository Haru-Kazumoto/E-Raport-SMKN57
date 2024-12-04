<?php
//update jlh pic
$sq="select idperusahaan,count(id) as jlh from tbdperusahaan group by idperusahaan";
foreachrecord($sq,"update tbpperusahaan set jlhpic={jlh}");

?>