<?php
include "include/conn.php";


require 'vendor/autoload.php';

use MongoDB\Client;

if (isset($_POST['gui'])) {
    $tendangnhap = $_POST['tendangnhap'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $danhmuc_ma = $_POST['danh-muc']; 

  

    //  $tendm = $danhmuc['dm_ten'];
    // Lấy giá trị tự tạo của khóa chính sau khi thêm vào bảng 'bai_viet'
    $last_insert_id = mysqli_insert_id($conn);

 

    // Thêm dữ liệu vào bảng 'baiviet' (MongoDB)
    $client = new Client("mongodb://localhost:27017");
    $mongodb = $client->selectDatabase("Test");
    $baivietCollection = $mongodb->databaiviet;
    $noidung = strip_tags($noidung);
    $noidung = preg_replace("/[.,!?`~]/", " ", $noidung);
    $noidung = str_replace('&nbsp;', ' ', $noidung);

    $baivietData = [
        'id' => $last_insert_id,
        'noidung' => $noidung . $tieude 
    
    ];

    $baivietCollection->insertOne($baivietData);

    // Tách từ bài viết
    $pythonScript = "C:/xampp/htdocs/VnCoreNLP-master/tachtu.py";
  
    $command = "python $pythonScript";
  
    exec($command, $output, $return_var);

}
?>
