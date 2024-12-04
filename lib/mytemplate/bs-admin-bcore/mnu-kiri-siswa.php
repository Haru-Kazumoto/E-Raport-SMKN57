<?php
$modeInfo=1;
//include $tppath."info-user.php";
$modeInfo=2;

$mainmenu="mmenu1";
$accd="accordion-toggle";
$clps="collapse";

extractRecord("select allowopenreport1 as aor1,allowopenreport2 as aor2 from tbconfig1" );
//title,det,icon,active
$aMenuLevel1=array(
/*	"judul|href  |data-parent|collapse|class|target|icon|pull|actv", */
	"Laporan|#|$mainmenu|$clps|$accd|-|icon-bar-chart|pull|" 
);

$asm=array();
$asm[]="Nilai Per KD|cls|raport&jcetak=chb|icn";

if ($aor1=='1') {
	$asm[]="Rapor Tengah Semester|cls|raport&jcetak=Raport&jmid=1|icn";
}
if ($aor2=='1') {
	$asm[]="Rapor Akhir Semester|cls|raport&jcetak=Raport&jmid=0|icn|awalEdit($rnd)";
}
$i=0;
$aSubMenuLevel1[$i]=$asm;

$i++;$aSubMenuLevel1[$i]=array();
$i++;$aSubMenuLevel1[$i]=array();
$i++;$aSubMenuLevel1[$i]=array();
//<li class=""><a href="button.html"><i class="icon-angle-right"></i> Buttons </a></li>

//sub menu
include $tppath."mnu-kiri-end.php";
?>



        