<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_date = $_POST["service_date"];
    $query = "SELECT * FROM bookings WHERE service_date = '$service_date'";
    $result = $conn->query($query);
    if ($result->num_rows > 0) {
        echo "Date and time are not available.";
    } else {
        echo "Date and time are available.";
    }
}
$conn->close();
?>