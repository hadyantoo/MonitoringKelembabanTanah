<?php
// post_data.php

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['value'])) {
        $soilMoistureValue = $_GET['value'];

        
        function getCurrentTimeWIB() {
            $url = 'http://worldtimeapi.org/api/timezone/Asia/Jakarta'; // Endpoint WorldTimeAPI untuk Jakarta
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);

            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['datetime'])) {
                    // Konversi waktu dari UTC ke WIB (UTC+7)
                    $datetimeUtc = new DateTime($data['datetime']);
                    $datetimeUtc->setTimezone(new DateTimeZone('Asia/Jakarta'));
                    return $datetimeUtc->format('H:i:s'); // Format hanya jam, menit, dan detik
                }
            }
            return false;
        }

        // Contoh penggunaan fungsi
        $currentDateTime = getCurrentTimeWIB();

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

        // Query untuk memasukkan data
        $sql = "INSERT INTO data (nilai,waktu) VALUES ($soilMoistureValue,'$currentDateTime')";

        if ($conn->query($sql) === TRUE) {
            echo "Data received and stored successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        // Menutup koneksi
        $conn->close();
    } else {
        echo "No value received";
    }
}
?>
