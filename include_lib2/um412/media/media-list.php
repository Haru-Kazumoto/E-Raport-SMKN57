<?php
$isiml="<style>
.txtfile {
	max-height:28px;
	margin-top:-4px;
}
.w4 {
	padding:3px;
}
</style>
";

if (!isset($dir)) $dir=".";
if (!isset($useAjax)) $useAjax=true;
if (!isset($formatPreview)) $formatPreview='';
$thisfile=$_SERVER['PHP_SELF'];
$back="";
$includeSubfolder=false;//subfolder tidak ditampilkan
$showType=false;
$showSize=false;
$showAccess=true;
$showOprM=true;


if (!isset($showResult)) $showResult=false;
$dirArray=array();
$myDirectory = opendir($dir);

while($entryName = readdir($myDirectory)) {
	$dirArray[] = $entryName;
	
}
closedir($myDirectory); // close directory

$indexCount	= count($dirArray);//jumlah file & folder
//sort($dirArray);
sort($dirArray,0);

/*
$isiml.="
	<TABLE border=0 cellpadding=5 cellspacing=0 width=100% >\n
	<TR>
	<TH class=w1 >Filename</TH>";

if ($showType) $isiml.="<th class=class=w2 >Filetype</th>";
if ($showSize) $isiml.="<th class=w3 >Filesize</th>";
if ($showAccess) $isiml.="<th class=w4 >Access Folder</th>";
$isiml.="</TR>\n
	</table>\n";

*/

//for($index=0; $index < $indexCount; $index++) {
	if ($formatPreview=='') {
		$isiml.="
		<TABLE class='' cellpadding=5 cellspacing=0 width=100% >\n";
	}
for($index=0; $index < $indexCount; $index++) { //descending
	$idx=$indexCount-$index-1;
	$item="$dir/".$dirArray[$idx];
    $itemweb="$webFolder".$dirArray[$idx];
	//echo "<br>$itemweb";
	$tipefile=@filetype($item);
   	$ukuranfile=@filesize($item);
	$pi = pathinfo($item);
	$ext=$pi["extension"];
	$idthumb="thumb".rand(192827,22121227);
	$oprku='';
	if (($tipefile=='dir') && (!$includeSubfolder)) continue;
   //if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
		$nf=$dirArray[$idx];
		$tp=$tipefile;
		
		$ukuran=$ukuranfile;
		$tid="it".rand(111,9222221);
		$af="
		<div class='input-group' style='width:100%'>
		<input type=text class='txtfile form-control'
		value='$itemweb' id=txmd"."$index  >		
		";
		$af.="
		<span class='input-group-btn' style='width:7%'>
					";
		if ($useAjax) {
			$af.="
			
			<button 
			class='btn btn-primary btn-sm'
			onclick=\"bukaJendela('$item');return false\"
			title='Preview di jendela baru'>
			<i class='fa fa-image'></i></button>
			
			<button href=# 
			class='btn btn-info btn-sm btncopy'
			title='Copy url ke clipboard'
			data-clipboard-action='copy' data-clipboard-target='#txmd"."$index' >Copy URL</button>";
			$af.=" <button href=# 
			title='Menghapus file'
			class='btn btn-danger btn-sm'
			onclick=\"if (confirm('Yakin file :$nf  akan dihapus?')) { bukaAjax('$targetIdDOM','$nfAction&op=remove&nf=$nf&nomorfolder=$ix'); }\" ><i class='fa fa-times'></i></button>";
    
		}
		else {
			$sty="style='border:2px solid #fff;padding:3px 5px;text-align:center'";
			$oprku.="
			<div style='text-align:center;width:60px;position:relative;z-index:20;top:5px;bottom:0px;left:".($ukuranIconW-70)."px;' >
			<a href=# class='btn-danger' $sty
			onclick=\"$('#"."tt$tid').show();return false;\" >
			  <i class='fa fa-info'></i>
			  </a>
			
			<a href=# class='btn-danger' $sty
			onclick=\"if (confirm('Yakin file :$nf  akan dihapus?')) {
			bukaAjax('$targetIdDOM','$nfAction&op=remove&nf=$nf&nomorfolder=$ix');
			}\" >
			  <i class='fa fa-times'></i>
			  </a>
			</div> 
			<div style='background:#fff;display:none;position:absolute;z-index:23;margin:10px 5px;width:".($ukuranIconW-20)."px;overflow:auto' id='tt$tid'>#nama#</div>
			";
		}
		$af.="</span>";
		$af.="</div>";
		
		if ($formatPreview=='') {
			$isiml.="<TR>";
			//<TD class=w1 ><a href=\"$item\">".potong($nf,40)."</a></td>
			if ($showType) $isiml.="	<td class=w2 >$tp</td>";
			if ($showSize) $isiml.="<td class=w3 >$ukuran</td>";
			if ($showAccess) $isiml.="<td class=w4>$af</td>";
			if ($oprku!='') {
				if ($showOprM) $isiml.="<td class=w5 >$oprku</td>";
			}
			$isiml.="</TR>\n";
		}
		else {
			$fp=$formatPreview;
			
			$formatImg='<a href="#src#" target=_blank id="#id#" style="position:relative;z-index:10;top:-20px;">
				<img src="#src#" class="#class#">
				</a>';
				
			$fp=str_replace("#img#",$formatImg,$fp);
			
			if (strstr('mp4,3gp,dat,mov',$ext)!='') {
					//jika video
				$class="previewmovie";
				$fp=str_replace("#src#",$imgMovie,$fp);
			} else {
				$class="previewimg";
				$fp=str_replace("#src#",$item,$fp);
			}
			
			$fp=str_replace("#class#",$class,$fp);
			$fp=str_replace("#id#",$tid,$fp);
			$fp=str_replace("#opr#",$oprku,$fp);
			$fp=str_replace("#nama#",$nf,$fp);
	
			
			$isiml.=$fp;
			//if ($showOpr) $isiml.="$oprku";
		} 
		
}

if ($formatPreview=="") {
	$isiml.="</table>";	
}
$isiml.="
 <!--script src='$js_path"."clipboard/clipboard.min.js'></script-->";
$isiml.=fbe(" 
    var clipboard = new ClipboardJS('.btncopy');

    clipboard.on('success', function(e) {
        console.log(e);
		alert('data berhasil dicopy');
    });

    clipboard.on('error', function(e) {
        console.log(e);
    });
    ");
//if (isset($_REQUEST['back'])) print("<a href=\"".$_REQUEST['back']."\">back</a>\n");
if ($showResult)   echo $isiml;
?>