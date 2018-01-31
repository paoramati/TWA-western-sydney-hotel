<?php
//database connection to be used for each page that accesses database
require_once("session.php");
$conn=mysqli_connect("localhost", "twa207", "twa207kj", "westernhotel207");
if ( !$conn ) {
	die("Connection failed: " . mysqli_connect_error());
}

?>