<?php

class htmlForm {
	public $nfAction="index.php";
	public $idForm="frm";
	public $nmForm="frm";
	public $nmValidasi="";
	public $rnd=0;
	public $tbSubmit="";
	public $useAjaxSubmit=false;
	public $tresult='';//tempathasilajax
	public $hideTresult=false;//tempathasilajax
	public $onsSubmit="";
	public $jenis="form";

	public $useClassDTDD=false;
	public $classdt='dtx';
	public $classdd='ddx';

	public $fbe="";
	public $fae="";
	public $showFbe=true;
	public $showFae=true;
	
	public $txtBeforeForm="";
	public $txtAfterForm="";
	public $txtAfterSubmit="";
	public $styleForm="";
	public $scriptCSS='';
	public $scriptJS='';
	public $scriptValidator='';
	public $hideFormAfterSubmit=true;
	private $t;
	private $isAkhirFormCalled=false;
	
	function addTxtAfterForm($t) {
		$this->txtAfterForm.=$t;
	}
	function addTxtBody($t) {
		$this->t.=$t;
	}
	function addTxtBeforeForm($t) {
		$this->txtBeforeForm.=$t;
	}
	function addTxtAfterSubmit($t) {
		$this->txtAfterSubmit.=$t;
	}
	

	function showExample() {
		/*
		//sample
		
		$frm=new htmlForm;
		$frm->nmForm="frm1";
		$frm->nmValidasi="$det";
		$frm->rnd=$rnd;	
		$frm->nfAction="index.php?contentOnly=1&useJS=2";
		$frm->useAjaxSubmit=true;//default
		$frm->hideFormAfterSubmit=false;
		$frm->tresult="tanggaran";
		$frm->tHideTesult=false;;
		$frm->awalForm();
		$frm->addHiddenField([["op","edit"],["det",$det]]);
		$frm->rowForm("rnama","Nama Lengkap","text|60|5|CF|fa-user");
		$frm->rowForm("tahun","Tahun","text|10|5");
		$frm->rowForm("bulan~".isiComboBulan("bulan"),"Bulan","text|30|5");
		$frm->rowForm("txtmateri2","Teks","TA3");
		$frm->rowForm("nfmgambar","File tambahan Materi (Gambar)","file|I|0|image/*|Pilih File Gambar|multiple","",$rab);
		//$frm->addHiddenField("det",$det);
		$frm->addTxtBody("<input type=submit class='btn btn-sm btn-primary' value='OK'>",$isi=" ");
		$frm->addFbe("");
		$frm->akhirForm();
		$form1= $frm->show();
		$t='';
		$t.=tpboxInput($form1,"Data Anggaran");
		$t.="<section class='content' id='tanggaran'></section>";
		*/		
	}

