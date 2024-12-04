$(document).ready(function() {
	//custom admin-lte berlaku untuk semua
	w=$(window).height();
	h=$('.main-header').height();
	hh=(w-h);
	
	$('.content-wrapper').css("height",hh+"px");
 
	s=$('.sidebar-menu').html();
	sw=$('.sidebar-menu').width();
	wsb=$(window).width();
 
	if (wsb<800) {
		//$('.content-wrapper').css("margin-left",'0px');
		$('.logoX').css("display",'none');
		//$('.content-wrapper').css("width",'100%');
	} else {
		wsb=$(window).width()-sw;	
	}
	
	
});

