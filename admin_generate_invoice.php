<?php
require_once 'config.php';

// Get invoice ID from the query string
$invoice_id = $_GET['id'];

// Get today's date
$todays_date = date('d-m-Y');

// Database queries
$stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->bind_param("i", $invoice['booking_id']);
$stmt->execute();
$result = $stmt->get_result();
$booking = $result->fetch_assoc();

$stmt = $conn->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
$stmt->bind_param("i", $invoice_id);
$stmt->execute();
$result = $stmt->get_result();
$invoice_items = $result->fetch_all(MYSQLI_ASSOC);

$stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $booking['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Get admin details
// Get admin details
$stmt = $conn->prepare("SELECT email, mobile_no FROM admins LIMIT 1");
if (!$stmt) {
    die("SQL Error: " . $conn->error); // Display SQL error
}
$stmt->execute();
$result = $stmt->get_result();
$admin = $result->fetch_assoc();


// Include TCPDF
require_once('TCPDF-6.7.5/tcpdf.php');


// Create a new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Add a page to the PDF
$pdf->AddPage();
$pdf->SetFont('dejavusans', 'B', 25);
$pdf->Cell(0, 10, 'BIKE SERVICE INVOICE', 0, 1, 'C');
$pdf->Ln(5);

// Set font for date and invoice ID
$pdf->SetFont('Helvetica', 'I', 10);
$pdf->Cell(0, 5, 'Date: ' . $todays_date, 0, 1, 'R');
$pdf->Cell(0, 5, 'Invoice id: ' . $invoice_id, 0, 1, 'R');
$pdf->Ln(5);

$pdf->SetFont('Helvetica', 'B', 11); // Set font to bold
$pdf->Cell(0, 5, 'Bill To:', 0, 1, 'L');

// Set font for invoice header details
$pdf->SetFont('Helvetica', '', 10);
$pdf->Cell(0, 5, 'User id: ' . $user['id'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Customer Name: ' . $user['name'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Vehicle Number: ' . $booking['vehicle_number'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Mobile: ' . $user['mobile'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Email: ' . $user['email'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Service Date: ' . $booking['service_date'], 0, 1, 'L');

// Add some spacing
$pdf->Ln(10);

// Table header with S.No column
$pdf->SetFont('Helvetica', 'B', 11);
$pdf->SetFillColor(200, 220, 225); // Light blue background for header
$pdf->Cell(15, 10, 'S.No', 1, 0, 'C', 1); // New S.No column
$pdf->Cell(60, 10, 'Description', 1, 0, 'C', 1);
$pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Unit Price', 1, 0, 'C', 1);
$pdf->Cell(40, 10, 'Total', 1, 1, 'C', 1); // Line break after last column

// Reset font for table content
$pdf->SetFont('Helvetica', '', 11);

// Initialize serial number
$s_no = 1;

// Loop through invoice items and add them to the table
foreach ($invoice_items as $item) {
    $pdf->Cell(15, 10, $s_no++, 1, 0, 'C'); // Display S.No
    $pdf->Cell(60, 10, $item['description'], 1, 0, 'L');
    $pdf->Cell(30, 10, $item['quantity'], 1, 0, 'C');
    $pdf->Cell(40, 10, number_format($item['price'], 2), 1, 0, 'R');
    $pdf->Cell(40, 10, number_format($item['total'], 2), 1, 1, 'R'); // Line break
}

// Add the total amount
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(145, 10, 'Total', 1, 0, 'R'); // Label aligned to the right
$pdf->Cell(40, 10, number_format($invoice['total'], 2), 1, 1, 'R'); // Total amount aligned to the right

// Add a line break before the admin contact details
$pdf->Ln(90);

// Admin Contact Details section
$pdf->SetFont('Helvetica', 'B', 11);
$pdf->Cell(0, 5, 'Contact Address :', 0, 1, 'L'); // Section title
$pdf->Ln(5);
$pdf->SetFont('Helvetica', '', 10);
$pdf->Cell(0, 5, 'Email: ' . $admin['email'], 0, 1, 'L');
$pdf->Cell(0, 5, 'Mobile: ' . $admin['mobile_no'], 0, 1, 'L');

$file_path = $_SERVER['DOCUMENT_ROOT'] . 'invoices/invoice_' . $invoice_id . '.pdf';
$pdf->Output($file_path, 'F');

// Check if file was saved successfully
if (file_exists($file_path)) {
    echo "Invoice saved successfully!";
    // Open the PDF 
    
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="invoice_' . $invoice_id . '.pdf"');
    readfile($file_path);
    exit;
} else {
    echo "Error saving invoice!";
}


