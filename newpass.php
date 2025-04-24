<!--this page becuse we forget the passwords so we can change the password from this page-->
<?php
session_start();
include_once("inc/db_connection.php");

$msg = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'], $_POST['new_password'], $_POST['user_type'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $newPassword = $_POST['new_password'];
    $userType = $_POST['user_type'];

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    if ($userType === 'patient') {
        $table = "patient";
        $emailColumn = "emailAddress";
    } elseif ($userType === 'doctor') {
        $table = "doctor";
        $emailColumn = "emailAddress";
    } else {
        $msg = "❌ Invalid user type.";
    }

    if (!empty($table)) {
        $sql = "UPDATE `$table` SET `password` = '$hashedPassword' WHERE `$emailColumn` = '$email'";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_affected_rows($conn) > 0) {
            $msg = "✅ Password updated successfully for $userType!";
        } else {
            $msg = "❌ Error: Could not update password. Check the email or try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="images/logo2.png">
        <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('images/background.gif');
            background-size: cover;
            background-position: center;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px 25px;
            width: 100%;
            max-width: 450px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: 600;
            margin-top: 15px;
            display: block;
            color: #444;
        }

        select, input[type="email"], input[type="password"], button {
            width: 100%;
            padding: 12px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 15px;
            text-align: center;
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔐 Change User Password</h2>
    <form method="POST" class="form-group">
        <label for="user_type">Select User Type:</label>
        <select name="user_type" id="user_type" required>
            <option value="" disabled selected>Select user type</option>
            <option value="patient">Patient</option>
            <option value="doctor">Doctor</option>
        </select>

        <label>Email address:</label>
        <input type="email" name="email" required>

        <label>New password:</label>
        <input type="password" name="new_password" required>

        <button type="submit">Update Password</button>
    </form>

    <?php if (!empty($msg)) { echo '<div class="message">'.$msg.'</div>'; } ?>
</div>

</body>
</html>
