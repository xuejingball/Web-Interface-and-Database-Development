<?php

$Username = $_SESSION['username'];
$query = "SELECT username FROM AdminUser WHERE username='$Username'";
$result = mysqli_query($db, $query);
$count = mysqli_num_rows($result);

?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<!-- <nav class="navbar navbar-light" style="background-color: #a6c1c4;"> -->
<nav class="topnav">
  <!-- <nav class="navbar navbar-inverse"> -->
  <div class="container-fluid">
    <ul class="nav navbar-nav">
         <li><a href="view_profile.php" <?php if($current_filename=='view_profile.php') echo "class='active'"; ?>>View Profile</a></li>
		 <li><a href="list_item.php" <?php if(strpos($current_filename, 'list_item.php') !== false) echo "class='active'"; ?>>List Item</a></li>
         <li><a href="search_item.php" <?php if($current_filename=='search_item.php') echo "class='active'"; ?>>Search Item</a></li>
         <li><a href="view_auction.php" <?php if($current_filename=='view_auction.php') echo "class='active'"; ?>>View auction result</a></li>
		 <?php if($count > 0) : ?>
			<li><a href="view_category_report.php" <?php if($current_filename=='view_category_report.php') echo "class='active'"; ?>>Category Report</a></li>
			<li><a href="view_user_report.php" <?php if($current_filename=='view_user_report.php') echo "class='active'"; ?>>User Report</a></li>
		 <?php endif; ?>
      <li><a href="logout.php" <span class='glyphicon glyphicon-log-out'></span> LogOut</a></li>
    </ul>
  </div>
</nav>
