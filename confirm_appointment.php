<?php
include_once("inc/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);  

    $sql = "UPDATE appointment SET status = 'Confirmed' WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_affected_rows($conn) > 0) {
        echo 'true';
    } else {
        echo 'false';
    }
}
?>
