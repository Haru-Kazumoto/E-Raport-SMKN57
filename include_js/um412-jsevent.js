var idleTime=0; 
var idleKeypress=0; 
var idleKeypressInt=130;//0.13 detik 
var lastIdleKeypress=0; 


function autoLogout() {
	//alert("Session Timeout.");
	$.ajax({
		url:"api.php?op=ceksesi",
		method:"post",
		data:{'det':'ceksesi'},
		success:function(data){			
			if (data==0) {
				try {
					logoutUser2(false);
					//location.href="index.php?op=logout";
				} catch(e) {}
			} else {
				//alert(data);
			}
		},
	});
	//location.href=urlLogout;
	//location.reload();
}

function resetTimerAL() {
	idleTime=0;	
}

function idleIncrement(){
	idleKeypress++;
	//document.title="Idle :"+idleTime;
}

function resetTimerKeypress() {
	lastIdleKeypress=idleKeypress;
	idleKeypress=0;	
	//alert("reset");
}


function idleKeyrpessIncrement(){
	idleKeypress++;
	//document.title="Idle :"+idleTime;
}

function cekTimeoutSesi() {
    if (timeoutSesi==0) 
		return ;
	else {
		//if (idleTime > timeoutSesi) { 
		if (idleTime/60 > timeoutSesi) {  
			idleTime=0;
			autoLogout();
		}
		
	}
}


if ((timeoutSesi>0)&& (isL==1)) {// 
	var events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'];
		
	events.forEach(function(name) {
		document.addEventListener(name, resetTimerAL, true); 
	});
	
	var idleInterval = setInterval('idleIncrement()', 1000); // 1 minute
	var cekTimeoutSesiInterval = setInterval("cekTimeoutSesi()", timeoutSesi*1000); // cek setiap timeout	
}


var eventsKeypress = [ 'keypress'];
	
eventsKeypress.forEach(function(name) {
	document.addEventListener(name, resetTimerKeypress, true); 
});

var idleKeypressInterval = setInterval('idleKeyrpessIncrement()',idleKeypressInt); // 0.3 minute
