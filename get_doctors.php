<?php
include_once("inc/db_connection.php");

if (isset($_GET['speciality_id'])) {
    $speciality_id = intval($_GET['speciality_id']);
    $sql = "SELECT id, firstName, lastName FROM doctor WHERE specialityID = $speciality_id";
    $result = mysqli_query($conn, $sql);

    $doctors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $doctors[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($doctors);
}
?>
