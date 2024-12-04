<?php
$acl1=[
	['aqua','#00c0ef'],
	['black','#111'],
	['blue','#0073b7'],
	['fuchsia','#f012be'],
	['gray','#d2d6de '],
	['green','#00a65a'],
	['lime','#01ff70'],
	['maroon','#d81b60'],
	['navy','#001f3f'],
	['olive','#3d9970'],
	['orange','#ff851b'],
	['purple','#605ca8'],
	['teal','#39cccc'],
	['yellow','#f39c12'],
	['yellow2','#f8821f'],
	['red','#dd4b39'],
	['red-active','#d33724'],
	['pink','#fb00e9'],	
	['pink2','#ed7cb0','#fff'],	
	['light-blue','#3c8dbc'],
	['light-gray','#f7f7f7','#000'],
	['light-green','#b4f2cc','#000'],
	['gray-active','#b5bbc8','#000'],
	['black-active','#000','#fff'],
	['yellow-active','#db8b0b'],
	['aqua-active','#00a7d0'],
	['blue-active','#005384'],
	['light-blue-active','#357ca5'],
	['green-active','#008d4c'],
	['navy-active','#001a35'], 
	['teal-active','#30bbbb'], 
	['olive-active','#368763'], 
	['lime-active','#00e765'], 
	['orange-active','#ff7701'], 
	['fuchsia-active','#db0ead'],
	['purple-active','#555299'], 
	['maroon-active','#ca195a'], 

];


//warna,warna back ligh,warna color ligh
$acl2=[
	['primary','#0073b7','#b5daf0','#03283e'],
	['danger','#dd4b39','#fbe3e0','#620707'],
	['success','#00a65a','#ccfbe5','#021e11'],
	['warning','#f39c12','#ffefd6','#512404'],
	['info','#00c0ef','#caeef7','#02333e'],
	 
	
];
/*
"
:root {
  --primary-color: #0076c6;
  --blur: 10px;
}

body {
  background-color: var(--primary-color);
}

.post {
  --padding: 1.5rem;
}
.post-content {
  padding: var(--padding);
}

";
*/
$t="
/*
um412-color.css 
mr.um412@gmail.com
generate: ".date('Y-m-d H:i:s')."
*/
[class^=\"bg-\"],
.box-title,
.box-header,
.box-header a, 
.box-header .btn {
    color: #fff 
}

.alert2 {
	border-radius: 3px;
	padding: 15px;
    margin-bottom: 20px;
    border: 1px solid transparent;
}

.alert2 a {
    color: #000;
    text-decoration: underline;
}

[class^=\"bg-\"].disabled {
 opacity:.65;
 filter:alpha(opacity=65);
}


.error, .text-red {
 color:#dd4b39 !important;
}

.bg-red,
.callout.callout-danger,
.alert-danger,
.alert-error,
.label-danger,
.modal-danger .modal-body {
	background-color:#dd4b39 !important:
}

.modal-danger .modal-header,
.modal-danger .modal-footer {
 background-color:#d33724 !important;
}

.modal-warning .modal-header,
.modal-warning .modal-footer {
 background-color:#db8b0b !important;
}

.modal-info .modal-header,
.modal-info .modal-footer {
 background-color:#00a7d0 !important;
}


.modal-primary .modal-header,
.modal-primary .modal-footer {
 background-color:#357ca5 !important;
}

.modal-success .modal-header,
.modal-success .modal-footer {
 background-color:#008d4c !important;
}


@media (max-width:1200px) {
}


";
foreach ($acl1 as $xcl) {
	$t.="
	
.box-$xcl[0] .box-header {
	background-color: $xcl[1];
}
.box.box-solid.box-$xcl[0] {
	border: 1px solid $xcl[1];
}
.text-$xcl[0] {
	color:$xcl[1] !important;
}
.bg-$xcl[0] {
	background-color:$xcl[1] !important;
	".(isset($xcl[2])?"color:$xcl[2];":"")."
}
	";
}

foreach ($acl2 as $xcl) {
$t.="
	.small-box, 
	.callout.callout-$xcl[0],
	.alert-$xcl[0], 
	.label-$xcl[0],
	.modal-$xcl[0] .modal-body,
	.modal-$xcl[0] .modal-header,
	.modal-$xcl[0] .modal-footer {
		color:#fff !important;
	}

	.alert-$xcl[0],
	.label-$xcl[0],
	.modal-$xcl[0] .modal-body {
		background-color: $xcl[1] !important;		
	}
	
	/*bglight*/
	.alert2-$xcl[0] {
		background-color: $xcl[2] !important;
		color: $xcl[3] !important;
	}
";
}

echo showTA($t); 
