<?php include "includes/header.php"; 


// if isset post submit
if( isset($_POST['submit']) ){
	
	// check all required fiels
	if( $_POST['terms'] != "checked" ){
		echo '<div class="alert alert-danger" role="alert">You have to accept the terms and the privacy policy. Need help? <a href="contact.php">Contact</a></div>';
		die();
	}

	// check all required fiels
	if( !empty($_POST['formCheck']) ){
		echo '<div class="alert alert-danger" role="alert">Spam check not passed. Need help? <a href="contact.php">Contact</a></div>';
		die();
	}

	// check valid btc address with blockexplorer API
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://blockexplorer.com/api/addr-validate/".$_POST['btcAddress']);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	$validAddress = curl_exec($ch);
	curl_close($ch);
	if( $validAddress != "true" ){ 
		echo '<div class="alert alert-danger" role="alert">This was no valid email address. Need help? <a href="contact.php">Contact</a></div>';
		die();
	}
	
	// check valid price
	if( !preg_match("/^[0-9]+[\.]?[0-9]*$/", $_POST['price']) ){
		echo '<div class="alert alert-danger" role="alert">This was no valid price. Need help? <a href="contact.php">Contact</a></div>';
		die();
	}


    // Undefined | $_FILES Corruption Attack | multiple files
    // If this request falls under any of them, treat it invalid.
    if ( !isset($_FILES['picture']['error']) || is_array($_FILES['picture']['error']) ) {
		echo '<div class="alert alert-danger" role="alert">Invalid picture upload. Need help? <a href="contact.php">Contact</a></div>';
		die();
    }
    // Check $_FILES ['error'] value.
    switch ($_FILES['picture']['error']) {
        case UPLOAD_ERR_OK:
            break;
        default:
			echo '<div class="alert alert-danger" role="alert">Error while uploading your picture. Need help? <a href="contact.php">Contact</a></div>';
			die();
    }
    // Check filesize here (max 2 mb = 2000000)
    if ($_FILES['picture']['size'] > 2000000) {
		echo '<div class="alert alert-danger" role="alert">Picture exeeded the filesize limit of 2MB. Need help? <a href="contact.php">Contact</a></div>';
		die();
    }
    // DO NOT TRUST $_FILES ['mime'] VALUE !!
    // Check MIME Type by yourself.
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    if (false === $extPic = array_search(
        $finfo->file($_FILES['picture']['tmp_name']),
        array( 'jpg' => 'image/jpeg', 'png' => 'image/png' ),
        true
    )) {
		echo '<div class="alert alert-danger" role="alert">Invalid picture format. Only PNG or JPG are allowed. Need help? <a href="contact.php">Contact</a></div>';
		die();
    }


	// email
	if( !empty($_POST['email']) ){
		// check if email is valid
		if( !preg_match("/^.+[@].+$/", $_POST['email']) ){
			echo '<div class="alert alert-danger" role="alert">This was no valid email address. Need help? <a href="contact.php">Contact</a></div>';
			die();
		}
		// auto approve the picture
		$approved = 1;
		// Spamty Link
		if( $_POST['showEmail'] == "checked" ){
			// cURL
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://api.spamty.eu/encryption/v4.php");
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); 
			curl_setopt($ch, CURLOPT_POSTFIELDS, array(
				'email'		=> $_POST['email'],
				'apiUser'	=> 'USER',
				'apiKey'	=> 'APIKEY',
				'output'	=> 'json'
			));
			$response = curl_exec($ch);
			curl_close($ch);
			$data = json_decode($response, true);
			if( $data['status'] == 1 ){ 
			    $spamtyLink = $data['urlPublic'];
			}else{
				echo '<div class="alert alert-info" role="alert">We had a problem while generating your Spamty link. Need help? <a href="contact.php">Contact</a></div>';
			}

		}
	}

	if(!empty($_POST['album'])){ 
		$album = md5($_POST['album']);
	}else{ 
		$album = ""; 
	}


	// get connection info from external file
	require_once "includes/dbconnect.php";
	// store data in db
	$dbQuery = $db->prepare("INSERT INTO `pics` (
			`ID`, 
			`approved`, 
			`fileExt`, 
			`btcAddress`, 
			`description`, 
			`album`, 
			`price`, 
			`email`, 
			`spamtyLink`, 
			`ip`, 
			`date`
		) VALUES (
			'',
			:approved,
			:extPic,
			:btcAddress,
			:description,
			:album,
			:price,
			:email,
			:spamtyLink,
			:ip,
			CURRENT_TIMESTAMP
		);");
	if( !$dbQuery->execute( array(
			':extPic' => $extPic,
			':approved' => $approved,
			':btcAddress' => $_POST['btcAddress'],
			':description' => $_POST['description'],
			':album' => $album,
			':price' => $_POST['price'],
			':email' => $_POST['email'],
			':spamtyLink' => $spamtyLink,
			':ip' => $_SERVER['REMOTE_ADDR']
	) ) ){
		//echo "\nPDO::errorInfo():\n"; 
		//print_r($dbQuery->errorInfo()); 
		//print_r($db->errorInfo()); 
		echo '<div class="alert alert-danger" role="alert">Error with our database! Need help? <a href="contact.php">Contact</a></div>';
		die();
	}
	$pdoLastId = $db->lastInsertId();



    // move file.
    if (!move_uploaded_file(
        $_FILES['picture']['tmp_name'],
        sprintf('img/%s.%s',
            $pdoLastId,
            $extPic
        )
    )) {
		echo '<div class="alert alert-danger" role="alert">Failed to store your uploaded picture on our server. Need help? <a href="contact.php">Contact</a></div>';
		die();
    }
    
    ?>
    <div class="alert alert-success" role="alert">You can <a href="view.php?id=<?php echo $pdoLastId; ?>" class="alert-link">find your picture here</a>.</div>
	<?php
}


