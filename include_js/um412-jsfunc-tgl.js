//fungsi tanggal
var days = ['MINGGU','SENIN','SELASA','RABU','KAMIS',"JUM'AT",'SABTU'];
var months = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
var tgl=new Date();
var th=tgl.getFullYear();
var tg=(tgl.getDate()>=10?"":"0")+tgl.getDate();
var bl=(tgl.getMonth()>=10?"":"0")+tgl.getMonth();
var jm=(tgl.getHours()>=10?"":"0")+tgl.getHours();
var mn=(tgl.getMinutes()>=10?"":"0")+tgl.getMinutes();
var dt=(tgl.getSeconds()>=10?"":"0")+tgl.getSeconds();
var hari=tg+"-"+bl+"-"+tgl.getFullYear();
var jam=jm+":"+mn+":"+dt;
var day = days[ tgl.getDay() ];
var month = months[ tgl.getMonth()];
var date = new Date(), y = date.getFullYear(), m = date.getMonth();
Date.prototype.gantiFormat=function(formatHasil){
	var d1 = this.getDate();
	var y1 = this.getFullYear(); 
	var m1 = this.getMonth()+1;
	return (d1>=10?d1:"0"+d1)+"/"+(m1>=10?m1:"0"+m1)+"/"+y1+"";
}

Date.prototype.awal= function(formatHasil) {
	return new Date(1970, 0, 1); 
}
Date.prototype.akhir= function(formatHasil) {
	return new Date(2037, 11, 31); 
}
Date.prototype.awalTahun= function(formatHasil) {
	var y = this.getFullYear(); 
	return new Date(y, 0, 1); 
}
Date.prototype.akhirTahun= function(formatHasil) {
	var y = this.getFullYear();
	return new Date(y, 11, 31); 
}
Date.prototype.awalBulan= function(formatHasil) {
	var date =this;var d = this.getDate();var y = this.getFullYear();var m = this.getMonth();
	return new Date(y, m, 1); 
}
Date.prototype.akhirBulan= function(formatHasil) {
	var date =this;var d = this.getDate();var y = this.getFullYear();var m = this.getMonth();
	return new Date(y, m+1, 0);  
}
Date.prototype.awalMinggu= function(formatHasil) {
	var gd =this.getDay();
	var d = this.getDate();var y = this.getFullYear();var m = this.getMonth();
	if (gd==1)
		return this;
	else
		return new Date(y, m, d-gd+1);  
}
Date.prototype.akhirMinggu= function(formatHasil) {
	var gd =this.getDay();
	var d = this.getDate();var y = this.getFullYear();var m = this.getMonth();
	if (gd==7)
		return this;
	else 
		return new Date(y, m, d+(7-gd));  
}

Date.prototype.kemarin= function(formatHasil) {
	var gd =this.getDay();
	var d = this.getDate();var y = this.getFullYear();var m = this.getMonth();
	return new Date(y, m, d-1);  
}