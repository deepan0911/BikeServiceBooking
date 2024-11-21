
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

// Retrieve booking details
$query = "SELECT * FROM bookings WHERE id = '$id'";
$result = $conn->query($query);
$booking = $result->fetch_assoc();

// Update payment status
if (isset($_POST['update_payment'])) {
  $payment_status = $_POST['payment_status'];
  $query = "UPDATE bookings SET payment_status = '$payment_status' WHERE id = '$id'";
  $conn->query($query);
  header('Location: admin.php?message=Payment status updated successfully');
  exit;
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Update Payment Status</title>
  <link rel="stylesheet" href="styles.css">
  <style>
    html {
  scroll-behavior: smooth;
}

body {
  scroll-behavior: smooth;
  font-family: 'Poppins', sans-serif;
  margin: 0;
  padding: 0;
  background-color: #f5f5f5;
}

h1 {
  color: black;
  font-size: 40px;
  text-align: center;
  margin-top: 50px;
  margin-bottom: 20px;
}

/* Form Styles */

form {
  width: 50%;
  margin: 0 auto;
  padding: 20px;
  background-color: #fff;
  border: 1px solid #ddd;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
  display: block;
  margin-bottom: 10px;
}

select {
  width: 100%;
  height: 40px;
  margin-bottom: 20px;
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

input[type="submit"] {
  width: 100%;
  height: 40px;
  background-color: #4CAF50;
  color: #fff;
  padding: 10px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}

input[type="submit"]:hover {
  background-color: #3e8e41;
}

/* Responsive Design */

@media only screen and (max-width: 768px) {
  form {
    width: 80%;
  }
}

@media only screen and (max-width: 480px) {
  form {
    width: 90%;
  }
  select {
    height: 30px;
    padding: 5px;
  }
  input[type="submit"] {
    height: 30px;
    padding: 5px;
  }
}

  </style>
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
    <h1>Update Payment Status</h1>
    <div class="booking-status-filters" style="background-color:lightgray; font-size: 20px;">
      <ul style="display:flex; padding:20px; justify-content:space-between;">
        <a href="admin.php" style="color:black;text-decoration:none;">All Bookings</a>
        <span style="color:black;"> | </span>
        <a href="admin_pending_bookings.php" style="color:black;text-decoration:none;">Pending Bookings</a>
        <span style="color:black;"> | </span>
        <a href="admin_completed_bookings.php" style="color:black;text-decoration:none;">Completed Bookings</a>
      </ul>
    </div>
    <div class="booking-form" style="background-color:antiquewhite; width: 100%; padding: 2rem;">
      <form action="" method="post">
        <div class="form-row">
          <div class="form-group">
            <label>Booking ID:</label>
            <span style="font-weight: bold;"><?php echo $booking['id']; ?></span>
          </div>
          <div class="form-group">
            <label>Vehicle Number:</label>
            <span style="font-weight: bold;"><?php echo $booking['vehicle_number']; ?></span>
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Payment Status:</label>
            <select name="payment_status">
              <option value="paid" <?php if ($booking['payment_status'] == 'paid') echo 'selected'; ?>>Paid</option>
              <option value="pending" <?php if ($booking['payment_status'] == 'pending') echo 'selected'; ?>>Pending</option>
              <option value="overdue" <?php if ($booking['payment_status'] == 'overdue') echo 'selected'; ?>>Overdue</option>
            </select>
          </div>
        </div>
        <input type="submit" name="update_payment" value="Update Payment Status" class="book-now">
      </form>
    </div>
  </main>

  <script>
    function confirmLogout() {
      if (confirm("Are you sure you want to log out?")) {
        window.location.href = "admin_logout.php";
      }
    }
  </script>
</body>
</html>

