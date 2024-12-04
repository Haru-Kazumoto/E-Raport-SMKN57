<?php
if (!isset($useAjaxMnuKiri)) $useAjaxMnuKiri=true;

if (!isset($_SESSION['tgmenu'])) $_SESSION['tgmenu']='';
//$tgmenu=$_SESSION['tgmenu'];//targetmenu
$tgmenu='content-wrapper';//targetmenu
 //$i++;$aMenu[$i]="Keluar|login&op=logout|fa fa-sign-out|_self|$showWidget=-|Yakin akan keluar dari program";

include_once $toroot."arrmenu.php";

/*
$aci=[
['fa fa-dashboard','fas fa-tachometer-alt'],
['fa fa-sign-out','fas fa-sign-out-alt'],
['fa fa-money','far fa-money-bill-alt'],
];
*/
if (!function_exists('changeIcnMenu')) {
	function changeIcnMenu($icn) {
		global $aci;
		if (isset($aci)) {
			foreach($aci as $ac) {
				if ($icn==$ac[0]) {
					return $ac[1];
				} 
			}
		}
		return $icn;
	}
}



$idmnu=0;
if (!isset($rndMenu)) {
	if (isset($_SESSION['rndmnu'])) 
		$rndMenu=$_SESSION['rndmnu'];
	else
		$rndMenu=$_SESSION['rndmnu']=$rnd;
}

$htmlWidget="";

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

	  /*
	  <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?=$tppath?>dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href='#' link="<?=$tppath?>#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
	  <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      
	  */
	  
	 $htmlMenu.=' 
	  <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
      ';
	  
	  $i=0;
	   $sDetController="";
		foreach ($aMenu as $mnu) {
			$am=explode("|","$mnu|||");
			$sm="";
			$spright=" <span class='pull-right-container'>
              <small class='label pull-right bg-red'>3</small>
              <small class='label pull-right bg-blue'>17</small>
            </span>";
			
			$spright="";
			//xx :bukan menu
			if ($am[1]=='xx') {
				$htmlMenu.=$am[0];
			} else {	
				//if ($am[1]=='')$am[1]=$am[0];//det
				if ($am[2]=='') $am[2]="fa fa-calendar";//det
				$am[2]=changeIcnMenu($am[2]);
				
				if (isset($aSubmenu[$i])) {
					$htmlWidgetSub="";
					$sm.="<ul class='treeview-menu'>";
					foreach ($aSubmenu[$i] as $xsm) {
						$onc="";
						$axsm=explode("|","$xsm||||||");
						if ($axsm[2]=='') $axsm[2]="fa fa-calendar";//det
						$axsm[2]=changeIcnMenu($axsm[2]);
				
						
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
					$spright='<span class="pull-right-container">
							  <i class="fa fa-angle-left pull-right"></i>
							</span>';
				}
				
				$idmnu++;
				$href='#';
				$onc="";
				
				$link=$adm_path."index.php?det=$am[1]&idmnu=$idmnu";
				if ($am[1]=='') 
					$link='#';
				
				if ($am[5]!='') {
					$onc="onclick=\"if (!confirm('$am[5]')) return false;\" ";
				}
				if (substr($am[1],0,3)=='js:') {
					$xam=substr($am[1],3);
					$onc="onclick=\"$xam;return false;\" ";
					$link='#';
				}

				if ($am[3]=='_blank') {
					$href="$link' target='_blank";
				} elseif($am[3]=='_self') {
					$href="$link";
				} else {
					if ($sm=="") {
						if ($useAjaxMnuKiri) {
							$acm="activateMnuKiri($idmnu,$rndMenu)";
							if ($am[5]!='') {
								$acm="if (confirm('$am[5]')) { $acm; } ";
							}
							$onc="onclick=\"$acm;return false;\"";
						} else {
							$href=$link;
						}
					}
				}
				
				
				$htmlMenu.="
					<li ".($sm==''?'':"class='treeview'")." >
				  <a lv='1' tgmenu='$tgmenu' href='$href' link='$link' $onc  id='mnu$idmnu'>
					<i class='$am[2]'></i> 
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
			$i++;
		}
		
		 $htmlMenu.=' 
		
		</ul>
    </section>
	<!-- /.sidebar -->
	';
   
?>