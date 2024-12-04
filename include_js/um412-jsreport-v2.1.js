 //pbps:pilih banyak, pilih satu pb/ps, nc=needconfirm
/*
 tbOpr('tb','$det',$rnd,$rndInput,'$configFrmInput',pbps,'awalEdit($rndInput);',$sdint);
sdint=show detail in new tab  (not in dialog)
*/
//function tbOpr(sop,sdet,rnd,rndInput,conf,pbps,nc,sdint){

$needReloadMnuKiri=false;


function tbOpr2(rnd,param,conf){
	/*
	sop:""
	conf:"width:wMax,height:hMax"
	tbopr2($rnd,'det=$det&idtd=$idtd&jsconfirm=1&jspilih=1&newrnd=$rnd&rndinput=$rndinput&jsmode=window','width:wMax');
	*/

	sdint=false;
	needConfirm=false;
	pbps="";
	rndInput=rnd;
	idtd="tinput_"+rnd;
	
	console.log(param);
	jsmode=getValueURL(param,"jsmode","a");
	if (jsmode[1]!='') {
		sdint=true;
		param=param.replaceAll(jsmode[0],"");
	}
	
	
	j=getValueURL(param,"jsconfirm","a");
	if (j[1]!='') {
		needConfirm=true;
		param=param.replaceAll(j[0],"");
	}
	
	j=getValueURL(param,"jspilih","a");
	if (j[1]!='') {
		pbps=j[1];
		param=param.replaceAll(j[0],"");
	}

	j=getValueURL(param,"rndinput","a");
	if (j[1]!='') {
		rndInput=j[1];
		param=param.replaceAll(j[0],"");
	}
	
	j=getValueURL(param,"idtd","a");
	if (jsmode[1]!='') {
		idtd=j[1];
		//param=param.replaceAll(jsmode[0],"");
	} 
	
	j=getValueURL(param,"sdet","a");
	if (j[1]!='') {
		sdet=j[1];
		param=param.replaceAll(j[0],"");
	} else {
		j=getValueURL(param,"det","a");
		if (j[1]!='') {
			sdet=j[1];
			param=param.replaceAll(j[0],"");
		}
		else  sdet='apaya';
	}
	
	for(i=1;i<=10;i++) { param=param.replaceAll("&&","&"); }
	
	sop=param;
	tbOpr(sop,sdet,rnd,rndInput,conf,pbps,needConfirm,sdint,idtd);	
}


//ganti isi kolom pertama di datatable jadi checkbox
function changeColDT(det,rnd,tbOprPos) {
		//if (tbOprPos!=2) return;
		if (tbOprPos==0) return;
	
		var table=$('#tbumum'+rnd).DataTable();
		table.rows().every( function ( rowIdx, tableLoop, rowLoop ) {
		   br=rowIdx+1;
		   var data = this.data();
		   val=data[0];
		   data[0]='<center>';
		   data[0]+='<input id=chb'+det+br+' name=chb'+det+' type=checkbox  value=\"'+val+'\" >';
		   data[0]+='<span id=idtd'+det+br+' class="stdn" ></span>';
		   data[0]+='</center>';
		   this.data(data);
		   
		   
		   
		} );
}

