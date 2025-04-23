<?php
ob_start(); 
session_start();
include_once("inc/db_connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    exit(header('Location:login.php'));
}

$user_id = $_SESSION['user_id'];
$user = getPatientInfo($user_id);
$appointments = getPatientAppointments($user_id);

function getPatientInfo($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT * FROM patient WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    return $result ? mysqli_fetch_assoc($result) : false;
}

function getPatientAppointments($id) {
    global $conn;
    $id = mysqli_real_escape_string($conn, $id);
    $sql = "SELECT a.*, d.firstName, d.lastName, d.uniqueFileName AS d_photo,
            DATE_FORMAT(a.time,'%h:%i %p') AS time2
            FROM appointment a
            LEFT JOIN doctor d ON a.doctorID = d.id
            WHERE a.patientID = '$id' AND a.status != 'Done'
            ORDER BY a.date ASC, a.time ASC";
    return mysqli_query($conn, $sql);
}

// التحقق من طول الباسوورد عند تغييره
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['password'])) {
    $password = $_POST['password'];

    // التحقق من طول الباسوورد
    if (strlen($password) < 8) {
        $error = "الباسوورد يجب أن يحتوي على 8 أحرف على الأقل.";
    } elseif (strlen($password) > 8) {
        $error = "الباسوورد يجب أن يحتوي على 8 أحرف فقط.";
    }

    // إذا تم التحقق بنجاح، قم بتحديث الباسوورد في قاعدة البيانات
    if (!isset($error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE patient SET password = '$hashed_password' WHERE id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        
        if ($result) {
            echo "تم تحديث الباسوورد بنجاح.";
        } else {
            echo "حدث خطأ أثناء تحديث الباسوورد.";
        }
    } else {
        echo $error; // عرض الخطأ للمستخدم.
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Patient Homepage</title>
    <link rel="icon" type="image/png" href="images/logo2.png">
    <link rel="stylesheet" href="css/patient.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<div class="header fade-in">
    <a href="logout.php" id="sign">Sign-out</a>
    <header>
        <h1 id="patientName">Welcome, <?= $user['firstName']; ?> <?= $user['lastName']; ?></h1>
    </header>
    <section>
        <h2 class="info" style="text-align: left; margin-left: 0;">Your Information</h2>
        <ul id="patientInfo">
            <li><strong>Full Name:</strong> <?= $user['firstName']; ?> <?= $user['lastName']; ?></li>
            <li><strong>Email:</strong> <?= $user['emailAddress']; ?></li>
        </ul>
    </section>
</div>

<div class="middle fade-in">
    <a href="booking.php" id="booking">Book an Appointment</a>
    <section>
        <h2>Your Appointments</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Doctor's Name</th>
                    <th>Doctor's Photo</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="appointmentsTable">
                <?php if ($appointments && mysqli_num_rows($appointments) > 0) {
                    foreach ($appointments as $appointment) { ?>
                        <tr id="row_<?= $appointment['id']; ?>">
                            <td><?= $appointment['date']; ?></td>
                            <td><?= $appointment['time2']; ?></td>
                            <td>Dr. <?= $appointment['firstName']; ?> <?= $appointment['lastName']; ?></td>
                            <td><img src="<?= $appointment['d_photo']; ?>" alt="Doctor's Photo"></td>
                            <td><?= $appointment['status']; ?></td>
                            <td><button class="cancel-btn" type="button" data-id="<?= $appointment['id']; ?>">Cancel</button></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr><td colspan="6" style="text-align: center;">No appointments to display</td></tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
</div>

<!-- تغيير الباسوورد -->
<div class="password-update">
    <h3>Update Your Password</h3>
    <form action="patient_homepage.php" method="POST">
        <label for="password">New Password:</label>
        <input type="password" name="password" id="password" required>
        <button type="submit">Update Password</button>
    </form>
</div>

<footer class="footer">
    <h2>Contact Us</h2>
    <ul>
        <li><img src="images/facebook.png" alt="facebook" /><a href="#">Lumièreclinic</a></li>
        <li><img src="images/social-media.png" alt="x" /><a href="#">@LumièreClinic</a></li>
        <li><img src="images/instagram.png" alt="instagram" /><a href="#">@Lumière_clinic</a></li>
        <li><img src="images/mail.png" alt="gmail" /><a href="#">info@lumièreclinic.com</a></li>
        <li><img src="images/phone.png" alt="phone" /><a href="#">+966 501 234 567</a></li>
    </ul>
    <div class="copyright">
        <p>© 2025 | All Rights Reserved by <strong>Lumière clinic</strong></p>
    </div>
</footer>

<!-- Animation Scroll -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const elements = document.querySelectorAll('.fade-in');
    function handleScroll() {
        elements.forEach((el) => {
            const rect = el.getBoundingClientRect();
            if (rect.top < window.innerHeight - 50) {
                el.classList.add('visible');
            }
        });
    }
    window.addEventListener('scroll', handleScroll);
    handleScroll();
});
</script>

<!-- AJAX Cancel Appointment -->
<script>
$(document).ready(function(){
    $('.cancel-btn').click(function(e){
        e.preventDefault();
        if (!confirm("هل أنت متأكد من إلغاء الموعد؟")) return;

        const id = $(this).data('id');
        const row = $('#row_' + id);

        $.ajax({
            url: 'ajax_cancel.php',
            type: 'POST',
            data: { id: id },
            success: function(response){
                if(response.trim() === "true"){
                    row.fadeOut(300, function(){ $(this).remove(); });
                } else {
                    alert("فشل في إلغاء الموعد. حاول لاحقًا.");
                }
            },
            error: function() {
                alert("حدث خطأ في الاتصال بالسيرفر.");
            }
        });
    });
});
</script>

</body>
</html>