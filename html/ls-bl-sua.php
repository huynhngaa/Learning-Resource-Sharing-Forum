<?php
include "include/conn.php";
if (!isset($_SESSION['user'])) {
    header("Location: 404.php");
    exit();
}

$bl_ma = $_POST['ma'];
$noidung = $_POST['noidung'];

$sql = "SELECT * FROM binh_luan WHERE bl_ma = $bl_ma";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_array($result);

if ($noidung == $row['bl_noidung']) {
    $_SESSION['sua-tb'] = true;
} else {
    $sql2 = "UPDATE binh_luan SET bl_noidung = '$noidung', trangthai =3 WHERE bl_ma = $bl_ma";
    $result2 = mysqli_query($conn, $sql2);
    $_SESSION['sua-tc'] = true;
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
