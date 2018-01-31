<!DOCTYPE html>
<html>
<head>
<title>Testing</title>
</head>
<body>

<?php
echo "The time is " . date("Y:m:d") . "<br>";

$checkin = "2014-04-20";
echo "<p>checkin date = $checkin</p>";
$current = date("Y-m-d");
//$checkin = strtotime($checkin);		//convert date string to date

echo "<p>current date = $current</p>";
echo "<p>checkin date = $checkin</p>";




if ($checkin < $current) 		//check against system date
	echo "<p>checkin can't be before current date!</p>";
else
	echo "<p>checkin is after current date</p>";

/*
$checkin = strtotime("2016-04-21");
echo date("Y-M-d", $checkin);
*/


?>

</body>
</html>