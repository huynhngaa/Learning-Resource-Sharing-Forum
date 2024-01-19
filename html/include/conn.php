<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luanvan";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Không kết nối: " . $conn->connect_error);
} 
mysqli_set_charset($conn, 'UTF8');
session_start();
?>