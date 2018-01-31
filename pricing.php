<?php
/*
	pricing.php
	This page allows an admin to change the price of hotel rooms.
	*/
require_once("session.php");
require_once("nocache.php");
require("connection.php");

//check for unauthorised access, and redirect if so.
if(empty($_SESSION["userType"])) {
	header("location: index.php");
	exit();
}
else {
	if($_SESSION["userType"] != 'admin') {
		header("location: index.php");
		exit();
	}
}

//initialise vars
$rid = "";
$price = "";
$errorMsg = "";
$successMsg = "";

if (isset($_POST["submit"])) {
	
	$validForm = TRUE;
	
	$errorMsg = "<div class=\"error\"><p>There are problems with this form:</p><ul>";
	
	//validate if a room is selected
	$rid = $_POST["rid"];
	if ($rid == "0") {
		$errorMsg .= "<li>Room has not been selected</li>";
		$validForm = FALSE;
	}

	//validate room price
	$price = $_POST["price"];
	if (empty($_POST["price"])) {
		$errorMsg .= "<li>New price has not been entered</li>";
		$validForm = FALSE;
	}
	else {
		if (is_numeric($price)) {
			if ($price < 10.0 || $price > 999.99) {
				$errorMsg .= "<li>The new price is not in the correct range ($10.0-$999.99)</li>";
				$validForm = FALSE;
			}
		}
		else {
			//if price was not numeric
			$errorMsg .= "<li>Price entered is not valid number</li>";
		}
	}
	
	//finish errorMsg is form invalid, else make errorMsg empty
	if ($validForm == FALSE) {
		$errorMsg .= "</ul></div>";
	}
	else
		$errorMsg = "";	
}

$sqRooms = "SELECT rid FROM rooms";

$results = mysqli_query($conn, $sqRooms)
	or die('Problem with query' . mysqli_error($conn));

	
if(isset($_POST["price"]) && $validForm == TRUE) {
	$price = mysqli_real_escape_string($conn, $price);
	
	$sqPrice = "UPDATE rooms SET rooms.price = '$price' WHERE rooms.rid = '$rid'";
	
	$update = mysqli_query($conn, $sqPrice)
		or die('Problem with query' . mysqli_error($conn));
		
	if ($update){
		//mysqli_close($conn);
		$successMsg = "<h3>Price of room $rid has been updated to $$price</h3>";
	}

}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Western Sydney Hotel: Change Room Price</title>
		<link rel="stylesheet" type="text/css" href="bmp_style.css">
    </head>
    <body>
		<div id="head">
			<h1>Western Sydney Hotel</h1>
		</div>
	
		<div class="nav">
			<?php include("nav_pages.php"); ?>
		</div>
			
		<h2>Change Room Price</h2>
		<h3>Select a room and enter it's new price per night</h3>
		
			
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" name="bookingForm" method="POST">
		<?php echo $errorMsg; ?>
			<label for="rid">Room ID</label>
			<select name="rid" id="rid">
				<option value="0" <?php if($_POST['rid'] == "0") { echo "selected=\"selected\""; } ?>>Please select a room:</option>
				<?php 
				
				while ($row = mysqli_fetch_array($results)) { ?>
					<option value="<?php echo $row["rid"] ?>"><?php echo $row["rid"] ?></option>
				<?php }
				?>
			</select>
			<br>
			<label for="price">Enter a new price:</label>
			$<input type="text" name="price" id="price" value="<?php echo $price ?>"></input>
			<br>
			<input class="sub" type="submit" name="submit" value="Finish">
			<input type="reset" name="reset" value="Reset">
		</form>
		
		<?php echo $successMsg; ?>
		
	<?php mysqli_close($conn); ?>
	</body>
</html>