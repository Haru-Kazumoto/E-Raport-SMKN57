<?php
if (!isset($useAjaxMnuKiri)) $useAjaxMnuKiri=true;
if (!isset($mnuVer)) $mnuVer=1;

if (!isset($_SESSION['tgmenu'])) $_SESSION['tgmenu']='';
//$tgmenu=$_SESSION['tgmenu'];//targetmenu
$tgmenu='content-wrapper';//targetmenu

//var_dump($aMenu);exit;
$idmnu=0;
if (!isset($rndMenu)) {
	if (isset($_SESSION['rndmnu'])) 
		$rndMenu=$_SESSION['rndmnu'];
	else
		$rndMenu=$_SESSION['rndmnu']=$rnd;
}


function buatSubMenuKiri($mnu,$lv) {
	echo "$lv -> ".showTA($mnu);
	global $idmnu,$useAjaxMnuKiri,$adm_path,$rndMenu,$tgmenu;	
	global $htmlWidgetSub ;
	global $sDetController;
	$htmlWidgetSub="";
	
	if (!is_array($mnu)) {
		$mnu=array($mnu);
	}
	
	$sm="<ul class='treeview-menu'>";
	foreach ($mnu as $xsm) {
		$ssm='';
		if (is_array($xsm)) {
			$cp=$xsm[0];
			$ssm=buatSubMenuKiri($axsm[1],$lv+1);
		} else {
			$cp=$xsm;
			
		}
		$onc="";
		$axsm=explode("|","$cp||||||");
		if ($axsm[2]=='') $axsm[2]="fa fa-calendar";//det
		
		if ($axsm[1]=='') {
			//$axsm[1]=$axsm[0];//det
			$link='';
		} else {
			$link=$adm_path."index.php?det=$axsm[1]";
			
		}
		$idmnu++;
		
		if ($axsm[3]=='_blank') {
			$href="$link' target='_blank";
		} elseif($axsm[3]=='_self') {
			$href="$link";
		} else {						
			if ($useAjaxMnuKiri) {
				$onc="activateMnuKiri($idmnu,$rndMenu);return false;";
				$href="#";
			} else {
				$href=$link;
				
			}
		}
		if ($tgmenu!="") {
			//$onc="bukaAjax('$tgmenu','$link&newrnd=$rnd',0,'awalEdit($rnd)');";
		}
		$sm.="
			<li class=''>
				<a href='$href' link='$link' lv='2' tgmenu='$tgmenu' onclick=\"$onc\" id='mnu$idmnu' >
				<i class='$axsm[2]'></i> 
					<span >$axsm[0]</span>
				</a>
					$ssm
			</li>     
			";
		if ($axsm[4]!='-') {	
			$htmlWidgetSub.="
			<a href='$href' link='$link' tgmenu='$tgmenu' onclick=\"$onc\" id='scmnu$idmnu' class='shortcut'>
				<i class='shortcut-icon $axsm[2]'></i>	
				<span class='shortcut-label'>$axsm[0]</span> 
			</a>
			";
		}
			
		$sDetController.=($sDetController==''?'':'|').$axsm[1];
	}
	$sm.="</ul>";
	return $sm;//,$htmlWidgetSub,$spright);
}


