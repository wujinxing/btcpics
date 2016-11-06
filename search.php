<?php include "includes/header.php"; ?>

<h1>Search</h1>

<div><a class="btn btn-primary" role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">New Search</a></div>
<div class="collapse" id="collapseExample">
  <div class="well">
  <!-- description -->
  <form method="get" action="search.php">
  <div class="form-group">
    <label for="q">Description</label>
    <input type="text" class="form-control" id="q" name="q" placeholder="Search Terms" value="<?php echo $_GET['q']; ?>">
  </div>
  <button type="submit" class="btn btn-default">Search</button>
  </form>  
  <!-- album -->
  <form method="get" action="search.php">
  <div class="form-group">
    <label for="album">Album</label>
    <input type="text" class="form-control" id="album" name="album" placeholder="Album ID" value="<?php echo $_GET['album']; ?>">
  </div>
  <button type="submit" class="btn btn-default">Search</button>
  </form>  
  </div>
</div>
<br>
<div class="row">
<?php
// get connection info from external file
require_once "includes/dbconnect.php";

// Possible values for search
//   album			$_GET['album']
//   description	$_GET['q']

// only one search term is allowed
if( isset($_GET['album']) && isset($_GET['q']) ):
	echo "Please search one for album OR description";
else:
	
	// select pictures
	if( !empty($_GET['album']) ){
		$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE `album` LIKE :album AND `approved` IS NOT NULL ORDER BY `ID` DESC LIMIT 15");
		$dbQuery->execute(array( ':album' => $_GET['album'] ));
	}elseif( !empty($_GET['q']) ){
		$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE `description` LIKE :description AND `approved` IS NOT NULL ORDER BY `ID` DESC LIMIT 15");
		$dbQuery->execute(array( ':description' => "%".$_GET['q']."%" ));
	}else{
		$dbQuery = $db->prepare("SELECT * FROM `pics` WHERE `approved` IS NOT NULL ORDER BY `ID` DESC LIMIT 5");
		$dbQuery->execute();
	}
	while( $dbD = $dbQuery->fetch(PDO::FETCH_ASSOC) ){ ?>
	<div class="col-md-3">
		<a href="view.php?id=<?php echo $dbD['ID']; ?>"><img src="picture.php?id=<?php echo $dbD['ID']; ?>" width="100%" height="100%" /></a>
		<p><?php echo $dbD['description']; ?></p>
		<p><a href="view.php?id=<?php echo $dbD['ID']; ?>">Buy for <?php echo $dbD['price']-$dbD['received']; ?> BTC</a></p>
	</div>
	<?php } 

endif;
?>
</div>

<?php include "includes/footer.php"; ?>