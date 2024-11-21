<?php
require_once 'config.php';
require_once 'vendor/autoload.php'; 
// require_once 'PHPMailer/PHPMailer.php';
// require_once 'PHPMailer/SMTP.php';
// require_once 'PHPMailer/Exception.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $invoice_id = $_POST['invoice_id'];

    // Generate invoice PDF
    $pdf_file_path = 'invoices/invoice_' . $invoice_id . '.pdf';

    // Get user's email
    $query = "SELECT email FROM users WHERE id = (SELECT user_id FROM bookings WHERE id = '$booking_id')";
    $result = $conn->query($query);
    $user = $result->fetch_assoc();
    $user_email = $user['email'];

    // Send PDF to user's email
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'deepann2004@gmail.com';
    $mail->Password = 'ezzg qqfa ybdz vwsn';
    $mail->Port = 587;
    $mail->SMTPSecure = 'tls';
    $mail->setFrom('deepann2004@gmail.com', 'Bike Service Centre');
    $mail->addAddress($user_email);
    $mail->addAttachment($pdf_file_path);
    $mail->isHTML(true);
    $mail->Subject = 'Invoice for Booking ID: ' . $booking_id;
    $mail->Body = "
        <p>Service complete notification.</p>
        <p>Please find the attached invoice for your booking.</p>
    ";


    try {
        $mail->send();
        echo 'success';
        // Update booking status
        $stmt = $conn->prepare("UPDATE bookings SET invoice_sent = 'sent' WHERE id = ?");
        $stmt->bind_param("i", $booking_id);
        $stmt->execute();
    } catch (PHPMailer\PHPMailer\Exception $e) {
        echo 'Error sending email: ' . $e->getMessage();
    }
} else {
    echo 'Invalid request method';
}



