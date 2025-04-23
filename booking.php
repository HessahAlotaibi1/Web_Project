<?php 
ob_start(); 
session_start();
include_once("inc/db_connection.php");

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'patient') {
    exit(header('Location:login.php'));
}

$user_id = $_SESSION['user_id'];
$error_msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['form2-submit'])) {
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

function getSpecialities() {
    global $conn;
    $sql = "SELECT * FROM `speciality`;";
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

$specialties = getSpecialities();
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

    <!-- Speciality Dropdown -->
    <div class="form-group">
      <label for="speciality">Speciality:</label>
      <select name="speciality" id="speciality" required>
        <option value="" disabled selected>Select a speciality</option>
        <?php foreach ($specialties as $speciality): ?>
          <option value="<?= $speciality['id']; ?>">
            <?= $speciality['speciality']; ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <!-- Booking Form -->
    <form method="post" action="" style="margin-top: 30px;">
      <div class="form-group" id="doctor-div" style="display: none;">
        <label for="doctor">Select Doctor:</label>
        <select name="doctor" id="doctor" required>
          <option value="" disabled selected>Select a Doctor</option>
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

      <?php if (!empty($error_msg)): ?>
        <p style="color:red;"><?= $error_msg; ?></p>
      <?php endif; ?>
    </form>
  </div>

  <script>
    document.getElementById("speciality").addEventListener("change", function () {
      const specialityId = this.value;

      fetch("get_doctors.php?speciality_id=" + specialityId)
        .then(response => response.json())
        .then(data => {
          const doctorSelect = document.getElementById("doctor");
          doctorSelect.innerHTML = '<option value="" disabled selected>Select a Doctor</option>';
          
          data.forEach(doctor => {
            const option = document.createElement("option");
            option.value = doctor.id;
            option.textContent = "Dr. " + doctor.firstName + " " + doctor.lastName;
            doctorSelect.appendChild(option);
          });

          document.getElementById("doctor-div").style.display = "block";
        })
        .catch(error => {
          console.error("Error fetching doctors:", error);
        });
    });
  </script>
</body>
</html>
