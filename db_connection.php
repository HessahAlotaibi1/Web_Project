<?php
$servername = "localhost"; // إذا كنتِ تستخدمين XAMPP، خليه localhost
$username = "root"; // الافتراضي في XAMPP يكون root
$password = ""; // إذا ما حطيتي باسورد خليه فاضي
$dbname = "clinic_system"; // اسم قاعدة البيانات اللي سويتيها

// إنشاء الاتصال بقاعدة البيانات
$conn = new mysqli($servername, $username, $password, $dbname);

// التحقق من الاتصال
if ($conn->connect_error) {
    die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
}
?>