function tbOpr(sop,sdet,rnd,rndInput,conf,pbps,needConfirm,sdint,idtd){
	//console.log("tbopr( sop="+sop+",sdet="+sdet+",rnd="+rnd+",rndinput="+rndInput+",conf="+conf+",pbps="+pbps+",needConfirm="+needConfirm+",sdint="+sdint+",idtd="+idtd+");");
	if (pbps===undefined) pbps=0;
	if (sdint===undefined) sdint=false;
	if (needConfirm==undefined) needConfirm=false;
	if (idtd==undefined) idtd="tinput_"+rndInput;
	aop=(sop+"|").split("|");
		
	hal=$("#thal_"+rnd).html();
	haltkn=$("#thaltkn_"+rnd).html();
	//tambahanparameter
	paramOpr=encodeURI($("#tParamOpr_"+rnd).val());
	op=aop[0];
	addurl=aop[1].trim();
	adet=(sdet+"|"+sdet).split("|");	det=adet[0];	dettujuan=adet[1];
	var pes="";
	aid=getValueArrayChecboxByName('chb'+det);
	//alert(aid);
	//ps:pilih satu, pb:pilih banyak
	
	
	needps=(addurl.indexOf('&ps=1')>0?true:(pbps==1?true:(',ed,view,'.indexOf(','+op+',')>=0?true:false)));
	needpb=(addurl.indexOf('&pb=1')>0?true:(pbps>1?true:(needps?true:(op=='del'?true:false))));
	
	ispb=isps=false;
	if (aid.indexOf(',')>0) {
		ispb=true;
	}else if (aid!='') {
		isps=true;
		ispb=true;
	}  
	
	
	if ((needps) &&(!isps)){
		pes+="Silahkan pilih salah satu data terlebih dahulu";		
	}
	else if ((needpb) &&(!ispb)){
		pes+="Silahkan pilih data terlebih dahulu";		
	}
	
		
	/*
	if (((op=='ed') || (op=='del')||(op.substring(0,3)=='ps_')||(op.substring(0,3)=='pb_') ) && (aid=='')) {
			pes+="Silahkan pilih data terlebih dahulu";
	} else if (((addurl.indexOf('&ps=')>0)) && (aid=='')) {
			pes+="Silahkan pilih satu data terlebih dahulu";
	} else if ((addurl.indexOf('&ps=')>0) && (aid.indexOf(',')>0)) {
			pes+="Silahkan pilih satu data saja";
	} else if ((pbps.indexOf('p')>0) && (aid=='')) {
			pes+="Silahkan pilih data terlebih dahulu";
	} else if ((pbps.indexOf('ps')>0) &&   (aid.indexOf(',')>0)) {
			pes+="Silahkan pilih satu data saja";
	} else if ((addurl.indexOf('&ps=')>0) && (aid.indexOf(',')>0)) {
			pes+="Silahkan pilih satu data saja";
	} else if ((pbps==1) && (aid==''))  {
			pes+="Silahkan pilih salah satu data";
	} else { //operasi lain, misal=upload
		//
	}
	*/
	if (pes!=''){ 
		bukaDialog(idtd,pes,"-m-,width:300"); 
		return false;
	}
	if (conf==undefined ) 
		conf='width:600';
	else if (conf=="" ) 
		conf='width:600';
	
	newop=(op=='ed'?'itb':(op=='tb'?'itb':(op=='del2'?'del':op)));
	myurl0='index.php?det='+dettujuan+'&parentrnd='+rnd+'&newop='+op+'&op='+newop+'&idtd='+idtd+'&newrnd='+rndInput+'&tknlinkback='+haltkn;
	myurl0+=paramOpr;
	//if (!sdint) myurl0+='&useJS=2';
	if (op=='del2') aid=rndInput+"";	
	myurl=myurl0;
	if (op!='tb') myurl+='&aid='+aid;
	if (addurl!='') {
		myurl+="&"+addurl;
		
	}
	
	
	if ( (op=='del')||(op=='del2'))   {
		if (!confirm("Yakin akan menghapus")) return ;
		myurl+='&contentOnly=1&useJS=2';
		$.ajax({
			url: myurl
		}).done(function(data) {
			if (data.length<3) {
				//berhasil
				var table=$("#tbumum"+rnd).dataTable();
				xid=aid.split(",");
				if (xid.length>10) { 
				
				} else {
					xid.forEach(function(v) {
						var rowIndex = table.fnGetPosition($("#tbumum"+rnd+" input[name=chb"+det+"][value='"+v+"']").closest('tr')[0]);
						table.fnDeleteRow(rowIndex);
					});
				}
				alert('Penghapusan berhasil'+data);
			}else {
				alert(data);
			}
			//$('#twaitscroll').remove();
			//$(xtload).append(data);
		});
	}
	else if ((op=='ed')||(op=='tb')||(op=='itb')) {
		if (sdint)
			window.open(myurl);
		else {	
			bukaAjaxD(idtd,myurl,conf,'awalEdit('+rndInput+')');
		}
		
	} else {
		if (needConfirm) {
			if (!confirm("Yakin akan menjalankan operasi ini?")) return ;
		}
		
		if (sdint) {				
			window.open(myurl0);
		} else {
			bukaAjaxD('tinput_'+rndInput,myurl,conf,'awalEdit('+rndInput+')');
		}
	} 
}
//tombol cekall
//operasi menambah,hapus,ubah
function tbCheckAll(det,rnd){
	var v=$("#chall"+det+''+rnd).is(':checked');
	tbCheckAll2(det,rnd,v);
} 
function tbCheckAll2(det,rnd,v){
	var name="chb"+det; 	
	$('input[name='+name+']:checkbox').each(function(){
		if (v)
			$(this).attr('checked','');
		else
			$(this).removeAttr('checked');
	});
}
function repositionReport(){;
	var h=window.innerHeight;//window.hMax;
	$("#tout").height(h-180);
}
//browse--------------------------------------------------------------------------------------------------------------
function cekItemP(rnd,rndx){
	var name="chitemp_"+rndx;
	var v=getValueArrayChecboxByName(name);
	$("#spilih_"+rndx).val(v);
}
function tambahBarisP (rnd,rndx){
	hapusBarisP(rnd);
	isiBarisP(rnd,rndx);
	//mengisi datanya
}
function hapusBarisP(rnd){
	var nf=$(".trdet_"+rnd);
	//alert(nf.length);
	nf.each(function(){
		no=$(this).attr('no');
		var d=$("#d_nofaktur_"+rnd+"_"+no);
		if ($(d).val()=="") $(this).remove();
		//var inp=$("#d_nofaktur_4595_1");
	})
}
function isiBarisP(rnd,rndx){
	var spilih=$("#spilih_"+rndx).val();
	var apilih=spilih.split(",");
	var j=apilih.length;
	//buat baris kosong sejumlah j
	for (i=0;i<j;i++) {
		if (apilih[i]=='') continue;
		$("#btaddrow_"+rnd).click();	
	}
	$(".tgl").datepicker({dateFormat:formatTgl});
	//mengetahui mulai yg kosong di baris berapa
	//isi
	var nf=$(".trdet_"+rnd);
	var i=0;
	nf.each(function(){
		no=$(this).attr('no');
		if (apilih[i]=='') $i++;
		data=apilih[i].split("|");
		//nofaktur|tglfaktur|nmpelanggan|jlhtagih|0|nmsales|keterangan
		var inp=$("#trdet_"+rnd+"_"+no+" :input");
		if ($(inp[1]).val()=='') {
			j=0;
			//jika nggak kosong 
			$(inp).each(function(){
				if ($(this).attr('id')!=undefined){
					$(this).val(data[j]);
					j++;	
				}
			});
			i++;
		}
	})
}

