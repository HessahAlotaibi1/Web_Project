<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinic_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
if (!$conn) {
    die("فشل الاتصال بقاعدة البيانات: " . mysqli_connect_error());
} else {
    echo "تم الاتصال بقاعدة البيانات بنجاح!";
}
?>