<?php

class htmlTable {
	public $sql="";
	public $id="";
	public $sFld="";
	public $sJudul="";

	public $sAddAttr="";
	public $sFormat="";
	public $sAlignX="";
	public $sWidth="";
	public $outputas='html';
	public $pAddEv='';
	public $includehead=1;
	public $showNo=false;
	public $noAwal=1;
	public $sColJlh="-";
	public $tbStyle="view";
		
	//public $addEv;
	public $sAlign="";

	//penambahan kolom array(array('judul','isi','lebar','align','format'));
	public $addCol="";
	public $gPathUpload=array();

	public $usePagination=false;
	public $clsPage="page";
	public $brPerPage;
	public $headTb="";//judul tabel
	
	public $addJudul="";//tambahan judul
	public $addJudul2="";//tambahan judul halaman 2 dst
	
	public $emptyMsg="Tidak ada data";//teks pesan jika kosong
	public $colContent=array();
	public $oncRow="";
	public $addClassRow="";
	public $rowFooter="";
	

	function showSample(){
		/*
		$tb=new htmlTable();
		$tb->sJudul="NIS,NAMA";
		$tb->sql="select nis,nama,nftugas from tbsiswa";
		$tb->tbStyle="view";
		$tb->sAlign="C,C,F";
		$tb->showNo=true;
		$tb->configFld[3]="F,2000,D,1,upload/tugas/"; //konfig file kolom 3 karena ada no	
		$tb->colContent[4])="carifield();";
		$tb->sFormat=",,,,,,";		
		$tb->sColJlh="2,3";	//kolom yg akan dijumlah
		$tb->showNo=true;
		$tb->usePagination=true;
		$tb->clsPage="page";
		$tb->brPerPage=16;
		$tb->brPerPage2=26;
		$tb->addJudul=$headP;//tambahan judul
		$tb->addJudul2="";//tambahan judul halaman 2 dst
		$t.=$tb->show();
		*/
	}
		
