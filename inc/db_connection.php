<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "lumiere_clinic";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erorr connection to the database: " . $conn->connect_error);
}


function executeQuery($query) {
	global $conn;

	$result = mysqli_query($conn, $query);

	if(!$result)
		$message = "Error while executing query.<br/>Mysql Error: " . mysqli_error($conn);
	else
		return $result;
}

?>
