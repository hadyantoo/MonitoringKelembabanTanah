<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "sensor_data";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT value, percentage, timestamp FROM current_soil_moisture ORDER BY timestamp DESC LIMIT 1";
$result = $conn->query($sql);

$current_data = null;
if ($result->num_rows > 0) {
    $current_data = $result->fetch_assoc();
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($current_data);
?>
