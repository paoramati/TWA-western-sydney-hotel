<?php
//This is the registration page for the Western Sydney Hotel, or TWA Assignment 1
require_once("session.php");
require_once("nocache.php");

//initialise vars for holding form data
$username = "";
$password = "";
$confirm = "";
$gname = "";
$sname = "";
$address = "";
$state = "";
$postcode = "";
$mobile = "";
$email = "";
$errorMsg = "";
$registerCorrect = TRUE;	

//if form has been submitted	
if (isset($_POST["submit"])) {
		
	/*
		error message is in a div containing a list of validation errors
		$valid holds whether there are errors in the form or not
		
	Reference: 			https://www.dougv.com/2009/06/retaining-values-in-a-form-following-php-postback-and-clearing-form-values-after-successful-php-form-processing/
	*/	
	$validForm = TRUE;		//bool maintains whether submitted form is valid
	$errorMsg = "<div class=\"error\"><p>There are problems with this form:</p><ul>";
	
	$username = $_POST["username"];
	if (empty($username)) {
		$errorMsg .= "<li>Username can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		//checks if 
		$check = "/^.{1,20}$/";
		if (!preg_match($check, $username)) {
			$errorMsg .= "<li>Username can not be longer than 20 characters.</li>";
			$validForm = FALSE;
		}
		else {
			require("connection.php");
			//sanitise username for db
			$username = mysqli_real_escape_string($conn, $username);
			//create db query to check username in db
			$sq_userCheck = "SELECT username FROM customers WHERE customers.username = '$username'";
			
			$resultsUser = mysqli_query($conn, $sq_userCheck)
				or die ('Problem with query' . mysqli_error($conn));
				
			if (mysqli_num_rows($resultsUser) > 0 ) {
				$errorMsg .= "<li>Username is taken.</li>";
				$validForm = FALSE;
			}
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
	
	//validate confirm password
	$confirm = $_POST["confirm"];
	if (empty($confirm)) {
		$errorMsg .= "<li>Confirm password can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		if ($confirm != $password) {
			$errorMsg .= "<li>Passwords do not match.</li>";
			$validForm = FALSE;
		}
	}
	
	//validate given name
	$gname = $_POST["gname"];
	if (empty($gname)) {
		$errorMsg .= "<li>Given name can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		$check = "/^[a-zA-Z\-\'\s]{1,20}$/";
		if (!preg_match($check, $gname)) {
			$errorMsg .= "<li>Given name must be no longer than 20 characters and " +
			"contain only alphabetical letters, hyphen, apostraphe, and space.</li>";
			$validForm = FALSE;
		}
	}
	
	//validate surname
	$sname = $_POST["sname"];
	if (empty($sname)) {
		$errorMsg .= "<li>Family name can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		$check = "/^[a-zA-Z\-\'\s]{1,20}$/";
		if (!preg_match($check, $sname)) {
			$errorMsg .= "<li>Family name must be no longer than 20 characters and " +
			"contain only alphabetical letters, hyphen, apostraphe, and space.</li>";
			$validForm = FALSE;
		}
	}
	
	//validate address
	$address = $_POST["address"];
	if (empty($address)) {
		$errorMsg .= "<li>Address can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		$check = "/^.{1,40}$/";
		if (!preg_match($check, $address)) {
			$errorMsg .= "<li>Address must be no longer than 40 characters.</li>";
			$validForm = FALSE;
		}
	}
	
	//validate state
	$state = $_POST["state"];
	if ($state == "") {
		$errorMsg .= "<li>State has not been chosen.</li>";
		$validForm = FALSE;
	}
	
	//validate postcode
	$postcode = $_POST["postcode"];
	if (empty($postcode)) {
		$errorMsg .= "<li>Postcode can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		$check = "/^[0-9]{4}$/";
		if (!preg_match($check, $postcode)) {
			$errorMsg .= "<li>Post must be 4 digits only.</li>";
			$validForm = FALSE;
		}
	}
	
	//validate mobile phone number
	$mobile = $_POST["mobile"];
	if (empty($mobile)) {
		$errorMsg .= "<li>Mobile phone number can not be empty.</li>";
		$validForm = FALSE;
	}
	else {
		$check = "/^04[0-9]{8}$/";
		if (!preg_match($check, $mobile)) {
			$errorMsg .= "<li>Mobile number must with 10-digits and start with \'04\'";
			$validForm = FALSE;
		}
	}
	
	//validate email address
	$email = $_POST["email"];
	if (empty($mobile)) {
		$errorMsg .= "<li>Email address can not be empty.<li>";
		$validForm = FALSE;
	}
	else {
		$check = "/^[\w]+@(?:[a-zA-Z0-9]+\.)+[a-zA-Z]{2,40}/";
		if (!preg_match($check, $email)) {
			$errorMsg .= "<li>Email must be no longer than 40 characters and may only contain one \'@\'.</li>";
			$validForm = FALSE;
		}
	}
	//if form is invalid, concat errorMsg into validation list
	if ($validForm == FALSE) {
		$errorMsg .= "</ul></div>";
	}
	else
		$errorMsg = "";		//set errorMsg to empty if form is valid.
}	

//write registration details to westernhotel database if form submitted and valid
if (isset($_POST["submit"]) && ($validForm == TRUE)) {
	//$username = mysqli_real_escape_string($conn, $username);
	$password = mysqli_real_escape_string($conn, $password);
	$gname = mysqli_real_escape_string($conn, $gname);
	$sname = mysqli_real_escape_string($conn, $sname);
	$address = mysqli_real_escape_string($conn, $address);
	$state = mysqli_real_escape_string($conn, $state);
	$postcode = mysqli_real_escape_string($conn, $postcode);
	$mobile = mysqli_real_escape_string($conn, $mobile);
	$email = mysqli_real_escape_string($conn, $email);
	
	//sql statement to insert registration data
	$sqRegister = "INSERT INTO customers (username, password, gname, sname, ";
	$sqRegister .= "address, state, postcode, mobile, email) VALUES ";
	$sqRegister .= "('$username', '$password', '$gname', '$sname', '$address', ";
	$sqRegister .= "'$state', '$postcode', '$mobile', '$email')";
	
	//insertion statement
	$results = mysqli_query($conn, $sqRegister)
		or die ('Problem with query' . mysqli_error($conn));
	
	//if registration INSERT is successful	
	if ($results){
		mysqli_close($conn);
		header("location: customerlogin.php");
	}
	else {
		mysqli_close($conn);
		$registerCorrect = FALSE;
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Western Sydney Hotel: Registration</title>
		<link rel="stylesheet" type="text/css" href="bmp_style.css">
		<script src="wsh_validation.js"></script>
	</head>	
<body>
	<div id="head">
		<h1>Western Sydney Hotel</h1>
	</div>
		
	<div class="nav">
		<?php	
			include("nav_pages.php");
		?>
	</div>
	
	<h2>Register for the Western Sydney Hotel</h2>
	<h3>Please enter your details to view and book a room</h3>
		
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" onsubmit="return validateForm(this);">
		<h4>NB: All fields are mandatory</h4>
		<?php echo $errorMsg; ?>
		<p class="error" id="errorDisplay"></p>
		<label for="username">Username</label>
		<input type="text" name="username" id="username" value="<?php echo $username; ?>">
		<span class="error" id="usernameMsg"></span>
		<br>
				
		<label for="password">Password</label>
		<input type="text" name="password" id="password" onblur="checkPassword(this)" value="<?php echo $password; ?>">
		<span class="error" id="passwordMsg"></span>
		<br>
				
		<?php 
			//event for password handling should be different.
			//perhaps only client-side validate on form submit
		?>
				
		<label for="confirm">Confirm Password</label>
		<input type="text" name="confirm" id="confirm" onblur="confirmPassword(this)" value="<?php echo $confirm; ?>">
		<span class="error" class="error" id="confirmMsg"></span>
		<br>
				
		<label for="first_name">Given Name</label>
		<input type="text" name="gname" id="gname" onblur="checkGivenName(this)" value="<?php echo $gname; ?>">
		<span class="error" id="gnameMsg"></span>
		<br>
				
		<label for="last_name">Family Name</label>
		<input type="text" name="sname" id="sname" onblur="checkSurname(this)" value="<?php echo $sname; ?>">
		<span class="error" id="snameMsg"></span>
		<br>
				
		<label for="address">Address</label>
		<input type="text" name="address" id="address" onblur="checkAddress(this)" value="<?php echo $address; ?>">
		<span class="error" id="addressMsg"></span>
		<br>
				
		<!--method ref: Doug Vanderweide
			https://www.dougv.com/2009/06/retaining-values-in-a-form-following-php-postback-and-clearing-form-values-after-successful-php-form-processing/
		-->
				
		<label for="state">State</label>
		<select name="state" id="state" onchange="checkState(this)">
			<option value="0" <?php if($_POST['state'] == "0") { echo "selected=\"selected\""; } ?>>Please select</option>
			<option value="NSW" <?php if($_POST['state'] == "NSW") { echo "selected=\"selected\""; } ?>>NSW</option>
			<option value="VIC" <?php if($_POST['state'] == "VIC") { echo "selected=\"selected\""; } ?>>Victoria</option>
			<option value="QLD" <?php if($_POST['state'] == "QLD") { echo "selected=\"selected\""; } ?>>Queensland</option>
			<option value="SA" <?php if($_POST['state'] == "SA") { echo "selected=\"selected\""; } ?>>South Australia</option>
			<option value="WA" <?php if($_POST['state'] == "WA") { echo "selected=\"selected\""; } ?>>WesterAustralia</option>
			<option value="TAS" <?php if($_POST['state'] == "TAS") { echo "selected=\"selected\""; } ?>>Tasmania</option>
			<option value="NT" <?php if($_POST['state'] == "NT") { echo "selected=\"selected\""; } ?>>Northen Territory</option>
			<option value="ACT" <?php if($_POST['state'] == "ACT") { echo "selected=\"selected\""; } ?>>ACT</option>
		</select>
		<span class="error" id="stateMsg"></span>
		<br>
				
		<label for="postcode">Postcode</label>
		<input type="text" name="postcode" id="postcode" size="4" onblur="checkPostcode(this)" value="<?php echo $postcode; ?>">
		<span class="error" id="postcodeMsg"></span>
		<br>
				
		<label for="mobile">Mobile Number</label>
		<input type="text" name="mobile" id="mobile" size="10" onblur="checkMobile(this)" value="<?php echo $mobile; ?>">
		<span class="error" id="mobileMsg"></span>
		<br>
				
		<label for="email">Email</label>
		<input type="text" name="email" id="email" onblur="checkEmail(this)" value="<?php echo $email; ?>">
		<span class="error" id="emailMsg"></span>
		<br>
				
		<input class="sub" type="submit" name="submit" value="Finish">
	</form>
<?php
if (isset($_POST["submit"]) && $registerCorrect == FALSE) {
	echo "<span class=\"error\">Problem with registration.</span>";
}
?>
</body>
</html>