<?php

include('lib/common.php');
// written by GTusername3

header("Cache-Control: no cache");
session_cache_limiter("private_no_expire");

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

include("lib/calculate_winner.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['SearchItems']) ) {

		$keyword = mysqli_real_escape_string($db, $_POST['keyword']);
		$category = mysqli_real_escape_string($db, $_POST['category']);
		$ConditionAtLeast = mysqli_real_escape_string($db, $_POST['condition_name']);
		$MinimumPrice = mysqli_real_escape_string($db, $_POST['minimumPrice']);
		$MaximumPrice = mysqli_real_escape_string($db, $_POST['maximumPrice']);

		$search_query = "SELECT itemID, item_name, Current_Bid, High_Bidder, get_it_now_price, auction_end_time,starting_bid FROM
				(SELECT A.*, B.bid_amount AS Current_Bid, B.username AS High_Bidder, B.is_winner FROM
				(SELECT Item.*,conditionID FROM Item,ConditionState WHERE auction_end_time > now() AND ConditionState.condition_name = Item.condition_name) AS A
				LEFT JOIN
				(SELECT username, itemID, bid_amount,is_winner FROM `bid/getitnow`  AS B1
				where bid_amount = (select max(bid_amount) FROM `bid/getitnow` AS B2 where B1.itemID = B2.itemID)) AS B
				ON A.itemID = B.itemID
				ORDER BY A.auction_end_time ASC ) AS C " .
				"WHERE is_winner IS NULL";

		$has_error = false;

		if (!empty($MinimumPrice) && !is_numeric($MinimumPrice)){
			$has_error = true;
			array_push($error_msg, "Please enter a numeric number for minimum price!");
		}
		if (!empty($MaximumPrice) && !is_numeric($MaximumPrice)){
			$has_error = true;
			array_push($error_msg, "Please enter a numeric number for maximum price!");
		}
		if (!empty($MaximumPrice) && !empty($MinimumPrice) && ($MaximumPrice <= $MinimumPrice )){
			$has_error = true;
			array_push($error_msg, "The minimum price should be lower than the maximum price!");
		}
		if(!$has_error){
			if (!empty($keyword)) {
				$search_query = $search_query . " AND (item_name LIKE '%$keyword%' OR description LIKE '%$keyword%') ";
			}
			if (!empty($category)) {
				$search_query = $search_query . " AND category_name = '$category' ";
			}
			if (!empty($ConditionAtLeast)) {
				$con_query = "SELECT conditionID FROM ConditionState WHERE condition_name LIKE '%$ConditionAtLeast%'";
				$con_result = mysqli_query($db, $con_query);
				include('lib/show_queries.php');
				$row = mysqli_fetch_array($con_result, MYSQLI_ASSOC);
				$ConditionID = $row['conditionID'];
				$search_query = $search_query . " AND conditionID <= $ConditionID ";
			}
			if (!empty($MinimumPrice)) {
				$search_query = $search_query . " AND ((Current_Bid is NULL AND  starting_bid  >= $MinimumPrice) OR (starting_bid <= Current_Bid AND  Current_Bid  >= $MinimumPrice)) ";
			}
			if (!empty($MaximumPrice)) {
				$search_query = $search_query . " AND ((Current_Bid is NULL AND  starting_bid  <= $MaximumPrice) OR (starting_bid <= Current_Bid AND  Current_Bid  <= $MaximumPrice))";
			}

			$search_result = mysqli_query($db, $search_query);

			//include('lib/show_queries.php');

			if (mysqli_affected_rows($db) == -1) {
				array_push($error_msg,  "SELECT ERROR:Failed to find items ... <br>" . __FILE__ ." line:". __LINE__ .$search_query);
			}else{
				array_push($error_msg, $search_query);
			}
		}
	}