	function awalForm() {
		if ($this->rnd==0) $this->rnd=rand(1232313,9232313);
		$idform=$this->idForm=$this->nmForm."_".$this->rnd;
		$ons="";
		if ($this->useAjaxSubmit) {
			if ($this->nmValidasi=="") $this->nmValidasi=$this->nmForm;
			$tempat=($this->tresult==''?"ts$idform":$this->tresult);
			$ons="onsubmit=\"ajaxSubmitAllForm('$idform','$tempat','$this->nmValidasi','selesaiEdit($this->rnd);','$this->hideFormAfterSubmit');return false;\" ";
			$this->nfAction.="&useJS=2&contentOnly=1";	
		} elseif ($this->onsSubmit!='') 
			$ons="onsubmit=\"$this->onsSubmit;return false;\" ";
		 
		$t="";
		$t=$this->txtBeforeForm;
		$t.="<form id='$idform' action='".$this->nfAction."' $ons method='Post' 
					style='".$this->styleForm."' enctype='multipart/form-data'>
					";
		
		$this->classdt=($this->useClassDTDD?"dt":"");
		$this->classdd=($this->useClassDTDD?"dd":"");
		
		
		$this->t.=$t;
	}
	function addHiddenField($arr1,$v=""){
		
		$t="";
		if (is_array($arr1)) {
			foreach($arr1 as $ar) {
				$onc=(isset($ar[2])?" onchange='$ar[2]'":"");
				$t.="<input type=hidden name='$ar[0]' id='$ar[0]"."_".$this->rnd."' value='$ar[1]' $onc>";
			}
		} else {
			$t.="<input type=hidden name='$arr1' id='$arr1"."_".$this->rnd."' value='$v'>";
		}
		$this->t.=$t;
	}
	//$frm->rowForm("rnama","Nama Lengkap","text|60|5|CF|fa-user");
	function rowForm($nmField,$cap="",$sOption="text|30|5|",$def="xx",$sOption2="",$pathUpload=""){
		global $js_path,$lib_path;
		$skipCap=false;
		$t='';
		if (strstr($nmField," ")!="") {
			//jika ada spasi
			$t.='
				<div class="form-group">
				<div class="col-sm-12" valign=top >
				'.$nmField.'		 
				</div>
			</div>';
		
		} else {
			$isi=$isi2='';
			$tperr='';
			if (strstr($nmField,"~")!='') {
				$anfd=explode("~",$nmField);
				$nmField=$anfd[0];
				$isi2=$anfd[1];
			}
			
			if ($def=="xx") {
				  $ev="global $"."$nmField; 
				  if (isset($"."$nmField)) 
					  $"."def=$"."$nmField;
				  else
					  $"."def='';
					  ";
				eval($ev);
			}
			
			$nmFieldInput=$nmField."_".$this->rnd;
			$ao=explode("|",$sOption."|30|5|||||");
			
			$adds="";
			$iType=strtolower($ao[0]);
			$addclsdtdd=$iType;
				
			$stitle="";
			$isi='';
			if (($iType=="text")||($iType=="password")||($iType=="email")||($iType=="n")) {
				//$adds.="size='$ao[1]'";
				if ($ao[2]*1>0) {
					$adds.=" minlength='$ao[2]' required";
					$stitle.="Silahkan masukkan $cap, minimal $ao[2] huruf";
				}
				if ($iType=="n") {
					$iType="text";
					$adds.=" number=true";
					if ($ao[2]>0) $adds.=" required ";
					$stitle.="Silahkan masukkan $cap, minimal $ao[2] huruf";
				}
				elseif ($ao[0]=='email') {
					$adds.=" email='true'";
					if ($ao[2]>0) $adds.=" required ";
					$stitle.=", format email harus benar";
				}
				//<label for="cname">Name (required, at least 2 characters)</label>
				//$adds="minlength='2'  required";
				$isi.='<input type="'.$iType.'" name="'.$nmField.'" 
				id="'.$nmFieldInput.'" '.$adds.' 
				value="'.($iType=='password'?'':$def).'" size="'.$ao[1].'"
				title="'.$stitle.'">';
			} elseif (($iType=="file")){
				$sOption="|".$sOption; //|file|I|0|i,
				
				$allowEdit=true;//$so2[1];
				$bentuk=2;
				$replace=true;
				/*
				//$so2=explode(",",$sOption.",,,");
				$so2=explode("|",$sOption."|||||");
				
				//$replace=$so2[0];
				//$bentuk=$so2[2]==""?1:2;//jenis tampilan
				//echo "allow $sOption >> $allowEdit >";
				*/
				
				$isi=inpFile($nmField,$cap,$defNF="",$sOption,$replace,$allowEdit,$bentuk,$adds);
				
				
			/*
			} elseif ($iType=="file2"){
				$isi="";
				$stf=$ao[1];//str_replace(array("file2","-"),array("".""),$iType);
				if ($stf=="") $stf="I,V,D";
				if ($ao[2]=="") $ao[2]="2,20,5";				
				$atf=explode(",",$stf);
				$asize=explode(",",$ao[2].",,");
				$replace="";$allowEdit=true;$defNF="";
				//$isi.="<div style='position:relative'>";
				$i=0;
				//if ($dM) var_dump($atf);exit;
				
				foreach($atf as $tf) {
					$nama=$nmField."$i";
					$size=$asize[$i];
					
					///$sOption="|file|$tf|$size";
					if (($tf=="I")||($tf=="G")!='') {
						$sOption="|file|$tf|$size|image/*|Pilih File";
						$isi.=inpFile($nmField,$cap,$defNF,$sOption,$replace,$allowEdit,$bentuk=2);
						$isi.=inpFile($nmField,$cap,$defNF,$sOption,$replace,$allowEdit,$bentuk=2);
						$isi.=inpFile($nmField,$cap,$defNF,$sOption,$replace,$allowEdit,$bentuk=2);
						
						//$isi.=$this->inpFile($nmField,$cap,$defNF="",$sOption);
						//$isi.=$this->inpFile($nmf,$cap,$defNF="",$sOption);
						//$isi.=$sOption;	
						//$isi.=$this->inpFile($nmField."a$i",$cap,$defNF,$sOption);
					}
					//if ($tf=="V") $isi.=$this->inpFile($nmField."_1",$cap,$defNF,$sOption="file|V|$size|video/*|Pilih file Video (MP4)",$replace,$allowEdit,2);
					//if ($tf=="D") $isi.=$this->inpFile($nmField."_2",$cap,$defNF,$sOption="file|D|$size|application/pdf,text/plain|Pilih File Dokumen (Word,PPT,Excel,PDF)",$replace,$allowEdit,2);
				
				
					
					$i++;
				}
				
				
				//$isi.="<br></div>";
				
			} elseif (($iType=="filep")){
				global $userType,$userid;
				$sOption="|".$sOption; //|file|I|0|i,
				//progress
				$cap="upload file";
				$idup="idup".genRnd(1,100,true);
				
			
				$sup1=[
				'cap'=>'',
				'id'=>$idup,
				'pathUpload'=>$pathUpload,
				'owntype'=>$userType,
				'ownid'=>$userid,
				'option'=>$sOption,
				];
				
				$_SESSION[$idup]=$sup1;
				
				$url=$js_path."/uploader1/index.php?idup=$idup";
				$isi="
				<span id=t$idup>
				$idup
				<iframe id=if$idup src='$url'></iframe>
				</span>";
			*/	
			} elseif (substr($iType,0,2)=="ta"){//textarea
				$addclsdtdd="ta";
				$jenista=($iType=="ta1"?1:($iType=="ta2"?2:3));
				$isi='
				<textarea  class="'.$iType.'" name="'.$nmField.'" id="'.$nmFieldInput.'" '.$adds.'  rows=3>'.$def.'</textarea>
				';
				$this->fbe.="cke('$nmFieldInput','$jenista');";
			}
			
			if ($isi2!='') $isi=$isi2;
			
			$t="";
			if ($tperr=='') $tperr='<div style="display:none" id="'.$nmField.'-err" ></div>';
					
			if ($cap=="-") {
				$t.='
				<div class="form-group" id="dl'.$nmField.'">
					<div class="'.$this->classdd.' col-sm-12" valign=top  id="dd'.$nmField.'" >
					'.$isi.'		 
					'.$tperr.'
					</div>
				</div>
					';
			} elseif ($ao[3]=='CF') {
				$t.="
				<div class='form-group has-feedback'>
						<input type='".$iType."' class='form-control' $adds placeholder='$cap' name='$nmField' value='$def' title='$stitle'>
						$tperr
						<span class='fa $ao[4] form-control-feedback'></span>
				</div>
				";
			} else {
				$t.='
				<div class="form-group" id="dl'.$nmField.'">
					<div class="'.$this->classdt.' col-sm-3 col-xs-5 dt'.$addclsdtdd.' " valign=top id="dt'.$nmField.'">'.$cap.'</div>
					<div class="'.$this->classdd.' col-sm-9 col-xs-7 dd'.$addclsdtdd.' " valign=top  id="dd'.$nmField.'" >
					'.$isi.'		 
					'.$tperr.'
					</div>
				</div>
					';
			}
			
			$this->scriptValidator.="
				 if (element.attr('name') == '$nmField' ) {
					if (error!=''){
						$('#$nmField-err').html(error);
						$('#$nmField-err').show();
					} else {
						$('#$nmField-err').hide();					
					}
				}
			";
		}
		$this->t.=$t;
		
	}
	
