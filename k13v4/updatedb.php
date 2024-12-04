<?php
$dbver=carifield("select dbver from tbconfig1")*1;
$ssq='';
if ($dbver<1) {
	$ssq.="alter table `matapelajaran` add desp21 text;
	alter table `matapelajaran` add desp22 text ;
	alter table `matapelajaran` add desp23 text ;
	alter table `matapelajaran` add desp24 text ;
	alter table `matapelajaran` add desp25 text ;
	alter table `matapelajaran` add desp26 text ;
	alter table `matapelajaran` add desk21 text ;
	alter table `matapelajaran` add desk22 text ;
	alter table `matapelajaran` add desk23 text ;
	alter table `matapelajaran` add desk24 text ;
	alter table `matapelajaran` add desk25 text ;
	alter table `matapelajaran` add desk26 text ;
	ALTER TABLE `tbconfig1` ADD `dbver` INT(3) NOT NULL default '1';
	
	";
}
//k13v4
if ($dbver<4) {
$ssq.="
	ALTER TABLE `guru` ADD `alloweditrkeg` INT(3) NOT NULL default '0';
	update tbconfig1 set dbver=4;	
	";

}
if ($ssq!='') querysql($ssq);
?>