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
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $speciality = isset($_POST['speciality']) ? $_POST['speciality'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $photo = isset($_FILES['photo']['name']) && $_FILES['photo']['tmp_name'] != '' ? $_FILES['photo'] : '';
  
    $user_id = signup_doctor($firstName, $lastName, $speciality, $email, $password, $photo);
  } else if ($role == 'patient')  {
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';    
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $user_id = signup_patient($firstName, $lastName, $gender, $dob, $email, $password);
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

$specialties = getSpecialities();

function encryptPassword($password) {
  $options = [
      'cost' => 12,
  ];
  return password_hash($password, PASSWORD_BCRYPT, $options);
}

function isEmailExists($email, $table) {
  global $conn;
  $emailAddress = mysqli_real_escape_string($conn, $email);

  $sql = "SELECT * FROM `$table` WHERE `emailAddress`='$emailAddress';";
  $result = mysqli_query($conn, $sql);
  if ($result && mysqli_num_rows($result) > 0)
      return true;
  return false;
}

function uplouad_image($target_dir, $file_name, $file)
{
    global $error_msg;
    $file_type = pathinfo($file['name'], PATHINFO_EXTENSION);
    $target_file = strtolower($target_dir . basename($file_name) . "." . $file_type);
    //$file_type = pathinfo($target_file, PATHINFO_EXTENSION);
    $check = getimagesize($file["tmp_name"]);
    if ($check == false) {
        $error_msg = "File is not an image.";
        return false;
    }

    if ($file_type != "jpg" && $file_type != "png" && $file_type != "jpeg" && $file_type != "gif") {
        $error_msg = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        return false;
    }

    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    } else {
        $error_msg = "Sorry, there was an error uploading your file.";
        return false;
    }
}

function signup_doctor($firstName, $lastName, $speciality, $email, $password, $photo) {
  global $conn;
  global $error_msg;

  if (empty($firstName) || empty($lastName) || empty($speciality) || empty($email) || empty($password)) {
      $error_msg = "All fields are required!";
      return false;
  }

  if (isEmailExists($email, 'doctor')) {
      $error_msg = "Email aready exists.";
      return false;
  }
  
  $firstName = mysqli_real_escape_string($conn, $firstName);
  $lastName = mysqli_real_escape_string($conn, $lastName);
  $specialityID = mysqli_real_escape_string($conn, $speciality);
  $emailAddress = mysqli_real_escape_string($conn, $email);
  $password = mysqli_real_escape_string($conn, $password);
  $hashed_password = encryptPassword($password);

  $uniqueFileName = "images/default_user.jpg";
  if (isset($_FILES['photo']['name']) && $_FILES['photo']['tmp_name'] != '') {
    $image_name = uniqid();
    $target_dir = "uploads/";
    $uniqueFileName = uplouad_image($target_dir, $image_name, $_FILES['photo']);
  }

  if ($error_msg)
    return false; 

  $sql = "INSERT INTO `doctor` (`firstName`, `lastName`, `specialityID`, `uniqueFileName`, `emailAddress`, `password`) VALUES ('$firstName', '$lastName', '$specialityID', '$uniqueFileName', '$emailAddress', '$hashed_password')"; 
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $user_id = mysqli_insert_id($conn);
      return $user_id;
  }

  $error_msg = "Error, Can not add doctor." . mysqli_error($conn) . $sql;
  return false;
}

