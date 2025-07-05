<?php require_once 'config.php'; session_start(); 
// Check if admin is already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header('Location: admin.php'); 
    exit;
} 
// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; 
    $password = $_POST['password']; 
    
    // Authenticate admin credentials
    $query = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        $_SESSION['admin_logged_in'] = true; 
        header('Location: admin.php'); 
        exit;
    } else {
        $error = 'Invalid username or password';
    }
} 
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body class="admin-login-page">
    <div class="login-form">
        <h1>Admin Login</h1>
        <form action="" method="post">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="username" >
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" >
            </div>
            <input type="submit" value="Login" class="login-btn">
        </form>
        <?php if (isset($error)) { ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php } ?>
    </div>
</body>
</html>



