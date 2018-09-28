<?php
include('lib/common.php');

if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$Username = mysqli_real_escape_string($db, $_POST['username']);
	$Password = mysqli_real_escape_string($db, $_POST['password']);
	$Cpassword = mysqli_real_escape_string($db, $_POST['cpassword']);
	$FirstName = mysqli_real_escape_string($db, $_POST['first_name']);
	$LastName = mysqli_real_escape_string($db, $_POST['last_name']);

	// username required
	if (empty($Username)) {
		array_push($error_msg,  "Please enter a username.");
	}
	// password required
	if (empty($Password)) {
		array_push($error_msg,  "Please enter a password.");
	}
	// first name required
	 if (empty($FirstName)) {
		array_push($error_msg,  "Please enter your first name.");
	}
	// last name required
	if (empty($LastName)) {
		array_push($error_msg,  "Please enter your last name.");
	}
	// confirm password required
	if (empty($Cpassword)) {
		array_push($error_msg,  "Please confirm your password.");
	}

	if(!empty($Username) && !empty($Password) && !empty($FirstName) && !empty($LastName) && !empty($Cpassword)){
		// match password with confirm password, if not match, error
		if($Password == $Cpassword)
		{
			$query = "SELECT username FROM User WHERE username='$Username'";
			$result = mysqli_query($db, $query);
			include('lib/show_queries.php');

			// if user already exists in database, error and ask user to enter another one
			if(!is_bool($result) && mysqli_num_rows($result)>0){
				//there is already a user with the same username
				echo '<script type="text/javascript"> alert("User already exists, try another username") </script>';
			}else{
				$query = "INSERT INTO User (userID, username, password, first_name, last_name) VALUES (NULL, '$Username', '$Password', '$FirstName', '$LastName')";
				$result = mysqli_query($db, $query);
				include('lib/show_queries.php');

				if($result){
					echo '<script type="text/javascript"> alert("User registered, go to the login page") </script>';
					header("Refresh:0;url=login.php");
				}else{
					echo '<script type="text/javascript"> alert("Failed!") </script>';
				}
			}

		}else{
			echo '<script type="text/javascript"> alert("Password and confirm password do not match") </script>';
		}
	}

}

?>

<!DOCTYPE html>
<html>
<head>
<title>Registration Page</title>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="css/style.css">
</head>

<body style="background-color:#b2bec3">

	<div id="main-wrapper">
	<center>
		<h2>GTBay Registration Form</h2>
		<img src="imgs/gt_logo.png" class="logo"/>
	</center>


	<form class="myform" action="register.php" method="post">

		<div class = "row">
      <div class = "col-md-12">
        <div class = "form-group">
          <label for = "firstname_form"> First Name: *</label>
          <input id = "firstname_form" type = "text" name = "first_name" class = "form-control" placeholder = "please enter your first name *" required = "required" data-error = "first name is required.">
          <div class="help-block with-errors"></div>
        </div>
      </div>
    </div>
		<div class = "row">
      <div class = "col-md-12">
        <div class = "form-group">
          <label for = "lastname_form"> Last Name: *</label>
          <input id = "lastname_form" type = "text" name = "last_name" class = "form-control" placeholder = "please enter your last name *" required = "required" data-error = "last name is required.">
          <div class="help-block with-errors"></div>
        </div>
      </div>
    </div>
		<div class = "row">
			<div class = "col-md-12">
				<div class = "form-group">
					<label for = "username_form"> Username: *</label>
					<input id = "username_form" type = "text" name = "username"  class = "form-control" placeholder = "please enter your username *" required = "required" data-error = "Username is required.">
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-12">
				<div class = "form-group">
					<label for = "password_form"> Password: *</label>
					<input id = "password_form" type = "password" name = "password"  class = "form-control" placeholder = "please enter your password *" required = "required" data-error = "Password is required.">
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>
		<div class = "row">
			<div class = "col-md-12">
				<div class = "form-group">
					<label for = "cpassword_form"> Confirm Password: *</label>
					<input id = "cpassword_form" type = "password" name = "cpassword"  class = "form-control" placeholder = "please enter your password again *" required = "required" data-error = "Confirm Password is required.">
					<div class="help-block with-errors"></div>
				</div>
			</div>
		</div>

		<div class = "row">
		<div id = "button" class="col-md-6">
			<a href="login.php"><input type="button" class = "button" value="<< Back"/></a>
		</div>
		<div class="col-md-6">
			<input name="signup_btn" type="submit" class="button" value="Sign Up"/>
		</div>
	</div>

	</form>
	<?php include("lib/error.php"); ?>

	</div>
</body>
</html>
