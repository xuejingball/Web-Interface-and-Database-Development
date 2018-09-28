<?php
include('lib/common.php');

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$Username = mysqli_real_escape_string($db, $_POST['username']);
	$Password = mysqli_real_escape_string($db, $_POST['password']);

	// username is required
    if (empty($Username)) {
            array_push($error_msg,  "Please enter your username.");
    }

	// password is required
	if (empty($Password)) {
			array_push($error_msg,  "Please enter your password.");
	}

    if ( !empty($Username) && !empty($Password) )   {

        $query = "SELECT password FROM User WHERE username='$Username'";

        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result);

		// username exists in database
        if (!empty($result) && ($count > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $storedPassword = $row['password'];

            $options = [
                'cost' => 8,
            ];
             //convert the plaintext passwords to their respective hashses
             // 'michael123' = $2y$08$kr5P80A7RyA0FDPUa8cB2eaf0EqbUay0nYspuajgHRRXM9SgzNgZO
            $storedHash = password_hash($storedPassword, PASSWORD_DEFAULT , $options);   //may not want this if $storedPassword are stored as hashes (don't rehash a hash)
            $enteredHash = password_hash($Password, PASSWORD_DEFAULT , $options);

			// show entered information
            if($showQueries){
                array_push($query_msg, "Plaintext entered password: ". $Password);
                //Note: because of salt, the entered and stored password hashes will appear different each time
                array_push($query_msg, "Entered Hash:". $enteredHash);
                array_push($query_msg, "Stored Hash:  ". $storedHash . NEWLINE);  //note: change to storedHash if tables store the plaintext password value
                //unsafe, but left as a learning tool uncomment if you want to log passwords with hash values
                //error_log('username: '. $Username  . ' password: '. $Password . ' hash:'. $enteredHash);
            }

            //depends on if you are storing the hash $storedHash or plaintext $storedPassword
			// match the user entered password with the password stored in database, if match, login; otherwise, error
            if (password_verify($Password, $storedHash) ) {
                array_push($query_msg, "Password is Valid! ");
                $_SESSION['username'] = $Username;
                array_push($query_msg, "logging in... ");
                header(REFRESH_TIME . 'url=view_profile.php');		

            } else {
                array_push($error_msg, "Login failed: " . $Username . NEWLINE);
                array_push($error_msg, "To demo enter: ". NEWLINE . "michael@bluthco.com". NEWLINE ."michael123");
            }

        } else {
                array_push($error_msg, "The username entered does not exist: " . $Username);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
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
		<h2>GTBay Login Form</h2>
		<img src="imgs/gt_logo.png" class="logo"/>
	</center>


	<form class="myform" action="login.php" method="post">
    <div class = "row">
      <div class = "col-md-12">
        <div class = "form-group">
          <label for = "username_form"> Username: *</label>
          <input id = "username_form" type = "text" name = "username" class = "form-control" placeholder = "please enter your username *" required = "required" data-error = "Username is required.">
          <div class="help-block with-errors"></div>
        </div>
      </div>
    </div>
    <div class = "row">
      <div class = "col-md-12">
        <div class = "form-group">
          <label for = "password_form"> Password: *</label>
          <input id = "password_form" type = "password" name = "password" class = "form-control" placeholder = "please enter your password *" required = "required" data-error = "Password is required.">
          <div class="help-block with-errors"></div>
        </div>
      </div>
    </div>
      <div class = "row">
  		<div id = "button" class="col-md-6">
        <a href="register.php"><input type="button" class="button" value="Register"/></a>
  		</div>
  		<div class="col-md-6">
        <input type="submit" class="button" value="Login"/><br>
  		</div>
  	</div>
	</form>

	<?php include("lib/error.php"); ?>

	</div>
</body>
</html>