	function rowForm2($nmField,$txt){
		
		$this->t.=$txt;
		if ($nmField!='') {
			$this->scriptValidator.="
				 if (element.attr('name') == '$nmField' ) {
					if (error!=''){
						$('#$nmField-err').html(error);
						$('#$nmField-err').show();
					} else {
						$('#$nmField-err').hide();					
					}
				}
			";
		}
	}
	
	function rowITB2($nmField,$cap="",$lebarOrJnsInput="text|5|20"){
		$this->rowForm($nmField,$cap,$lebarOrJnsInput);
	}
	 
	//$isi=inpFile($nmField,$cap,$defNF,$sOption);
	
	//rowitb3("nama_reg~Nama Reg._30#email_reg~Email");
	function rowITB3($sFldCap,$lebar=35,$param="~",$jenisITB=""){
		global $scolsm_rowitb,$jenisITB3,$aDefDLDT,$rnd;
		if ($jenisITB!="") $jenisITB3=$jenisITB;
		if (!isset($scolsm_rowitb)) {
			$scolsm_rowitb="3,9";
			$acolsm=explode(",",$scolsm_rowitb);
		} else {
			$acolsm=$aDefDLDT;		
		}
		$acolsm=explode(",",$scolsm_rowitb);
		if ($sFldCap=="") return "";
	 
		$cap=$isi="";
		$afc=explode("#",$sFldCap);
		$i=0;
		$nmFieldInput="";
		foreach($afc as $fc) {
			$afd=explode("|",$fc."|||||");
			$anmf=explode($param,$afd[0]);
			$nmFieldAsli=$nmField=$anmf[0];
			if (!isset($anmf[1])) {
				//group
				$cap=$nmFieldAsli;
				$isi="";
				
			} else { 
				
				if ($i==0) 
					$cap=$anmf[1];
				else
					$isi.=" $anmf[1] : ";
				$lb=(!isset($anmf[2])?$lebar:$anmf[2]);
						
				if (strstr($nmField," ")!='') {
					$isi.=$nmFieldAsli;
				} else {
						
					$def="";
					$nmFieldInput=$nmField."_".$rnd;
			 
					if ($i==0) {
						//$cap.=$afd[1];
					} else {
						//$isi.=" $afd[1] : ";
						
					}
					if ($nmField!='') {
						//$lb=($afd[2]==""?$lebar:$afd[2]);
						
						if ((strstr($nmField," ")!='')||($jenisITB3=='view')) {
							$isi.=$nmFieldAsli;
						} else {
							//echo ">".$nmField."<br>";
							if ($nmField!='') {
								eval("global $"."$nmField; $"."def=$"."$nmField;");
								$nmFieldInput=$nmField."_".$rnd;
								$isi.="<input type=text name='$nmField' id='$nmField"."_$rnd' value='$def' size='$lb'>";
							}
						}
					}
					if ($afd[3]!='') $isi.=" ".$afd[3];
				}
			}
			$i++;
		}
		
		if ($isi=="") {
			$t='
				<div class="form-group">
					<div class="col-sm-12 subtitleform2" valign=top>'.$cap.'</div>
				</div>
			';
			
		} else {
			$t='
				<div class="form-group ">
					<div class="col-sm-'.$acolsm[0].' col-xs-5" valign=top>'.$cap.'</div>
					<div class="col-sm-'.$acolsm[1].' col-xs-7 tdform2x" valign=top  id="dd'.$nmFieldInput.'" >'.$isi.'</div>
				</div>
			';
		}
		$this->t.=$t;
	}

