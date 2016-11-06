<?php 

if( isset($_POST['formSubmit']) ){
	// Check if all fields filled out
	if( empty($_POST['formName']) || empty($_POST['formEmailad']) || empty($_POST['formSubject']) || empty($_POST['formMessage']) || !empty($_POST['formCheck'])) { 
		echo '<div class="alert alert-danger">Please fill out all fields.</div>';
	}
	// Check if valid email was entered
	elseif(!preg_match('/^.+[@].+$/', $_POST['formEmailad'])) { 
		echo '<div class="alert alert-danger">This was no valid email address.</div>';
	}
	// If all was successful
	else{
		// send email with ticket
		$sendTo = 'mail@example.com';
		$subject = $_POST['formSubject'];
		$message = $_POST['formMessage']."\n \n Message from: ".$_POST['formName']."\n ".$_POST['formEmailad']."\n Sent on: ".date("m d, Y H:i");
		$message = wordwrap($message, 70);
		$header = 'From: '.$_POST['formEmailad'];
		mail($sendTo, $subject, $message, $header);

		echo '<div class="alert alert-success">Your message was sent.</div>';

		$hideForm = true;
	}
}

if( $hideForm != true){
?>

<form class="form-horizontal" role="form" method="post" action="">

  <div class="form-group">
    <label for="formName" class="col-sm-2 control-label">Name</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="formName" name="formName" value="<?php echo $_POST['formName']; ?>"/>
    </div>
  </div>

  <div class="form-group">
    <label for="formEmailad" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="formEmailad" name="formEmailad" value="<?php echo $_POST['formEmailad']; ?>"/>
    </div>
  </div>

  <div class="form-group">
    <label for="formSubject" class="col-sm-2 control-label">Subject</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="formSubject" name="formSubject" value="<?php if(!empty($_POST['formSubject'])){ echo $_POST['formSubject']; }else{ echo $formSubject; } ?>" />
    </div>
  </div>

  <div class="form-group">
    <label for="formMessage" class="col-sm-2 control-label">Message</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="formMessage" name="formMessage" rows="3"><?php if(!empty($_POST['formMessage'])){ echo $_POST['formMessage']; }else{ echo $formMessage; } ?></textarea>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="formCheck" id="formCheck" placeholder="No fill out here please" ><!-- Spam Protection -->
      <input type="submit" name="formSubmit" id="formSubmit" class="btn btn-primary" value="SEND">
    </div>
  </div>
</form>

<?php } 