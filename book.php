<?php 
/*
	book.php
	This page allows customers to book a hotel room
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
	if($_SESSION["userType"] != 'customer') {
		header("location: index.php");
		exit();
	}
}

$username = $_SESSION["username"];
$rid = "";
$price = "";
$checkin = "";
$checkout = "";
$errorMsg = "";
$bookingErrorMsg = "";
$bookingConfirmMsg = "";
$output = "";

if (isset($_POST["submit"])) {
	
	$validForm = TRUE;
	
	$errorMsg = "<div class=\"error\"><p>There are problems with this form:</p><ul>";
	
	//validate if a room is selected
	$rid = $_POST["rid"];
	if ($rid == "0") {
		$errorMsg = "<li>Room has not been selected</li>";
		$validForm = FALSE;
	}
	
	//validate check-in date
	$checkin = $_POST["checkin"];
	if (empty($checkin)) {
		$errorMsg .= "<li>Check-in date  bguO
		can not be empty</li>";
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
				$currentDate = date("Y-m-d");		//POST current system date
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
	//finish errorMsg if form invalid, else make errorMsg empty
	if ($validForm == FALSE) {
		$errorMsg .= "</ul></div>";
	}
	else
		$errorMsg = "";
}
//if booking form submitted & valid
if (isset($_POST["submit"]) && $validForm == TRUE) {
	$checkin = mysqli_real_escape_string($conn, $checkin);
	$checkout = mysqli_real_escape_string($conn, $checkout);

	//trying to find if room is unavailable
	$sqAvailable = "SELECT bookings.bid FROM bookings WHERE bookings.rid = '$rid' "
	. "AND ('$checkin' < bookings.checkout AND '$checkout' >= bookings.checkin)";
	
	$resultsRoom = mysqli_query($conn, $sqAvailable)
		or die('Problem with query' . mysqli_error($conn));
	
	//any records returned means the room is booked
	if (mysqli_num_rows($resultsRoom) > 0) {
		$bookingErrorMsg = "<h4>Sorry, room $rid is not available at the specified dates</h4>";
	}
	else {
		//if the room is available, get room price
		$sqPrice = "SELECT price FROM rooms WHERE rooms.rid = '$rid'";
		$resultsPrice = mysqli_query($conn, $sqPrice) 
			or die('Problem with query ' . mysqli_error($conn));		
		//$num_rows = mysqli_num_rows($resultsPrice);
		while($row = mysqli_fetch_array($resultsPrice)) {
			$price = $row["price"];
		}
	/*
	make date objects of date strings to find out date difference
		REFERENCE:	http://stackoverflow.com/questions/2040560/finding-the-number-of-days-between-two-dates
		AUTHOR: Saksham Gupta
	'%a' specifies total number of days as a results of DateTim::diff()
		REFERENCE: 
		http://php.net/manual/en/dateinterval.format.php
	*/
		$checkinDate = new DateTime($checkin);
		$checkoutDate = new DateTime($checkout);
		$days = $checkoutDate->diff($checkinDate)->format("%a");

		//calculate cost by room price * number of days
		$cost = $price * $days;
		//$cost = number_format($cost, 2);
		
		//$output .= "<p>days = $days <br> cost = $cost </p>";
		
		$sqBook = "INSERT INTO bookings (rid, username, checkin, checkout, cost)";
		$sqBook .= " VALUES ('$rid', '$username', '$checkin', '$checkout', '$cost')";
		//insert booking from query
		$bookResults = mysqli_query($conn, $sqBook)
			or die('Problem with query' . mysqli_error($conn));
		//display booking details if insert successful	
		if($bookResults) {
			$bookingConfirmMsg = "<div class=\"confirm\"><h3>Your booking has been completed.</h3>" .
			"<h4>Booking details:</h4><p><strong>Room ID:</strong> $rid <br> " .
			"<strong>Check-in:</strong> $checkin<br><strong>Check-out:</strong> " . 
			"$checkout <br><strong>Cost:</strong> $$cost</p></div>";
		}
		else {
			//if booking unsuccesful
			$bookingConfirmMsg = "<span class=\"error\">Problem making this booking. Booking could not be made.</span>";
		}
	}
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Western Sydney Hotel: Book Rooms</title>
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
	https://www.doylecollection.com/hotels/the-dupont-circle-hotel/rooms-suites
	-->
		<img id="room2" src="images/room6.jpg" alt="WSH Lobby" width="700" height="auto">
		
		<h2>Book WSH Rooms</h2>
		<h3>Enter room and reservation details to make a booking</h3>
			
		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" name="bookingForm" method="POST">
		<?php echo $errorMsg; ?>
			<label for="rid">Room ID</label>
			<select name="rid" id="rid">
				<option value="0" <?php if($_POST['rid'] == "0") { echo "selected=\"selected\""; } ?>>Please select a room:</option>
				<?php 
				//query to fetch room ids for dropdown list
				$sqRooms = "SELECT rid FROM rooms";
				$results = mysqli_query($conn, $sqRooms)
					or die('Problem with query' . mysqli_error($conn));
				while ($row = mysqli_fetch_array($results)) { ?>
					<option value="<?php echo $row["rid"]?>" <?php if($_POST['rid'] == $row["rid"]) { echo "selected=\"selected\""; } ?>><?php echo $row["rid"] ?></option>
				<?php 
				}
				 ?>
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
		<br>
		<?php echo $bookingErrorMsg; ?>
		<?php echo $bookingConfirmMsg; ?>
	<?php mysqli_close($conn); ?>
			

    </body>
</html>
