<?php
session_start();
include_once("inc/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    $sql = "DELETE FROM appointment WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_affected_rows($conn) > 0) {
        echo "true";
    } else {
        echo "false";
    }
}
?>