<?php
include_once $toroot."arrmenu.php";
$m=new htmlMenu();
$m->rndMenu=$rnd;
$m->aMenu=$aMenu;
$m->aSubmenu=$aSubmenu;
/*
$m->tgMenu='content-wrapper';

$i=0;$aMenu[$i]="Beranda|beranda|fa fa-dashboard";
if(!$isOnline) {
	$i++;$aMenu[$i]="gen|default&op=gen|fa fa-book";
}
if (userType('admin,sa,direksi')) {
	$i++;$aMenu[$i]="Referensi||fa fa-folder-open|sm";
	$aSubmenu[$i][]="Data Kategori Barang|barangcat|fa fa-book|sm";
	$aSubmenu[$i][]="Data Barang|barang|fa fa-book|sm";
}

	$i++;$aMenu[$i]="Keluar|login&op=logout|fa fa-sign-out||s|Yakin?|o";

$aci=[
	['fa fa-dashboard','fas fa-tachometer-alt'],
	['fa fa-sign-out','fas fa-sign-out-alt'],
	['fa fa-money','far fa-money-bill-alt'],
	['fa fa-sticky-note-o','fa fa-bars'],
];

$this->aci=$aci;

*/

$htmlMenu=$m->show(0);//jresult: 0:htmlMenu,1:widgetMenu;
$htmlWidget=$m->htmlWidget;
