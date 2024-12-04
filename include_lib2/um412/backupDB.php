<?php
/*
 * This file contains the Backup_Database class wich performs
 * a partial or complete backup of any given MySQL database
 * @author Daniel López Azaña <http://www.daniloaz.com-->
 * modified by um412@yahoo.com
 * @version 1.0
*/

//ini_set('memory_limit', '2044M'); 
ini_set('memory_limit', '-1');

error_reporting(E_ALL);
 
class Backup_Database {
    var $host = '';
	var $username = '';
	var $passwd = '';
	var $dbName = '';
	var $charset = '';
	var $nfbackup = '';
	var $sqlResult='';
	
	function Backup_Database($host, $username, $passwd, $dbName, $nfbackup='',$charset = 'utf8') {
        $this->host     = $host;
        $this->username = $username;
        $this->passwd   = $passwd;
        $this->dbName   = $dbName;
        $this->charset  = $charset;
		$this->nfbackup  = $nfbackup;
        $this->initializeDatabase();
    }

    protected function initializeDatabase()    {
        /*
		$conn = mysql_connect($this->host, $this->username, $this->passwd);
        mysql_select_db($this->dbName, $conn);
        if (! mysql_set_charset ($this->charset, $conn))
        {
            mysql_query2('SET NAMES '.$this->charset);
        }
		*/
    }
   
   public function backupTables($tables = '*', $outputDir = '.',$dataOnly=false) {
        try     {
            /**
            * Tables to export
            */
			$db= $this->dbName  ;
            if($tables == '*')
            {
                $tables = array();
                $result = mysql_query2('SHOW TABLES');
                while($row = mysql_fetch_row($result)) { $tables[] = $row[0]; }
            }
            else {
                $tables = is_array($tables) ? $tables : explode(',',$tables);
            }

			$sql="";
           // $sql = 'CREATE DATABASE IF NOT EXISTS '.$this->dbName.";\n\n";
           // $sql .= 'USE '.$this->dbName.";\n\n";

            /**
            * Iterate tables
            */
			//echo "<textarea cols=100 rows=8>";
			$t="";
			$maxrow=30;	//maxr row per isert				
					
            foreach($tables as $table)  {
                $t.="Backing up ".$table." table... (data:$dataOnly) ";
				$ok=mysql_query2('OPTIMIZE TABLE '.$table);
				if ($ok) {
					$sqselect='SELECT * FROM '.$table;
					//echo "<br>".$sqselect;
					$result = mysql_query2($sqselect);
					$numFields = mysql_num_fields($result);
					$sqf="select group_concat(cn) from (SELECT COLUMN_NAME as cn FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table') as tbb";
					$rsf = mysql_query2($sqf);
					$rsfx=mysql_fetch_array($rsf);
					$sfld=($rsfx[0]); 
					
					if (!$dataOnly) {
						$sql .= 'DROP TABLE IF EXISTS `'.$table.'`;';
						$row2 = mysql_fetch_row(mysql_query2('SHOW CREATE TABLE '.$table));
						$sql.= "\n\n".$row2[1].";\n\n";
					}
					//for ($i = 0; $i < $numFields; $i++)  {
						$r=0;
						$sss1=$sss2="";
						while($row = mysql_fetch_row($result)) {
							$sss2=' INSERT INTO `'.$table.'`('.$sfld.')  VALUES ';
						
							$sss1 .=($sss1==""?"":",") ."\n".'  (';
							for($j=0; $j<$numFields; $j++)  {
								$row[$j] = addslashes($row[$j]);
								//$row[$j] = preg_replace("\n","\\n",$row[$j]);
								$row[$j] = str_replace("\n","\\n",$row[$j]);
								if (isset($row[$j])){
									$sss1 .= '"'.$row[$j].'"' ;
								} else {
									$sss1.= '""';
								}
								
								if ($j < ($numFields-1))
								{
									$sss1 .= ',';
								}
							}
							$sss1.= ")";
							if ($r%$maxrow==0) {
								$sql.=$sss2.$sss1.";\n";
								$sss1="";
							}
							$r++;
						}
						
						if ($sss1!='') $sql.=$sss2.$sss1.";\n";
					//}

					$sql.="\n\n\n";

					$t.= " OK\n";
				} //jika table bisa dibackup
            }
			
			/*backup trigger*/
			$t.="<br>backup Trigger";
			$sql_create="";
			$st="SELECT * FROM information_schema.TRIGGERS WHERE TRIGGER_SCHEMA='".($this->dbName )."'";
			$ht=mysql_query2($st);
			//$t="";
			
			while ($ht && $row=mysql_fetch_assoc($ht)) {
				$sql_create = "
				DROP TRIGGER IF EXISTS `".$row['TRIGGER_NAME']."` ;\n
				DELIMITER $$\n
				CREATE TRIGGER `{$row['TRIGGER_NAME']}` {$row['ACTION_TIMING']} {$row['EVENT_MANIPULATION']} ON `{$row['EVENT_OBJECT_TABLE']}` 
				FOR EACH ROW 
				";
				$sql_create .= "\n".str_replace("\t",'',$row['ACTION_STATEMENT'])."\n";
				//$t .= "\n/*\n".$sql_create."*/\n";
				$sql_create.="
				\n 
				$$\n
				DELIMITER ;\n";
				
				$sql.="
				 
				$sql_create
				
				";
			}
			
			$t.=" OK\n";
	    }
        catch (Exception $e) {
            var_dump($e->getMessage());
            return false;
        }
		//$t.="simpan file";
        $this->sqlResult.=$sql;
		$t.=($this->saveFile($sql, $outputDir));
		return $t;
		

    }
	
	public function getSQL(){
		return $this->sqlResult;
		
	}
 
    /**
     * Save SQL to file
     * @param string $sql
     */
    protected function saveFile(&$sql, $outputDir = '.') {
        if (!$sql) return 'Err, no sql created....';

        try {
			$nfa='db-backup-'.$this->dbName.'-'.date("Ymd-His", time()).'.sql';
			
			$nf=$outputDir.$nfa;
			if ($this->nfbackup!='') 
				$nf=$this->nfbackup;
            //$nf=str_replace(",","",$nf);
			//echo "<br>".$nf;
			$handle = fopen($nf,'w+');
            fwrite($handle, $sql) or die("cannot create $nf");
            fclose($handle);
			//chdir  $outputDir.'/'
			//echo "creating zip file... $nf.zip";
			createZipFile($nf,"$nf.zip");
        }
        catch (Exception $e)
        {
            var_dump($e->getMessage());
            return 'Err';
        }

        return 'Save file ..OK';
    }
}
