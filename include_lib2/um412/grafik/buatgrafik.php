<?php
require_once "conf.php";

require_once ($lib_path.'jpGraph/jpgraph.php');
//require_once ($lib_path.'jpGraph/jpgraph_line.php');
require_once ($lib_path.'jpGraph/jpgraph_bar.php');

// Some data
$ydata = array(11,3,8,1);

$judul1="CONTOH GRAFIK";
// Create the graph. These two calls are always required

$y1 = array(11,3,8,12,5,1,9,13,5,7);
$w=300;$h=250;
$w=600;$h=500;
if (isset($_REQUEST['w'])) $w=($_REQUEST['w']);
if (isset($_REQUEST['h'])) $h=($_REQUEST['h']);

$graph = new Graph($w,$h);
$graph->SetScale('textlin');
$graph->SetShadow();


if (isset($_REQUEST['xlabel'])) $graph->xaxis->SetTickLabels(explode(",",$_REQUEST['xlabel']));
if (isset($_REQUEST['y1'])) $y1=explode(",",$_REQUEST['y1']);
if (isset($_REQUEST['judul1'])) $judul1=($_REQUEST['judul1']);

$graph->title->Set($judul1);

/*
format data:
y1=4,4,4,4
xlabel=a,b,c,d
=
*/

/*
$data1y=array(47,80,40,116);
$data2y=array(61,30,82,105);
$data3y=array(115,50,70,93);
*/

//$theme_class=new UniversalTheme;
//$graph->SetTheme($theme_class);


//$graph->ygrid->SetColor('gray');
$graph->ygrid->SetFill(false);
//$graph->xaxis->SetTickLabels(array('A','B','C','D'));
$graph->yaxis->HideLine(false);
$graph->yaxis->HideTicks(false,false);

// Create the bar plots
$b1plot = new BarPlot($y1);

// ...and add it to the graPH
$graph->Add($b1plot);//


//$b1plot->SetColor("white");
$b1plot->SetFillGradient("#4B0082","white",GRAD_LEFT_REFLECTION);
//$b1plot->SetFillGradient("#4B0082","white",GRAD_RAISED_PANEL);
$b1plot->SetWidth(45);

// Display the graph
$graph->Stroke();
?>