<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in'])) {
  header('Location: admin_login.php');
  exit;
}

$booking_id = $_GET['id'];

// Retrieve booking details
$query = "SELECT * FROM bookings WHERE id = '$booking_id'";
$result = $conn->query($query);
$booking = $result->fetch_assoc();

// Check if invoice already exists
$query = "SELECT * FROM invoices WHERE booking_id = '$booking_id'";
$result = $conn->query($query);
$invoice_exists = $result->num_rows > 0;
$existing_items = array();
if ($invoice_exists) {
  $existing_invoice = $result->fetch_assoc();
  $existing_items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = '$existing_invoice[id]'");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Invoice</title>
  <link rel="stylesheet" href="styles1.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
  <main>
    <h1>Add Invoice for Booking ID: <?php echo $booking_id; ?></h1>
    <div class="booking-details-main">
      <h2>Booking Details:</h2>
      <table class="booking-details-table">
        <tr>
          <th>Vehicle Number:</th>
          <td><?php echo $booking['vehicle_number']; ?></td>
        </tr>
        <tr>
          <th>Complaint:</th>
          <td><?php echo $booking['complaint']; ?></td>
        </tr>
        <tr>
          <th>Service Date:</th>
          <td><?php echo $booking['service_date']; ?></td>
        </tr>
      </table>
    </div>
    <?php if ($invoice_exists) { ?>
      <div class="invoice-details-main">
        <h2>Existing Invoice</h2>
        <table class="invoice-details-table">
          <tr>
            <th>S.No</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
          <?php 
          $existing_items = $conn->query("SELECT * FROM invoice_items WHERE invoice_id = '$existing_invoice[id]'");
          $serial_number = 1;
          $total = 0;
          while ($item = $existing_items->fetch_assoc()) { 
            $total += $item['total'];
          ?>
          <tr>
            <td><?php echo $serial_number; ?></td>
            <td><?php echo $item['description']; ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo $item['price']; ?></td>
            <td><?php echo $item['total']; ?></td>
          </tr>
          <?php 
            $serial_number++;
          } 
          ?>
          <tr style="font-weight: bold;">
            <td colspan="4">Total:</td>
            <td><?php echo $total; ?></td>
          </tr>
        </table>
        <div class="action-button-container" style="text-align: center; margin-top: 20px;">
          <button type="button" class="send-invoice-button" onclick="sendInvoice()">Send Invoice</button>
          <button type="button" class="view-invoice-button" onclick="viewInvoice()">View Invoice</button>

        </div>
      </div>
    <?php } ?>
    <h2>Add New Invoice Items</h2>
    <form action="admin_save_invoice.php" method="POST">
      <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
      <table id="invoiceTable">
        <thead>
          <tr>
            <th>S.No</th>
            <th>Description</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><input type="text" name="description[]" required class="input-field"></td>
            <td><input type="number" name="quantity[]" required oninput="calculateTotal(this)" class="input-field"></td>
            <td><input type="number" step="0.01" name="price[]" required oninput="calculateTotal(this)" class="input-field"></td>
            <td class="total-cell">0.00</td>
            <td><button type="button" onclick="addRow()">+</button></td>
          </tr>
        </tbody>
      </table>
      <div class="action-button-container" style="text-align: center; margin-top: 20px;">
        <button type="submit" class="save-button">Save Invoice</button>
        
      </div>
    </form>
  </main>
  <script>
    let rowCounter = 2;
    function addRow() {
      const tableBody = document.getElementById('invoiceTable').getElementsByTagName('tbody')[0];
      const newRow = tableBody.insertRow();
      newRow.innerHTML = `
        <td>${rowCounter}</td>
        <td><input type="text" name="description[]" required class="input-field"></td>
        <td><input type="number" name="quantity[]" required oninput="calculateTotal(this)" class="input-field"></td>
        <td><input type="number" step="0.01" name="price[]" required oninput="calculateTotal(this)" class="input-field"></td>
        <td class="total-cell">0.00</td>
        <td><button type="button" onclick="removeRow(this)">-</button></td>
      `;
      rowCounter++;
    }
    
    function removeRow(button) {
      const row = button.parentNode.parentNode;
      row.parentNode.removeChild(row);
      rowCounter--;
      updateRowNumbers();
    }
    
    function updateRowNumbers() {
      const rows = document.getElementById('invoiceTable').getElementsByTagName('tbody')[0].getElementsByTagName('tr');
      for (let i = 0; i < rows.length; i++) {
        rows[i].getElementsByTagName('td')[0].innerText = i + 1;
      }
    }
    
    function calculateTotal(input) {
      const row = input.parentNode.parentNode;
      const quantity = row.getElementsByTagName('input')[1].value;
      const price = row.getElementsByTagName('input')[2].value;
      const total = quantity * price;
      row.getElementsByClassName('total-cell')[0].innerText = total.toFixed(2);
    }

    function viewInvoice() {
    var bookingId = <?php echo $booking_id; ?>;
    var existingInvoiceId = <?php echo $existing_invoice['id']; ?>;
    window.open('admin_generate_invoice.php?id=' + existingInvoiceId, '_blank');
  }

    
  function sendInvoice() {
    var bookingId = <?php echo $booking_id; ?>;
    var existingInvoiceId = <?php echo $existing_invoice['id']; ?>;

    $.ajax({
        type: "POST",
        url: "admin_send_invoice.php",
        data: {
            booking_id: bookingId,
            invoice_id: existingInvoiceId
        },
        success: function(response) {
            if (response === 'success') {
                alert("Invoice sent successfully!");
            } else {
                alert("Error sending invoice: " + response);
            }
        },
        error: function(xhr, status, error) {
            console.log(xhr.responseText);
        }
    });
}


  </script>
</body>
</html>