function buatLiMenu($mnu,$lv=0) {
	global $idmnu,$useAjaxMnuKiri,$adm_path,$rndMenu,$tgmenu;	
	global $htmlWidget,$sDetController;
	global $htmlWidgetSub ;
	$htmlMenu="";
	if (is_array($mnu)) {
		//punya submenu
		$cp=$mnu[0];
		$sm=buatSubMenuKiri($mnu[1],1);	
		$spright='<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>';
				
	} else {
		$cp=$mnu;
		$sm="";	
	}
	$am=explode("|","$cp||||||");
	
	/*
	$spright=" <span class='pull-right-container'>
	  <small class='label pull-right bg-red'>3</small>
	  <small class='label pull-right bg-blue'>17</small>
	</span>";
	*/
	
	$spright="";
	if ($mnu=='') {
		//jika kosong
	}elseif ($am[1]=='xx') {
		//xx :bukan menu
		$htmlMenu.=$am[0];
	} else {	
		//if ($am[1]=='')$am[1]=$am[0];//det
		if ($am[2]=='') $am[2]="fa fa-calendar";//det
		
		/*
		if (isset($aSubmenu[$i])) {
			$aaa=buatSubMenuKiri($aSubmenu[$i]);
			$sm.=$aaa[0];
			$htmlWidgetSub.=$aaa[1];
			$spright=$aaa[2];
			
		}
		*/
		
		$idmnu++;
		$href='#';
		$onc="";
		
		$link=$adm_path."index.php?det=$am[1]&idmnu=$idmnu";
		if ($am[1]=='') $link='#';
		
		if ($am[3]=='_blank') {
			$href="$link' target='_blank";
		} elseif($am[3]=='_self') {
			$href="$link";
		} else {
			if ($sm=="") {
				if ($useAjaxMnuKiri) 
					$onc="onclick=\"activateMnuKiri($idmnu,$rndMenu);return false;\"";
				else
					$href=$link;
				if ($tgmenu!="") {
					//$onc="bukaAjax('$tgmenu','$link&newrnd=$rnd',0,'awalEdit($rnd)');";
				}
			}
		}
		
		
		$htmlMenu.="
			<li ".($sm==''?'':"class='treeview'")." >
		  <a lv='$lv' tgmenu='$tgmenu' href='$href' link='$link' $onc  id='mnu$idmnu'>
			<i class='$am[2]'></i> $lv
			<span >$am[0]</span>
			$spright
		  </a>$sm
		</li>       
		
		";
		
		
		if ($am[4]!='-') {	
			if ($sm=="") {
					$htmlWidget.="
					<a href='$href' link='$link' tgmenu='$tgmenu'  $onc  id='scmnu$idmnu' class='shortcut'>
						<i class='shortcut-icon $am[2]'></i>	
						<span class='shortcut-label'>$am[0]</span> 
					</a>
					";
			} else {
				$htmlWidget.="
				<div class='widget-header widget-header-shortcut' style='clear:both;display:block;'>$am[0]</div>
				$htmlWidgetSub";
			}
		}
		$sDetController.=($sDetController==''?'':'|').$am[1];
	}
	return $htmlMenu;
} //akhir buatlimenu

$htmlWidget="";
$htmlWidgetSub="";
$htmlMenu='
	<style style="display:none">
	.sidebar-menu > li {
		border-bottom: 1px #353430 solid;
	}
	.sidebar-menu, .main-sidebar .user-panel, .sidebar-menu > li.header {
		white-space: normal !important;
	}
	.sidebar-menu > li > a > .fa, 
	.sidebar-menu > li > a > .glyphicon, 
	.sidebar-menu > li > a > .ion {
		width: 15px;
	}
	.sidebar-menu > li > a {
		padding: 5px 5px 5px 15px;
		display: block;
	}
	.sidebar-menu > li > a>span {
		display:inline-block;
		margin: -18px 0px 0px 22px;	
	}
	.sidebar-menu > li:hover > a, 
	.sidebar-menu > li.active > a, 
	.sidebar-menu > li.menu-open > a {
		color: #fff;
		background: #37a8d5 !important;
	}
	</style>';
	
	$htmlMenu.='
	<!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">';
	  
	 $htmlMenu.=' 
	  <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
      ';
	  
	  $i=0;
	   $sDetController="";
		
		foreach($aMenu as $mn) {
			$htmlMenu.=buatLiMenu($mn,$lv=0);
		}
		$htmlMenu.=' 
		
		</ul>
    </section>
	<!-- /.sidebar -->
	';
   
?>