<?php
include 'auth.php';
include 'db_connection.php';

$doctor_id = $_SESSION['user_id'];

$doctor_query = $conn->prepare("SELECT firstName, lastName, emailAddress FROM Doctor WHERE id = ?");
$doctor_query->bind_param("i", $doctor_id);
$doctor_query->execute();
$doctor_result = $doctor_query->get_result()->fetch_assoc();

$doctor_query->close();
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية للطبيب</title>
</head>
<body>
    <h2>مرحبًا، دكتور <?php echo htmlspecialchars($doctor_result['firstName'] . " " . $doctor_result['lastName']); ?></h2>
    <p>بريدك الإلكتروني: <?php echo htmlspecialchars($doctor_result['emailAddress']); ?></p>

    <a href="logout.php">تسجيل الخروج</a>

    <h3>المواعيد القادمة</h3>
    <table border='1'>
        <tr>
            <th>التاريخ</th>
            <th>الوقت</th>
            <th>المريض</th>
            <th>الحالة</th>
            <th>الإجراءات</th>
        </tr>

        <?php
        $appointments_query = $conn->prepare("SELECT A.id, A.date, A.time, P.firstName, P.lastName, A.status 
                                              FROM Appointment A 
                                              JOIN Patient P ON A.PatientID = P.id 
                                              WHERE A.DoctorID = ? 
                                              ORDER BY A.date, A.time");
        $appointments_query->bind_param("i", $doctor_id);
        $appointments_query->execute();
        $result = $appointments_query->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['date']) . "</td>
                    <td>" . htmlspecialchars($row['time']) . "</td>
                    <td>" . htmlspecialchars($row['firstName'] . " " . $row['lastName']) . "</td>
                    <td>" . htmlspecialchars($row['status']) . "</td>
                    <td>";
            if ($row['status'] == 'Pending') {
                echo "<a href='confirm_appointment.php?id=" . urlencode($row['id']) . "'>تأكيد</a>";
            }
            echo "</td></tr>";
        }

        $appointments_query->close();
        $conn->close();
        ?>
    </table>
</body>
</html>