function uploadImportCSV(rndx){
		idForm="fimp_"+rndx;
		if ($('#nffx'+rndx).html()=='') {
			alert('Pilih file yang akan diimport terlebih dahulu');
		} else {
			ajaxSubmitAllForm(idForm,'ts'+idForm,'','awalEdit('+rndx+')',false);
		};
}

function reloadPage(det,timeout){
	if (timeout==undefined) timeout=100;
	setTimeout("location.href='index.php?det="+det+"';",timeout);
}

function gantiPeriode(rnd){
	tgl=new Date();
	//$("#ttglp_"+rnd).hide();
	p=$("#periode_"+rnd).val();
	$("#ttglp_"+rnd).hide(); 
	if (p=="Hari Ini") {
		hi=tgl.gantiFormat();
		$("#tgl1_"+rnd).val(hi);
		$("#tgl2_"+rnd).val(hi);
	} else if (p=="Minggu Ini") {
		aw=tgl.awalMinggu().gantiFormat();
		ak=tgl.akhirMinggu().gantiFormat(); 
		$("#tgl1_"+rnd).val(aw);
		$("#tgl2_"+rnd).val(ak);
	} else if (p=="Bulan Ini") {
		aw=tgl.awalBulan().gantiFormat();
		ak=tgl.akhirBulan().gantiFormat(); 
		$("#tgl1_"+rnd).val(aw);
		$("#tgl2_"+rnd).val(ak);
	} else if (p=="Tahun Ini") {
		aw=tgl.awalTahun().gantiFormat();
		ak=tgl.akhirTahun().gantiFormat(); 
		$("#tgl1_"+rnd).val(aw);
		$("#tgl2_"+rnd).val(ak);
	} else if (p=="Kemarin") {
		aw=tgl.kemarin().gantiFormat();
		ak=tgl.kemarin().gantiFormat(); 
		$("#tgl1_"+rnd).val(aw);
		$("#tgl2_"+rnd).val(ak);
	} else if ((p=="All")||(p=="Semua Waktu")||(p=="All Time")) {
		aw=tgl.awal().gantiFormat();
		ak=tgl.akhir().gantiFormat(); 
		$("#tgl1_"+rnd).val(aw);
		$("#tgl2_"+rnd).val(ak);
	} else if (p=="Custom") {
		$("#ttglp_"+rnd).show(); 
	} else {
	}
}

