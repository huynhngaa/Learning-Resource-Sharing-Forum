<?php
include "conn.php";


if (isset($_POST['binhluan'])) {
    

  $bv = $_POST['bv-ma'];
  $username = $_POST['username'];
  $noidung = $_POST['noidung'];
 

  // Thêm dữ liệu vào bảng 'bai_viet'
  $sql1 = "INSERT INTO binh_luan(bv_ma, nd_username, bl_noidung, trangthai) 
          VALUES ($bv, '$username', '$noidung',3)";
  $res1 = mysqli_query($conn, $sql1);


  $_SESSION['binhluan'] = true;
  $current_page = $_SERVER['HTTP_REFERER'];

  header("Location: $current_page");
}

?>
