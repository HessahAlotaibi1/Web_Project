<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lumiere_clinic";

// إنشاء اتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}

// إذا نجح الاتصال
//echo "تم الاتصال بقاعدة البيانات بنجاح!";



function executeQuery($query) {
	global $conn;

	$result = mysqli_query($conn, $query);

	if(!$result)
		$message = "Error while executing query.<br/>Mysql Error: " . mysqli_error($conn);
	else
		return $result;
}




?>
