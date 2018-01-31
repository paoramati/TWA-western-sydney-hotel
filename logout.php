<?php 
require_once("nocache.php");
session_start();
//if unlogged user reaches this page, redirect to home page
if(empty($_SESSION["userType"])) {
	session_unset();
	session_destroy();
	header("location: index.php");
}

if (isset($_SESSION["userType"])) {
	//if customer, end session and redirect to customer login
	if ($_SESSION["userType"] == 'customer') {
		session_unset();
		session_destroy();
		header("location: customerlogin.php");
	}
	//if admin, end session and redirect to admin login
	if ($_SESSION["userType"] == 'admin') {
		session_unset();
		session_destroy();
		header("location: customerlogin.php");
	}	
}

























?>
<?php
// Start the session




?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Western Sydney Hotel: Log Out</title>
		<link rel="stylesheet" type="text/css" href="bmp_style.css">
    </head>
    <body>
		<div id="head">
			<h1>Western Sydney Hotel: Search Rooms</h1>
		</div>
	
		<div class="nav">
			<?php include("nav_pages.php"); ?>
		</div>
			
			
		<div id="section">
			<h2>Welcome!</h2>
			
			<p>The Western Sydney Hotel is New South Wale's premier hotel.<br> Founded in 2016, the WSH is your 5-star link to the city and the beautiful Blue Mountains.</p>
			
			<p></p>
		</div>
		
		<div class="instructions">
			
			<p><strong>Help</strong></p>
			<p>Home: This page</p>
			<p>Customer Registration: Register with WSH to view and book rooms</p>
			<p>Customer Login: If you are registered, login to view rooms and making bookings</p>
			<p>Administrator Login: Staff login page</p>
		</div>
		
		
			
			
			
			
			

    </body>
</html>
