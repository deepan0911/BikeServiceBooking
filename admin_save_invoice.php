
<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $booking_id = $_POST['booking_id'];
  $description = $_POST['description'];
  $quantity = $_POST['quantity'];
  $price = $_POST['price'];

  // Validate input data
  if (empty($booking_id) || empty($description) || empty($quantity) || empty($price)) {
    echo "Please fill in all fields.";
    exit;
  }

  // Check if invoice already exists
  $query = "SELECT * FROM invoices WHERE booking_id = '$booking_id'";
  $result = $conn->query($query);
  $invoice_exists = $result->num_rows > 0;

  if ($invoice_exists) {
    // Retrieve existing invoice details
    $existing_invoice = $result->fetch_assoc();
    $existing_items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = '$existing_invoice[id]'");
  }

  // Calculate total invoice amount
  $total = 0;
  foreach ($price as $key => $p) {
    $total += $p * $quantity[$key];
  }

  // Insert invoice header into database
  if (!$invoice_exists) {
    $query = "INSERT INTO invoices (booking_id, invoice_date, total) VALUES ('$booking_id', CURDATE(), '$total')";
    $conn->query($query);
    $invoice_id = $conn->insert_id;
  } else {
    $invoice_id = $existing_invoice['id'];
    // Update existing invoice total
    $query = "UPDATE invoices SET total = total + '$total' WHERE id = '$invoice_id'";
    $conn->query($query);
  }

  // Insert invoice items into database
  foreach ($description as $key => $desc) {
    $item_total = $price[$key] * $quantity[$key];
    $query = "INSERT INTO invoice_items (invoice_id, description, quantity, price, total) VALUES ('$invoice_id', '$desc', '$quantity[$key]', '$price[$key]', '$item_total')";
    $conn->query($query);
  }

  // Redirect to same page
  header('Location: admin_add_invoice.php?id=' . $booking_id);
  exit;
} else {
  echo "Invalid request method.";
  exit;
}
?>



