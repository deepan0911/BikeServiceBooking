
<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

// Retrieve bookings from database
$query = "SELECT * FROM bookings ORDER BY id DESC";
$result = $conn->query($query);
$bookings = $result->fetch_all(MYSQLI_ASSOC);

// Check if email sent successfully
if (isset($_GET['message'])) {
  $message = $_GET['message'];
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Admin Dashboard</title>
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
    <h1>Admin Dashboard</h1>
    <h2>Bookings</h2>
    <?php if (isset($message) && strpos($message, 'Email sent successfully') !== false) { ?>
      <script>
        alert('<?php echo $message; ?>');
        window.history.replaceState({}, '', 'admin.php'); // remove query string
      </script>
    <?php } ?>
    <!-- Search container -->
    <div class="search-container">
      <form action="admin.php" method="GET">
        <input type="text" name="search" placeholder="Search by Vehicle Number">
        <button type="submit">Search</button>
      </form>
    </div>
    <div class="booking-status-filters" style="background-color:lightgray; font-size: 20px;">
      <ul style="display:flex; padding:20px; justify-content:space-between;">
        <a href="admin.php" style="color:black;text-decoration:none;">All Bookings</a>
        <span style="color:black;"> | </span>
        <a href="admin_pending_bookings.php" style="color:black;text-decoration:none;">Pending Bookings</a>
        <span style="color:black;"> | </span>
        <a href="admin_completed_bookings.php" style="color:black;text-decoration:none;">Completed Bookings</a>
      </ul>
    </div>
    <table class="dashboard-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>User</th>
          <th>Vehicle Number</th>
          <th>Complaint</th>
          <th>Service Date</th>
          <th>Status</th>
          <th>Completed At</th>
          <th>Invoice</th>
          <th>Payment Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        // Search functionality
        if (isset($_GET['search'])) {
          $search_query = "SELECT * FROM bookings WHERE vehicle_number LIKE '%".$_GET['search']."%' ORDER BY id DESC";
          $result = $conn->query($search_query);
          $bookings = $result->fetch_all(MYSQLI_ASSOC);
        } else {
          $query = "SELECT * FROM bookings ORDER BY id DESC";
          $result = $conn->query($query);
          $bookings = $result->fetch_all(MYSQLI_ASSOC);
        }
        foreach ($bookings as $booking) { ?>
          <tr>
            <td><?php echo $booking['id']; ?></td>
            <td><?php echo $booking['user_id']; ?></td>
            <td><?php echo $booking['vehicle_number']; ?></td>
            <td><?php echo $booking['complaint']; ?></td>
            <td><?php echo $booking['service_date']; ?></td>
            <td>
              <?php if ($booking['status'] == 1) { ?>
                <span style="color: green;">Completed</span>
              <?php } else { ?>
                <span style="color: orange;">Pending</span>
              <?php } ?>
            </td>
            <td>
              <?php if ($booking['completed_at'] != null) { ?>
                <span><?php echo $booking['completed_at']; ?></span>
              <?php } else { ?>
                <span>N/A</span>
              <?php } ?>
            </td>
            <td>
              <?php if ($booking['invoice_sent'] == 'sent') { ?>
                <span style="color: green;">Sent</span>
              <?php } else { ?>
                <span style="color: orange;">Not Sent</span>
              <?php } ?>
            </td>
            <td>
              <?php if ($booking['payment_status'] == 'paid') { ?>
                <span style="color: green;">Paid</span>
              <?php } elseif ($booking['payment_status'] == 'pending') { ?>
                <span style="color: orange;">Pending</span>
              <?php } elseif ($booking['payment_status'] == 'overdue') { ?>
                <span style="color: red;">Overdue</span>

<?php } elseif ($booking['payment_status'] == 'overdue') { ?>
    <span style="color: red;">Overdue</span>
  <?php } ?>
</td>
<td>
  <button class="action-button"> 
    <a href="admin_booking_details.php?id=<?php echo $booking['id']; ?>">View</a> 
  </button>
  <button class="action-button" onclick="confirmMarkCompleted(<?php echo $booking['id']; ?>)" style="font-size:16px;"> 
    Mark Completed 
  </button>
  <button class="action-button"> 
    <a href="admin_add_invoice.php?id=<?php echo $booking['id']; ?>">Add Invoice</a> 
  </button>
  <?php if ($booking['payment_status'] != 'paid') { ?>
  <button class="action-button"> 
    <a href="admin_update_payment_status.php?id=<?php echo $booking['id']; ?>">Update Payment</a> 
  </button>
  <?php } ?>
</td>

</tr>
<?php } ?>
</tbody>
</table>

</main>
</body>
</html>

<script>
function confirmLogout() {
if (confirm("Are you sure you want to log out?")) {
window.location.href = "admin_logout.php";
}
}

function confirmMarkCompleted(id) {
if (confirm("Are you sure you want to mark this booking as completed?")) {
window.location.href = "admin_mark_completed.php?id=" + id;
}
}
</script>


