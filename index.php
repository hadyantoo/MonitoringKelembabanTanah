<?php
// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "sensor_data";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengambil nilai sensor terakhir
$sql = "SELECT nilai, waktu FROM data ORDER BY id DESC LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mengambil data hasil query
    $row = $result->fetch_assoc();
    $nilaiTerakhir = $row['nilai'];
} else {
    echo "Tidak ada data yang ditemukan";
}

// Mengambil 6 data terbaru
$sql6 = "SELECT nilai, waktu FROM data ORDER BY id DESC LIMIT 6";
$result6 = $conn->query($sql6);

$data = array();
$labels = array();

if ($result6->num_rows > 0) {
    // Memasukkan hasil query ke dalam array
    while ($row6 = $result6->fetch_assoc()) {
        $data[] = $row6['nilai'];
        $labels[] = $row6['waktu'];
    }
} else {
    echo "Tidak ada data yang ditemukan";
}

// Menutup koneksi
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafik Sederhana</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h4>Monitoring Kelembaban Tanah</h4>
        <div class="col">
            <div class="box">
                <h4>Nilai Sensor Terakhir</h4>
                <p><?php echo $nilaiTerakhir."%";?></p>
            </div>
            <div class="box">
                <h4>Grafik Sensor Kelembaban</h4>
                <div id="chart"></div>
            </div>
        </div>
    </div>

    <script>
        // Mengambil data dari PHP ke dalam JavaScript
        const data = <?php echo json_encode($data); ?>;
        const labels = <?php echo json_encode($labels); ?>;

        // Kode JavaScript untuk membuat grafik dengan data dan label yang diambil
        document.addEventListener('DOMContentLoaded', () => {
            const chart = document.getElementById('chart');

            data.forEach((value, index) => {
                const barContainer = document.createElement('div');
                barContainer.style.position = 'relative';
                barContainer.style.display = 'inline-block';

                const bar = document.createElement('div');
                bar.classList.add('bar');
                bar.style.height = `${value}px`; // Menggunakan nilai langsung untuk tinggi bar
                bar.textContent = `${value}`; // Menampilkan nilai langsung
                barContainer.appendChild(bar);

                const label = document.createElement('div');
                label.classList.add('label');
                label.textContent = labels[index];
                barContainer.appendChild(label);

                chart.appendChild(barContainer);
            });
        });
    </script>
</body>
</html>
