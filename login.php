
<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate input
    if (empty($email) || empty($password)) {
        echo 'Please fill in all fields!';
        exit;
    }

    // Check if email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows === 0) {
        echo 'Invalid email or password!';
        exit;
    }

    $user = $result->fetch_assoc();
    $hashed_password = $user['password'];

    // Verify password
    if (!password_verify($password, $hashed_password)) {
        echo 'Invalid email or password!';
        exit;
    }

    // Login successful
    $_SESSION['logged_in'] = true;
    echo 'Login successful!';

    session_start();
    $_SESSION['logged_in'] = true;
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    // Update last login datetime
    $query = "UPDATE users SET last_login = NOW() WHERE email = '$email'";
    $conn->query($query);
    
    exit;
    
}
header('Location:index.php');

?>


