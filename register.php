
<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($name) || empty($email) || empty($mobile) || empty($password) || empty($confirm_password)) {
        echo 'Please fill in all fields!';
        exit;
    }

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo 'Email already exists!';
        exit;
    }

    // Check if mobile exists
    $query = "SELECT * FROM users WHERE mobile = '$mobile'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        echo 'Mobile number already exists!';
        exit;
    }

    // Check password strength
    if ($password !== $confirm_password) {
        echo 'Passwords do not match!';
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Register user
    $query = "INSERT INTO users (name, email, mobile, password) VALUES ('$name', '$email', '$mobile', '$hashed_password')";
    $conn->query($query);

    echo 'Registration successful!';
    exit;
}
?>

