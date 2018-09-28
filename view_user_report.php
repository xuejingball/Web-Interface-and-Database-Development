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
	<h3>User Report</h3>

    <table class="table table-hover">
        <thead>
            <tr>
              <td class='heading'><strong><u>Username</u></strong></td>
              <td class='heading'><strong><u>Listed</u></strong></td>
              <td class='heading'><strong><u>Sold</u></strong></td>
              <td class='heading'><strong><u>Purchased</u></strong></td>
              <td class='heading'><strong><u>Rated</u></strong></td>
            </tr>
        </thead>
        <tbody>
			<?php
			$query = "SELECT T1.username,T2.Listed,T3.Sold,T4.Purchased,T5.Rated from
			(SELECT username FROM User) AS T1

			LEFT OUTER JOIN
			(SELECT username, COUNT(itemID) AS Listed FROM Item GROUP BY username) AS T2
			ON T1.username = T2.username

			LEFT OUTER JOIN
			(SELECT username, COUNT(A.itemID) AS Sold FROM
			((SELECT itemID, username FROM Item) AS A INNER JOIN
			(SELECT itemID FROM `bid/getitnow` where is_winner IN (1,2)) AS B ON A.itemID = B.itemID) GROUP BY username) AS T3
			ON T1.username = T3.username

			LEFT OUTER JOIN
			(SELECT username, COUNT(itemID) AS Purchased FROM `bid/getitnow` WHERE is_winner IN (1,2) GROUP BY username) AS T4
			ON T1.username = T4.username

			LEFT OUTER JOIN
			(SELECT username, COUNT(*) AS Rated FROM (SELECT DISTINCT itemID, username FROM Rate) AS A GROUP BY username) AS T5
			ON T1.username = T5.username
			ORDER BY T2.Listed DESC;
			";

            $result = mysqli_query($db, $query);
             if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
                 array_push($error_msg,  "SELECT ERROR: Query User Report <br>" . __FILE__ ." line:". __LINE__ );
            }

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
				print "<tr>";
                print "<td>{$row['username']}</td>";
				if(!empty($row['Listed'])){
					print "<td>{$row['Listed']}</td>";
				}else {
					print "<td> 0 </td>";
				}
				if(!empty($row['Sold'])){
					print "<td>{$row['Sold']}</td>";
				}else {
					print "<td> 0 </td>";
				}
				if(!empty($row['Purchased'])){
					print "<td>{$row['Purchased']}</td>";
				}else {
					print "<td> 0 </td>";
				}
				if(!empty($row['Rated'])){
					print "<td>{$row['Rated']}</td>";
				}else {
					print "<td> 0 </td>";
				}
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
