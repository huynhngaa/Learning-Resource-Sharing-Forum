<?php
session_start();
include( './includes/connect.php' );

if (isset($_GET['postId'])) {
    
    $postId = $_GET['postId'];
    $an_hien =  $_GET['an_hien'];

    $parts = explode('|', $postId);
    $bv = $parts[0]; // Giá trị của bv_ma
    $tt = $parts[1]; // Giá trị của ghi_chu

    if( $tt == null){
        $tt = 3;
    }

    $user = $_SESSION['Admin'];

    $kt="select * from kiem_duyet where bv_ma = '$postId'";
    $result_kt = mysqli_query($conn,$kt);
    $row_kt = mysqli_fetch_assoc($result_kt);
   
        if(mysqli_num_rows($result_kt) > 0){
            if($an_hien == '5'){
                $trangthai = "UPDATE kiem_duyet SET ghi_chu = '$an_hien' WHERE bv_ma = '$bv'";
                mysqli_query($conn, $trangthai);  
            }else{
                $trangthai = "UPDATE kiem_duyet SET ghi_chu = '' WHERE bv_ma = '$bv'";
                mysqli_query($conn, $trangthai);  
            }
           
        }   
   
}

?>