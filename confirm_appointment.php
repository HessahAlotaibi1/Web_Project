<?php
include 'auth.php';
include 'db_connection.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $appointment_id = intval($_GET['id']);
    $doctor_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE Appointment SET status = 'Confirmed' WHERE id = ? AND DoctorID = ?");
    $stmt->bind_param("ii", $appointment_id, $doctor_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        $_SESSION['message'] = "✅ تم تأكيد الموعد بنجاح!";
    } else {
        $_SESSION['message'] = "❌ حدث خطأ أثناء تأكيد الموعد.";
    }

    $stmt->close();
    header("Location: doctor_home.php");
    exit();
}
?>
<a href="logout.php">تسجيل الخروج</a>