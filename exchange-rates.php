<?php include "includes/header.php"; ?>

<h1>Bitcoin exchange rates</h1>
<p>The current exchange rates for <em>1 BTC</em> =</p>

<?php
$data=json_decode(file_get_contents("exchange-rates.json"), true);
?>
<p>
	<?php echo $data['USD']['last']; ?> USD<br>
	<?php echo $data['GBP']['last']; ?> GBP<br>
	<?php echo $data['EUR']['last']; ?> EUR<br>
	<?php echo $data['CHF']['last']; ?> CHF
</p>

<h2>Exchanges</h2>
<p>We recommend the following Bitcoin exchanges:</p>
<ul>
	<li><a href="https://www.bitcoin.de/de/">Bitcoin.de</a> (only EUR)</li>
	<li><a href="https://btc-e.com">BTC-e</a> (USD, GBP, EUR)</li>
</ul>
<?php include "includes/footer.php"; ?>