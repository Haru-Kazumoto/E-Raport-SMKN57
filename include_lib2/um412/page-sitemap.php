<?php
$useJS=2;
include_once('conf.php'); 

$no_sitemap_file =($no ==''? 0:$no);
 
$xml = '<?phpxml version="1.0" encoding="UTF-8"?>

<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
  
 
if (empty($offset))    $offset = $no;
$limit =1000;  

$query = mysql_query2("select * from tbpage");
while ($data=mysql_fetch_array($query))  {
	if ($data['pg']!='') {
		$xml .= ' <url>
		';
		$xml .= '<loc>'.$folderHost.'?page=page&amp;pg='.urlencode($data[pg]).'&amp;jd='.urlencode($data['judul']).'</loc>
		'; 
		$xml .= '<priority>0.5</priority>
		';
		$xml .= '<lastmod>'.date("Y").'-'.date("m-d").'</lastmod>
		';
		$xml .= '<changefreq>daily</changefreq>
		';
		$xml .= '</url>';
	}
}


$query = mysql_query2("select id,judul from tbnews");
while ($r=mysql_fetch_array($query))  {
	//if ($data['pg']!='') {
		$xml .= ' <url>
		';
		$xml .= '<loc>'.$folderHost.'?page=news&amp;nohead=2&amp;idnews='.$r['id'].'&amp;jd='.urlencode($r['judul']).'</loc>
		'; 
		$xml .= '<priority>0.5</priority>
		';
		$xml .= '<lastmod>'.date("Y").'-'.date("m-d").'</lastmod>
		';
		$xml .= '<changefreq>daily</changefreq>
		';
		$xml .= '</url>';
	//}
}


/*
$query = mysql_query2("select * from tbsearch");
while ($data=mysql_fetch_array($query))  {
 	$xml .= '<url>';
	$xml .= ' <loc>'.$folderHost.'/search-daf.php?s='. urlencode($data['katakunci']).'&amp;isi='.urlencode($data['isi']).'</loc>';
	$xml .= ' <priority>0.5</priority>';
	$xml .= ' <lastmod>'.date("Y").'-'.date("m-d").'</lastmod>';
	$xml .= ' <changefreq>daily</changefreq>';
	$xml .= '</url>';
}

*/
mysql_close();
$xml .= '</urlset>';
$nmfile=$toroot.'sitemap_'.$no_sitemap_file.'.xml';
$a = fopen($nmfile,"w");
if (!$a) echo "error open....";
$h=fputs($a, $xml);
fclose($a);
echo "Host:$folderHost File:$nmfile
<a href='$nmfile' target=_blank>lihat hasil</a>
";
?>