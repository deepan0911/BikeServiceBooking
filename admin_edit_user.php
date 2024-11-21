

<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Get user ID from URL
$id = $_GET['id'];

// Retrieve user data from database
$query = "SELECT * FROM users WHERE id = '$id'";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Update user data
if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];

    $query = "UPDATE users SET name = '$name', mobile = '$mobile', email = '$email' WHERE id = '$id'";
    $conn->query($query);

    header('Location: admin_users.php');
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin.php">Bookings</a></li>
                <li><a href="admin_users.php">Users</a></li>
                <li><a href="admin_logout.php">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main class="dashboard">
        <h1>Edit User</h1>
        <form action="" method="post">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>"><br><br>
            <label>Mobile:</label>
            <input type="text" name="mobile" value="<?php echo $user['mobile']; ?>"><br><br>
            <label>Email:</label>
            <input type="email" name="email" value="<?php echo $user['email']; ?>"><br><br>
            <input type="submit" name="update" value="Update">
        </form>
    </main>
</body>
</html>
