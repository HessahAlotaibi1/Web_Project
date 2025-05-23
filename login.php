<?php 
ob_start(); 
session_start();
include_once("inc/db_connection.php");

if (isset($_SESSION['user_id'])) {
  $role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : '';
  if ($role == 'doctor')
    exit(header("location:doctor.php"));
  else if ($role == 'patient')
    exit(header("location:patient.php")); 
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $role = isset($_POST['role']) ? $_POST['role'] : '';
  
  if ($role == 'doctor') {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';  
    $user_id = login_doctor($email, $password);
  } else if ($role == 'patient')  {  
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $user_id = login_patient($email, $password);
  }

  if($user_id > 0) {
      $_SESSION['user_logged'] = true;
      $_SESSION['user_id'] = $user_id;
      $_SESSION['user_role'] = $role;

      if ($role == 'doctor')
        exit(header("location:doctor.php"));
      else if ($role == 'patient')
        exit(header("location:patient.php")); 
  }
}

function encryptPassword($password) {
  $options = [
      'cost' => 12,
  ];
  return password_hash($password, PASSWORD_BCRYPT, $options);
}

function login_doctor($email, $password) {
  global $conn;
  global $error_msg;

  if (empty($email) || empty($password)) {
      $error_msg = "All fields are required!";
      return false;
  }

  $emailAddress = mysqli_real_escape_string($conn, $email);
  $password = mysqli_real_escape_string($conn, $password);

  $sql = "SELECT * FROM `doctor` WHERE `emailAddress` = '$emailAddress';"; 
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
      $user = mysqli_fetch_assoc($result);
      if (password_verify($password, $user['password'])) {
          return $user['id'];
      } else {
          $error_msg = "Email or password is incorrect.";
          return false;
      }
  } else {
      $error_msg = "No account found with that email.";
      return false;
  }
}


function login_patient($email, $password) {
  global $conn;
  global $error_msg;

  if (empty($email) || empty($password)) {
      $error_msg = "All fields are required!";
      return false;
  }

  $emailAddress = mysqli_real_escape_string($conn, $email);
  $password = mysqli_real_escape_string($conn, $password);

  $sql = "SELECT * FROM `patient` WHERE `emailAddress` = '$emailAddress';"; 
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
      $user = mysqli_fetch_assoc($result);
      if (password_verify($password, $user['password'])) {
          return $user['id'];
      } else {
          $error_msg = "Email or password is incorrect.";
          return false;
      }
  } else {
      $error_msg = "No account found with that email.";
      return false;
  }
}


?>

<!DOCTYPE html>
<html>
<link rel="stylesheet" href="css/login.css">

<head>
    <title>Log-in</title>
    <link rel="icon" type="image/png" href="images/logo2.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/login.css">
</head>

<body>
<div class="alert-container">
    <?php if (!empty($error_msg)): ?>
      <div class="alert error-alert">
        <?= $error_msg ?>
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
      </div>
    <?php endif; ?>

    <?php if (!empty($success_msg)): ?>
      <div class="alert success-alert">
        <?= $success_msg ?>
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
      </div>
    <?php endif; ?>
</div>
    <img src="images/logogreen.png" alt="Logo">
    <div class="tabs">
        <a href="#" class="active">Login</a>
        <a href="signup.php" style="color: #5d5d5d; left: 30%;">Sign up</a>
    </div>

    <form action="login.php" method="post">
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
        <input type="password" id="p" name="password" placeholder="Enter your password" required>
        <br>
        <div class="rolesholder">
            <label class="roles">Your role: </label>
            <label class="roles">
                <input type="radio" name="role" value="doctor" id="doctorRole" checked>Doctor
            </label>
            <label class="roles">
                <input type="radio" name="role" value="patient" id="patientRole">Patient
            </label>
        </div>
        <br>
      <label>
        <a href="newpass.php" style="color: #006e6e; text-decoration: none; font-size: 18px; font-weight: 500;"
          onmouseover="this.style.color='#006e6e'; this.style.textDecoration='underline';"
          onmouseout="this.style.color='#006e6e'; this.style.textDecoration='none';">
          Forgot my password
        </a>
      </label>

        <input type="submit" value="Log In" id="log-button">
    </form>

   <script>
   //for the alert
  document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
      setTimeout(() => {
        alert.style.opacity = "0";
        alert.style.transform = "translateY(-10px)";
        setTimeout(() => {
          alert.style.display = "none";
        }, 500);
      }, 4000); 
    });
  });
</script>

</body>

</html>
