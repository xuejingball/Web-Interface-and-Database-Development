<?php

include('lib/common.php');
// written by GTusername2

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

$search_itemID = (int)$_GET['itemID'];

if (empty($searched_itemID)) {
	$searched_itemID = mysqli_real_escape_string($db, $_POST['ID_item']);
}

$query = "SELECT itemID, item_name, description, category_name, condition_name, returnable, get_it_now_price, auction_end_time, starting_bid " .
"FROM Item " .
"WHERE itemID = $search_itemID";

$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
	array_push($error_msg,  "SELECT ERROR:  <br>" . __FILE__ ." line:". __LINE__ );
} else {
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$ID_item = $row['itemID'];
		$name_item = $row['item_name'];
		$description_item = $row['description'] ;
		$condition_item = $row['condition_name'];
		$category_item = $row['category_name'];
		$returable = (int)$row['returnable'];
		$getitnowprice_item = $row['get_it_now_price'];
		$auctionendtime_item = $row['auction_end_time'];
		$starting_bid = $row['starting_bid'];
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if(isset($_POST['update_description'])) {

		$description_item = mysqli_real_escape_string($db, $_POST['new_description']);
		$ID_item = mysqli_real_escape_string($db, $_POST['ID_item']);
		$update_query = "UPDATE Item Set description = '$description_item' WHERE itemID = $ID_item ;";

		$retval = mysqli_query($db, $update_query);
		include('lib/show_queries.php');
		if(!$retval) {
			array_push($error_msg,  "Update ERROR: Description <br>" . __FILE__ ." line:". __LINE__ );
		}
		header("Refresh:0;url=bid_item.php?itemID=$ID_item");
	}
	if(isset($_POST['getitnow'])) {
		$your_bid = mysqli_real_escape_string($db, $_POST['getitnowprice']);
		$username = $_SESSION['username'];
		$ID_item = mysqli_real_escape_string($db, $_POST['ID_item']);
		$name_item = mysqli_real_escape_string($db, $_POST['name_item']);
		$current_time = date('Y-m-d H:i:s', strtotime("now"));

		$query = "SELECT auction_end_time FROM Item WHERE itemID = $search_itemID and auction_end_time > now()";

		$result = mysqli_query($db, $query);
		if (empty($result)) {
			echo '<script type="text/javascript"> alert("Auction is ended!") </script>';
		} else {
			$valid = true;
			$maxbid_query = "SELECT max(bid_amount) AS max_bid FROM `Bid/GetItNow` WHERE itemID=$ID_item; ";
			$maxbid_result = mysqli_query($db, $maxbid_query);
			$max_bid = -1;
			if (!empty($maxbid_result) ) {
				while ($row = mysqli_fetch_array($maxbid_result, MYSQLI_ASSOC)){
					$max_bid = $row['max_bid'];
				}
			}

			if(doubleval($max_bid) == doubleval($your_bid) ) {
				$valid = false;
			}

			if($valid == true) {
				$query = "INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount, is_winner) " .
				"VALUES ('$username', '$ID_item', '$current_time', '$your_bid', 2)";

				$result = mysqli_query($db, $query);
				include('lib/show_queries.php');

				if($result){
					echo '<script type="text/javascript"> alert("Get it now successfully.") </script>';
				}else{
					echo '<script type="text/javascript"> alert("Get it now failed!") </script>';
				}
			} else {
				echo '<script type="text/javascript"> alert("Already sold!") </script>';
			}
		}
			//header("Refresh:0;url=bid_item.php?itemID=$ID_item");
		header("Refresh:0;url=search_item.php");
	}
	if (isset($_POST['bid_btn'])) {
		$your_bid = mysqli_real_escape_string($db, $_POST['yourbid']);
		$username = $_SESSION['username'];
		$ID_item = mysqli_real_escape_string($db, $_POST['ID_item']);
		$name_item = mysqli_real_escape_string($db, $_POST['name_item']);
		$current_time = date('Y-m-d H:i:s', strtotime("now"));
		$minimal_price = mysqli_real_escape_string($db, $_POST['min_price']);
		$get_now = mysqli_real_escape_string($db, $_POST['getitnowprice']);

		$query = "SELECT auction_end_time FROM Item WHERE itemID = $search_itemID and auction_end_time > now()";

		$result = mysqli_query($db, $query);
		if (empty($result)) {
			echo '<script type="text/javascript"> alert("Auction is ended!") </script>';
		} else {
			if(empty($your_bid) || !is_numeric($your_bid) ){
				echo '<script type="text/javascript"> alert("Please check your bid price!") </script>';
			}else if(doubleval($your_bid) < doubleval($minimal_price)){
				echo '<script type="text/javascript"> alert("Your bid price cannot be lower than the minimum price!") </script>';
			}else if( (int)$get_now > 0  && doubleval($your_bid) >= doubleval($get_now) ){
				echo '<script type="text/javascript"> alert("Your bid price must be lower than get it now price!") </script>';
			}else{
				$valid = true;
				$maxbid_query = "SELECT max(bid_amount) AS max_bid FROM `Bid/GetItNow` WHERE itemID=$ID_item; ";
				$maxbid_result = mysqli_query($db, $maxbid_query);
				$max_bid = -1;
				if (!empty($maxbid_result) ) {
					while ($row = mysqli_fetch_array($maxbid_result, MYSQLI_ASSOC)){
						$max_bid = $row['max_bid'];
					}
				}
				if($max_bid >= $your_bid ) {
					$valid = false;
				}

				if($valid == true) {
					$query = "INSERT INTO `Bid/GetItNow` (username, itemID, date_and_time, bid_amount) " .
					"VALUES ('$username', '$ID_item', '$current_time', '$your_bid')";

					$result = mysqli_query($db, $query);
					include('lib/show_queries.php');

					if($result){
						echo '<script type="text/javascript"> alert("bid added") </script>';
					}else{
						echo '<script type="text/javascript"> alert("Add bid Failed!") </script>';
					}
				}
			}
		}

		header("Refresh:0;url=bid_item.php?itemID=$ID_item");
	}

}

