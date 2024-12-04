<?php

class db {
	public $host,$user,$password,$dbName;
	public $pes;
	public $koneksi;
	public $koneksiDb;
	public $avoidConnectDb=false;
	public $nfSqlDb="installp/db.sql";
	public $nmTabel;
	public $tables=array();
		
	function connect($hst="",$usr="",$pss="",$mydb="") {
		//global $hst,$usr,$pss,$mydb;
		//echo $pss;
		global $isOnline;
		global $det;
		if ($hst!="") $this->host=$hst;
		if ($usr!="") $this->user=$usr;
		if ($pss!="") $this->passord=$pss;
		if ($mydb!="") $this->dbName=$mydb;

		/*
		if (substr(phpVersion(),0,1)*1>=7) {
			$pes="Versi PHP Tidak Support<br>
			Versi Server: $phpVersion<br>
			Versi yang dibutuhkan : 5.6.X<br>
			";
			
			$this->koneksi = @mysqli_connect($this->host,$this->user,$this->password, $this->dbName);
			
			if (!$this->koneksi) {
				$pes.="Error: Unable to connect to MySQL." . PHP_EOL;
				$pes.= "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
				$pes.= "Debugging error: " . mysqli_connect_error() . PHP_EOL;
			}
			
			/*
			//echo "Success: A proper connection to MySQL was made! The my_db database is great." . PHP_EOL;
			//echo "Host information: " . mysqli_get_host_info($link) . PHP_EOL;
			$sql_statement = "SELECT name,password FROM user";
			$result = $mysqli -> prepare($sql_statement);
			$result -> execute();
			$result -> store_result();
			* /
			echo $pes;
			exit;
		}	
		*/
		
		$this->koneksi = @mysql_connect($this->host,$this->user,$this->password, $this->dbName);		
		if (!$this->koneksi) {	
			echo "<center><div style='border:2px #000 solid;width:400px'>";
			//echo "<pre>".mysql_error()."</pre>";
			echo "<div style='background:#000;color:#fff;padding:10px;'>ATTENTION</div>";
			echo "<br>".($isOnline?"Online":"Offline")." server is bussy.....<br/>";
			echo mysql_error();
			echo "<br /><input type=button  value='Try Again Now' onclick=\"document.location.reload(true);\" >&nbsp;";
			echo "<input type=button  value=Back onclick=\"window.history.back()\">";
			echo "<script>document.write('<br/><br/>System will automatically reload page in 30 seconds....<br/>');setTimeout('location.reload(true);',30000);</script>";
			echo "<br/><br/></div></center>";
			exit;
		} else {
			//echo "koneksi sukses...";
		}
		
		$this->koneksiDb=mysql_select_db($this->dbName);
		 
		if (!$this->koneksiDb) {
			if (!isset($avoidConnectDB)) $avoidConnectDB=false;
			if (!$avoidConnectDB) {
				if ($det!="buatdb") {
					echo "Database tidak ditemukan.".($isOnline?"": $this->dbName );
					if (file_exists($this->nfSqlDb)){
						echo "<br><a href='index.php?det=buatdb'>Buat Database</a> ";
					}
					//exit;
				}
			}
		}
		$_SESSION['usr']=$usr;
		$_SESSION['db']=$mydb;
		return $this->koneksiDb;
	}
	
	/*
	table digunakan untuk seleksi, query,repairdb,changecollaton
	*/
	function table($nmTabel="*") {
		if ($nmTabel=="*"){ 
			$tables=array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result)) { $tables[] = $row[0]; }
		}	else {
			$tables = is_array($nmTabel) ? $nmTabel : explode(',',$nmTabel);
		}
		
		$this->tables=$tables;
		$this->nmTabel=$nmTabel;
		return $this;
	}
	
	function select($sy=""){	 
		$this->sql="select * from $this->nmTabel ".($sy==""?"":" where $sy");
		return $this;
	}
	
	function query($sql){
		$this->sql=$sql;
		return $this;
	}
		
	function fetch($jresult="a"){
		$h=mysql_query($this->sql);
		//echo "hooooooooooooo ";exit;
		$result=array();
		while ($r=mysql_fetch_array($h)) {
			$result[]=$r;
		}
		if ($jresult=="a") 
			return $result;
		else
			return json_encode($result);
		
	}

	function repairDB() {
		//if (empty($this->tables)) 
		$t="";		
		$t="<br>Repairing DB";
		
	
		$this->table("*");
		$tables=$this->tables;
		
		$sql="";			$t="";
		$maxrow=30;	//maxr row per isert				
		foreach($tables as $table)  {
			$t.="<br>Optimizing & repairing table ".$table."...  ";
			$ok1=mysql_query('OPTIMIZE TABLE '.$table);
			$ok2=mysql_query('REPAIR TABLE '.$table);
			$t.=($ok1?"ok":"fail")."/".($ok2?"ok":"fail");
		}
		return $this->showConsole($t);
	}
	
	function changeCollation($collation="utf8_general_ci") {
		//if (empty($this->tables)) 
		$this->table("*");
		$tables=$this->tables;
		//	"ALTER DATABASE dbname CHARACTER SET utf8 COLLATE utf8_general_ci;";
		$sql="";			$t="";
		$maxrow=30;	//maxr row per isert				
		$t="";	
		$t.="<pre>";
		foreach($tables as $table)  {
			$t.="<br>Table: $table";
			$sq="ALTER TABLE $table CONVERT TO CHARACTER SET utf8 COLLATE $collation";
			mysql_query($sq);
			$t.=$sq;
		}
		$t.="</pre>";	
		return $this->showConsole($t);
	}
	
	function showConsole($t){
		$rndx=rand(111,2222);
		return "
		<div id='consoledb$rndx' class='console' style='width:98%;overflow:auto;margin:10px;padding:10px;height:300px;background:#000;color:#fff' >
			$t
		</div>
		";
	}
	
}

?>