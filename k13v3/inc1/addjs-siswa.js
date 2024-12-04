
			//khusus untuk siswa
	function gantiComboNilai(cb,jkom,rnd){
			document.getElementById('tnilai_'+rn).innerHTML='';
			sm=semester=document.getElementById('semester_'+rn).value;
			if ((cb=='jenisMP')||(cb=='semester')) { 
				document.getElementById('tmp_'+rn).innerHTML='-';
				jenisMP=document.getElementById('jenisMP_'+rn).value;
				kode_kelas=document.getElementById('kode_kelas_'+rnd).value;
				if (jenisMP=='') return;
				bukaAjax("tmp","filtercombo.php?jkom="+jkom+"&kode_kelas="+kode_kelas+"&combo=mp&semester="+semester+"&jenisMP="+jenisMP+"&usemap=1");
			}
			else if ((cb=='mp')||(cb=='ki')) { 
				km=kode_matapelajaran=document.getElementById('kode_matapelajaran_'+rnd).value;
				if ((km!='')&&(sm!='')) 
				bukaAjax('tnilai',"inputnilaisiksis.php?newrnd="+rnd+"&op=showdata&semester="+semester+"&kode_matapelajaran="+kode_matapelajaran,0,'bukaMapMP('+rnd+')');
					
			}
		}
		