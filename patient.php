<?php
ob_start(); 
session_start();
include_once("inc/db_connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
	exit(header('Location:login.php'));
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

$user = getPatientInfo($user_id);
$appointments = getPatientAppointments($user_id);

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])){
  $action = isset($_GET['action']) ? $_GET['action'] : '';

  if ($action == 'Cancel') {
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(cancelAppointment($id))
      exit(header("location:patient.php"));
  }
}

function getPatientInfo($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "SELECT p.* FROM `patient` p WHERE p.`id` = '$id';";
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $user = mysqli_fetch_assoc($result);
      return $user;
  }

  $error_msg = "Error, Can not get user info.";
  return false;
}

function getPatientAppointments($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "SELECT a.*, d.id AS did, d.firstName, d.lastName, d.uniqueFileName AS d_photo, DATE_FORMAT(a.`time`,'%h:%i %p') AS time2 FROM `appointment` a LEFT JOIN `doctor` d ON a.`doctorID` = d.`id` WHERE a.`patientID` = '$id' AND a.`status` != 'Done' ORDER BY a.`date` ASC, a.`time` ASC;";
  $result = mysqli_query($conn, $sql);

  if ($result) {
      return $result;
  }

  $error_msg = "Error, Can not get data.";
  return false;
}

function cancelAppointment($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "DELETE FROM `appointment` WHERE id = '$id';";
  $result = mysqli_query($conn, $sql);

  if($result && mysqli_affected_rows($conn) > 0) {
    return true;
  }

  $error_msg = "Can not delete appointment.";
  return false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Homepage</title>
    <link rel="icon" type="image/png" href="images/logo2.png">
    <link rel="stylesheet" href="css/patient.css">
    <script src="script.js"></script>
</head>

<body>
    <div class="header fade-in">
    <a href="logout.php" id="sign">Sign-out</a>
        <header>
            <h1 id="patientName">Welcome, <?= $user['firstName']; ?> <?= $user['lastName']; ?></h1>
        </header>
        <section>
            <h2 class="info" style="text-align: left;  margin-left: 0px; ">Your Information</h2>
            <ul id="patientInfo">
                <li><strong>Full Name:</strong> <?= $user['firstName']; ?> <?= $user['lastName']; ?></li>
                <li><strong>Email:</strong> <?= $user['emailAddress']; ?></li>
              <!--  <li><strong>ID:</strong> <?= $user['id']; ?></li>
                <li><strong>Gender:</strong> <?= $user['Gender']; ?></li>
                <li><strong>Date of Birth:</strong> <?= $user['DoB']; ?></li>-->
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
                    <?php if ($appointments && mysqli_num_rows($appointments) > 0) { foreach ($appointments as $key => $appointment) { ?>
                    <tr>
                        <td><?= $appointment['date']; ?></td>
                        <td><?= $appointment['time2']; ?></td>
                        <td>Dr. <?= $appointment['firstName']; ?> <?= $appointment['lastName']; ?></td>
                        <td><img src="<?= $appointment['d_photo']; ?>" alt="Doctor's Photo"></td>
                        <td><?= $appointment['status']; ?></td>
                        <td><a class="cancel" href="patient.php?action=Cancel&id=<?= $appointment['id']; ?>"> Cancel</a></td>
                    </tr>
                    <?php }
                    } else { ?>
                    <tr>
                      <td colspan="6" style="text-align: center;">No appointments to display</td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </section>
    </div>
    <footer class="footer">
        <!-- Contact Us-->
        <h2>Contact Us</h2>
        <ul>
            <li>
                <img src="images/facebook.png" alt="facebook" />
                <a href="#">Lumièreclinic</a>
            </li>
            <li>
                <img src="images/social-media.png" alt="x" />
                <a href="#">@LumièreClinic</a>
            </li>
            <li>
                <img src="images/instagram.png" alt="instagram" />
                <a href="#">@Lumière_clinic</a>
            </li>
            <li>
                <img src="images/mail.png" alt="gmail" />
                <a href="#">info@lumièreclinic.com</a>
            </li>
            <li>
                <img src="images/phone.png" alt="phone" />
                <a href="#">+966 501 234 567</a>
            </li>
        </ul>
        <div class="copyright">
            <p>
                © 2025 | All Rights Reserved by <strong>Lumière clinic</strong>
            </p>
        </div>
    </footer>
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
</body>

</html>