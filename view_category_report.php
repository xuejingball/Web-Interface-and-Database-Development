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
   <h3>Category Report</h3>

   <table class="table table-hover">
    <thead>
      <tr>
        <td class='heading'><strong><u>Category</u></strong></td>
        <td class='heading'><strong><u>Total Items</u></strong></td>
        <td class='heading'><strong><u>Min Price</u></strong></td>
        <td class='heading'><strong><u>Max Price</u></strong></td>
        <td class='heading'><strong><u>Average Price</u></strong></td>
      </tr>
    </thead>
    <tbody>
     <?php
     $query = "SELECT category_name, COUNT(itemID)  AS total_items, ".
     "MIN(get_it_now_price)  AS min_price, MAX(get_it_now_price)  AS max_price, AVG(get_it_now_price)  AS avg_price ".
     "FROM Item ".
     "GROUP BY category_name ".
     "ORDER BY category_name ";

     $result = mysqli_query($db, $query);
     if (!empty($result) && (mysqli_num_rows($result) == 0) ) {
       array_push($error_msg,  "SELECT ERROR: Query Category Report <br>" . __FILE__ ." line:". __LINE__ );
     }

     while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
      print "<tr>";
      print "<td>{$row['category_name']}</td>";
      print "<td>{$row['total_items']}</td>";

      if(empty($row['min_price'])){
        print "<td> - </td>";
      } else {
        print "<td>{$row['min_price']}</td>";
      }

      if(empty($row['max_price'])){
        print "<td> - </td>";
      } else {
        print "<td>{$row['max_price']}</td>";
      }

      if(empty($row['avg_price'])){
        print "<td> - </td>";
      } else {
        $avg_price = number_format((float)$row['avg_price'], 2, '.', '');
        print "<td> $avg_price </td>";
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
