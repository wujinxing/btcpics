<?php include "includes/header.php"; 


// get connection info from external file
require_once "includes/dbconnect.php";
// select picture
$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE `ID` = :id");
$dbQuery->execute(array( ':id' => $_GET['id'] ));
$dbD = $dbQuery->fetch(PDO::FETCH_ASSOC);

if( $dbD['approved'] != 1 ):
	echo "This picture has to be approved by a team member. It is not available yet.";
else:
?>

<div class="row">
<div class="col-md-8">

<h1>Picture</h1>
<p class="lead"><?php echo $dbD['description']; ?></p>

<?php // not enough money -> show thumb
if( $dbD['received'] < $dbD['price'] ): ?>
	<p>Price: <?php echo $dbD['price']; ?> BTC | Already received: <?php echo $dbD['received']; ?> BTC</p>
	<p>Send Bitcoins to: <strong><?php echo $dbD['btcAddress']; ?></strong></p>
	<p>The picture will be available in the full resolution if someone sends <strong><?php echo $dbD['price']-$dbD['received']; ?> BTC</strong> to the address above.</p>
<?php // price reached -> show image
else: ?>
	<p>Send Bitcoins to: <strong><?php echo $dbD['btcAddress']; ?></strong></p>
	<p>The creator has reached his price of <?php echo $dbD['price']; ?> BTC for this picture. You can send donations to the address above.</p>
<?php endif; ?>

<p><a href="picture.php?id=<?php echo $_GET['id']; ?>">
	<img src="picture.php?id=<?php echo $_GET['id']; ?>" width="70%" height="70%" />
</a></p>

<p>Share the picture:
	<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://btcpics.de/view.php?id='.$_GET['id']); ?>" target="_blank" title="On Facebook"><img src="img/buddycons/facebook_r.png" alt="Facebook"></a>
	<a href="https://twitter.com/share?url=<?php echo urlencode('https://btcpics.de/view.php?id='.$_GET['id']); ?>&text=<?php echo urlencode('Picture for '.($dbD['price']-$dbD['received']).' BTC '); ?>&related=bitcoinpics" target="_blank" title="On Twitter"><img src="img/buddycons/twitter_r.png" alt="Twitter"></a>
	<a href="mailto:?to=&subject=<?php echo rawurlencode('Picture for '.($dbD['price']-$dbD['received']).' BTC'); ?>&body=<?php echo rawurlencode('You can find it here: https://btcpics.de/view.php?id='.$_GET['id']); ?>" target="_blank" title="Via email"><img src="img/buddycons/email_r.png" alt="E-Mail"></a>
</p>


<p xmlns:dct="http://purl.org/dc/terms/">
  <a rel="license" href="license.php">
    <img src="https://licensebuttons.net/p/zero/1.0/80x15.png" style="border-style: none;" alt="CC0" />
  </a>
  <br />You can use this picture under the terms of <a href="license.php">this license</a>.
</p>

<h2>Comments</h2>

</div>

<!-- Sidebar -->
<div class="col-md-3 col-md-offset-1">
	<h3>More</h3>
	<ul>
		<?php if(!empty($dbD['spamtyLink'])){ ?>
		<li><a href="<?php echo $dbD['spamtyLink']; ?>" target="_blank" rel="nofollow" title="Email address on Spamty">Contact the uploader</a></li>
		<?php } ?>
		<?php if(!empty($dbD['album'])){ ?>
		<li><a href="search.php?album=<?php echo $dbD['album']; ?>" title="Album">More from the same album</a></li>
		<?php } ?>
		<li><a href="license.php" title="License info">License</a></li>
		<li><a href="report.php?id=<?php echo $_GET['id']; ?>" title="Report this photo">Report</a></li>
	</ul>
	<h3>BTCpics.de</h3>
	<ul>
		<li><a href="contact.php" title="Contact website owner">Contact</a></li>
		<li><a href="faq.php" title="Help/FAQ">FAQ</a></li>
		<li><a href="terms.php">Terms of Use</a></li>
		<li><a href="upload.php" title="Upload your own pictures">Upload pictures</a></li>
	</ul>
</div>
</div>

<?php endif;
include "includes/footer.php"; ?>