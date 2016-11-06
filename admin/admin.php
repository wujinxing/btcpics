<!DOCTYPE html>
<html lang="en">
  <head>
	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" />
  </head>
  <body>
    <div class="container"><!-- container -->

<h1>Admin</h1>
<hr>
<?php
// message
if(!empty($_GET['message'])){ ?>
	<div class="alert alert-info" role="alert"><?php echo $_GET['message']; ?></div>
<?php }

// get connection info from external file
require_once "../includes/dbconnect.php";

// count images that need to be approved
$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE (`approved` IS NULL) OR (`reviewed` IS NULL) OR ((`approved` IS NULL) AND (`reviewed` IS NULL)) ORDER BY `ID`");
$dbQuery->execute();
$num = 0;
while( $dbD = $dbQuery->fetch() ){ $num++; }
echo "<p>To approve: ".$num."</p>";
// end if no images
if($num == 0){ die(); }

$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE (`approved` IS NULL) OR (`reviewed` IS NULL) OR ((`approved` IS NULL) AND (`reviewed` IS NULL)) ORDER BY `ID` LIMIT 1");
$dbQuery->execute();

while( $dbD = $dbQuery->fetch(PDO::FETCH_ASSOC) ){ ?>
	<h3>ID <?php echo $dbD['ID']; ?></h3>
	<p>Address: <?php echo $dbD['btcAddress']; ?> (<a href="https://blockchain.info/address/<?php echo $dbD['btcAddress']; ?>" target="_blank">Blockchain</a>)<br>
	Description: <strong><?php echo $dbD['description']; ?></strong><br>
	Album: <?php echo $dbD['album']; ?> (<a href="https://btcpics.de/search.php?album=<?php echo $dbD['album']; ?>" target="_blank">View Album</a>)<br>
	Price: <?php echo $dbD['price']; ?><br>
	Received: <?php echo $dbD['received']; ?><br>
	Email: <?php echo $dbD['email']; ?> (<a href="mailto:<?php echo $dbD['email']; ?>">Send mail</a>)<br>
	Spamty link: <?php echo $dbD['spamtyLink']; ?> (<a href="<?php echo $dbD['spamtyLink']; ?>" target="_blank">Go to</a>)<br>
	IP: <?php echo $dbD['ip']; ?> (<a href="http://<?php echo $dbD['ip']; ?>.ipaddress.com" target="_blank">Lookup</a>)<br>
	Date: <?php echo $dbD['date']; ?></p>	
	<p><img src="picture.php?id=<?php echo $dbD['ID']; ?>" width="70%" height="70%" /></p>
<hr>
Share on 
	<a href="https://twitter.com/home?status=<?php echo urlencode('New picture "'.$dbD['description'].'" on https://btcpics.de/view.php?id='.$dbD['ID']); ?>" target="_blank">
	Twitter</a>
<hr>
<form class="form-horizontal" role="form" method="post" action="do.php">
  <div class="form-group">
    <label for="formMessage" class="col-sm-2 control-label">Reason for deletion</label>
    <div class="col-sm-10">
      Your picture was deleted from BTCpics.de because ...
      <textarea class="form-control" id="formMessage" name="formMessage" rows="3">it violates our terms of use.</textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <input type="hidden" name="ID" id="ID" value="<?php echo $dbD['ID']; ?>">
      <input type="hidden" name="email" id="email" value="<?php echo $dbD['email']; ?>">
      <input type="hidden" name="description" id="description" value="<?php echo $dbD['description']; ?>">
      <input type="submit" name="formSubmit" id="formSubmit" class="btn btn-success" value="APPROVE">
      <input type="submit" name="formSubmit" id="formSubmit" class="btn btn-danger" value="DELETE">
    </div>
  </div>
</form>

<?php } 

?>


<div class="footer">
<hr>
<p class="text-center">BTCpics Admin</p>
</div>
    </div><!-- /container -->
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
 </body>
</html>