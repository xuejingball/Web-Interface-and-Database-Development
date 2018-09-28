<?php

include('lib/common.php');
// written by GTusername2

	if (!isset($_SESSION['username'])) {
		header('Location: login.php');
		exit();
	}

	$itemID = (int)$_GET['itemID'];

	if (empty($itemID)) {
		$itemID = mysqli_real_escape_string($db, $_POST['itemID']);
	}
	//echo $itemID;
	$deleteID = (int)$_GET['deleteID'];
	// if (!empty($deleteID)){
	// 	echo $deleteID;
	// } else {
	// 	echo '0';
	// }

	$item_query = "SELECT Item.itemID,Item.item_name FROM Item WHERE Item.itemID = $itemID ";
    $item_result = mysqli_query($db, $item_query);
	if (!empty($item_result) && (mysqli_num_rows($item_result) == 0) ) {
        array_push($error_msg,  "SELECT ERROR: Item query <br>" . __FILE__ ." line:". __LINE__ );
    } else {
		while ($row = mysqli_fetch_array($item_result, MYSQLI_ASSOC)){
			$itemID = $row['itemID'];
			$item_name = $row['item_name'];
		}
	}

	$avgstar_query = "SELECT AVG(Rate.star) as avg_star FROM Rate WHERE Rate.itemID in (select Item.itemID from Item where LTRIM(RTRIM(Item.item_name)) = LTRIM(RTRIM('$item_name')) );";
	// $avgstar_query = "SELECT AVG(Rate.star) as avg_star FROM Rate WHERE Rate.itemID = $itemID;";
    $avgstart_result = mysqli_query($db, $avgstar_query);
	if (!empty($avgstart_result) && (mysqli_num_rows($avgstart_result) == 0) ) {
        array_push($error_msg,  "SELECT ERROR: avg star query <br>" . __FILE__ ." line:". __LINE__ );
    } else {
		while ($row = mysqli_fetch_array($avgstart_result, MYSQLI_ASSOC)){
			$avg_star = $row['avg_star'];
		}
	}

	$ratings_query = "SELECT Rate.*, first_name, last_name FROM Rate,User WHERE itemID in (select Item.itemID from Item  where LTRIM(RTRIM(Item.item_name)) = LTRIM(RTRIM('$item_name')) )
						AND User.username = Rate.username ;" ;
	$ratings_result = mysqli_query($db, $ratings_query);
	// if (!empty($ratings_result) && (mysqli_num_rows($ratings_result) == 0) ) {
        // array_push($error_msg,  "SELECT ERROR: ratings <br>" . __FILE__ ." line:". __LINE__ );
	$rating_count = mysqli_num_rows($ratings_result);
	//echo $rating_count;

	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
		if (isset($_POST['rate_btn'])) {
			$username = $_SESSION['username'];
			$itemID = mysqli_real_escape_string($db, $_POST['itemID']);
			$item_name = mysqli_real_escape_string($db, $_POST['item_name']);
			$avg_star = mysqli_real_escape_string($db, $_POST['avg_star']);
			echo $your_star = mysqli_real_escape_string($db, $_POST['your_star']);
			$your_comment = mysqli_real_escape_string($db, $_POST['your_comment']);
			$current_time = date('Y-m-d H:i:s', strtotime("now"));


			if(empty($your_star)){
				echo '<script type="text/javascript"> alert("Please select your rating of stars!") </script>';
			}
			// else if(empty($your_comment)){
			// 	echo '<script type="text/javascript"> alert("Please input your comment!") </script>';
			// }
			else {
				$query = "INSERT INTO Rate (username, itemID, star, comments, rating_time) " .
				"VALUES ('$username', $itemID, '$your_star', '$your_comment', '$current_time') ";

				$result = mysqli_query($db, $query);
				//include('lib/show_queries.php');

				if($result){
					echo '<script type="text/javascript"> alert("Rating added") </script>';
				}else{
					echo '<script type="text/javascript"> alert("You can only rate once!") </script>';
				}
			}
		}
		header("Refresh:0;url=rating.php?itemID=$itemID");
	}

	if(!empty($deleteID)) {
		$username = $_SESSION['username'];
		$deleteID = $_GET['deleteID'];
		$itemID = $_GET['itemID'];
		
		$query = "DELETE FROM Rate WHERE itemID = $deleteID and username = '$username';";

		$result = mysqli_query($db, $query);
		include('lib/show_queries.php');

		if($result){
			echo '<script type="text/javascript"> alert("Successful!") </script>';
		}else{
			echo '<script type="text/javascript"> alert("Failed!") </script>';
		}
		header("Refresh:0;url=rating.php?itemID=$itemID");
	}

