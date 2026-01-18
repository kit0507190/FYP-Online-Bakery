<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bakeryhouse"; // 👈 请确保这里与 phpMyAdmin 中的名字一致
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }
$conn->set_charset("utf8mb4");
?>