<?php 
ob_start(); 
session_start();
include_once("inc/db_connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
	exit(header('Location:login.php'));
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

$specialties = getSpecialities();
$doctors = getDoctors();

$selected_doctor = false;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  if (isset($_POST['form1-submit'])) {
    $selected_doctor = isset($_POST['doctor']) ? $_POST['doctor'] : '';
  } else if (isset($_POST['form2-submit'])) {
    $patient_id = $user_id;
    $doctor_id = isset($_POST['doctor']) ? $_POST['doctor'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';
    $reason = isset($_POST['reason']) ? $_POST['reason'] : '';
    if(bookAppointment($patient_id, $doctor_id, $date, $time, $reason))
      exit(header("location:patient.php"));
  }
}

function bookAppointment($patient_id, $doctor_id, $date, $time, $reason) {
  global $conn;
  global $error_msg;

  if (empty($patient_id) || empty($doctor_id) || empty($date) || empty($time) || empty($reason)) {
      $error_msg = "All fields are required!";
      return false;
  }
  
  $patientID = mysqli_real_escape_string($conn, $patient_id);
  $doctorID = mysqli_real_escape_string($conn, $doctor_id);
  $date = mysqli_real_escape_string($conn, $date);
  $time = mysqli_real_escape_string($conn, $time);
  $reason = mysqli_real_escape_string($conn, $reason);
  $status = "Pending";

  $sql = "INSERT INTO `appointment` (`patientID`, `doctorID`, `date`, `time`, `reason`, `status`) VALUES ('$patientID', '$doctorID', '$date', '$time', '$reason', '$status')"; 
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $appointment_id = mysqli_insert_id($conn);
      return $appointment_id;
  }

  $error_msg = "Error, Can not add appointment." . mysqli_error($conn) . $sql;
  return false;
}

function getSpecialities() {
  global $conn;
  $sql = "SELECT * FROM `speciality`;";
  $result = mysqli_query($conn, $sql);
  $arr = array();
  if ($result && mysqli_num_rows($result) > 0) {
      $arr = $result->fetch_all(MYSQLI_ASSOC);
  }
  return $arr;
}

function getDoctors() {
  global $conn;
  $sql = "SELECT * FROM `doctor`;";
  $result = mysqli_query($conn, $sql);
  $arr = array();
  if ($result && mysqli_num_rows($result) > 0) {
      $arr = $result->fetch_all(MYSQLI_ASSOC);
  }
  return $arr;
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Patient | Book an Appointment</title>
  <link rel="icon" type="image/png" href="images/logo2.png">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/booking.css">
</head>

<body id="Patient">
  <div class="main">
    <h1>Book an Appointment</h1>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'GET') { ?>
      <!-- First Form: Select Specialty -->
      <form id="specialty-form" action="booking.php" method="post">
        <div class="form-group">
          <label for="speciality">Speciality:</label>
          <select name="speciality" id="speciality" required>
              <?php if ($specialties) { ?>
              <option value="" disabled selected>Select a speciality</option>
              <optgroup label="General Medicine Clinic">
                  <?php foreach ($specialties as $key => $speciality) { ?>
                  <?php if ($speciality['group'] == 'General Medicine Clinic') {?>
                  <option value="<?= $speciality['id']; ?>"><?= $speciality['speciality']; ?></option>
                  <?php }} ?>
              </optgroup>

              <optgroup label="Dermatology & Aesthetic Clinic">
                  <?php foreach ($specialties as $key => $speciality) { ?>
                  <?php if ($speciality['group'] == 'Dermatology & Aesthetic Clinic') {?>
                  <option value="<?= $speciality['id']; ?>"><?= $speciality['speciality']; ?></option>
                  <?php }} ?>
              </optgroup>

              <optgroup label="Dentistry">
                  <?php foreach ($specialties as $key => $speciality) { ?>
                  <?php if ($speciality['group'] == 'Dentistry') {?>
                  <option value="<?= $speciality['id']; ?>"><?= $speciality['speciality']; ?></option>
                  <?php }} ?>

              </optgroup>

              <?php } ?>
          </select>
          </div>
          <div class="form-group" style="display:none;">
            <select id="all_doctors" disabled>
                <?php if ($doctors) { ?>
                <?php foreach ($doctors as $key => $doctor) { ?>
                <option value="<?= $doctor['id']; ?>" speciality="<?= $doctor['specialityID']; ?>">Dr. <?= $doctor['firstName']; ?> <?= $doctor['lastName']; ?></option>
                <?php }} ?>
            </select>
          </div>

          <div class="form-group" id="doctors_div" style="visibility:hidden;">
          <label for="doctor">Doctor:</label>
          <select name="doctor" id="doctor" required>
            <option value="" disabled selected>Select a Doctor</option>
          </select><br>
        </div>

        <button type="submit" id="select-specialty-btn" name="form1-submit">Submit</button>
      </form>
    <?php } ?>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
      <!-- Second Form: Booking Details -->
      <form id="booking-form" action="booking.php" method="POST">
      <input type="hidden" name="doctor" value="<?= $selected_doctor; ?>" />
        <div class="form-group">
          <label>Select Date:</label>
          <input type="date" id="appointment-date" name="date" required><br>
        </div>
        <div class="form-group">
          <label>Select Time:</label>
          <input type="time" id="appointment-time" name="time" required><br>
        </div>
        <div class="form-group">
          <label>Reason for Visit:</label>
          <textarea name="reason" id="reason" name="reason" required></textarea><br>
        </div>
        <button type="submit" id="booking" name="form2-submit">Submit Booking</button>
      </form>
    <?php } ?>
  </div>

</body>

<script>
  const allDoctorsDropdown = document.getElementById("all_doctors");
  const specialityDropdown = document.getElementById("speciality");

  if (specialityDropdown) {
    specialityDropdown.addEventListener("change", function () {
      const selectedSpecialty = this.value;

      if (!selectedSpecialty) return;

      const doctorDropdown = document.getElementById("doctor");
      doctorDropdown.innerHTML = '<option value="" disabled selected>Select a Doctor</option>';
      for (var i = 0; i < allDoctorsDropdown.length; i++) {
        var doctor = allDoctorsDropdown[i];
        var speciality = doctor.getAttribute("speciality");
        if (selectedSpecialty === speciality) {
          const option = document.createElement("option");
          option.value = doctor.value;
          option.textContent = doctor.textContent;
          doctorDropdown.appendChild(option);
        }
      }

      document.getElementById("doctors_div").style.visibility = "visible";
    });
  }
</script>


</html>