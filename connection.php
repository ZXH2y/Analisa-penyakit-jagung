<?php
$host = 'localhost';
$user = 'andri';
$password = '223280019';
$database = 'diagnosa_penyakit_jagung'; 

$connection = mysqli_connect($host, $user, $password, $database);

if (!$connection) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
