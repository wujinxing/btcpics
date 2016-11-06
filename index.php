<?php include "includes/header.php"; ?>

<h1>BTCpics</h1>
<p>Buy stock photos with bitcoins.</p>


<div class="row">
<div class="col-md-8">
<h2>Popular pictures</h2>
<?php
// get connection info from external file
require_once "includes/dbconnect.php";
// select picture
$dbQuery = $db->query("SELECT * FROM `pics` WHERE `album` LIKE '31eafa2a7fcbf07c0f0928f3409a7f92' AND `approved` IS NOT NULL LIMIT 5;");

foreach($dbQuery as $dbD){ ?>
<div>
	<a href="view.php?id=<?php echo $dbD['ID']; ?>"><img src="picture.php?id=<?php echo $dbD['ID']; ?>" width="20%" height="20%" /></a>
	<p><?php echo $dbD['description']; ?></p>
	<p><a href="view.php?id=<?php echo $dbD['ID']; ?>">Buy for <?php echo $dbD['price']-$dbD['received']; ?> BTC</a></p>
</div>
<?php } ?>
</div>
<!-- sidebar -->
<div class="col-md-3 col-md-offset-1">
	<h3>Upload</h3>
	<p>Upload your pictures to BTCpics.de and earn some Bitcoins by selling them. It's completely free.</p>
	<a href="upload.php"><button type="button" class="btn btn-success">Upload your pics</button></a>
</div>
</div>

<?php include "includes/footer.php"; ?>