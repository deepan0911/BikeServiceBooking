<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Retrieve pending bookings from database
$query = "SELECT * FROM bookings WHERE status = 0";
$result = $conn->query($query);
$bookings = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pending Bookings</title>
    <link rel="stylesheet" href="styles1.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="admin.php">Bookings</a></li>
            <li><a href="admin_users.php">Users</a></li>
            <li><a href="#" onclick="confirmLogout()">Logout</a></li>
        </ul>
    </nav>
</header>
<main class="dashboard">
    <h1>Pending Bookings</h1>
    <table class="dashboard-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Vehicle Number</th>
            <th>Complaint</th>
            <th>Service Date</th>
            <th>Status</th>
            <th>Invoice</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($bookings as $booking) { ?>
            <tr>
                <td><?php echo $booking['id']; ?></td>
                <td><?php echo $booking['user_id']; ?></td>
                <td><?php echo $booking['vehicle_number']; ?></td>
                <td><?php echo $booking['complaint']; ?></td>
                <td><?php echo $booking['service_date']; ?></td>
                <td><span style="color: orange;">Pending</span></td>
                <td><?php if ($booking['invoice_sent'] == 'sent') { ?><span style="color: green;">Sent</span><?php } else { ?><span style="color: orange;">Not Sent</span><?php } ?></td>
                <td>
                    <button class="action-button"><a href="admin_booking_details.php?id=<?php echo $booking['id']; ?>">View</a></button>
                    <button class="action-button"><a href="admin_mark_completed.php?id=<?php echo $booking['id']; ?>">Mark Completed</a></button>
                    <button class="action-button"><a href="admin_add_invoice.php?id=<?php echo $booking['id']; ?>">Add Invoice</a></button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</main>
        </body>
        </html>