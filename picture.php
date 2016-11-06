<?php 

// get connection info from external file
require_once "includes/dbconnect.php";
// select picture
$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE `ID` = :id");
$dbQuery->execute(array( ':id' => $_GET['id'] ));
$dbD = $dbQuery->fetch(PDO::FETCH_ASSOC);

// this is called if there are any errors
function imageNotFound(){
	header('Content-Type: image/png');
	readfile("img/notfound.png");
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

// check if image exists and show it
$imageLocation = "img/".$_GET['id'].".".$dbD['fileExt'];
if( !file_exists($imageLocation) ){
	imageNotFound();
}

// not enough money -> show thumb
if( $dbD['received'] < $dbD['price'] ):
	// get size of original image [0] width [1] height
	$size = getimagesize($imageLocation);
	// see how much money was received
	$receivedRatio = ($dbD['received']/$dbD['price'])*0.7;
	// choose the new image width
	$newWidth = $size[0]*$receivedRatio;
	if($newWidth < 70){ $newWidth = 70; }
	// load Imagick
	$image = new Imagick($imageLocation);
	$image->thumbnailImage($newWidth, 0);
	echo $image;

// price reached -> show image
else:
	readfile($imageLocation);
endif;