?>


<link rel="stylesheet" href="css/style.css">
<?php include("lib/menu.php"); ?>

    <div class="container">
			<div class="center_content">
				<div class="col-lg-10 col-lg-offset-1">
					<div class="features">

							<div class="profile_section">

			<h3>GTBay Item Rating</h3>

			<form class="ratingform" action="rating.php" method="post">
				<label>Item ID: </label>
				<input type = "hidden" name = "itemID" value = "<?php print $itemID;?> " readonly>
				<?php print $itemID;?><br>

				<label>Item Name: </label>
				<input type = "hidden" name = "item_name" value = "<?php print $item_name;?> " readonly>
				<?php print $item_name;?><br>

				<label>Average Rating: </label>
				<input type = "hidden" name = "avg_star" 
				value = "<?php print number_format((float)$avg_star, 1, '.', '');?> " readonly>
				<?php print number_format((float)$avg_star, 1, '.', '');?><br>

				<?php if ($rating_count > 0 ) : ?>
					<label><strong>Ratings: </strong></label>
				</div>

					<table class="table table-hover">
						<tr>
							<td class='heading'>Rated by</td>
							<td class='heading'>Star</td>
							<td class='heading'>Date</td>
							<td class='heading'>Comments</td>
							<td class='heading'>Action</td>
						</tr>
						<tbody>
							<?php
							if (isset($ratings_result)) {
								while ($row = mysqli_fetch_array($ratings_result, MYSQLI_ASSOC)){
									print "<tr>";
									print "<td>{$row['first_name']} {$row['last_name']}</td>";
									print "<td>{$row['star']}</td>";
									print "<td>{$row['rating_time']}</td>";
									print "<td>{$row['comments']}</td>";
									if($row['username'] == $_SESSION['username']) {
										print "<td><a href='rating.php?itemID=".$itemID."&deleteID=".$row['itemID']."'><input type='button' name='delete_btn' value='Delete'/></a></td>";
									} else {
										print "<td> - </td>";
									}
									print "</tr>";
								}
							}
							?>
						</tbody>
					</table>
				<?php endif; ?>

				<label>My Rating: </label><br>
					<input type="radio" name="your_star" <?php if(isset($your_star) && $your_star==0);?> value="0"/>0 star&nbsp
					<input type="radio" name="your_star" <?php if(isset($your_star) && $your_star==1);?> value="1"/>1 star&nbsp
					<input type="radio" name="your_star" <?php if(isset($your_star) && $your_star==2);?> value="2"/>2 stars&nbsp
					<input type="radio" name="your_star" <?php if(isset($your_star) && $your_star==3);?> value="3"/>3 stars&nbsp
					<input type="radio" name="your_star" <?php if(isset($your_star) && $your_star==4);?> value="4"/>4 stars&nbsp
					<input type="radio" name="your_star" <?php if(isset($your_star) && $your_star==5);?> value="5"/>5 stars<br>


				<label>Comments: </label><br>
				<textarea type="text" class="inputvalues" name="your_comment" rows = "4"></textarea>

				<div class = "row">
					<div id = "button" class="col-md-6">
						<a href="bid_item.php?itemID=<?php echo $itemID; ?>"><input type="button" value="Cancel" class="button" /></a>
					</div>
					<div class="col-md-6">
						<input name="rate_btn" type="submit" class="button" value="Rate This Item"/>
					</div>

			 </div>


			</form>

	</div>
</div>
		<?php include("lib/error.php"); ?>

</div>
</div>
</body>
</html>