//autorefresh datatable
var ardt;
function autoRerfreshDT(rnd,timer){
	$('#tbumum'+rnd).DataTable().columns.adjust().draw();
	if (timer>0) ardt=setTimeout("autoRerfreshDT("+rnd+","+timer+")",timer);
}
function refreshDT(rnd) {
	$('#tbumum'+rnd).DataTable().columns.adjust().draw();

}

function activateMnuKiri(idmnu,rnd) {
	//activateMnuKiri
	m=$("#mnu"+idmnu);
	tgmenu=m.attr("tgmenu");
	url=m.attr("link");
	//pclass=m.parent().attr("class")
	//console.log(url);
	
	if (url!='#')  {
		//alert(url);
		//$('.sidebar-toggle').click();
		if (tgmenu=="") {
			location.href=url;
			return false;
		} else {
			if (window.innerWidth<=420) $('.sidebar-toggle').click();
			url+="&newrnd="+rnd;
			bukaAjax(tgmenu,url,0,"awalEdit("+rnd+");fixLayout2();");
			return false;
			
		}
	}
}

function fixSidebar(){
	var h=$(window).innerHeight();
	var hn=$('.main-header').innerHeight();
	var hc=h-hn;
	$('.main-sidebar').css('position',"fixed");
	if (1==2){
	//if (typeof $.fn.slimScroll != 'undefined') {
 		$('.sidebar').slimScroll({height:hc+'px',alwaysVisible : true,});
	} else {
		$('.sidebar').css('height',hc+"px");
		$('.sidebar').css('overflow',"auto");
	}
}

function fixLayout2(){
	var h=$(window).innerHeight();
	var hn=$('.main-header').innerHeight();
	var hc=h-hn;
		//$('.wrapper').height(h);
	//$('.wrapper').css("overflow","auto");
	//$('.wrapper').css('overflow','hidden');
	if (1==2){
	//if (typeof $.fn.slimScroll != 'undefined') {
 		$('.content-wrapper').slimScroll({height:hc+'px',alwaysVisible : true});
	} else {
		$('.content-wrapper').css('height',hc+"px");
		$('.content-wrapper').css('overflow',"auto");
	}
	//$('.content-wrapper').css('min-height','100px!important');	
	fixSidebar();
	
}

function redrawDT($id) {
	$('#'+id).DataTable().columns.adjust().draw();
}

function reloadMnuKiri(adds){
	bukaAjax("tmmenu","index.php?det=mnukiri&contentOnly=1&useJS=2&"+adds,0,"$('.main-sidebar').tree();fixSidebar();$needReloadMnuKiri=false;");
}

//dijalankan di awal
$(document).ready(function(){
	fixLayout2();
})


