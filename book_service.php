
<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once 'config.php';

$fromEmail = 'deepann2004@gmail.com';
$fromName = 'Bike Service Center';
$mailServer = 'smtp.gmail.com';
$mailPort = 587;
$mailUsername = 'deepann2004@gmail.com';
$mailPassword = 'cgdj rdqv tkur eeeu';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate user input
    $name = filter_var($_POST["name"], FILTER_SANITIZE_STRING);
    $mobile = filter_var($_POST["mobile"], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $complaint = filter_var($_POST["complaint"], FILTER_SANITIZE_STRING);
    $vehicle_number = filter_var($_POST["vehicle_number"], FILTER_SANITIZE_STRING);
    $service_date = filter_var($_POST["service_date"], FILTER_SANITIZE_STRING);

    // Check if user is logged in
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        $name = $_SESSION['name'];
        $mobile = $_SESSION['mobile'];
        $email = $_SESSION['email'];
        $user_id = $_SESSION['user_id']; // Use existing user ID

        echo "Session Contents:<br>";
        echo "Name: $name<br>";
        echo "Mobile: $mobile<br>";
        echo "Email: $email<br>";
        echo "User ID: $user_id<br>";
    
    } else {
        // Check if user already exists
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_id = $result->fetch_assoc()['id'];
        } else {
            // Insert new user data
            $query = "INSERT INTO users (name, mobile, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $name, $mobile, $email);
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
            } else {
                echo "Error inserting user data: " . $conn->error;
                exit;
            }
        }
    }

    // Convert datetime to timestamp
    $timestamp = strtotime($service_date);
    // Get hour from timestamp
    $hour = date('H', $timestamp);

    // Check if booking time is between 9am and 5pm
    if ($hour >= 9 && $hour <= 17) {
        // Check if date and time are already booked
        $query = "SELECT * FROM bookings WHERE service_date = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $service_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Date and time are already booked. Please select another date and time.";
        } else {
            // Insert booking data into database
            $query = "INSERT INTO bookings (user_id, vehicle_number, complaint, service_date) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isss", $user_id, $vehicle_number, $complaint, $service_date);

            if ($stmt->execute()) {
                // Send booking details to user's email
                $subject = "Booking Confirmation - $name";
                $message = "Dear $name,\nYour booking has been confirmed!\n\nBooking Details:\n- Name: $name\n- Email: $email\n- Vehicle Number: $vehicle_number\n- Complaint: $complaint\n- Service Date: $service_date\n\nThank you for choosing our services.\n\nBest regards, Service center";

                // Create PHPMailer instance
                $mail = new PHPMailer();
                $mail->isSMTP();
                $mail->Host = $mailServer;
                $mail->Port = $mailPort;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->Username = $mailUsername;
                $mail->Password = $mailPassword;
                $mail->setFrom($fromEmail, $fromName);
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->Body = $message;

                if ($mail->send()) {
                    echo "Booking successful! Confirmation email sent to $email.";
                } else {
                    echo "Booking successful! Error sending confirmation email: " . $mail->ErrorInfo;
                }
                } else {
                    echo "Error booking service: " . $conn->error;
                }
                } 
                } else {
                    echo "Booking time must be between 9am and 5pm.";
                }
                } else {
                    echo "Invalid request method.";
                }
                $conn->close();
                ?>


