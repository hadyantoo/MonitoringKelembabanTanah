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

$sql_historical = "SELECT value, percentage, timestamp FROM historical_soil_moisture ORDER BY timestamp DESC";
$result_historical = $conn->query($sql_historical);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Soil Moisture Data</title>
    <script>
        function fetchCurrentData() {
            fetch('current_data.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-value').innerText = data.value;
                    document.getElementById('current-percentage').innerText = data.percentage + '%';
                    document.getElementById('current-timestamp').innerText = data.timestamp;
                });
        }

        setInterval(fetchCurrentData, 3000);
    </script>
</head>
<body>
    <h1>Soil Moisture Data</h1>

    <h2>Current Data</h2>
    <p>Value: <span id="current-value"><?php echo $current_data['value']; ?></span></p>
    <p>Percentage: <span id="current-percentage"><?php echo $current_data['percentage']; ?>%</span></p>
    <p>Timestamp: <span id="current-timestamp"><?php echo $current_data['timestamp']; ?></span></p>

    <h2>Historical Data</h2>
    <table border="1">
        <tr>
            <th>Value</th>
            <th>Percentage</th>
            <th>Timestamp</th>
        </tr>
        <?php
        if ($result_historical->num_rows > 0) {
            while($row = $result_historical->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["value"] . "</td>";
                echo "<td>" . $row["percentage"] . "</td>";
                echo "<td>" . $row["timestamp"] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No data found</td></tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
$conn->close();
?>
