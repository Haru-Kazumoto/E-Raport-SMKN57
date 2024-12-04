<style>

/* ================================================================ 
This copyright notice must be kept untouched in the stylesheet at 
all times.

The original version of this stylesheet and the associated (x)html
is available at http://www.stunicholls.com/menu/pro_drop_1.html
Copyright (c) 2005-2007 Stu Nicholls. All rights reserved.
This stylesheet and the associated (x)html may be modified in any 
way to fit your requirements.
=================================================================== */
#navatas, #navbawah {padding:0; list-style:none; height:28px;  position:relative; z-index:1500; font-family:arial, verdana, sans-serif; background:<?=$aCStyle[0]?>;  }
#navatas li.top {display:block; float:left;}
#navatas li a.top_link {display:block; float:left; height:28px; line-height:25px; color:#fff; font-weight:bold; text-decoration:none; font-size:12px; font-weight:bold; padding:0 0 0 12px; cursor:pointer;background:<?=$aCStyle[0]?>;}
#navatas li a.top_link span {float:left; display:block; padding:0 24px 0 12px; height:28px;background:<?=$aCStyle[0]?> right top;}
#navatas li a.top_link span.down {float:left; display:block; padding:0 24px 0 12px; height:28px; background:url(blanka.gif) no-repeat right top;}
#navatas li a.top_link:hover {color:#fff; background:  no-repeat;}
#navatas li a.top_link:hover span {background: no-repeat right top;}
#navatas li a.top_link:hover span.down {background:<?=$aCStyle[0]?> no-repeat right top;}

#navatas li:hover > a.top_link {color:#fff; background:  no-repeat;}
#navatas li:hover > a.top_link span {background: no-repeat right top;}
#navatas li:hover > a.top_link span.down {background:<?=$aCStyle[0]?> no-repeat right top;}

/* Default list styling */

#navatas li:hover {position:relative; z-index:200;}

/* keep the 'next' level invisible by placing it off screen. */
#navatas ul, 
#navatas li:hover ul ul,
#navatas li:hover ul li:hover ul ul,
#navatas li:hover ul li:hover ul li:hover ul ul,
#navatas li:hover ul li:hover ul li:hover ul li:hover ul ul
{position:absolute;  text-align:left;left:-9999px; top:-9999px; width:0; height:0; margin:0; padding:0; list-style:none;}

#navatas li:hover ul.sub
{left:0; top:28px; background: #fff; padding:3px; border:1px solid <?=$aCStyle[0]?>; white-space:nowrap; width:200px; height:auto; z-index:300;}
#navatas li:hover ul.sub li
{display:block; height:20px; position:relative; float:left; width:200px; font-weight:normal;}
#navatas li:hover ul.sub li a
{display:block; font-size:12px; height:20px; width:200px; line-height:20px; text-indent:5px; color:#000; text-decoration:none;}
#navatas li ul.sub li a.fly
{background:#fff  200px 7px no-repeat;display:block; text-align:left;}
#navatas li:hover ul.sub li a:hover 
{background:#3a93d2; color:#fff; }
#navatas li:hover ul.sub li a.fly:hover
{background:<?=$aCStyle[0]?> 200px 7px no-repeat; color:#fff;}

#navatas li:hover ul li:hover > a.fly {background:#3a93d2  200px 7px no-repeat; color:#fff;} 

#navatas li:hover ul li:hover ul,
#navatas li:hover ul li:hover ul li:hover ul,
#navatas li:hover ul li:hover ul li:hover ul li:hover ul,
#navatas li:hover ul li:hover ul li:hover ul li:hover ul li:hover ul
{left:90px; top:-4px; background: #fff; padding:3px; border:1px solid <?=$aCStyle[0]?>; white-space:nowrap; width:90px; z-index:400; height:auto;}


#navatas_top_search {display:block; float:left;  text-decoration:none; }

#cse-search-box {
	width:340px;
	height:28px;
	float:right;
	display:inline;
	text-align:right;
	display:none;

}
.textboxsearch {
	width:130px;
	height:18px;
	font-family:Arial, verdana, serif;
	font-size:1.1em;
	background:#fff;
}
.submitsearch {
	width:35px;
	height:20px;
	border:none;
	margin:4px 0 5px 5px;
	font-family:Arial, verdana, serif;
	font-size:1.1em;
	font-weight:bold;
	background:<?=$aCStyle[0]?>;
	color:#fff;
	cursor:pointer;
}


</style>