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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $value = $_POST['value'];
    $percentage = $_POST['percentage'];

    // Hapus data lama
    $delete_sql = "DELETE FROM current_soil_moisture";
    $conn->query($delete_sql);

    // Simpan data baru
    $sql = "INSERT INTO current_soil_moisture (value, percentage) VALUES ('$value', '$percentage')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