	function rowView($cap="",$isi="-x-",$usedd=false){
		$t="";
		if (($isi=="-x-") ||($cap=='')) {
			$isi=($isi=='-x-'?$cap:$isi);
			
			$t.='
			<div class="form-group ">
				<div class="dtdd  col-sm-12" valign=top>'.$isi.'</div>
			</div>
		
			';			
		} else {
			if ($usedd) $isi=": ".$isi;
			$t.='
		 
				<div class="form-group ">
					<div class="'.$this->classdt.' col-sm-3 col-xs-5" valign=top>'.$cap.'</div>
					<div class="'.$this->classdd.' col-sm-9 col-xs-7" valign=top  >'.($cap==''?'':'').$isi.'		 
					</div>
				</div>
		 
				';
		}
		$this->t.=$t;
	}
	
	function akhirForm($capSubmit="",$cls="primary") {
		$this->classdt=($this->useClassDTDD?"dt":"");
		$this->classdd=($this->useClassDTDD?"dd":"");
		
		$t="";
		if ($capSubmit!="") {
			$t.='
			<div class="form-group">
				<div class="'.$this->classdt.' col-sm-3 col-xs-5 " valign=top>&nbsp;</div>
				<div class="'.$this->classdd.' col-sm-9 col-xs-7 " valign=top  id="ddsubmit" >
				<input type=submit value="'.$capSubmit.'" class="btn btn-'.$cls.'">
				</div>
			</div>
				';
		}
		$t.=$this->txtAfterSubmit;
		$t.="</form>";
		$t.=$this->txtAfterForm;
		if ($this->tresult=='') 
			$t.="<div id='ts".$this->idForm."' ".($this->hideTresult?"style='display:none;margin-top:15px'":"").">
				</div>";

		
		$this->t.=$t;
		$this->isAkhirFormCalled=true;
	}
	
	function addScriptJS($t) {
		$this->scriptJS.=$t;
	}
	function addScriptValidator($t) {
		$this->scriptValidator.=$t;
	}
	function addFbe($t){
		$this->fbe.=$t;
	}
	function addFae($t){
		$this->fae.=$t;
	}
	
	function show(){
		if (!$this->isAkhirFormCalled) {
			$this->akhirForm();
		}
		
		if ($this->jenis=='form') { 		
			if ($this->scriptValidator!='') {
				$this->addScriptJS(" 
				$('#".$this->idForm."').validate({
						errorPlacement: function(error, element) {
							".$this->scriptValidator."
						}
				});");
			}
			
			$t= $this->t;
			//jika mengunakan ajax submit, fae ditambah
			if ($this->scriptJS!='') $this->fbe.=$this->scriptJS;
			if ($this->showFbe) {
				$t.= ($this->fbe==""?"":fbe($this->fbe,$this->rnd));
			}
			if ($this->showFae) {
				$t.= ($this->fae==""?"":fae($this->fae,$this->rnd));
			}
			/*
			$t.="
			$(document).ready(function() {
				$this->scriptJS
			});
			";
			*/
		} else {
			$t=$this->t;
		}
		
		return $t;
		
	}
}


