<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_username = $_GET['this_username'];
    $vt = $_GET['vt'];
    try {
        // Thử xóa người dùng
        $xoa_nguoi_dung = "DELETE FROM nguoi_dung WHERE nd_username= '$this_username'";
        mysqli_query($conn, $xoa_nguoi_dung);
        echo "<script>alert('Bạn đã xóa người dùng thành công!');</script>";
        header("Refresh: 0;url=QL_NguoiDung.php?vt=" . urlencode($vt));
    } catch (mysqli_sql_exception $e) {
        echo "<script>alert('Dữ liệu đang được sử dụng không thể xóa! Vui lòng kiểm tra lại');</script>";
        header("Refresh: 0;url=QL_NguoiDung.php?vt=" . urlencode($vt));
    }
   

?>

