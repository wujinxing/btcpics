<?php 

// get connection info from external file
require_once "../includes/dbconnect.php";
// select picture
$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE `ID` = :id");
$dbQuery->execute(array( ':id' => $_GET['id'] ));
$dbD = $dbQuery->fetch(PDO::FETCH_ASSOC);

// this is called if there are any errors
function imageNotFound(){
	header('Content-Type: image/png');
	readfile("../img/notfound.png");
	die();	
}

// no data in database found
if( !is_array($dbD) ){
	imageNotFound();	
}

// use correct file extension
if( $dbD['fileExt'] == "jpg" ){
	header('Content-Type: image/jpeg');
}
elseif( $dbD['fileExt'] == "png" ){
	header('Content-Type: image/png');
}
else{
	imageNotFound();
}

// check if image exists
$imageLocation = "../img/".$_GET['id'].".".$dbD['fileExt'];
if( !file_exists($imageLocation) ){
	imageNotFound();
}

// show image
readfile($imageLocation);