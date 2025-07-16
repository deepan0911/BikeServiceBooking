<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
require_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Email Config
$fromEmail = 'deepann2004@gmail.com';
$fromName = 'Bike Service Center';
$mailServer = 'smtp.gmail.com';
$mailPort = 587;
$mailUsername = 'deepann2004@gmail.com';
$mailPassword = 'cgdj rdqv tkur eeeu'; // Use Gmail App Password

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = htmlspecialchars(trim($_POST["name"]));
    $mobile = htmlspecialchars(trim($_POST["mobile"]));
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
    $complaint = htmlspecialchars(trim($_POST["complaint"]));
    $vehicle_number = htmlspecialchars(trim($_POST["vehicle_number"]));
    $service_date = htmlspecialchars(trim($_POST["service_date"]));

    if (!$email) {
        die("Invalid email address.");
    }

    // Check session or handle as guest
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($_SESSION['user_id'])) {
        $name = $_SESSION['name'] ?? $name;
        $mobile = $_SESSION['mobile'] ?? $mobile;
        $email = $_SESSION['email'] ?? $email;
        $user_id = $_SESSION['user_id'];
    } else {
        // Check if user already exists in DB
        $query = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user_id = $result->fetch_assoc()['id'];
        } else {
            // Insert new user
            $query = "INSERT INTO users (name, mobile, email) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $name, $mobile, $email);
            if ($stmt->execute()) {
                $user_id = $conn->insert_id;
            } else {
                die("Error inserting user data: " . $conn->error);
            }
        }
    }

    // Validate service time
    $timestamp = strtotime($service_date);
    if (!$timestamp) {
        die("Invalid service date format.");
    }

    $hour = date('H', $timestamp);
    if ($hour < 9 || $hour > 17) {
        die("Booking time must be between 9am and 5pm.");
    }

    // Check if the selected date/time is already booked
    $query = "SELECT * FROM bookings WHERE service_date = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $service_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("This date and time are already booked. Please choose another.");
    }

    // Insert booking
    $query = "INSERT INTO bookings (user_id, vehicle_number, complaint, service_date) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("isss", $user_id, $vehicle_number, $complaint, $service_date);

    if ($stmt->execute()) {
        // Send confirmation email
        $subject = "Booking Confirmation - $name";
        $message = "Dear $name,\n\nYour bike service booking has been confirmed.\n\n"
                 . "Booking Details:\n"
                 . "- Name: $name\n"
                 . "- Email: $email\n"
                 . "- Vehicle Number: $vehicle_number\n"
                 . "- Complaint: $complaint\n"
                 . "- Service Date: $service_date\n\n"
                 . "Thank you for choosing our services.\n\nBest regards,\nBike Service Center";

        $mail = new PHPMailer(true);
        try {
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

            $mail->send();
            echo "Booking successful! Confirmation email sent to $email.";
        } catch (Exception $e) {
            echo "Booking successful, but email failed: " . $mail->ErrorInfo;
        }
    } else {
        echo "Error booking service: " . $conn->error;
    }
} else {
    echo "Invalid request method.";
}

$conn->close();
?>
