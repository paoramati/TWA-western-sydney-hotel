<?php 
/*
	nav_pages.php
	Generates dynamic links for the WSH, depending on whether a user is logged in or not
*/
require_once("session.php");
require_once("nocache.php");
//if no session vars hold userType
if (empty($_SESSION["userType"])) {
	echo "<ul class=\"nav\">";
	echo "<li class=\"nav\"><a href=\"index.php\">Home</a></li>";
	echo "<li class=\"nav\"><a href=\"register.php\">Customer Registration</a></li>";
	echo "<li class=\"nav\"><a href=\"customerlogin.php\">Customer Login</a></li>";
	echo "<li class=\"nav\"><a href=\"adminlogin.php\">Administrator Login</a></li>";
	echo "</ul>";
}	
if (isset($_SESSION["userType"])) {
	//if user is logged in as a customer	
	if ($_SESSION["userType"] == 'customer') {
		echo "<ul class=\"nav\">";
		echo "<li class=\"nav\"><a href=\"index.php\">Home</a></li>";
		echo "<li class=\"nav\"><a href=\"search.php\">Search Rooms</a></li>";
		echo "<li class=\"nav\"><a href=\"book.php\">Book Rooms</a></li>";
		echo "<li class=\"nav\"><a href=\"logout.php\">Logout</a></li>";
		echo "</ul>";
	}
	//if user is logged in as an admin
	if ($_SESSION["userType"] == 'admin') {
		echo "<ul class=\"nav\">";
		echo "<li class=\"nav\"><a href=\"home.php\">Home</a></li>";
		echo "<li class=\"nav\"><a href=\"pricing.php\">Change Pricing</a></li>";
		echo "<li class=\"nav\"><a href=\"browse.php\">Browse Database Tables</a></li>";
		echo "<li class=\"nav\"><a href=\"logout.php\">Logout</a></li>";
		echo "</ul>";
	}
}


		
?>