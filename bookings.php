<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Bookings</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Noto+Sans+JP:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    
</head>
<body>

<header>
  <nav>
  <div class="nav-container">
    <ul>
      <li class="dropdown">
        <a href="#" class="dropbtn">ACCOUNT</a>
        <div class="dropdown-content">
          <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>
            <a href="logout.php" class="login" onclick="return logoutAlert(event)">LOGOUT</a>
            <a href="bookings.php" class="login">BOOKINGS</a>
            <a href="payment.php" class="login">PAYMENT</a>
          <?php } else { ?>
            <a href="user_login.html" class="login">LOGIN</a>
          <?php } ?>
        </div>
      </li>
      <li><a href="index.php">HOME</a></li>
      <li><a href="#about">ABOUT</a></li>
      <li><a href="#contact">CONTACT</a></li>
    </ul>
  </div>
</nav>
</header>

<main>
<section class="bookings">
        <h2>Your Previous Bookings</h2>
        <hr>
        <div class="bookings-container">
            <?php
            // Connect to the database
            include 'config.php'; 

            // Fetch bookings and amounts for the logged-in user
            $user_email = $_SESSION['email'];
            $query = "
                SELECT b.*, u.name, i.total 
                FROM bookings b 
                JOIN users u ON b.user_id = u.id 
                LEFT JOIN invoices i ON b.id = i.booking_id 
                WHERE u.email = '$user_email' 
                ORDER BY b.service_date DESC"; 
            $result = mysqli_query($conn, $query);

            if (!$result) {
                echo "<p>Error fetching bookings: " . mysqli_error($conn) . "</p>";
            } elseif (mysqli_num_rows($result) > 0) {
                while ($booking = mysqli_fetch_assoc($result)) {
                    echo "<div class='booking-item'>";
                    echo "<p><strong>Vehicle Number:</strong> " . htmlspecialchars($booking['vehicle_number']) . "</p>";
                    echo "<p><strong>Issue:</strong> " . htmlspecialchars($booking['complaint']) . "</p>";
                    echo "<p><strong>Service Date:</strong> " . htmlspecialchars($booking['service_date']) . "</p>";
                    echo "<p><strong>Status:</strong> " . htmlspecialchars($booking['status'] ? 'Completed' : 'Pending') . "</p>";
                    echo "<p><strong>Amount Paid:</strong> ₹" . htmlspecialchars($booking['total'] ? $booking['total'] : '0.00') . "</p>";
                    echo "<p><strong>Payment Status:</strong> ";
                    if ($booking['payment_status'] == 'paid') {
                    echo "<span style='color: green;'>Paid</span>";
                    } elseif ($booking['payment_status'] == 'pending') {
                    echo "<span style='color: red;'>Pending</span>";
                    } else {
                    echo htmlspecialchars($booking['payment_status']);
                    }
                    echo "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No previous bookings found.</p>";
            }

            mysqli_close($conn);
            ?>
        </div>
    </section>
</main>

<footer>
    <div class="contact-info">
        <p>Address: Bike Service Centre, Coimbatore</p>
        <p>Phone: 9360648801</p>
        <p>Email: <a href="mailto:deepann2004@gmail.com" style="text-decoration:none;color:white;">deepann2004@gmail.com</a></p>
    </div>
    <div class="social-media">
        <a href="#" target="_blank" class="facebook"><i class="fa-brands fa-facebook"></i></a>
        <a href="#" target="_blank" class="twitter"><i class="fa-brands fa-twitter"></i></a>
        <a href="#" target="_blank" class="instagram"><i class="fa-brands fa-instagram"></i></a>
    </div>
</footer>

<script>
function logoutAlert(event) {
    if (!confirm("Are you sure you want to logout?")) {
        event.preventDefault(); // Prevent the default action
        return false; // Stop the link from following
    }
    return true; // Allow the logout if confirmed
}

</script>

</body>
</html>
