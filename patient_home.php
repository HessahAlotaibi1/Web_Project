<?php
session_start();
include 'auth.php';
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    echo "يجب تسجيل الدخول لعرض المواعيد!";
    exit;
}

$patient_id = $_SESSION['user_id'];

// جلب بيانات المريض
$patient_query = $conn->prepare("SELECT firstName, lastName, emailAddress FROM Patient WHERE id = ?");
$patient_query->bind_param("i", $patient_id);
$patient_query->execute();
$patient_result = $patient_query->get_result()->fetch_assoc();
$patient_query->close();

if (!$patient_result) {
    echo "خطأ: لم يتم العثور على بيانات المريض.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية للمريض</title>
</head>
<body>
    <h2>مرحبًا، <?php echo htmlspecialchars($patient_result['firstName'] . " " . $patient_result['lastName']); ?></h2>
    <p>بريدك الإلكتروني: <?php echo htmlspecialchars($patient_result['emailAddress']); ?></p>

    <a href="logout.php">تسجيل الخروج</a>

    <h3>المواعيد المحجوزة</h3>
    <table border='1'>
        <tr>
            <th>التاريخ</th>
            <th>الوقت</th>
            <th>الطبيب</th>
            <th>الحالة</th>
            <th>إلغاء</th>
        </tr>

        <?php
        $appointments_query = $conn->prepare("
            SELECT A.id, A.date, A.time, D.firstName, D.lastName, A.status 
            FROM Appointment A 
            JOIN Doctor D ON A.DoctorID = D.id 
            WHERE A.PatientID = ? 
            ORDER BY A.date ASC, A.time ASC
        ");
        $appointments_query->bind_param("i", $patient_id);
        $appointments_query->execute();
        $result = $appointments_query->get_result();

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['date']) . "</td>
                        <td>" . htmlspecialchars($row['time']) . "</td>
                        <td>د. " . htmlspecialchars($row['firstName'] . " " . $row['lastName']) . "</td>
                        <td>" . htmlspecialchars($row['status']) . "</td>
                        <td>";
                if ($row['status'] == 'Pending') {
                    echo "<a href='cancel_appointment.php?id=" . urlencode($row['id']) . "'>إلغاء</a>";
                }
                echo "</td></tr>";
            }
        } else {
            echo "<tr><td colspan='5'>لا توجد مواعيد محجوزة.</td></tr>";
        }

        $appointments_query->close();
        $conn->close();
        ?>
    </table>
</body>
</html>