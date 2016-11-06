<?php include "includes/header.php"; ?>

<h1>Report a picture</h1>
<p>Please use this form to report a picture on BTCpics.de which doesn't comply with our <a href="terms.php">terms of service</a> or violates your copyright.<br>
You can also <a href="http://3q3.de/" rel="nofollow" target="_blank">contact me via email</a> and encrypt the email with <a href="gpgkey.asc">my GPG key</a>.</p>

<?php 
if(empty($_GET['id'])){ $id="..."; }else{ $id=$_GET['id']; }
$formMessage = "I want to report the picture with the ID ".$id." because ...";
$formSubject = "Report a picture";
include "includes/form.php"; 

include "includes/footer.php"; ?>