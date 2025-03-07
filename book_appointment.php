<?php
session_start();
include 'db_connection.php'; // الاتصال بقاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        echo "يجب تسجيل الدخول لحجز موعد!";
        exit;
    }

    $patient_id = $_SESSION['user_id'];
    $doctor_name = $_POST['doctor_name'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $reason = $_POST['reason'];

    // التحقق من أن جميع الحقول ممتلئة
    if (empty($doctor_name) || empty($date) || empty($time) || empty($reason)) {
        echo "الرجاء ملء جميع الحقول!";
        exit;
    }

    // الحصول على معرف الطبيب بناءً على اسمه
    $stmt = $conn->prepare("SELECT id FROM Doctor WHERE CONCAT(firstName, ' ', lastName) = ?");
    $stmt->bind_param("s", $doctor_name);
    $stmt->execute();
    $stmt->bind_result($doctor_id);
    $stmt->fetch();
    $stmt->close();

    if (!$doctor_id) {
        echo "لم يتم العثور على الطبيب المحدد!";
        exit;
    }

    // التحقق من عدم وجود موعد بنفس التوقيت
    $stmt = $conn->prepare("SELECT id FROM Appointment WHERE DoctorID = ? AND date = ? AND time = ?");
    $stmt->bind_param("iss", $doctor_id, $date, $time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "هذا الموعد محجوز بالفعل! الرجاء اختيار وقت آخر.";
        $stmt->close();
        exit;
    }
    $stmt->close();

    // إدخال الموعد في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO Appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $time, $reason);

    if ($stmt->execute()) {
        echo "تم الحجز بنجاح!";
    } else {
        echo "حدث خطأ أثناء الحجز، حاول مرة أخرى!";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "طلب غير صالح!";
}
?>