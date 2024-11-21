
<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Retrieve booking details from database
$id = $_GET['id'];
$query = "SELECT b.id, u.name, u.email, u.mobile, b.vehicle_number, b.complaint, b.service_date, b.status 
          FROM bookings b 
          JOIN users u ON b.user_id = u.id 
          WHERE b.id = '$id'";
$result = $conn->query($query);
$booking = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking Details</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="admin.php">Back to Bookings</a></li>
            </ul>
        </nav>
    </header>
    <main class="booking-details-main">
        <h1>Booking Details</h1>
        <section class="booking-details-section">
            <table class="booking-details-table">
                <tr>
                    <th>ID</th>
                    <td><?php echo $booking['id']; ?></td>
                </tr>
                <tr>
                    <th>User Name</th>
                    <td><?php echo $booking['name']; ?></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><?php echo $booking['email']; ?></td>
                </tr>
                <tr>
                    <th>Mobile</th>
                    <td><?php echo $booking['mobile']; ?></td>
                </tr>
                <tr>
                    <th>Vehicle Number</th>
                    <td><?php echo $booking['vehicle_number']; ?></td>
                </tr>
                <tr>
                    <th>Complaint</th>
                    <td><?php echo $booking['complaint']; ?></td>
                </tr>
                <tr>
                    <th>Service Date</th>
                    <td><?php echo $booking['service_date']; ?></td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php if ($booking['status'] == 1) { ?>
                            <span style="color: green;">Completed</span>
                        <?php } else { ?>
                            <span style="color: orange;">Pending</span>
                        <?php } ?>
                    </td>
                </tr>
            </table>
        </section>
    </main>
</body>
</html>



