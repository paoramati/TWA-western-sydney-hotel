<?php
//delete.php	
require("connection.php");

$sqDelete = "DELETE FROM bookings WHERE bid = '4' AND bid = '5'";
$results = mysqli_query($conn, $sqDelete)
	or die('Problem with query ' . mysqli_error($conn));
	
mysqli_close($conn);



?>