  <div id="top">

            <nav class="navbar navbar-inverse navbar-fixed-top " style="padding-top: 10px;">
                <a data-original-title="Show/Hide Menu" data-placement="bottom" data-tooltip="tooltip" class="accordion-toggle btn btn-primary btn-sm visible-xs" data-toggle="collapse" href="#menu" id="menu-toggle">
                    <i class="icon-align-justify"></i>
                </a>
                <!-- LOGO SECTION -->
                <header class="navbar-header" style='margin-right:20px;height:70px'>

                    <a href="index.html" class="navbar-brand">
                    <img src="<?=$toroot?>img/logo_diknas.png" style="max-height:60px;" alt="" />
                    </a>
                </header>
                <header class="navbar-header" style='margin-right:20px;margin-left:10px;font-size:20px;color:#fff'>

                SISTEM INFORMASI PENILAIAN 
                <BR>
                SEKOLAH MENENGAH KEJURUAN KURIKULUM 2013 REVISI
                </header>
                <!-- END LOGO SECTION -->
                <ul class="nav navbar-top-links navbar-right">


                    <!--ADMIN SETTINGS SECTIONS -->
                    <!--li class="dropdown">
                        <a class="dropdown-toggle btn btn-primary navbaruser" data-toggle="dropdown" href="#" ><?=$sisawkt?></a>
                    </li-->

                    <li class="dropdown">
                        <a class="dropdown-toggle btn btn-primary navbaruser" data-toggle="dropdown" href="#" style='color:#fff' >
                            <!--i class="icon-user "></i-->
                            <?php
                           // include_once "info-user.php";
							
							?>
                            <img src='<?=$fotoUser?>' style='width:30px;margin-right:5px;color:#fff'>&nbsp;<?=$userName?>
                            
                            &nbsp;<i class="icon-caret-down"></i>
                        </a>
						
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href=# onclick="bukaAjax('content','index.php?det=profile');return false"><i class="icon-user"></i> User Profile </a>
                            </li>
                            <li><a href=# onclick="bukaAjax('content','index.php?det=profile&op=edit');return false"><i class="icon-gear"></i> Settings </a>
                            </li>
                            <li class="divider"></li>
                            <li><a href="index2.php?det=login&op=logout"><i class="icon-signout"></i> Keluar </a>
                            </li>
                        </ul>
                    </li>
                    <!--li class="dropdown">
                        <a  href="index2.php?det=login&op=logout">
                            <i class="icon-signout "></i>&nbsp; Keluar
                        </a>

                      
                    </li-->
                    <!--END ADMIN SETTINGS -->
                </ul>

            </nav>

        </div>