<?php
$ssq="";
$umdbver=getconfig("umdbver")*1;
//echo "umdbver $umdbver ";
if ($umdbver<=0) {
	$ssq.="
	alter table tbconfig add rndjs decimal(12) default 1;
	alter table tbconfig add umdbver decimal(12) default 1;
	
	update tbconfig set rndjs=1;
	";
}

if ($umdbver<2) {
	$ssq.="
	
	alter table tbconfig change rndjs rndjs decimal(12) default 1;
	alter table tbconfig change umdbver umdbver decimal(12) default 1;
	";
}


if ($umdbver<3) {
	$ssq.="
	ALTER TABLE `tbuser` ADD `statuser` INT NOT NULL AFTER `telp`, ADD `lastclick` DATETIME NOT NULL AFTER `statuser`; 
	";
}

if ($umdbver<4) {
	$ssq.="
	
	CREATE TABLE `tbh_logclick` (
	  `id` int(11) NOT NULL,
	  `vurl` varchar(200) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `vuid` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `tglclick` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `mark` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `ip` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
	  `ket` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;

	CREATE TABLE `tbh_logh2` (
	  `id` mediumint(9) NOT NULL,
	  `user` varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `ket` text NOT NULL,
	  `created_by` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  `modified_by` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
	  `idtrans` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `jenislog` varchar(20) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `ip` varchar(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `tb` varchar(30) NOT NULL,
	  `sq` text NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;

	CREATE TABLE `tbh_logip` (
	  `id` int(11) NOT NULL,
	  `ip` varchar(17) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `svuid` text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
	  `jlhip` int(5) NOT NULL,
	  `ket` varchar(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
	  `mark` varchar(20) NOT NULL DEFAULT '',
	  `tgl` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;


	CREATE TABLE `tbl1kode` (
	  `id` int(11) NOT NULL,
	  `kode` varchar(10) NOT NULL,
	  `awalan` varchar(20) NOT NULL,
	  `akhiran` varchar(20) NOT NULL,
	  `digit` int(11) NOT NULL,
	  `noterakhir` int(11) NOT NULL,
	  `modified_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;

	ALTER TABLE `tbh_logclick`  ADD PRIMARY KEY (`id`);
	ALTER TABLE `tbh_logh2`  ADD PRIMARY KEY (`id`);
	ALTER TABLE `tbh_logip`  ADD PRIMARY KEY (`id`),  ADD UNIQUE KEY `ip` (`ip`);
	ALTER TABLE `tbl1kode`  ADD PRIMARY KEY (`id`);

	ALTER TABLE `tbh_logclick`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20091;
	ALTER TABLE `tbh_logh2`  MODIFY `id` mediumint(9) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1602;
	ALTER TABLE `tbh_logip`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
	ALTER TABLE `tbl1kode`  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

	update tbconfig set umdbver=4;
	";
}
if ($ssq!='') {
	querysql($ssq,$cancelIfError=false,$showPes=true,$saveLog=false);
}
