<?php

include('lib/common.php');
// written by GTusername4

if (!isset($_SESSION['username'])) {
	header('Location: login.php');
	exit();
}

    // ERROR: demonstrating SQL error handlng, to fix
    // replace 'sex' column with 'gender' below:
    $query = "SELECT username, last_name, first_name " .
		 "FROM User " .
		 "WHERE User.username='{$_SESSION['username']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');

    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }
	
	$admin_query = "SELECT position " .
		 "FROM AdminUser " .
		 "WHERE AdminUser.username='{$_SESSION['username']}'";
	$admin_result = mysqli_query($db, $admin_query);	
	$count = mysqli_num_rows($admin_result);
?>

<head>
<title>GTBay Main Menu</title>
<link rel="stylesheet" href="css/style.css">
</head>

<body style="background-color:#b2bec3">
<?php include("lib/menu.php"); ?>
	<div id="main-wrapper">
	<center>
		<h3>GTBay Main Menu</h3>
		<img src="imgs/gt_bay.png" class="logo"/>
	</center>


	<form class="myform" action="view_profile.php" method="post">
	<center>
		<h3 class="item_label">Welcome to GT Bay!</h3>
		<p>username: <?php print $row['username'];?></p>
		<p>First Name: <?php print $row['first_name'];?></p>
		<p>Last Name: <?php print $row['last_name'];?></p>
		<?php if(!empty($admin_result) && count>0) : ?>
			<p>Position: <?php 
						$row = mysqli_fetch_array($admin_result, MYSQLI_ASSOC);
						print $row['position'];
					 ?></p>
		<?php endif; ?>
	</center>
	</form>

	<?php include("lib/error.php"); ?>

	</div>
</body>

</html>