	function show() {
		global $useDecimal;
		
		if ($this->sAddAttr=="") {
			if ($this->tbStyle=="view")
				$this->sAddAttr=" class='table table-bordered table-stripless' border=1 width=98% align=center cellspacing=1 cellpadding=5 ||";
			else
				$this->sAddAttr=" class='tbcetakbergaris' border=1 width=98% align=center cellspacing=1 cellpadding=5 ||";
			//default
		}
		$ssql=str_replace("<br>","",$this->sql);
		$asql=explode("|","$ssql|$ssql");
		$sql=str_replace("xx","",$asql[0]);
		
		//$aKol=getArrayFieldName($sql);
		//salign=C,-,C,L... -:tidak ditampilkan
		$arrTable=array();
		global $r;
		$sql=str_replace("-no-","1",$sql);
		$sql=str_replace(",,",",",$sql);
		//echo $sql;
		$h=mysql_query($sql) or die("<br>err htmltbl : ". mysql_error()." ") ;
		$nr=mysql_num_rows($h);
		if ($nr==0) return $this->emptyMsg;

		$sAlignX=$this->sAlignX;
		if ($sAlignX!='') $sAlign=$sAlignX;
				
		/*
		global $sClass;
		if ($sClass!='') $sAlign=$sAlignX;
		*/
		//$pAddEv=$this->pAddEv;
		//if ($this->pAddEv!=='') $this->addEv=$this->pAddEv;
		//if ($brPerPage==0) $brPerPage=40;
		$t="";
		
		/*
			$brPerPage=40;
		*/
		//mencari sfield
		if ($this->sFld=="") {
			$sfld=str_replace("select ","",$asql[1]);
			$sfld=str_replace("xx","",$sfld);
			$posfrom=strpos($sfld," from ");
			$sfld=substr($sfld,0,$posfrom);
			//mencari nama field
			foreach ($afld as $fld) {
				$posas=strpos($sfld," as ");
				if ($posas>0){
					$afld[$i]=substr($afld[$i],$posas,strlen($afld[$i]));
				}
				$i++;
			}
		} else {
			$sfld=$this->sFld;
			$afld=explode(",",$sfld);
		}
		$i=0;
		$arrKolJlh=array();
		if ($this->sColJlh!='-') {
			$arrKolJlh=explode(",",$this->sColJlh);
		}
			
		//kolom yang akan dijumlah diisi nilai 0
		$aKol=$aKolNew=array();
		$i=0; 
		if ($this->showNo) {
			$aKol[$i]="NO"; 
			$aKolNew[$i]="NO"; 
			$this->sJudul="NO,".$this->sJudul;
			$this->sAlign="C,".$this->sAlign;		
			$this->sFormat="N,".$this->sFormat;		
			//$this->sFld="no,".$this->sFld;		
			$i++;
		}
		
		$aJdlKol=explode(",",$this->sJudul);		
		$aAlign=explode(",",$this->sAlign);
		$aFormat=explode(",",$this->sFormat);
		$oo=0;
		$aFld=array();
		while ($oo < mysql_num_fields($h)) { 
			$meta = mysql_fetch_field($h, $oo);
			if (isset($aJdlKol[$i])) {
				if (strtolower(substr($aJdlKol[$i],0,2)!="xx")) {
					if (!isset($aFormat[$i])) $aFormat[$i]="";
					if (!isset($aAlign[$i])) $aAlign[$i]="C";
					$aKol[$i]=$meta->name; 
					$aKolNew[$i]=$meta->name; 
					$i++;
				}
			}
			$aFld[]=$meta->name; 					
			$oo++;
		}
		
		$jlhPureCol=$i;
		
		//jika ada penambahan kolom
		if (is_array($this->addCol)) {
			foreach ($this->addCol as $ev) {
				array_push($aJdlKol,$ev[0]);
				array_push($aAlign,(isset($ev[3])?$ev[3]:"C"));
				array_push($aFormat,(isset($ev[4])?$ev[4]:"S"));
				//$this->aWidth.="".(isset($ev[2])?$ev[2]:"");		
				$aKol[$i]=$ev[1]; 
				$aKolNew[$i]=$ev[1]; 
				$i++;
			}
		}
		

		
	//	print_r($aJdlKol);
	//	print_r($aKol);
		
		
		if ($this->includehead==1)  {
			$arrTable[]=$aKol;
		}
		//print_r($aKol);
		//echo $sql;
		//memmecah attr
		$headTb="";
		$addAttr=explode("|",$this->sAddAttr."||");	
		if ($this->id=="") $this->id="idt".genRnd();
		$addid="id='$this->id'";
		$headTb.="<table $addAttr[0] $addid >";
		$headTb.="<tr $addAttr[1]>";
		//atur lebar
		$aW=explode(",",$this->sWidth);
		$i=0;
		foreach($aKol as $kol) {
			if (!isset($aW[$i])) $aW[$i]=1;
			$i++;
		}
		$aW=hitungSkala($aW);
		//judul
		
	
		 
		$i=0;
		foreach($aKol as $kol) {
			$jdl=($aJdlKol[$i]==""?$kol:$aJdlKol[$i]);
			$jdl=str_replace("_"," ",$jdl);
			if ((substr($aJdlKol[$i],0,2)!='xx') &&($aAlign[$i]!='-')) {
				$aAlign[$i]=strtolower($aAlign[$i]);
				$align=($aAlign[$i]=="c"?"center":($aAlign[$i]=='l'?"left":($aAlign[$i]=='r'?"right":$aAlign[$i])));
				$headTb.="<th style='text-align:$align;width:$aW[$i]%' class='w$i'>$jdl</th>";
			}
			$i++;
		}
		$headTb.="</tr>";
		$this->headTb=$headTb;
		$t="";
		$pg=1;
		$t.=$this->showHeader(1);;
		//echo $jlhPureCol;
		
		$br=0;
		$arrJlh=array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
		while($r=mysql_fetch_array($h)) {
			$rndx=rand(123,99999);
			$troe=($br%2==0?$addAttr[1]:$addAttr[2]);
			if ($this->oncRow!="") {
				$onc="onclick=\"".evalGFF("=\"".$this->oncRow."\" ")." ;return false;\" ";
			} else {
				$onc="";
			}
		
			$t.="<tr class='$troe $this->addClassRow' $onc >";
			$t.="<span id=te$rndx style='display:none'></span>";
			$i=$j=$k=0;
			$jlhC=$colNew=array();
			foreach($aKol as $kol) {
				$aAlign[$i]=strtolower($aAlign[$i]);
				$align=($aAlign[$i]=="c"?"center":($aAlign[$i]=='l'?"left":($aAlign[$i]=='r'?"right":$aAlign[$i])));
				$format=strtolower($aFormat[$i]);
				if ((substr($aJdlKol[$i],0,2)!='xx') &&($aAlign[$i]!='-')) {
					if (($kol=="NO")||($aJdlKol[$i]=='No')) {
						$isi=$this->noAwal+$br;
					} elseif ($i<$jlhPureCol) {
						if (isset($this->colContent[$i])) {
							$isi=evalGFF($this->colContent[$i]);
						} else {
							$isi=$r[$kol];
						}
					} else {
						$isi=$kol;
					}
					
					if (strstr($isi,"#")!='') { //evaluasi jika ada data #nmfield#
						foreach($aFld as $f) {
							//echo $f.">";
							if (isset($r[$f])) $isi=str_replace("#$f#",$r[$f],$isi);
						}
						$isi=str_replace("#rndx#",$rndx,$isi);
					}
					//$isi=strtoupper(str_replace("_"," ",$isi));
					
					
					if (in_array($i,$arrKolJlh)) {
						$arrJlh[$i]+=$r[$kol]*1;
					}
					
					if ($format=='%') {
						$isi.=" %";
					}
					elseif ($format=='c') {
						$isi="".maskRp($isi,0,$useDecimal);
					}
					elseif ($format=='cx') {
						$isi="".maskRp($isi,0,9);
					}
					elseif ($format=='d') {
						$isi=tglIndo2($isi,"d M Y");
					}
					elseif ($format=='d2') {
						$isi=tglIndo2($isi,"d x Y");
					}
					elseif ($format=='d3') {
						$isi=tglIndo2($isi,"d-m-Y");
					}
					elseif ($format=='dt') {
						$isi=tglIndo2($isi,"d x Y H:i");
					}
					elseif ($format=='dts') {
						$isi=tglIndo2($isi,"d x Y H:i:s");
					}
					elseif ($format=='f') {
						$confFld=explode(",",$this->configFld[$i]);
						$sf=createLinkFile($snmfile=$isi,$confFld[4],$this->configFld[$i]);
						$isi="<center>$sf</center>";		 
					}
					elseif ($format=='f2') {
						$confFld=explode(",",$this->configFld[$i]);
						$sf=showNFFile2($nftugas=$isi,$path=$confFld[4],$cap="File Tugas","b");
				
						//$sf=createLinkFile($snmfile=$isi,$confFld[4],$this->configFld[$i]);
						$isi="<center>$sf</center>";		 
					}
					
					$t.="<td style='text-align:$align'>".$isi."</td>";
					$colNew[]=$isi;
					$j++;
				}				
				$i++;
			}
			
			$arrTable[]=$colNew;	
			$t.="</tr>";
			
			$br++;
			if ($this->usePagination) {
				if ($this->brPerPage>0){
					if ($br%$this->brPerPage==0) {
						$pg++;
						$t.="</table> ";
						$t.="</div>";
						$t.=$this->showHeader($pg);
					}
				}
			}
		}
		if ($this->rowFooter=='') {
			if ($this->sColJlh!='-') {
				$i=0;
				$t.="<tr >";
				//echo showTA($arrKolJlh);
				foreach($aKol as $kol) {				
					if (in_array($i,$arrKolJlh)) {
						$vv=maskRp($arrJlh[$i],0,0);
					} else {
						$vv=" ";
					}
					$t.="<td style='text-align:right'>$vv</td>";
					$i++;
				}
				$t.="</tr>";
			}
		} else {
			$isi=$this->rowFooter;
			$i=0;
			foreach($aKol as $kol) {				
				if (isset($arrJlh[$i])) {
					$isi=str_replace("#jlh$i#",maskRp($arrJlh[$i],0,0),$isi);
				}
				$i++;
			}
			$t.=$isi;
			
		}
			
		$t.="</table>";
		if ($this->usePagination) {
			$t.="</div>"; //page
		}
		if ($this->outputas=='html')
			return $t;
		else
			return $arrTable;
	}

	function showHeader($pg){
		$t="";
		if ($this->usePagination) {
			$t.="<div class='$this->clsPage' >";
		}
		if ($pg==1) 
			$t.=$this->addJudul;
		else
			$t.=$this->addJudul2;
		$t.=$this->headTb;
		return $t;
	}
	

	
}
?>