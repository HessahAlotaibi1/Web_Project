<?php
session_start();
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    if (empty($email) || empty($password) || empty($role)) {
        echo "❌ جميع الحقول مطلوبة.";
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "❌ البريد الإلكتروني غير صالح.";
        exit();
    }

    if ($role == "doctor") {
        $query = "SELECT id, password FROM Doctor WHERE emailAddress = ?";
    } else {
        $query = "SELECT id, password FROM Patient WHERE emailAddress = ?";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password);

    if ($stmt->fetch() && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['user_type'] = $role;
        header("Location: " . ($role == "doctor" ? "doctor_home.php" : "patient_home.php"));
        exit();
    } else {
        echo "❌ البريد الإلكتروني أو كلمة المرور غير صحيحة.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!-- نموذج تسجيل الدخول -->
<form method="post" action="login.php">
    <label>البريد الإلكتروني:</label>
    <input type="email" name="email" required><br>

    <label>كلمة المرور:</label>
    <input type="password" name="password" required><br>

    <label>الدور:</label>
    <select name="role" required>
        <option value="patient">مريض</option>
        <option value="doctor">طبيب</option>
    </select><br>

    <button type="submit">تسجيل الدخول</button>
</form>