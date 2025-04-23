<?php
session_start();
include_once("inc/db_connection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id']) && isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'patient') {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $patient_id = $_SESSION['user_id'];

    $check_sql = "SELECT * FROM appointment WHERE id = '$id' AND patientID = '$patient_id'";
    $check_result = mysqli_query($conn, $check_sql);

    if ($check_result && mysqli_num_rows($check_result) > 0) {
        $sql = "DELETE FROM appointment WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        echo ($result && mysqli_affected_rows($conn) > 0) ? 'true' : 'false';
    } else {
        echo 'false';
    }
} else {
    echo 'false';
}
?>