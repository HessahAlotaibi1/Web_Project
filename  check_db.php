<?php
include 'db_connection.php';

$sql = "SELECT * FROM Patient";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id"]. " - Name: " . $row["firstName"]. " " . $row["lastName"]. "<br>";
    }
} else {
    echo "لا يوجد بيانات في جدول المرضى.";
}

$conn->close();
?>