//datatable
function customDTHeight2(addh) {
	h=window.hMax;
	try { hhead=$('thead').outerHeight(); } catch(e) { hhead=0; }
	
	try { hhead2=$('.dataTables_scrollHead').height();  } catch(e) { hhead2=0; }
	
	//hbody ini berlaku jika tinggi hhead 1 baris,jika 2 baris perlu coding lagi.....
	if (h>400) {
		hbody=h-hhead-addh;
	} else {
		hbody=h-hhead;	
	}
	
	$('.dataTables_scrollBody').height( hbody );		
	$('.dataTables_scrollBody').css( 'max-height',hbody );			
	$('.dataTables_scrollFoot').css('width','100%');
	$('.tbumum').css('width','100%');
	$('table.dataTable').css('margin-top','5px !important;');
	$('.dataTables_scrollHeadInner').css('width','100%');
	
	$('tfoot tr th').css('padding','0px');
	$('tfoot tr th input').css('width','100%');
	$('tfoot tr th input').css('text-align','center');
	$('.dataTables_length').css('padding-left','5px');		
	
}

/*

axios.post(myurl, {
	'notrans':'123'
})
.then(function (response) {
	//console.log(response);
	var json = response.data;
	var jsonPretty = JSON.stringify(json, null, '\t');
	//console.log(jsonPretty);
});



*/
function prepareReloadDT(rnd,url){
	if (idleKeypress>=3) {
		//idleKeypress=0;
		//reloadDT(rnd,url);
		if (url==undefined) 
			url=removeAmp($('#thalDT_'+rnd).html());
		else
			$('#thalDT_'+rnd).html(url);
		eval("dataTable"+rnd+".ajax.url('"+url+"'); dataTable"+rnd+".ajax.reload();");
		//return "reload "+rnd+" > "+url+" ok";
	
	} else {
		//console.log("tunggu "+idleKeypress+" "+rnd+" > "+url+" ")	;
	}
	return false;
}

function reloadDT(rnd,url) {
	//if (idleKeypress>2) {
		//tunda
		setTimeout("prepareReloadDT("+rnd+",'"+url+"');",idleKeypressInt*3.1);
	//} else {
	    //lastIdleKeypress
		//alert(idleKeypress);
	//}
}

function changeSelectByValue(id){
	vv=$("#"+id).attr('vv');
	if (vv!=undefined) {
		$("#"+id).val(vv);
	}
		
}

function resizeInputTb(clstb) {
	
	isi=$(".tb-"+clstb+" .tisicoldet");
	isi.each(function(){
		w1=$(this).parent().width();
		h1=$(this).parent().height();
		$(this).width(w1);
		$(this).height(h1);
	})
	
	$(".tb-"+clstb+" .tisicoldet input").each(function(){
		h1=$(this).parent().parent().height()-8;
		//$(this).height(h1);
		w1=$(this).parent().parent().width()-8;
		//nama=$(this).prop("id");
		//alert(nama+" > "+w1);
		$(this).css({
		  'width':w1+'px',
		  'max-width':w1+'px'
		})	
	})
}

//fungsi tb dialog input atas
function callTbDialogAtas(rnd,url,opsidialog,func2,title){
	console.log("callTbDialogAtas("+rnd+",'"+url+"','"+opsidialog+"','"+func2+"','"+title+"');");
	idform="form_"+rnd;
	tempat="tdiaA-"+rnd;
	url+="&contentOnly=1&useJS=2";
	//alert(idform);
	dt=$("#" +idform).serialize();
	//console.log(dt);
	$.post(url, dt ).done(function( data ) {
		if (data.length>10) {
			//w=(wMax<=500?wMax-5:500);
			//zIndexDialog++;
			//$(nmForm).show();
			//$("#"+tcek).html(data);	
			//$("#"+tcek).dialog({width:w});
			$("#"+tempat).html(data);		
			bukaAjaxD(tempat,'',opsidialog,func2,title);
			return false;
		} else {			
		
		}
	});
}