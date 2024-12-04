<?php
require_once $um_class."db.php";
class htmlJSON {
	public $jeda=5000;
	public $name="sesi";
	public $sql="";
	public $awalT="<table class='table table-bordered table-stripless' width='98%' cellspacing='1' cellpadding='5' border='1' align='center'><tbody><tr><th style='text-align:center'>KELAS</th><th style='text-align:center'>JLH. SISWA</th></tr>";
	public $akhitT="</table>";
	public $templateDT='"<tr ><td align=center>"+obj.KELAS+"</td><td align=center>"+obj.JLH_SISWA+"</td></tr>"';
	public $urlCek="index.php?op=ceksison";	//tempate cek 
	
	function cek(){
		global $db;
		$nm=$this->name;
		$dt=$db->query($this->sql)->fetch("json");
		if (isset($_SESSION[$nm])) {
			if ($dt==$_SESSION[$nm]) { 
				//return $dt;
				return "x"; 
			}
		} 
		$_SESSION[$nm]=$dt;
		return $dt;
	}
	
	function createPlace(){
		global $db;
		$tempat="t".$this->name;
		$w="<div id='$tempat'></div>";
		$dt=$db->query($this->sql)->fetch();
		
	}
	function createScript($useScriptTag=true){
		$nama=$this->name;
		$namaFC="cek".$nama;
		$jeda=$this->jeda;
		$template=$this->templateDT;
		$t="
		awalt=\"$this->awalT\";
			 
			function $namaFC"."(){
				//console.log('reload');
				myurl='".$this->urlCek."&contentOnly=1&useJS=2&idj=".$nama."';
				$".".ajax({
					url:myurl
				}).done(function(response){
					if (response=='x') {
						
					} else {
						 
						var as = JSON.parse(response);
						console.log(response);
						
						t=awalt;
						$".".each($".".parseJSON(response), function(idx, obj) {
							eval('t+=$template;');
						});
						t+='</tbody></table>';
						$"."('#t".$this->name."').html(t);
						 
					}
					setTimeout('$namaFC"."()',$jeda);
				});
			}
			setTimeout('$namaFC"."()',$jeda);";
			if ($useScriptTag)
				return "<script>$t</script>";
			else
				return $t;
	}
}

?>