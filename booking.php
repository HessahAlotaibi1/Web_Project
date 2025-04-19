<?php 
ob_start(); 
session_start();
include_once("inc/db_connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    exit(header('Location:login.php'));
}

$user_id = $_SESSION['user_id'];

$specialties = getSpecialities();
$selected_speciality = '';
$filtered_doctors = [];
$selected_doctor = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['form1-submit'])) {
        $selected_speciality = $_POST['speciality'];
        $filtered_doctors = getDoctorsBySpeciality($selected_speciality);
    } elseif (isset($_POST['form2-submit'])) {
        $doctor_id = $_POST['doctor'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $reason = $_POST['reason'];

        if (bookAppointment($user_id, $doctor_id, $date, $time, $reason)) {
            header('Location:patient.php');
            exit;
        } else {
            $error_msg = "Failed to book the appointment.";
        }
    }
}

// ======= FUNCTIONS =========

function getSpecialities() {
    global $conn;
    $sql = "SELECT * FROM `speciality`;";
    $result = mysqli_query($conn, $sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function getDoctorsBySpeciality($specialityID) {
    global $conn;
    $sql = "SELECT * FROM `doctor` WHERE `specialityID` = '$specialityID'";
    $result = mysqli_query($conn, $sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function bookAppointment($patient_id, $doctor_id, $date, $time, $reason) {
    global $conn;
    if (!$patient_id || !$doctor_id || !$date || !$time || !$reason) return false;

    $sql = "INSERT INTO `appointment` (`patientID`, `doctorID`, `date`, `time`, `reason`, `status`) 
            VALUES ('$patient_id', '$doctor_id', '$date', '$time', '$reason', 'Pending')";
    return mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Book Appointment</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/booking.css">
</head>
<body id="Patient">
  <div class="main">
    <h1>Book an Appointment</h1>

    <!-- Form 1: Select Speciality -->
    <form method="post" action="">
      <div class="form-group">
        <label for="speciality">Speciality:</label>
        <select name="speciality" id="speciality" required>
          <option value="" disabled <?= $selected_speciality == '' ? 'selected' : '' ?>>Select a speciality</option>
          <?php foreach ($specialties as $speciality): ?>
            <option value="<?= $speciality['id']; ?>" <?= $selected_speciality == $speciality['id'] ? 'selected' : '' ?>>
              <?= $speciality['speciality']; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <button type="submit" id="select-specialty-btn" name="form1-submit">Submit</button>
    </form>

    <!-- Form 2: Booking Details -->
    <?php if (!empty($filtered_doctors)): ?>
    <form method="post" action="" style="margin-top: 30px;">
      <div class="form-group">
        <label for="doctor">Select Doctor:</label>
        <select name="doctor" id="doctor" required>
          <option value="" disabled selected>Select a Doctor</option>
          <?php foreach ($filtered_doctors as $doc): ?>
            <option value="<?= $doc['id']; ?>">
              Dr. <?= $doc['firstName'] . ' ' . $doc['lastName']; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="form-group">
        <label>Select Date:</label>
        <input type="date" name="date" required>
      </div>

      <div class="form-group">
        <label>Select Time:</label>
        <input type="time" name="time" required>
      </div>

      <div class="form-group">
        <label>Reason for Visit:</label>
        <textarea name="reason" required></textarea>
      </div>

      <button type="submit" id="booking" name="form2-submit">Submit Booking</button>

      <?php if (isset($error_msg)): ?>
        <p style="color:red;"><?= $error_msg; ?></p>
      <?php endif; ?>
    </form>
    <?php endif; ?>
  </div>
</body>
</html>