?>

            <?php include("lib/menu.php"); ?>
						<link rel="stylesheet" href="css/style.css">

        	<div class="container">

			<div class="center_content">
				<div class="col-lg-10 col-lg-offset-1">
					<div class="title_name"><?php print $user_name; ?></div>
					<div class="features">

						<div class="profile_section">
							<h3>Search for Items</h3>

							<form name="searchform" action="search_item.php" method="POST">
								<div class = "row">
									<div class = "col-md-12">
										<div class = "form-group">
											<label for = "keyword_form"> Keyword: </label>
											<input id = "Keyword_form" type = "text" name = "keyword" value = "<?php echo isset($_POST['keyword'])?$_POST['keyword']:''?>" class = "form-control" placeholder = "please enter keyword.">
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>


								<div class = "row">
									<div class = "col-md-12">
										<div class = "form-group">
											<label for = "category_form"> Category: </label>
											<select name = "category">
										<option value = "" selected = "selected"> All </option>
										<?php
												$query = "SELECT category_name FROM Category ORDER By category_name";
												$result = mysqli_query($db, $query);
												 include('lib/show_queries.php');

                                                if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get category name... <br>" . __FILE__ ." line:". __LINE__ );
                                                }

												 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
												 		print '<option value ="' . $row['category_name'] . '">' . $row['category_name'] . '</option>';
												}
										?>
										</select>
											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>

								<div class = "row">
									<div class = "col-md-6">
										<div class = "form-group">
											<label for = "condition_name"> Condition At Least: </label>

											<select name = "condition_name">;
										<option value = "" selected = "selected"> All </option>

										<?php
												$query = "SELECT * FROM ConditionState ORDER BY conditionID";
												$result = mysqli_query($db, $query);
												 include('lib/show_queries.php');

                                                if (is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                                                    array_push($error_msg,  "Query ERROR: Failed to get condition name... <br>" . __FILE__ ." line:". __LINE__ );
                                                }

												 while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
													 print $row['condition_name'];
													 print '<option value ="' . $row['condition_name'] . '">' . $row['condition_name'] . '</option>';
												}
											?>

										</select>

											<div class="help-block with-errors"></div>
										</div>
									</div>
								</div>



									<div class = "row">
										<div class = "col-md-12">
											<div class = "form-group">
												<label for = "minimumPrice_form"> Minimum Price $: </label>
												<input id = "minimumPrice_form" type = "text" name = "minimumPrice" value = "<?php echo isset($_POST['minimumPrice'])?$_POST['minimumPrice']:''?>" class = "form-control" placeholder = "please enter minimum Price.">
												<div class="help-block with-errors"></div>
											</div>
										</div>
									</div>
									<div class = "row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="maximumPrice_form">Maximum Sale Price $: </label>
												<input id="maximumPrice_form" type="text" name="maximumPrice" value ="<?php echo isset($_POST['maximumPrice'])?$_POST['maximumPrice']:''?>" class="form-control" placeholder="Please enter maximum price ">
												<div class="help-block with-errors"></div>
											</div>
										</div>
									</div>



								<input type="submit" name="SearchItems" class = "button" value="Search"/><br>
							</form>
						</div>

						<?php if(isset($search_result)) : ?>
						<!-- <div class='container'> -->
						<!-- <h4>Search Results</dh4> -->
						<table class="table table-hover">
							<thead>
								<tr>
								  <td class='heading'>ID</td>
								  <td class='heading'>Item Name</td>
								  <td class='heading'>Current Bid</td>
								  <td class='heading'>High Bidder</td>
								  <td class='heading'>Get It Now Price</td>
								  <td class='heading'>Auction Ends</td>
								</tr>
							</thead>
							<tbody>
								<?php
									if (isset($search_result)) {
										while ($row = mysqli_fetch_array($search_result, MYSQLI_ASSOC)){
											$searched_itemID = urlencode($row['itemID']);
											print "<tr>";
											print "<td>{$row['itemID']}</td>";
											print "<td><a href='bid_item.php?itemID=$searched_itemID'>{$row['item_name']}</a></td>";

											if(!empty($row['Current_Bid'])){
												print "<td>{$row['Current_Bid']}</td>";
											}else {
												print "<td> - </td>";
											}

											if(!empty($row['High_Bidder'])){
												print "<td>{$row['High_Bidder']}</td>";
											}else {
												print "<td> - </td>";
											}
											//print "<td>{$row['bid_amount']}</td>";
											//print "<td>{$row['username']}</td>";
											print "<td>{$row['get_it_now_price']}</td>";
											print "<td>{$row['auction_end_time']}</td>";
											print "</tr>";
										}
									}	?>
							</tbody>
						</table>
						<!-- </div> -->
						<?php endif; ?>
					 </div>
				</div>

                <?php include("lib/error.php"); ?>

				<div class="clear"></div>
			</div>

		</div>
	</body>
</html>
