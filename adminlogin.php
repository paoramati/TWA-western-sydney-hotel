<?php
/*
	adminlogin.php
	The administrator login screen of the WSH. On successful login, admin is redirected to browse.php
*/
require_once("session.php");
require_once("nocache.php");
//initialise vars for holding admin login data
$username = "";
$password = "";
$errorMsg = "";

if (isset($_POST["submit"])) {
	
	$validForm = TRUE;		//bool maintains whether submitted form is valid

	$errorMsg = "<div class=\"error\"><p>There are problems with this form:</p><ul>";
	
	$username = $_POST["username"];
	if (empty($username)) {
		$errorMsg .= "<li>Username can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		//checks password is too long
		$check = "/^.{1,20}$/";
		if (!preg_match($check, $username)) {
			$errorMsg .= "<li>Username can not be longer than 20 characters.</li>";
			$validForm = FALSE;
		}
	}

	//validate user password
	$password = $_POST["password"];
	if (empty($password)) {
		$errorMsg .= "<li>Password can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		$check = "/^.{6,20}$/";
		if(!preg_match($check, $password)) {
			$errorMsg .= "<li>Password must be between 6-20 characters long.</li>";
			$validForm = FALSE;
		}
	}

	//if valid so far, check in db to see if this is a registered admin
	if ($validForm == TRUE) {
		require("connection.php");	
		//sanitise username & password for db	
		$username = mysqli_real_escape_string($conn, $username);
		$password = mysqli_real_escape_string($conn, $password);
		//create db query to check username & password in db
		$sq_admin = "SELECT username, password FROM administrators WHERE administrators.username = '$username' AND administrators.password = '$password'";
			
		$results = mysqli_query($conn, $sq_admin)
			or die ('Problem with query' . mysqli_error($conn));
			
		if (mysqli_num_rows($results) > 0 ) {
			//admin exists
			session_start();
			$_SESSION["username"] = $username;
			$row = mysqli_fetch_array($results);
			$_SESSION["usergname"] = $row["gname"];	//get user name from db
			$_SESSION["userType"] = 'admin';
			mysqli_close($conn);
			header("location: browse.php");
		}
		//if login details not found is db
		else {
			$errorMsg .= "<li>Login details are incorrect. Please try re-entering your details.</li>";
			$validForm = FALSE;
		}
	}
	
	//if form login is invalid, concat & display error message list
	if ($validForm == FALSE) {
		$errorMsg .= "</ul></div>";
	}
	else
		$errorMsg = "";		//set errorMsg to empty if form is valid.	
}
?>
<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Western Sydney Hotel: Administrator Login</title>
		<link rel="stylesheet" type="text/css" href="bmp_style.css">
		<script src="wsh_validation.js"></script>
	</head>
	<body>
		<div id="head">
		
			<h1>Western Sydney Hotel</h1>
		</div>
		<div class="nav">
			<?php	
				include "nav_pages.php";
			?>
		</div>
		<h2>Administrator Login</h2>
		<h3>Please enter your username and password to sign in</h3>
		
		<form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
			<?php echo $errorMsg; ?>
			<label for="username">Username</label>
			<input type="text" name="username" id="username" value="<?php echo $username; ?>">
			<span class="error" id="usernameMsg"></span>
			<br>
				
			<label for="password">Password</label>
			<input type="text" name="password" id="password" onblur="checkPassword(this)" value="<?php echo $password; ?>">
			<span class="error" id="passwordMsg"></span>
			<br>
			
			<input class="sub" type="submit" name="submit" value="Finish">
			<input type="reset" name="reset" value="Reset">
		
		</form>
	</body>
</html>