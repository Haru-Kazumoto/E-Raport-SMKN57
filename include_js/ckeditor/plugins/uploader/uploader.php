<?php
$config = array(
	'access_types' =>array('jpeg', 'jpg', 'png', 'bmp', 'gif', 'cdr', 'swf',  'psd', 'zip', 'rar', '7z', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'html','avi','cs','exe','fla','mp3','pdf','ppt'),
	'folder' => $_SERVER['DOCUMENT_ROOT'].'/project/ckplugins/tmp/images/',// меняем путь до фото
	'use_md5'=>true, // менять имя на md5 хеш
	'replace'=>false, // заменять файл с тем же именем, работает только с вместе с use_md5
);
function getk($val,$k=0){
	return is_array($val)?$val[$k]:$val;
}
function saveFile( $k=0,$fileElementName = 'image' ){
	global $config;
	if( !getk($_FILES[$fileElementName]['error'],$k)
	 and getk($_FILES[$fileElementName]['tmp_name'],$k) 
	 and getk($_FILES[$fileElementName]['tmp_name'],$k) != 'none' ){
		$name = getk($_FILES[$fileElementName]['name'],$k);
		if( move_uploaded_file(getk($_FILES[$fileElementName]['tmp_name'],$k), $config['folder'].$name) ){
			$fi = pathinfo($config['folder'].$name);
			$obj = isset($fi["extension"])?strtolower($fi["extension"]):'';
			if( in_array($obj,$config['access_types']) ){
				$k1 = '';
				if( $config['use_md5'] ){
					if( !$config['replace'] )
						while( file_exists($config['folder'].md5($name.$k1).'.'.$obj) )$k1.='1';
					rename($config['folder'].$name,$config['folder'].md5($name.$k1).'.'.$obj);
					return $name.'='.md5($name.$k1).'.'.$obj;
				}else return $name.'='.$name.'.'.$obj;
			}else unlink($config['folder'].$name);
		}	
	}
}

if( isset($_FILES['image']['name'])and is_array($_FILES['image']['name']) ){
	$val = array();
	foreach($_FILES['image']['name'] as $k=>$name){
		$val[]=saveFile($k);
	}
	die(implode(';',$val));
};
?>