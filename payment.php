<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['email'])) {
    $user_email = $_SESSION['email'];
} else {
    header('Location: user_login.html');
    exit;
}

// Retrieve user ID from email
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id'];
} else {
    $user_id = 0;
}

// Retrieve latest booking ID
$sql = "SELECT id FROM bookings WHERE user_id = ? ORDER BY service_date DESC LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $booking_id = $row['id'];
} else {
    $booking_id = 0;
}

// Retrieve invoice amount
$sql = "SELECT total FROM invoices WHERE booking_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $invoice_amount = $row['total'];
} else {
    $invoice_amount = 0;
}

// Generate UPI payment data for QR code
$upiId = 'deepan09112004@okicici'; // Your UPI ID
$payeeName = 'Deepan.N'; // Payee Name
$amount = number_format($invoice_amount, 2, '.', ''); // Ensure it's a string for the URL
$transactionId = $booking_id; // Booking ID as transaction ID
$purpose = 'Invoice Payment'; // Payment purpose
// Create the UPI link for QR code
$upiLink = "upi://pay?pa=$upiId&pn=$payeeName&am=$amount&cu=INR&tid=$transactionId&tn=$purpose";

// Retrieve payment status from bookings table
$sql = "SELECT payment_status FROM bookings WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$hasPendingPayment = false;
while ($row = $result->fetch_assoc()) {
    if (strtolower($row['payment_status']) == 'pending') {
        $hasPendingPayment = true;
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Payment</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&family=Merriweather:ital,wght@0,300;0,400;0,700;0,900;1,300;1,400;1,700;1,900&family=Noto+Sans+JP:wght@100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
    <section class="payment">
        <h2>Make Payment</h2>
        <hr>
        <?php 
        // Retrieve payment status from bookings table
        $sql = "SELECT payment_status FROM bookings WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();
        

        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $payment_status = $row['payment_status'];
        } else {
            $payment_status = 'pending';
            echo "No payment status found. Defaulting to 'pending'.<br>";
        }
        
        if ($hasPendingPayment && $invoice_amount > 0 )  { ?>
            <div class="payment-methods">
                <div class="payment-header">
                    <h3>Choose Payment Method:</h3>
                </div>
            </div>
            <div class="payment-methods-container">
                <div class="payment-method qr-code-container">
                    <div id="qrcode"></div>
                    <p>Scan QR Code to make payment</p>
                </div>
                <div class="payment-method gpay-container">
                    <img src="gpay-logo.png" alt="Google Pay">
                    <p style="color:black;">Make payment using Google Pay</p>
                </div>
                <a href="<?php echo $upiLink; ?>" class="btn btn-primary" style="text-decoration:none;">Pay with GPay</a>
            </div>
            <div class="invoice-details">
                <h3>Invoice Details:</h3>
                <p>User Email: <?php echo htmlspecialchars($user_email); ?></p>
                <p>Booking ID: <?php echo htmlspecialchars($booking_id); ?></p>
                <p>Invoice Amount: ₹<?php echo number_format($invoice_amount, 2); ?></p>
            </div>
            <form action="update_payment_status.php" method="post">
                <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($booking_id); ?>">
            </form>
        <?php } else { ?>
            <p>No payments pending.</p>
        <?php } ?>
    </section>
</main>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Generate the QR code
        var qrcode = new QRCode(document.getElementById("qrcode"), {
            text: "<?php echo $upiLink; ?>",
            width: 256,
            height: 256
        });
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

