<?php
$thisfile=$_SERVER['PHP_SELF'];
$back="$thisfile?".$_SERVER['HTTP_REQUEST'];

if (!isset($_REQUEST['dir'])) 
	$dir=".";
   else
   $dir=$_REQUEST['dir'];
// open this directory 
$myDirectory = opendir($dir);

// get each entry
while($entryName = readdir($myDirectory)) {
	$dirArray[] = $entryName;
}

// close directory
closedir($myDirectory);

//	count elements in array
$indexCount	= count($dirArray);
Print ("$indexCount files<br>\n");

// sort 'em
sort($dirArray);

// print 'em
print("<TABLE border=1 cellpadding=5 cellspacing=0 class=whitelinks>\n");
print("<TR><TH>Filename</TH><th>Filetype</th><th>Filesize</th><th>Access Folder</th></TR>\n");
// loop through the array of files and print them all
for($index=0; $index < $indexCount; $index++) {
	$item="$dir/".$dirArray[$index];
   //if (substr("$dirArray[$index]", 0, 1) != "."){ // don't list hidden files
		print("<TR><TD><a href=\"$item\">$dirArray[$index]</a></td>");
		print("<td>");
		print(filetype($item));
		print("</td>");
		print("<td>");
		print(filesize($item));
		print("</td>");
		
		print("<td>");
		if (filetype($item)=='dir') {
			print("<a href=\"$thisfile?dir=".urlencode($item)."&back=".urlencode($back)."\">$dirArray[$index]</a>");
		}
		else
			print(" ");
		
		print("</td>");
		
		print("</TR>\n");
	//}
}
print("</TABLE>\n");
if (isset($_REQUEST['back'])) print("<a href=\"".$_REQUEST['back']."\">back</a>\n");
?>