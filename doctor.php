<?php
ob_start(); 
session_start();
include_once("inc/db_connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
	exit(header('Location:login.php'));
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

$user = getDoctorInfo($user_id);
$appointments = getDoctorAppointments($user_id);
$patients = getDoctorPatients($user_id);

if($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])){
  $action = isset($_GET['action']) ? $_GET['action'] : '';

  if ($action == 'Confirm') {
    $id = isset($_GET['id']) ? $_GET['id'] : '';
    if(confirmAppointment($id))
      exit(header("location:doctor.php"));
  }
}

function getDoctorInfo($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "SELECT d.*, s.speciality FROM `doctor` d LEFT JOIN `speciality` s ON d.specialityID = s.id WHERE d.`id` = '$id';";
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $user = mysqli_fetch_assoc($result);
      return $user;
  }

  $error_msg = "Error, Can not get user info.";
  return false;
}

function getDoctorAppointments($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "SELECT a.*, p.id AS pid, p.firstName, p.lastName, p.Gender, TIMESTAMPDIFF(YEAR, p.`DoB`, CURDATE()) AS age, DATE_FORMAT(a.`time`,'%h:%i %p') AS time2 FROM `appointment` a LEFT JOIN `patient` p ON a.`patientID` = p.`id` WHERE a.`doctorID` = '$id' AND a.`status` != 'Done' ORDER BY a.`date` ASC, a.`time` ASC;";
  $result = mysqli_query($conn, $sql);

  if ($result) {
      return $result;
  }

  $error_msg = "Error, Can not get data.";
  return false;
}

function getDoctorPatients($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "SELECT a.*, p.firstName, p.lastName, p.Gender, TIMESTAMPDIFF(YEAR, p.`DoB`, CURDATE()) AS age FROM `appointment` a LEFT JOIN `patient` p ON a.`patientID` = p.`id` WHERE a.`doctorID` = '$id'AND a.`status` = 'Done'  ORDER BY p.`firstName`, p.`lastName` ASC;";
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $patients = array();
      foreach($result as $key => $patient) {            
          $patient['medications'] = getPatientMedication($patient['patientID'], $patient['doctorID']);
          $patients[] = $patient;
      }
      return $patients;
  }

  $error_msg = "Error, Can not get data.";
  return false;
}

function getPatientMedication($patient_id, $doctor_id) {
  global $conn;
  global $error_msg;

  $patientID = mysqli_real_escape_string($conn, $patient_id);
  $doctorID = mysqli_real_escape_string($conn, $doctor_id);
  $sql = "SELECT m.* FROM `medication` m INNER JOIN `prescription` p ON m.`id` = p.`medicationID` LEFT JOIN `appointment` a ON a.`id` = p.`appointmentID` WHERE a.`doctorID` = '$doctorID' AND a.`patientID` = '$patientID';";
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $medications = "";
      foreach($result as $key => $medication) { 
        $medications .= $medication['medicationName'] . "<br>\n";    
      }
      return $medications;
  }

  $error_msg = "Error, Can not get data.";
  return false;
}

function confirmAppointment($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "UPDATE `appointment` SET `status` = 'Confirmed' WHERE id = '$id';";
   $result = mysqli_query($conn, $sql);
  if ($result) {
    return true;
  }

  $error_msg = "Can not update appointment.";
  return false;
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Doctor Homepage</title>
  <link rel="icon" type="image/png" href="images/logo2.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/doctor.css">
</head>

<body>
  <script src="script.js"></script>

  <div class="header">
    <a href="logout.php" id="sign">Sign-out</a>
    <header>
      <h1 id="doctorName">Welcome, <?= $user['firstName']; ?> <?= $user['lastName']; ?></h1>
    </header>
  </div>

  <div class="doctor-info-container">
    <img id="doctorPhoto" src="<?= $user['uniqueFileName']; ?>" alt="Doctor Photo">
    <div class="doctor-details">
      <h2 style="text-align: left;  margin-left: 0px; ">Your Information</h2>
      <ul id="doctorInfo">
        <li><strong>Full Name:</strong> <?= $user['firstName']; ?> <?= $user['lastName']; ?></li>
        <li><strong>Email:</strong> <?= $user['emailAddress']; ?></li>
        <!--<li><strong>ID:</strong> <?= $user['id']; ?></li>-->
        <li><strong>Speciality:</strong> <?= $user['speciality']; ?></li>
      </ul>
    </div>
  </div>

  <section>
    <h2>Upcoming Appointments</h2>
    <div class="table-wrapper">
    <table>
      <thead>
        <tr>
          <th>Date</th>
          <th>Time</th>
          <th>Patient's Name</th>
          <th>Age</th>
          <th>Gender</th>
          <th>Reason for Visit</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody id="appointmentsTable">
      <?php if ($appointments && mysqli_num_rows($appointments) > 0) {
        foreach ($appointments as $key => $appointment) { ?>
        <tr>
          <td><?= $appointment['date']; ?></td>
          <td><?= $appointment['time2']; ?></td>
          <td><?= $appointment['firstName']; ?> <?= $appointment['lastName']; ?></td>
          <td><?= $appointment['age']; ?></td>
          <td><?= $appointment['Gender']; ?></td>
          <td><?= $appointment['reason']; ?></td>
          <td>
            <?= $appointment['status']; ?> </br> 
            <?php if ($appointment['status'] == 'Pending') { ?> 
              <a class="button" href="doctor.php?action=Confirm&id=<?= $appointment['id']; ?>">
              Confirm
              </a>
            <?php } else if ($appointment['status'] == 'Confirmed') { ?> 
              <a class="button" href="prescribe.php?&aid=<?= $appointment['id']; ?>&pid=<?= $appointment['pid']; ?>">
              Prescribe
              </a>
            <?php } ?>
          </td>
        </tr>
      <?php }
      } else { ?>
      <tr>
        <td colspan="7" style="text-align: center;">No appointments to display</td>
      </tr>
  <?php } ?>
      </tbody>
    </table>
    </div>
  </section>

  <section>
    <h2>Your Patients</h2>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Age</th>
          <th>Gender</th>
          <th>Medications</th>
        </tr>
      </thead>
      <tbody id="patientsTable">
      <?php if ($patients) {
        foreach ($patients as $key => $patient) { ?>
        <tr>
          <td><?= $patient['firstName']; ?> <?= $patient['lastName']; ?></td>
          <td><?= $patient['age']; ?></td>
          <td><?= $patient['Gender']; ?></td>
          <td><?= $patient['medications']; ?></td>
        </tr>
      <?php }}  else { ?>
      <tr>
        <td colspan="4" style="text-align: center;">No data to display</td>
      </tr>
  <?php }?>
      </tbody>
    </table>
  </section>
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
    /*
    document.addEventListener('DOMContentLoaded', () => {
      function loadUserData() {
        let userData = localStorage.getItem("userData");
        if (userData) {
          userData = JSON.parse(userData);
          document.getElementById("doctorName").textContent = `Welcome, ${userData.firstName}`;

          document.getElementById("doctorInfo").innerHTML = ` 
            <li><strong>Full Name:</strong> ${userData.firstName} ${userData.lastName}</li>
            <li><strong>Email:</strong> ${userData.email}</li>
            <li><strong>ID:</strong> ${userData.id}</li>
            <li><strong>Speciality:</strong> ${userData.speciality}</li>
          `;

          const doctorPhoto = document.getElementById("doctorPhoto");
          doctorPhoto.src = userData.photo ? `images/${userData.photo}` : "images/download.jpg";
        }
      }

      loadUserData();
    });*/
  </script>

</body>

</html>