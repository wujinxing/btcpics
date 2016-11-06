<?php
// get connection info from external file
require_once "../includes/dbconnect.php";

$returnMessage = "";

if( $_POST['formSubmit'] == "APPROVE" ){
	// Update db
	$dbQuery = $db->prepare("UPDATE `pics` SET `approved` = 1, `reviewed` = 1 WHERE `ID` = ".$_POST['ID']);
	$dbQuery->execute();
	$returnMessage .= $_POST['ID']." reviewed & approved";
}elseif( $_POST['formSubmit'] == "DELETE" ){
	// send mail
	if(!empty($_POST['email'])){
		// send email with ticket
		$sendTo = $_POST['email'];
		$subject = "Picture deleted from BTCpics.de";
		$message = "Hello, \n \nYour picture was deleted from BTCpics.de because ".$_POST['formMessage']." \nThe picture had the ID ".$_POST['ID']." and the description: ".$_POST['description']." \n \nKind regards, \nBTCpics team";
		$message = wordwrap($message, 70);
		$header = 'From: email@btcpics.de';
		mail($sendTo, $subject, $message, $header);
		$returnMessage .= "Email sent. ";
	}
	// Delete from db
	$dbQuery = $db->prepare("DELETE FROM `pics` WHERE `ID` = ".$_POST['ID']);
	$dbQuery->execute();
	$returnMessage .= $_POST['ID']." deleted";
}else{
	$returnMessage .= "Invalid value for formSubmit"; 
}


header("Location: admin.php?message=".urlencode($returnMessage));