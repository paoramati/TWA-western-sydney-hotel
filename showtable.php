<?php
/*
	showtable.php
	The page that shows database tables chosen from browse.php
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

if(isset($_POST["table"])) {
	$tableName = $_POST["table"];
}

//get table data
$sq1 = "SELECT * FROM $tableName";
$table = mysqli_query($conn, $sq1)
	or die('Problem with query ' . mysqli_error($conn));
	
$num_columns = mysqli_num_fields($table);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<title>Western Sydney Hotel: Show Database Tables</title>
	<link rel="stylesheet" type="text/css" href="bmp_style.css">
	</head>
<body>
	<div id="head">
		<h1>Western Sydney Hotel</h1>
	</div>
	
	<div class="nav">
		<?php include("nav_pages.php"); ?>
	</div>
		
	<h2>Show WSH Database Tables</h2> 		
	<h3>Below is the WSH database table for <?php echo $tableName ?></h3>
	

	<h4><?php echo $tableName; ?> Table</h4>
	
	<table>
		
		<tr>
		<?php	
		//get field names for table and output as column headers
		for ($i=0; $i<$num_columns; $i++) {
			$fieldName = mysqli_fetch_field($table); 
		?>
			<th><?php echo $fieldName->name ?></th>
		<?php	
		}		//close for loop
		?>
		</tr>
		
		<?php
		//fetch rows of table which there are still rows to fetch
		while ($row = mysqli_fetch_row($table)) { ?>
		<tr>
		<?php
		//for every cell in the row
		foreach($row as $value): ?>
			<td><?php echo $value; ?></td>
			<?php
			endforeach;
			?>
		</tr>
	<?php		
		}
		mysqli_close($conn);
		?>
	<table>
	<br>
	<a href="browse.php" alt="Browse WSH Database Tables">Go back</a>
	</body>
</html>