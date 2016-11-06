<?php
// protection
if($_GET['key'] != "HHjs198dall18s"){
	die();
}

$sitemapContent = "";
// sitemap header
$sitemapContent .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';

// sitemap list each page
// get connection info from external file
require_once "includes/dbconnect.php";
$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE `approved` IS NOT NULL ORDER BY `ID` DESC");
$dbQuery->execute();
while( $dbD = $dbQuery->fetch(PDO::FETCH_ASSOC) ){ 
	$sitemapContent .= '<url><loc>https://example.com/view.php?id='.$dbD['ID'].'</loc></url>';
}


// sitemap footer
$sitemapContent .= '</urlset>';

// Cache note
$sitemapContent .= "<!-- cached sitemap on ".date("Y-m-d-H-i")." (format: Y-m-d-H-i) -->";


// create the cache file & write content
$fp = fopen("sitemap-pics.xml", "w");
fwrite($fp, $sitemapContent);

