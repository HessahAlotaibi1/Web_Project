<?php
session_start();
include 'db_connection.php'; // الاتصال بقاعدة البيانات

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION['user_id'])) {
        die(json_encode(["status" => "error", "message" => "يجب تسجيل الدخول لحجز موعد!"]));
    }

    $patient_id = $_SESSION['user_id'];
    $doctor_name = htmlspecialchars(trim($_POST['doctor_name']));
    $date = htmlspecialchars(trim($_POST['date']));
    $time = htmlspecialchars(trim($_POST['time']));
    $reason = htmlspecialchars(trim($_POST['reason']));

    // التحقق من أن جميع الحقول ممتلئة
    if (empty($doctor_name) || empty($date) || empty($time) || empty($reason)) {
        die(json_encode(["status" => "error", "message" => "الرجاء ملء جميع الحقول!"]));
    }

    // التحقق من صحة الاتصال بقاعدة البيانات
    if (!$conn) {
        die(json_encode(["status" => "error", "message" => "فشل الاتصال بقاعدة البيانات!"]));
    }

    // الحصول على معرف الطبيب بناءً على اسمه
    $stmt = $conn->prepare("SELECT id FROM Doctor WHERE CONCAT(firstName, ' ', lastName) = ?");
    $stmt->bind_param("s", $doctor_name);
    $stmt->execute();
    $stmt->bind_result($doctor_id);
    $stmt->fetch();
    $stmt->close();

    if (!$doctor_id) {
        die(json_encode(["status" => "error", "message" => "لم يتم العثور على الطبيب المحدد!"]));
    }

    // التحقق من عدم وجود موعد بنفس التوقيت
    $stmt = $conn->prepare("SELECT id FROM Appointment WHERE DoctorID = ? AND date = ? AND time = ?");
    $stmt->bind_param("iss", $doctor_id, $date, $time);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        die(json_encode(["status" => "error", "message" => "هذا الموعد محجوز بالفعل! الرجاء اختيار وقت آخر."]));
    }
    $stmt->close();

    // إدخال الموعد في قاعدة البيانات
    $stmt = $conn->prepare("INSERT INTO Appointment (PatientID, DoctorID, date, time, reason, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("iisss", $patient_id, $doctor_id, $date, $time, $reason);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "تم الحجز بنجاح!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "حدث خطأ أثناء الحجز، حاول مرة أخرى!"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "طلب غير صالح!"]);
}
?>