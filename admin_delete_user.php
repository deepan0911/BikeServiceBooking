

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

if (isset($_POST['delete'])) {
    // Delete user data from database
    $query = "DELETE FROM users WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: admin_users.php');
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
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
        <h1>Delete User</h1>
        <p>Are you sure you want to delete <?php echo $user['name']; ?>?</p>
        <form action="" method="post">
            <input type="submit" name="delete" value="Delete">
            <a href="admin_users.php">Cancel</a>
        </form>
    </main>
</body>
</html>




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

if (isset($_POST['delete'])) {
    // Delete user data from database
    $query = "DELETE FROM users WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: admin_users.php');
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
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
        <h1>Delete User</h1>
        <p>Are you sure you want to delete <?php echo $user['name']; ?>?</p>
        <form action="" method="post">
            <input type="submit" name="delete" value="Delete">
            <a href="admin_users.php">Cancel</a>
        </form>
    </main>
</body>
</html>




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

if (isset($_POST['delete'])) {
    // Delete user data from database
    $query = "DELETE FROM users WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: admin_users.php');
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
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
        <h1>Delete User</h1>
        <p>Are you sure you want to delete <?php echo $user['name']; ?>?</p>
        <form action="" method="post">
            <input type="submit" name="delete" value="Delete">
            <a href="admin_users.php">Cancel</a>
        </form>
    </main>
</body>
</html>




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

if (isset($_POST['delete'])) {
    // Delete user data from database
    $query = "DELETE FROM users WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: admin_users.php');
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
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
        <h1>Delete User</h1>
        <p>Are you sure you want to delete <?php echo $user['name']; ?>?</p>
        <form action="" method="post">
            <input type="submit" name="delete" value="Delete">
            <a href="admin_users.php">Cancel</a>
        </form>
    </main>
</body>
</html>




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

if (isset($_POST['delete'])) {
    // Delete user data from database
    $query = "DELETE FROM users WHERE id = '$id'";
    if ($conn->query($query) === TRUE) {
        header('Location: admin_users.php');
        exit;
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete User</title>
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
        <h1>Delete User</h1>
        <p>Are you sure you want to delete <?php echo $user['name']; ?>?</p>
        <form action="" method="post">
            <input type="submit" name="delete" value="Delete">
            <a href="admin_users.php">Cancel</a>
        </form>
    </main>
</body>
</html>


