<?php
//css untuk lx300
if (!isset($clspage)) $clspage="A4";
if (!isset($marginPage))  {
	if ($clspage=="A4")
		$marginPage="1cm ";
	else
		$marginPage="0.5cm ";
}
echo "

<style>
#tampilan_$rnd,
#tampilan_$rnd td {
	font-size: 16px;
	font-family: Calibri;
}

#tampilan_$rnd td { 
	padding:3px 
}
.tbkepada td {
	padding:0px !important; 
	
}
.tdjudul { 
	text-align:center;

}
.sket {
	border:1px solid #000;
	padding:0px 5px;
	margin-left:-1px;

}
.stot {
	border:1px solid #000;
	padding:1px 5px;
	margin-left:10px;

}
.jdlfaktur {
	font-weight:bold;
	font-size:28px;
	text-align:center;
}

";

if ($clspage=="A5") {
	echo "	
	.page {
	  padding: $marginPage;
	  background: white;
	  width: 21cm;
	  height: 15.85cm;
	  display: block;
	  margin: 0 auto;
	  margin-bottom: 0.5cm;
	} 
	@media print {
	  body, .page {
		padding:$marginPage;
		height: 14.85cm;
	  }
	}
	"; 
} else {
	//A4
	echo "	
	.page {
	  padding:$marginPage;
	  background: white;
	  width: 21cm;
	  height: 29.7cm;
	  display: block;
	  margin: 0 auto;
	  margin-bottom: 0.5cm;
	}
	@media print {
	  body, .page {
		padding:$marginPage;

	  }
	}

	"; 

	
}
echo "

@media print {
  body, .page {
    margin: 0;
    box-shadow: 0;

  }
}
</style>
";

?>
