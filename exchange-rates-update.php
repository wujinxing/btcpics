<?php 
// protection
if($_GET['key'] != "H7akbdGjas6"){
	die();
}

// cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://blockchain.info/ticker");
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
$response = curl_exec($ch);
curl_close($ch);

file_put_contents("exchange-rates.json", $response);