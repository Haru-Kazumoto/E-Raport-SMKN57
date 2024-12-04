<?php

class htmlMenu {
	public $idxMenu=0;//nomor index menu dan sub menu
	public $useAjax=true;
	public $tgMenu='content-wrapper'; //target menu
	public $rndMenu=1;
	public $htmlWidget="";//result widget
	public $htmlMenu="";//result menu
	public $aMenu=array();
	public $aSubMenu=array();
	public $aci=array(); //array change icon from default template (admin-lte) to other template 
	public $sDetController="";
	
	public $css="
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
		";
	public $addCss="";
	
	public function sample(){
		/*
		$m=new htmlMenu();
		$m->rndMenu=$rnd;
		/ *
		$m->tgMenu='content-wrapper';
		
		$i=0;$aMenu[$i]="Beranda|beranda|fa fa-dashboard";
		if(!$isOnline) {
			$i++;$aMenu[$i]="gen|default&op=gen|fa fa-book";
		}
		if (userType('admin,sa,direksi')) {
			$i++;$aMenu[$i]="Referensi||fa fa-folder-open|sm";
			$aSubmenu[$i][]="Data Kategori Barang|barangcat|fa fa-book|sm";
			$aSubmenu[$i][]="Data Barang|barang|fa fa-book|sm";
		}
		
			$i++;$aMenu[$i]="Keluar|login&op=logout|fa fa-sign-out||s|Yakin?|o";

		$aci=[
			['fa fa-dashboard','fas fa-tachometer-alt'],
			['fa fa-sign-out','fas fa-sign-out-alt'],
			['fa fa-money','far fa-money-bill-alt'],
			['fa fa-sticky-note-o','fa fa-bars'],
		];
		
		$this->aci=$aci;
		$this->aMenu=$aMenu;
		$this->aSubmenu=$aSubmenu;
		
		* /
		$htmlMenu=$m->show(0);//jresult: 0:htmlMenu,1:widgetMenu;
		
		
		*/
		
	}
	public function __construct() {
		global $toroot;
		global $useAjaxMnuKiri;
		global $rndMenu;
				
		global $spc;
		//if (!isset($_SESSION['tgmenu'])) $_SESSION['tgmenu']='';
		
		
		if (!isset($this->rndMenu)) {
			if (isset($_SESSION['rndmnu'])) 
				$this->rndMenu=$_SESSION['rndmnu'];
			else
				$this->rndMenu=$_SESSION['rndmnu']=$rnd;
		}
	}
	
	//jhasil: 0:htmlMenu,1:htmlWidget
	public function show ($jresult=0){
		global $toroot,$adm_path;
		
		$htmlWidget="";
		$aMenu=$this->aMenu;
		$aSubmenu=$this->aSubmenu;
		$rndMenu=$this->rndMenu;
		
		$htmlMenu="
			<style style='display:none'>
			$this->css
			$this->addCss
			</style>";
			
			$htmlMenu.="
			<!-- sidebar: style can be found in sidebar.less -->
			<section class='sidebar'>";

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
			   $this->sDetController="";
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
						$am[2]=$this->changeIcnMenu($am[2]);
						
						if (isset($aSubmenu[$i])) {
							$xs=$this->createSubmenu($aSubmenu[$i]);
							$sm=$xs[0];
							$htmlWidgetSub=$xs[1];
							$spright=$xs[2];
						}
						
						$this->idxMenu++;
						$href='#';
						$onc="";
						
						$link=$adm_path."index.php?det=$am[1]&this->idxMenu=$this->idxMenu";
						if ($am[1]=='') 
							$link='#';
						
						if ($am[5]!='') {
							$onc="onclick=\"if (confirm('$am[5]')) activateMnuKiri(this->idxMenu,$rndMenu);return false;\" ";
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
								if ($this->useAjax) {
									$acm="activateMnuKiri($this->idxMenu,$rndMenu)";
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
						  <a lv='1' tgmenu='$this->tgMenu' href='$href' link='$link' $onc  id='mnu$this->idxMenu'>
							<i class='$am[2]'></i> 
							<span >$am[0]</span>
							$spright
						  </a>$sm
						</li>       

						";
						
						
						if ($am[4]!='-') {	
							if ($sm=="") {
									$htmlWidget.="
									<a href='$href' link='$link' tgmenu='$this->tgMenu'  $onc  id='scmnu$this->idxMenu' class='shortcut'>
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
						$this->sDetController.=($this->sDetController==''?'':'|').$am[1];
					}
					$i++;
				}
				
				 $htmlMenu.=' 
				
				</ul>
			</section>
			<!-- /.sidebar -->
			';
			$this->htmlMenu=$htmlMenu;
			$this->htmlWidget=$htmlWidget;
			
			if ($jresult==0)
				return $htmlMenu;
			else
				return $htmlWidget;
			
	}
	
	public function changeIcnMenu($icn) {
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
	
	private function createSubmenu($asub){
		global $adm_path;
		$sm="";
		$rndMenu=$this->rndMenu;
		
		$htmlWidgetSub="";
		$sm.="<ul class='treeview-menu'>";
		foreach ($asub as $xsm) {
			$onc="";
			$axsm=explode("|","$xsm||||||");
			if ($axsm[2]=='') $axsm[2]="fa fa-calendar";//det
			$axsm[2]=$this->changeIcnMenu($axsm[2]);
	
			
			if ($axsm[1]=='') {
				//$axsm[1]=$axsm[0];//det
				$link='';
			} else {
				$link=$adm_path."index.php?det=$axsm[1]";
				
			}
			$this->idxMenu++;
			
			if ($axsm[3]=='_blank') {
				$href="$link' target='_blank";
			} elseif($axsm[3]=='_self') {
				$href="$link";
			} else {						
				if ($this->useAjax) {
					$onc="activateMnuKiri($this->idxMenu,$rndMenu);return false;";
					$href="#";
				} else {
					$href=$link;
					
				}
			}
			if ($this->tgMenu!="") {
				//$onc="bukaAjax('$this->tgMenu','$link&newrnd=$rnd',0,'awalEdit($rnd)');";
			}
			$sm.="
				<li class=''>
					<a href='$href' link='$link' lv='2' tgmenu='$this->tgMenu' onclick=\"$onc\" id='mnu$this->idxMenu' >
					<i class='$axsm[2]'></i> 
						<span >$axsm[0]</span>
					</a>
				</li>     
				";
			if ($axsm[4]!='-') {	
				$htmlWidgetSub.="
				<a href='$href' link='$link' tgmenu='$this->tgMenu' onclick=\"$onc\" id='scmnu$this->idxMenu' class='shortcut'>
					<i class='shortcut-icon $axsm[2]'></i>	
					<span class='shortcut-label'>$axsm[0]</span> 
				</a>
				";
			}
					
			$this->sDetController.=($this->sDetController==''?'':'|').$axsm[1];
		}
		$sm.="</ul>";
		$spright='<span class="pull-right-container">
				  <i class="fa fa-angle-left pull-right"></i>
				</span>';
				return [$sm,$htmlWidgetSub,$spright] ;
	}
	
}


/*
$aci=[
['fa fa-dashboard','fas fa-tachometer-alt'],
['fa fa-sign-out','fas fa-sign-out-alt'],
['fa fa-money','far fa-money-bill-alt'],
];
*/



   
?>