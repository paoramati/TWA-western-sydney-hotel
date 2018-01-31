<?php 
/*
	index.php
	The home page of the WSH.
*/
require_once("session.php");
require_once("nocache.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Western Sydney Hotel: Homepage</title>
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
		image credit:	http://www.bykoket.com/blog/secret-design-amazing-hotel-interiors/interior-design-of-five-star-hotel-lobby/
		-->
		<img src="images/lobby3.jpg" alt="WSH Lobby" width="700" height="auto">
		<h2>Welcome to Western Sydney Hotel!</h2>		
		<p>The Western Sydney Hotel is New South Wale's premier hotel.<br><br>
		Founded in 2016, the WSH is your 5-star link to the city and the beautiful Blue Mountains.</p>
		<br><br>
		<div class="instructions">
			<h4>WSH Website Details</h4>
			<p><strong>Home:</strong> This page</p>
			<p><strong>Customer Registration:</strong> Register with WSH to search rooms and make bookings</p>
			<p><strong>Customer Login:</strong> Registered customers can login to search rooms and make bookings</p>
			<p><strong>Administrator Login:</strong> Registered staff can login to view WSH data tables and update room prices</p>
		</div>
	 </body>
</html>
