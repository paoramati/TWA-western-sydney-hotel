<?php
/*
	browse.php
	The browse database tables screen. Requires admin login. Get table names from database and supply as radio buttons to fetch database results
*/
require_once("session.php");
require_once("nocache.php");

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
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>Western Sydney Hotel: Browse Database Tables</title>
	<link rel="stylesheet" type="text/css" href="bmp_style.css">
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
	<h2>Browse WSH Database Tables</h2>
	<h3>Please select a WSH table to display</h3>

	<?php
	require("connection.php");
	$sq_tables = "SHOW TABLES";	
	//var to hold query with adjustments from lecture notes 8
	$tables = mysqli_query($conn, $sq_tables)	
		or die ('Problem with query' . mysqli_error($conn));
	?>
	<form action="showtable.php" name="tableForm" method="post">
			<?php 
			while($tableName = $tables->fetch_array()) {
			?>	
				<input type="radio" name="table" value="<?php echo $tableName[0] ?>"><?php echo $tableName[0] ?><br>
		
			<?php }		//close while loop
		mysqli_close($conn); ?>		
		<br>
		<input type="submit" value="Submit"/>
		
	</form>
	</body>
</html>