$min_query = "SELECT max(bid_amount) AS MAX FROM `Bid/GetItNow` WHERE itemID=$search_itemID; ";
$min_result = mysqli_query($db, $min_query);
if (!empty($min_result) && (mysqli_num_rows($min_result) == 0) ) {
	array_push($error_msg,  "SELECT ERROR:  <br>" . __FILE__ ." line:". __LINE__ .$min_query);
} else {
	while ($row = mysqli_fetch_array($min_result, MYSQLI_ASSOC)){
		$min_bid = $row['MAX'];
	}
}

if($min_bid <= $starting_bid) {
	if($starting_bid > 0) {
		$min_bid = $starting_bid;
	} else {
		$min_bid = 0.01;
	}
} else {
	$min_bid = $min_bid + 1;
}

$min_bid = number_format((float)$min_bid, 2, '.', '');

$cur_username = $_SESSION['username'];
$desc_query = "SELECT * FROM Item WHERE itemID = $search_itemID AND username = '$cur_username'";
$desc_result = mysqli_query($db, $desc_query);
$desc_count = mysqli_num_rows($desc_result);

?>

<?php include("lib/menu.php"); ?>
<link rel="stylesheet" href="css/style.css">
<div class="container">
	<div class="center_content">
		<div class="col-lg-10 col-lg-offset-1">
			<div class="features">
				<div class="profile_section">
					<h3>GTBay Item For Sale</h3>
					<form class="bidform" action="bid_item.php" method="post">

						<div class = "row">
							<div class = "col-md-6">
								<div class = "form-group">
									<label for = "itemname_form"> Item ID:</label>

									<input type = "hidden" id = "itemname_form" name = "ID_item" value = "<?php print $ID_item;?> ">
									<?php print $ID_item ;?>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class = "col-md-6">
								<div class = "form-group">
									<?php
									// print '<a href="rating.php?itemID=' . urlencode($search_itemID) . '"><input type="button" class = "button"  value="View Ratings"/></a>';
									print '<td><a href="rating.php?itemID=' . urlencode($search_itemID) . '">View Ratings</a></td>';
									?><br>

									<div class="help-block with-errors"></div>
								</div>
							</div>

						</div>

						<div class = "row">
							<div class = "col-md-6">
								<div class = "form-group">
									<label for = "itemname_form"> Item Name: </label>
									<input type = "hidden" name = "name_item" value = "<?php print $name_item;?> ">
									<?php print $name_item;?><br>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="returnable_form">Returns Accepted?  </label>
									<?php
									if( $returable == 1){
										print 'YES';
									} else {
										print 'NO';
									}
									?>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>

						<div class = "row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="category_form">Category: </label>
									<?php print $category_item;?>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="category_form">Condition: </label>
									<?php print $condition_item;?>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>

						<div class = "row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="auctionend_form">Auction Ends: </label>
									<br><?php print $auctionendtime_item;?>
									<div class="help-block with-errors"></div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="getitnow_form">Get It Now price:  </label>
									<input type = "hidden" name = "getitnowprice" value = "<?php print $getitnowprice_item;?> " >
									<?php print $getitnowprice_item;?>

									<?php if(!empty($getitnowprice_item)) : ?>
										<input type="submit" name="getitnow" class="button btn-sm" value="Get It Now"><br>
									<?php else : ?>
										<input type="hidden" name="getitnow_1" class="button btn-sm" value="Get It Now"><br>
									<?php endif; ?>
									<div class="help-block with-errors"></div>
								</div>
							</div>
						</div>

						<div class = "row">

							<div class = "col-md-12">
								<div class = "form-group">
									<label for = "description_form"> Description: </label>
									<?php if($desc_count > 0) : ?>
										<textarea id = "description_form"  name = "new_description" class = "form-control" rows = "4" placeholder = "<?php print $description_item;?>"><?php print $description_item;?></textarea>
										<input type="submit" name= "update_description" class="button btn-sm" value="UpdateDescription">
									<?php else : ?>
										<textarea readonly id = "description_form" name = "new_description" placeholder = "<?php print $description_item;?>" class = "form-control" rows = "4"><?php print $description_item;?> </textarea>
									<?php endif; ?><br>
									<div class="help-block with-errors"></div>
								</div>
							</div>

						</div>
						<label><strong>Show Lastest Bids </strong></label>

					</div>

						<table class="table table-hover">
							<tr>
								<td class='heading'>Bid Amount</td>
								<td class='heading'>Time of Bid</td>
								<td class='heading'>Username</td>
							</tr>
							<tbody>
								<?php
								$bid_query = "SELECT bid_amount, date_and_time, username FROM `Bid/GetItNow` WHERE itemID = $search_itemID ORDER BY date_and_time DESC LiMIT 4; ";
								$bid_result = mysqli_query($db, $bid_query);
								if (isset($bid_result)) {
									while ($row = mysqli_fetch_array($bid_result, MYSQLI_ASSOC)){
										print "<tr>";
										print "<td>{$row['bid_amount']}</td>";
										print "<td>{$row['date_and_time']}</td>";
										print "<td>{$row['username']}</td>";
										print "</tr>";
									}
								}
								?>
							</tbody>
						</table>
						<label><strong>Your Bid: $ </strong></label>
						<input type="text" class="inputvalues" name="yourbid" /><br>

						<label>minimum bid $:</label>
						<input type = "hidden" name = "min_price" value = "<?php print $min_bid;?> " >
						<?php print $min_bid;?><br>


						<div class = "row">
							<div class="col-md-6">
								<!-- <a href="view_profile.php"><input type="button" class = "button" id="back_button" value="Cancel"/></a> -->
								<a href="search_item.php"><input type="button" onclick="history.back();" value="Cancel" class="button" /></a>

							</div>
							<div class="col-md-6">
								<input type="submit" name="bid_btn" class="button" value="Bid On This Item"/>
							</div>

						</div>


					</form>
					<?php include("lib/error.php"); ?>
			</div>
		</div>
	</div>
</div>
</body>
</html>
