<?php
include 'auth.php';
include 'db_connection.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment_id = intval($_GET['id']);
    $patient_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM Appointment WHERE id = ? AND PatientID = ? AND status = 'Pending'");
    $stmt->bind_param("ii", $appointment_id, $patient_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $_SESSION['message'] = "✅ تم إلغاء الموعد بنجاح!";
    } else {
        $_SESSION['message'] = "❌ لا يمكن إلغاء هذا الموعد.";
    }

    $stmt->close();
    header("Location: patient_home.php");
    exit();
}
?>
<a href="logout.php">تسجيل الخروج</a>