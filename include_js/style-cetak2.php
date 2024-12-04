<?php

$sty="<style>
* {
	margin:0;
	padding:0;
	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
}
*/
.tout,.tview {
	background:#ddd;
	
}
body, td {
	/*
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	*/
}
.f11px,
.f11px td
 {
	font-size:11px;
}
.arial,
.arial td
 {
	font-family:Arial, Helvetica, sans-serif;
}
.wrap {
	margin:0 auto;
	}
.page,
.page-landscape,
.page2,
.page2-landscape,
.page-free,
.page-tractor  {
	position:relative;
	background:#FFF;
	box-shadow:0 2px 10px rgba(0,0,0,0.3);
	-webkit-box-sizing: none;
	-moz-box-sizing: none;
	box-sizing: none;
	page-break-after: always;	
	margin:1.5cm auto;
	font-family:arial;
}
.page  {	
	height:27.9cm;/*asli:29  */
	width:19cm; /*asli:19, padding 2cm jadi 17 */
	padding:1.5cm 2cm 1.5cm 1.5cm;
}
.page-landscape{	
	width:29.7cm;
	height:19cm;
	padding:0.5cm 0.5cm 0.5cm 0.5cm;

}

.page2-landscape {
	width:33cm;
	min-height:19cm;
	margin:1.5cm auto;
	padding:-0.5cm;
}

.page-free{	
	margin:1cm auto;
	padding:0.5cm 0.5cm 0.5cm 0.5cm;
	overflow:auto;

}

.page-tractor{	
	width:19cm; 
	padding:0.5cm 0.5cm 0.5cm 0.5cm;
	overflow:auto;
}

.page .form-group,
.page-landscape .form-group,
.page2 .form-group,
.page2-landscape .form-group,
.page-free,
.page-tractor,
.form-group {
    margin-bottom: 0px;
}



table { page-break-inside:auto  }
tr    { page-break-inside:avoid; page-break-after:auto }
thead { display:table-header-group }
tfoot { display:table-footer-group }
table.tbcetakbergaris , 
.tbcetakbergaris th, 
.tbcetakbergaris td {
    border: 1px solid #000;
    border-collapse: collapse;
}


.tbcetakbergaris td { 
	padding:3px;
}
.tbcetaktanpagaris td {
    border:none;
}
.judultbcetak {
	font-size:13px;
	font-weight:bold;
	margin:0 0 20px 0;
	}
.page h1,
.page-landscape h1 {
font-size:20px;
font-weight:bold;
}
.page h2,
.page-landscape h2 {
	font-size:18px;
	font-weight:bold;
	margin:0px 0 15px 0;
}
.page h3,
.page-landscape h3 {
	font-size:13px;
	font-weight:bold;
	margin:12px 0px 5px 0px;
}
.judul2 {
	font-size:18px;
	font-weight:bold;
	}
.judul3 {
	font-size:13px;
	font-weight:bold;
}


.form-group {
    clear: both;
}	

.pre {
	background-color: none;
	border: none;
}
.dl{
	margin:5px 0px;
	clear:both;
	display:inline-block;
}
.dt {
	float:left;
	
	width:33.33%;
}
.dd {
	float:left;
	width:66.67%;
}

.dtdd {
	width:100%;
}

.col-sm-4,.col-sm-8 {
   float:left;
}
.col-sm-8 {
	clear:right;
}

/*
body, div, td, p {
		font-family:Arial, Helvetica, sans-serif;
		font-size:8pt;
	}
*/	
@media print {
	body {
		background:#fff;
	}
	@page {
	    /*size: A4;*/
		margin:0px ;/* 11mm 17mm 17mm 17mm;*/  
	    -webkit-print-color-adjust: exact;
	}
	
	.page,
	.page-landscape,
	.page2,
	.page2-landscape,
	.page-tractor,
	.page-free {
		box-shadow:none;
		width:100%;
		margin:0;
		background:none;
		/*border:1px solid #ccc;*/
	}

	.footer {
		position: fixed;
		bottom:0.7cm;
		left:0.7cm;
		right:0.7cm;
	}
	thead { 
		display: table-header-group;
	}
	.f12px,
	.f12px td {
		font-size:12px;
	}
	.f13px,
	.f13px td {
		font-size:13px;
	}
	.f14px,
	.f14px td {
		font-size:14px;
	}
}
</style>";
