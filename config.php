<?php
$servername = "sql208.infinityfree.com";
$username = "if0_37559493";
$password = "cR7Lv3xfnJ";
$dbname = "if0_37559493_bike_service";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
