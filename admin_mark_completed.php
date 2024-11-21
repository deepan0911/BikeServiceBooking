<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: admin_login.php');
    exit;
}

// Get booking ID
$id = $_GET['id'];

// Update booking status and completed_at datetime
$query = "UPDATE bookings SET status = 1, completed_at = NOW() WHERE id = '$id'";
$conn->query($query);

// Redirect to admin dashboard
header('Location: admin.php?message=Booking marked completed successfully!');
exit;
?>

