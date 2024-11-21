<?php
session_start();

// Unset all session variables
session_unset();

// Destroy the session
session_destroy();
?>

<script>
alert('Account Logged Out!');
</script>

<meta http-equiv="refresh" content="0; url=admin_login.php">
