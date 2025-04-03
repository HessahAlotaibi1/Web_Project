<?php
ob_start(); 
session_start();
include_once("inc/db_connection.php");


if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'doctor') {
	exit(header('Location:login.php'));
}

$user_id = $_SESSION['user_id'];

if (!isset($_REQUEST['aid']) || !isset($_REQUEST['pid'])) {
	exit(header('Location:doctor.php'));
}

$appointment_id = $_REQUEST['aid'];
$patient_id = $_REQUEST['pid'];

$medications = getMedications();
$patient = getPatientInfo($patient_id);

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $appointment_id = isset($_POST['aid']) ? $_POST['aid'] : '';
  $patient_id = isset($_POST['pid']) ? $_POST['pid'] : '';
  
  if (isset($_POST['medication'])) {
    foreach ($_POST['medication'] as $key => $value) {
      $medication_id = isset($_POST['medication'][$key]) ? $_POST['medication'][$key] : '';
      
      addMedication($appointment_id, $medication_id);
    }

    if(updateAppointment($appointment_id))
      exit(header("location:doctor.php"));
  } else {
    $error_msg = "Please select medication";
  }
}

function addMedication($appointment_id, $medication_id) {
  global $conn;
  global $error_msg;

  if (empty($appointment_id) || empty($medication_id)) {
      $error_msg = "All fields are required!";
      return false;
  }

  $appointmentID = mysqli_real_escape_string($conn, $appointment_id);
  $medicationID = mysqli_real_escape_string($conn, $medication_id);

  $sql = "INSERT INTO `prescription` (`appointmentID`, `medicationID`) VALUES ('$appointmentID', '$medicationID')"; 
  $result = mysqli_query($conn, $sql);

  if ($result) {
      return true;
  }

  $error_msg = "Error, Can not add prescription." . mysqli_error($conn) . $sql;
  return false;
}

function updateAppointment($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "UPDATE `appointment` SET `status` = 'Done' WHERE id = '$id';";
   $result = mysqli_query($conn, $sql);
  if ($result) {
    return true;
  }

  $error_msg = "Can not update appointment.";
  return false;
}

function getPatientInfo($id) {
  global $conn;
  global $error_msg;

  $id = mysqli_real_escape_string($conn, $id);
  $sql = "SELECT *, TIMESTAMPDIFF(YEAR, `DoB`, CURDATE()) AS age FROM `patient` WHERE `id` = '$id';";
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $user = mysqli_fetch_assoc($result);
      return $user;
  }

  $error_msg = "Error, Can not get patient info.";
  return false;
}

function getMedications() {
  global $conn;
  $sql = "SELECT * FROM `medication`;";
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
    <title>Doctor | Prescribe</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/prescribe.css">

</head>

<body>
    <div class="main">
        <h1>Patient's Medications</h1>

        <form id="Prescribe-form" action="prescribe.php" method="post">
            <input type="hidden" name="aid" value="<?= $appointment_id; ?>" />
            <input type="hidden" name="pid" value="<?= $patient['id']; ?>" />
            <div class="form-group">
                <label>Patient's Name:</label>
                <input type="text" id="patient-name" name="patient-name" value="<?= $patient['firstName']; ?> <?= $patient['lastName']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Patient's Age:</label>
                <input type="number" id="patient-age" name="patient-age" value="<?= $patient['age']; ?>" disabled>
            </div>

            <div class="form-group">
                <label>Gender: </label>
                <label>Female <input type="radio" name="gender" value="female" <?= $patient['Gender'] == 'Female' ? "checked" : ""; ?> disabled></label>
                <label>Male <input type="radio" name="gender" value="male"<?= $patient['Gender'] == 'Male' ? "checked" : ""; ?> disabled></label>
            </div>

            <div class="medication-group">
                <label>Medication: </label>
                <ul>
                    <?php if ($medications) { 
                      foreach ($medications as $key => $medication) { ?>
                    <li><label><input type="checkbox" name="medication[]" value="<?= $medication['id']; ?>">
                            <?= $medication['medicationName']; ?></label></li>
                    <?php }} ?>
                </ul>
            </div>

            <button type="submit" class="submit">Submit</button>
        </form>

        <?php
            if (!empty($error_msg)) {
              echo "<script type='text/javascript'>alert('$error_msg');</script>";
            } else if (!empty($success_msg)) {
              echo "<script type='text/javascript'>alert('$success_msg');</script>";
            }
        ?>
    </div>

</body>

</html>