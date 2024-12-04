<?php

class htmlPrint { 
//yang akan dicetak $isi=''
	public $t="";
	public $id="";
	public $margin="2.5cm";
	public $clsPage="page";//jika dikosongkan maka tanpa menggunakan <div class=page
	public $alignBtn="right";
	public $addBtn="";
	public $useFormatCetak=false;
	public $media="";
	public $body="";
	
	function showHead(){
		global $rnd,$js_path;
		$t='';
		if ($this->media=='') {  
			$t.="
			<div class='breadcrumb2'>
				<div align='$this->alignBtn' style='margin-right:20px;margin-top:5px'>
					$this->addBtn
					<input type=button class='btn btn-sm btn-mini btn-success' value='Cetak' onclick=\"printDiv('tout_$rnd',2);\" >   
				</div>
			</div>
			 <div id='tinput_$rnd'></div>
			 <div id='tout_$rnd' class=tout>
			 ";
			$t.= "<link rel='stylesheet' href='$js_path"."style-cetak.css' >";
			//class: page/page-landscape
			if ($this->useFormatCetak) {
				$body=str_replace("#pb#","</div><div class='$clsPage'>",$this->body);
				$body=str_replace("#cekpb#","",$body);
				if ($this->clsPage!="") $t.= "<div class='$this->clsPage'>";
				$t.= $body;
				if ($this->clsPage!="") $t.= "</div>";
			}
			else $t.= $this->body;
			
			$t.="
			
			<style>
			.page,
			.page-landscape,
			.page2,
			.page-landscape2 {
					padding:$this->margin;
			
				}
				
				
			@media print {
				.page,
				.page-landscape,
				.page2,
				.page-landscape2 {
					padding:$this->margin;
					margin:0;
				}
			}
			</style>
			";	
			
			$t.= "</div>";//tout
		} else { //cetak pdf
		//if ($this->media=='pdf') {  
			$wtable=630;
			$wtable50=$wtable/2;
			$t.="<style>";
			$t.= file_get_contents("$js_path"."style-cetak.css");
			$t.="</style>";
			if (!isset($nfpdf)) $nfpdf="hasil_$rnd.pdf";
			$html=$t;
			$html=str_replace("'",'"',$html);
			include $um_path."head-pdf.php";
			
		}
		return $t;
	}

  

}
?>