?>

<h1>Upload a picture</h1>

<form class="form-horizontal" enctype="multipart/form-data" role="form" method="POST" action="upload.php">

  <!-- Picture & Thumb -->
  <div class="form-group">
    <label for="picture" class="col-sm-2 control-label">Picture *</label>
    <div class="col-sm-10">
    	<input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
	    <input type="file" name="picture" id="picture">
	    <p class="help-block">Upload only a picture that you own. No nudity, porn or NSFW. Only JPG or PNG, max size 2 MB. <a href="faq.php#filesize" target="_blank">Larger files?</a></p>
    </div>
  </div>

  <!-- Bitcoin -->
  <div class="form-group">
    <label for="btcAddress" class="col-sm-2 control-label">Bitcoin address *</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="btcAddress" name="btcAddress" value="" placeholder="" />
		<p class="help-block"><strong>Use a unique address for each picture</strong> and do not reuse an old address. Create a new one for each picture you upload here. <a href="faq.php#bitcoin-wallet" target="_blank">Get a new address</a>.</p>
    </div>
  </div>
  <div class="form-group">
    <label for="price" class="col-sm-2 control-label">Price (in BTC) *</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="price" name="price" value="" placeholder="" />
		<p class="help-block">The price for your picture (for example <em>0.03</em> BTC). When the price was reached it is displayed in full resolution. You can set this to <em>0</em> if you want to give your picture away for free.</p>
    </div>
  </div>

  <!-- Description -->
  <div class="form-group">
    <label for="description" class="col-sm-2 control-label">Description</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="description" name="description" value="" placeholder="" />
		<p class="help-block">A description and some keywords so others can find your picture.</p>
    </div>
  </div>

  <!-- Album ID -->
  <div class="form-group">
    <label for="album" class="col-sm-2 control-label">Album ID</label>
    <div class="col-sm-10">
		<input type="text" class="form-control" id="album" name="album" value="" placeholder="" />
		<p class="help-block">You can use the same album ID (which shouldn't be easy to guess) for all pictures you want to be in one album. This will put an album link under each one of them. <a href="faq.php#album" target="_blank">More info</a></p>
    </div>
  </div>

  <!-- Email -->
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
		<input type="email" class="form-control" id="email" name="email" value="" placeholder="" />
		<p class="help-block">Not required but recommended so you can contact us to edit/remove the picture from our website. <strong>The email won't be published.</strong></p>
    </div>
  </div>
  <div class="form-group">
    <label for="showEmail" class="col-sm-2 control-label">Show contact link</label>
    <div class="col-sm-10">
		<input type="checkbox" class="form-control" id="showEmail" name="showEmail" value="checked" />
		<p class="help-block">If you check this a link will be shown next to your picture so others can contact you via email (<a href="faq.php#spamty" target="_blank">powered by Spamty</a>).</p>
    </div>
  </div>

  <!-- License -->
  <div class="form-group">
    <label for="license" class="col-sm-2 control-label">License *</label>
    <div class="col-sm-10">
		<select class="form-control" id="license" name="license" disabled>
			<option selected>CC0 1.0</option>
			<!-- currently only support for CC0 1.0; more CC licenses to be added soon -->
			</select>
		<p class="help-block">Your picture will be available for anyone to use under the terms of this <a href="license.php" target="_blank">license</a>.</p>
    </div>
  </div>

  <!-- Terms -->
  <div class="form-group">
    <label for="terms" class="col-sm-2 control-label">Terms & Privacy *</label>
    <div class="col-sm-10">
		<input type="checkbox" class="form-control" id="terms" name="terms" value="checked" checked />
		<p>I agree to the <a href="terms.php" target="_blank">terms of use</a> and the <a href="privacy.php" target="_blank">privacy policy</a>. <strong>I own the copyright for the selected picture</strong>. I accept that my picture will be available under the chosen license.</p>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<p>* required fiels</p>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
		<input type="hidden" name="formCheck" id="formCheck" placeholder="No fill out here please" ><!-- Spam Protection -->
		<input type="submit" name="submit" id="submit" class="btn btn-primary" value="UPLOAD">
    </div>
  </div>
</form>

<?php include "includes/footer.php"; ?>