<?php 
/*
	search.php
	This page allows logged in customers to search available hotel rooms. 
*/
require_once("session.php");
require_once("nocache.php");

//check for unauthorised access, and redirect if so.
if(empty($_SESSION["userType"])) {
	header("location: index.php");
	exit();
}
else {
	if($_SESSION["userType"] != 'customer') {
		header("location: index.php");
		exit();
	}
}

$beds = "";
$orientation = "";
$checkin = "";
$checkout = "";
$errorMsg = "";
$searchResults = "";
$searchTable = "";

//validation of searchForm
if (isset($_POST["submit"])) {
	
	$validForm = TRUE;
	$errorMsg = "<div class=\"error\"><p>There are problems with this form:</p><ul>";

	$beds = $_POST["beds"];
	if ($beds == "0") {
		$errorMsg .= "<li>Number of beds has not been selected</li>";
		$validForm = FALSE;
	}

	//if orientation == '0', select search statement should not specifiy orientation in statement
	$orientation = $_POST["orientation"];
	
	
	//validate check-in date
	$checkin = $_POST["checkin"];
	if (empty($checkin)) {
		$errorMsg .= "<li>Check-in date can not be empty</li>";
		$validForm = FALSE;
	}
	else {
		$dateCheck = "/^\d{4}-\d{2}-\d{2}$/";		//date format regex
		if (!preg_match($dateCheck, $checkin)) {
			$errorMsg .= "<li>Check-in date must be of format YYYY-MM-DD.</li>";
			$validForm = FALSE;
		}
		else {	//if date format is correct, split date string and check date 
			$inYear = substr($checkin, 0, 4);
			$inMonth = substr($checkin, 5, 2);
			$inDay = substr ($checkin, 8, 2);
			//if date is not a valid date
			if (!checkdate($inMonth, $inDay, $inYear)) {
				$errorMsg .= "<li>The check-in date is not a valid date</li>";	
				$validForm = FALSE;
			}
			else {
				$currentDate = date("Y-m-d");		//get current system date
				if ($checkin < $currentDate) {	
					$errorMsg .= "<li>Check-in date can't be before current date ($currentDate)</li>";
					$validForm = FALSE;
				}
			}
		}		
	}
	
	//validate check-out date
	$checkout = $_POST["checkout"];
	if (empty($checkout)) {
		$errorMsg .= "<li>Check-out date can not be empty</li>";
		$validForm = FALSE;
	}
	else {
		$dateCheck = "/^\d{4}-\d{2}-\d{2}$/";		//date format regex
		if (!preg_match($dateCheck, $checkout)) {
			$errorMsg .= "<li>Check-out date must be of format YYYY-MM-DD</li>";
			$validForm = FALSE;
		}
		else {		
			$outYear = substr($checkout, 0, 4);
			$outMonth = substr($checkout, 5, 2);
			$outDay = substr ($checkout, 8, 2);
			//if date is not a valid date
			if (!checkdate($outMonth, $outDay, $outYear)) {
				
				$errorMsg .= "<li>The check-out date is not a valid date</li>";	
				$validForm = FALSE;
			}
			else {
				if ($checkout < $checkin) {
					$errorMsg .= "<li>Check-out date can't be before check-in date</li>";
					$validForm = FALSE;
				}
			}
		}		
	}	
	//finish errorMsg is form invalid, else make errorMsg empty
	if ($validForm == FALSE) {
		$errorMsg .= "</ul></div>";
	}
	else
		$errorMsg = "";		
}
//check bookings and rooms database
if (isset($_POST["submit"]) && ($validForm == TRUE)) {
	require("connection.php");
	
	$beds = mysqli_real_escape_string($conn, $beds);
	$orientation = mysqli_real_escape_string($conn, $orientation);
	$checkin = mysqli_real_escape_string($conn, $checkin);
	$checkout = mysqli_real_escape_string($conn, $checkout);
	
	$sqSearch = "SELECT DISTINCT rooms.rid, beds, orientation, price FROM rooms " .
		"LEFT JOIN bookings ON rooms.rid = bookings.rid WHERE rooms.beds = '$beds'";
	//add room orientation if selected in searchForm
	if (!$orientation == 0) {
		$sqSearch .= " AND rooms.orientation = '$orientation'";
	}
	//if rooms unavailable
	$sqSearch .= " AND rooms.rid NOT IN (SELECT bookings.rid FROM bookings WHERE " .
		"('$checkin' < bookings.checkout AND '$checkout' >= bookings.checkin))";
	
	$searchResults = mysqli_query($conn, $sqSearch)
		or die('Problem with query' . mysqli_error($conn));
	
	if($searchResults) {
	//html table to display on form submission
	$searchTable = "<div class=\"searchResults\">
		<h4>Available Rooms:</h4>
		<table>
			<tr>
				<th>Room ID</th>
				<th>Beds</th>
				<th>Orientation</th>
				<th>Price</th>
			</tr>";	
	}
	else {
		$searchTable = "<h5>Sorry, no rooms are available that match your criteria</h5>";
	}
}	
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Western Sydney Hotel: Search Rooms</title>
		<link rel="stylesheet" type="text/css" href="bmp_style.css">
    </head>
    <body>
		<div id="head">
			<h1>Western Sydney Hotel</h1>
		</div>
	
		<div class="nav">
			<?php include("nav_pages.php"); ?>
		</div>
	<!--
	image credit:
	http://www.thepurplepassport.com/picks/washingtondc/Hotel/the-dupont-hotel/
	-->
		<img src="images/room1.jpg" alt="WSH Room" width="700" height="auto">
		
		<h2>Search Available Rooms</h2> 		
		<h3>Enter room requirements and reservation dates to find available rooms</h3>
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" name="searchForm" method="POST">
			<?php echo $errorMsg; ?>
			<label for="beds">Number of beds:</label>
			<select name="beds" id="beds">
				<option value="0" <?php if($_POST['beds'] == "0") { echo "selected=\"selected\""; } ?>>Please select</option>
				<option value="1" <?php if($_POST['beds'] == "1") { echo "selected=\"selected\""; } ?>>1 Bed</option>
				<option value="2" <?php if($_POST['beds'] == "2") { echo "selected=\"selected\""; } ?>>2 Beds</option>
				<option value="3" <?php if($_POST['beds'] == "3") { echo "selected=\"selected\""; } ?>>3 Beds</option>
			</select>
			<br>
			<label for="orientation">Orientation:</label>
			<select name="orientation" id="orientation">
				<option value="0" <?php if($_POST['orientation'] == "0") { echo "selected=\"selected\""; } ?>>Please select</option>
				<option value="N" <?php if($_POST['orientation'] == "N") { echo "selected=\"selected\""; } ?>>North</option>
				<option value="S" <?php if($_POST['orientation'] == "S") { echo "selected=\"selected\""; } ?>>South</option>
				<option value="E" <?php if($_POST['orientation'] == "E") { echo "selected=\"selected\""; } ?>>East</option>
				<option value="W" <?php if($_POST['orientation'] == "W") { echo "selected=\"selected\""; } ?>>West</option>
			</select>
			<br>
			<label for="checkin">Check-in date<br>(YYYY-MM-DD):</label>
			<input type="text" name="checkin" id="checkin" value="<?php echo $checkin ?>">
			<br>
			<label for="checkout">Check-out date<br>(YYYY-MM-DD):</label>
			<input type="text" name="checkout" id="checkout" value="<?php echo $checkout ?>">
			<br>
			<input type="submit" name="submit" value="Submit">
		</form>
		<!--this table will display after successful form submission -->		
		<?php 
		echo $searchTable;
		if(isset($_POST["submit"]) && $validForm == TRUE) {
			while ($row = mysqli_fetch_array($searchResults)) { ?>
			<tr>
				<td><?php echo $row["rid"] ?></td>
				<td><?php echo $row["beds"] ?></td>
				<td><?php echo $row["orientation"] ?></td>
				<td><?php echo $row["price"] ?></td>
			</tr>
			<?php
				}
			mysqli_close($conn); 
			}
			?>
		</table>
		</div>	
    </body>
</html>
