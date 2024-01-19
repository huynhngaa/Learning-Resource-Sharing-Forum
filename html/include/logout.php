<?php 
session_start();
unset($_SESSION['user']);
//header('location:login.php');
$current_page = $_SERVER['HTTP_REFERER'];
    
// Chuyển hướng người dùng đến trang hiện tại
header("Location: $current_page");

?>