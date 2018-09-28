<?php   
$query = "SELECT username, A.itemID, date_and_time, bid_amount,is_winner,auction_end_time,minimum_sale_price FROM 
	(SELECT username, itemID, date_and_time, bid_amount,is_winner FROM `bid/getitnow`  AS A1 
	where bid_amount = (select max(bid_amount) FROM `bid/getitnow` AS A2 where A1.itemID = A2.itemID) AND A1.is_winner IS NULL) AS A 
	INNER JOIN 
	(SELECT itemID,auction_end_time,minimum_sale_price FROM Item WHERE auction_end_time <= now()) AS B 
	ON A.itemID = B.itemID
	";

	$result = mysqli_query($db, $query);
	
	if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
        // echo 'No record need to be updated.';
	} else {
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
			$is_winner = 0;
			if($row['bid_amount'] >= $row['minimum_sale_price']){
				$is_winner = 1;
			}
			
			$update_sql = "Update `bid/getitnow` SET is_winner = $is_winner".
			" WHERE itemID = '{$row['itemID']}' and username = '{$row['username']}' and date_and_time = '{$row['date_and_time']}'";
			$retval = mysqli_query($db, $update_sql);
			
			if(!$retval) {
				array_push($error_msg,  "Update ERROR: Calculate winner <br>" . __FILE__ ." line:". __LINE__ );
			}
		}
	}
	?>