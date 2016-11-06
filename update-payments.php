<?php 

if($_GET['key'] != "HP9hhg9Lfd135fjYCTTP00sm6V"){
	die();
}

/*
run 8 times per 60 minutes
	run every 30 min with setcronjob.com
	run every 10 min with easycron.com
Checking 40 addresses per hour
	Checking 5 addresses per run

*/

// log information, output at the end
$log = "";
function logger($content){
	global $log;
	$log .= $content."; ";
}

// get connection info from external file
require_once "includes/dbconnect.php";
// select pictures
$dbQuery = $db->query("SELECT * FROM `pics` WHERE `received` < `price` ORDER BY `lastUpdatedDate`, `lastUpdatedTime` LIMIT 5;");

foreach($dbQuery as $dbD){
	logger("Start checking ".$dbD['btcAddress']);
	// cURL
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://blockchain.info/address/".$dbD['btcAddress']."?format=json&limit=0");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	$response = curl_exec($ch);
	$response = json_decode($response, true);
	curl_close($ch);
	// problems with blockchain API
	if(!preg_match("/^[0-9]+$/", $response['total_received'])){
		logger("Error: Blockchain API returned ".$response['total_received']);
	}
	// successful API request
	else{
		logger("Blockchain API returned ".$response['total_received']);
		// bitcoin values are in Satoshi, so they have to be devided by 100000000
		$received = $response['total_received']/100000000;
		$db->query("UPDATE `pics` SET `received` = '".$received."', `lastUpdatedDate` = '".date('Ymd')."', `lastUpdatedTime` = '".date('His')."' WHERE `ID` = ".$dbD['ID'].";");
		logger("Updated ID ".$dbD['ID']);
	}
}


// output log
echo $log;