<?php
include "conn.php";


if (isset($_POST['repbl'])) {
    
  

  $bv = $_POST['bv-ma'];
  $bl_cha = $_POST['bl-ma'];
  $username = $_POST['username'];
  $noidung = $_POST['noidung'];


  // Thêm dữ liệu vào bảng 'bai_viet'
  $sql1 = "INSERT INTO binh_luan(bv_ma, nd_username, bl_noidung,trangthai) 
          VALUES ($bv, '$username', '$noidung',3)";
  $res1 = mysqli_query($conn, $sql1);
 $sql2 = "INSERT INTO rep_bl  VALUES ($bl_cha, @@identity)";
$res2 = mysqli_query($conn, $sql2);

  $_SESSION['repbl'] = true;
  $current_page = $_SERVER['HTTP_REFERER'];

  header("Location: $current_page");
}

?>
