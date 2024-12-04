<?php
require_once ($lib_path.'jpGraph/jpgraph.php');
//require_once ($lib_path.'jpGraph/jpgraph_line.php');
require_once ($lib_path.'jpGraph/jpgraph_pie.php');
$judul1="CONTOH GRAFIK";
// Create the graph. These two calls are always required

$y1 = array(11,3,8,12,5,1,9,13,5,7);
$w=500;$h=250;

if (isset($_REQUEST['w'])) $w=($_REQUEST['w']);
if (isset($_REQUEST['h'])) $h=($_REQUEST['h']);

$graph = new PieGraph($w,$h);
//$graph->SetShadow();

if (isset($_REQUEST['y1'])) {
	$y1=explode(",",$_REQUEST['y1']);
	$ysum=array_sum($y1);
	if ($ysum==0) $ysum=1;
	$i=0;
	foreach ($y1 as $yx) {
		$y1[$i]=round($y1[$i]/$ysum*100,4);
		$i++;
		
	}
}
if (isset($_REQUEST['judul1'])) $judul1=($_REQUEST['judul1']);

 
$graph->title->Set($judul1);
$graph->title->SetFont(FF_FONT1,FS_BOLD);

// The pie plot
$p1 = new PiePlot($y1);
//$p1->SetLegends(array("May ($%d)","June ($%d)","July ($%d)","Aug ($%d)"));
if (isset($_REQUEST['xlabel'])) $p1->SetLegends(explode(",",$_REQUEST['xlabel']));

// Move center of pie to the left to make better room
// for the legend
$p1->SetCenter(0.35,0.5);

// No border
$p1->ShowBorder(false);

// Label font and color setup
$p1->value->SetFont(FF_FONT1,FS_BOLD);
$p1->value->SetColor("darkred");

// Use absolute values (type==1)
$p1->SetLabelType(PIE_VALUE_ABS);

// Label format
//$p1->value->SetFormat("$%d");
$p1->value->Show();

// Size of pie in fraction of the width of the graph
$p1->SetSize(0.3);

// Legends
$graph->legend->Pos(0.05,0.15);

$graph->Add($p1);
$graph->Stroke();
?>
