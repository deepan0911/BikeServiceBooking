<?php
session_start();
require_once 'config.php';

// Update last_logged_out datetime
$query = "UPDATE users SET last_logged_out = NOW() WHERE email = '".$_SESSION['email']."'";
$conn->query($query);

// Unset and destroy session
session_unset();
session_destroy();

header('Location: index.php');
exit;
?>