function signup_patient($firstName, $lastName, $gender, $dob, $email, $password) {
  global $conn;
  global $error_msg;

  if (empty($firstName) || empty($lastName) || empty($gender) || empty($dob) || empty($email) || empty($password)) {
      $error_msg = "All fields are required!";
      return false;
  }
  
  if (isEmailExists($email, 'patient')) {
      $error_msg = "Email aready exists.";
      return false;
  }
  
  $firstName = mysqli_real_escape_string($conn, $firstName);
  $lastName = mysqli_real_escape_string($conn, $lastName);
  $gender = mysqli_real_escape_string($conn, $gender);
  $DoB = mysqli_real_escape_string($conn, $dob);
  $emailAddress = mysqli_real_escape_string($conn, $email);
  $password = mysqli_real_escape_string($conn, $password);
  $hashed_password = encryptPassword($password);
  
  if ($error_msg)
    return false; 

  $sql = "INSERT INTO `patient` (`firstName`, `lastName`, `gender`, `DoB`, `emailAddress`, `password`) VALUES ('$firstName', '$lastName', '$gender', '$DoB', '$emailAddress', '$hashed_password')"; 
  $result = mysqli_query($conn, $sql);

  if ($result) {
      $user_id = mysqli_insert_id($conn);
      return $user_id;
  }

  $error_msg = "Error, Can not add patient." . mysqli_error($conn) . $sql;
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

?>

<!DOCTYPE html>
<html>

<head>
    <title>Sign-up</title>
    <link rel="icon" type="image/png" href="images/logo2.png">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/singup.css">
    <script src="script.js"></script>

</head>

<body>
    <img src="images/logogreen.png" alt="Logo">
    <div class="tabs">
        <a href="login.php" style="color: #5d5d5d; ">Login</a>
        <a href="#" class="active" style="left: 30%;">Sign up</a>
    </div>

    <div class="rolesholder">
        <label class="roles">Your role: </label>
        <label class="roles">
            <input type="radio" name="role" value="Doctor" id="doctorRole" >Doctor
        </label>
        <label class="roles">
            <input type="radio" name="role" value="Patient" id="patientRole">Patient
        </label>
    </div>


    <!-- Form for Doctor -->
    <form id="Doctor" action="signup.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="role" value="doctor" />

        <div class="form-group">
            <label for="firstName">First name:</label>
            <input type="text" id="firstName" name="firstName" placeholder="Your first name..">
        </div>
        <div class="form-group">
            <label for="lastName">Last name:</label>
            <input type="text" id="lastName" name="lastName" placeholder="Your last name..">
        </div>
        <div class="form-group">
            <label for="photo">Photo:</label>
            <input type="file" id="photo" name="photo">
        </div>
        <div class="form-group">
            <label for="speciality">Speciality:</label>
            <select name="speciality" id="speciality" required>
                <?php if ($specialties) { ?>
                <option value="" disabled selected>Select your speciality</option>
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
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Your email..">
        </div>
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Your password..">
        </div>
        <button class="signup" type="button" onclick="return saveUserData('doctor');">Sign-up</button>
    </form>

    <!-- Form for Patient -->
    <form id="Patient" action="signup.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="role" value="patient" />
        <div class="form-group">
            <label for="patientFirstName">First name:</label>
            <input type="text" id="patientFirstName" name="firstName" placeholder="Your first name..">
        </div>
        <div class="form-group">
            <label for="patientLastName">Last name:</label>
            <input type="text" id="patientLastName" name="lastName" placeholder="Your last name..">
        </div>
        <div class="gender-group">
            <label>Gender:</label>
            <label><input type="radio" name="gender" value="Female" checked> Female</label>
            <label><input type="radio" name="gender" value="Male"> Male</label>
        </div>
        <div class="form-group">
            <label for="dob">Date of birth:</label>
            <input type="date" id="dob" name="dob" placeholder="Your date of birth..">
        </div>
        <div class="form-group">
            <label for="patientEmail">Email:</label>
            <input type="email" id="patientEmail" name="email" placeholder="Your email..">
        </div>
        <div class="form-group">
            <label for="patientPassword">Password:</label>
            <input type="password" id="patientPassword" name="password" placeholder="Your password..">
        </div>
        <button class="signup" type="button" onclick="return saveUserData('patient')">Sign-up</button>
    </form>


    <?php
            if (!empty($error_msg)) {
              echo "<script type='text/javascript'>alert('$error_msg');</script>";
            } else if (!empty($success_msg)) {
              echo "<script type='text/javascript'>alert('$success_msg');</script>";
            }
        ?>
    <script>
    window.onload = function () {
    doctorForm.style.display = "none";
    patientForm.style.display = "none";
    };
    const doctorRole = document.getElementById("doctorRole");
    const patientRole = document.getElementById("patientRole");
    const doctorForm = document.getElementById("Doctor");
    const patientForm = document.getElementById("Patient");

    function showForm() {
        doctorForm.style.display = "none";
        patientForm.style.display = "none";
        if (doctorRole.checked) {
            doctorForm.style.display = "block";
        } else if (patientRole.checked) {
            patientForm.style.display = "block";
        }
    }
    doctorRole.addEventListener("change", showForm);
    patientRole.addEventListener("change", showForm);
    doctorForm.style.display = "block";
    </script>
    <script>
    function saveUserData(role) {
        let userData = {};
        let gender = document.querySelector('input[name="gender"]:checked')?.value;
        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Email validation regex
        if (password.length < 8) {
        alert("Password must be at least 6 characters.");
        return;
        }


        if (role === "doctor") {
          let password = document.getElementById("password").value.trim();
          if (password.length < 8) {
              alert("Password must be at least 8 characters.");
              return;
          }

            let firstName = document.getElementById("firstName").value.trim();
            let lastName = document.getElementById("lastName").value.trim();
            let email = document.getElementById("email").value.trim();
            let speciality = document.getElementById("speciality").value.trim();
            let photo = document.getElementById("photo").files[0]?.name;

            if (!firstName || !lastName || !email || !speciality || !gender) {
                alert("All fields are required except for the photo!");
                return;
            }

            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address!");
                return;
            }
            doctorForm.submit();
        } else if (role === "patient") {
          let password = document.getElementById("password").value.trim();
          if (password.length < 8) {
              alert("Password must be at least 8 characters.");
              return;
          }

            let firstName = document.getElementById("patientFirstName").value.trim();
            let lastName = document.getElementById("patientLastName").value.trim();
            let email = document.getElementById("patientEmail").value.trim();
            let dob = document.getElementById("dob").value.trim();

            if (!firstName || !lastName || !email || !dob || !gender) {
                alert("All fields are required!");
                return;
            }

            if (!emailRegex.test(email)) {
                alert("Please enter a valid email address!");
                return;
            }
            patientForm.submit();
        }
        return true;
    }
    </script>
</body>

</html>