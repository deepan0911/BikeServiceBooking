
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'config.php';
require_once 'vendor/autoload.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Sanitize user input
  $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);

  // Email settings
  $to = 'deepann2004@gmail.com';
  $subject = 'Bike Service Centre Website';

  // Email body
  $message_body = "
    <h2>Bike Service Centre Website</h2>
    <p>Name: $name</p>
    <p>Email: $email</p>
    <p>Message: $message</p>
  ";

  $mail = new PHPMailer(true);

  // SMTP settings
  $mail->isSMTP();
  $mail->Host = 'smtp.gmail.com';
  $mail->SMTPAuth = true;
  $mail->Username = 'deepann2004@gmail.com';
  $mail->Password = 'ezzg qqfa ybdz vwsn';
  $mail->Port = 587;

  // Email content
  $mail->setFrom($email, $name);
  $mail->addAddress($to);
  $mail->Subject = $subject;
  $mail->Body = $message_body;
  $mail->AltBody = strip_tags($message_body);
  $mail->isHTML(true);

  // Send email
  try {
    $mail->send();
    echo "
      <script>
        alert('Thank you for contacting us! Your message has been sent successfully.');
        window.location.href = 'index.php';
      </script>
    ";
    exit;
  } catch (Exception $e) {
    echo 'Error sending email: ', $e->getMessage();
  }
} else {
  echo 'Invalid request!';
}
?>

