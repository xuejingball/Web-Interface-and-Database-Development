<?php

include('lib/common.php');
// written by GTusername3

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}
?>

<?php include("lib/menu.php"); ?>
<link rel="stylesheet" href="css/style.css">

<div class="container">
	<div class="center_content">

		<h3>Auction Results</h3>
		<?php include("lib/calculate_winner.php"); ?>
		<table class="table table-hover">
			<thead>
				<tr>
					<td class='heading'><strong><u>ID</u></strong></td>
					<td class='heading'><strong><u>Item Name</u></strong></td>
					<td class='heading'><strong><u>Sale Price</u></strong></td>
					<td class='heading'><strong><u>Winner</u></strong></td>
					<td class='heading'><strong><u>Auction Ended</u></strong></td>
				</tr>
			</thead>
			<tbody>
				<?php
				$query = "SELECT itemID, item_name, bid_amount, username,is_winner,auction_end_time FROM 
				(SELECT A.itemID, item_name, bid_amount, username,is_winner,auction_end_time FROM
					(SELECT itemID, item_name, auction_end_time FROM Item) AS A
				LEFT JOIN
					(SELECT itemID, username, date_and_time,bid_amount,is_winner FROM `bid/getitnow` AS B1 where bid_amount = (select max(bid_amount) from `bid/getitnow` AS B2 where B1.itemID = B2.itemID)) AS B
				ON A.itemID = B.itemID
				WHERE auction_end_time <= now() AND (is_winner = 1)
				
				Union ALL
                
                SELECT A.itemID, item_name, NULL, NULL,is_winner,auction_end_time FROM
					(SELECT itemID, item_name, auction_end_time FROM Item) AS A
				LEFT JOIN
					(SELECT itemID, username, date_and_time,bid_amount,is_winner FROM `bid/getitnow` AS B1 where bid_amount = (select max(bid_amount) from `bid/getitnow` AS B2 where B1.itemID = B2.itemID)) AS B
				ON A.itemID = B.itemID
				WHERE auction_end_time <= now() AND (is_winner is NULL or is_winner = 0)
				
				Union ALL
				
				SELECT A.itemID, item_name, bid_amount, username,is_winner, date_and_time as auction_end_time FROM
				(SELECT itemID, item_name, auction_end_time FROM Item) AS A
				LEFT JOIN
				(SELECT itemID, username, date_and_time,bid_amount,is_winner FROM `bid/getitnow` AS B1 where bid_amount = (select max(bid_amount) from `bid/getitnow` AS B2 where B1.itemID = B2.itemID)) AS B
				ON A.itemID = B.itemID
				WHERE is_winner = 2) AS C

				ORDER BY auction_end_time DESC;";

				$result = mysqli_query($db, $query);
				if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
					array_push($error_msg,  "SELECT ERROR: Category Report; Or No Item information <br>" . __FILE__ ." line:". __LINE__ );
				}

				while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
					print "<tr>";
					print "<td>{$row['itemID']}</td>";
					print "<td>{$row['item_name']}</td>";

					if(empty($row['bid_amount'])){
						print "<td> - </td>";
					} else {
						print "<td>{$row['bid_amount']}</td>";
					}

					if(empty($row['username'])){
						print "<td> - </td>";
					} else {
						print "<td>{$row['username']}</td>";
					}

					print "<td>{$row['auction_end_time']}</td>";

					print "</tr>";
    			}
				?>
			</tbody>
		</table>

		<div class="btn-group pull-right">
			<a href="view_profile.php"><button type="button" class="btn btn-primary">Done</button></a>
		</div>

	</div>
</div>
</body>
</html>
