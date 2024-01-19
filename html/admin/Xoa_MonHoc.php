<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_mh_ma = $_GET['this_mh_ma'];

    // $danh_muc="SELECT * FROM danh_muc where mh_ma= '$this_dm_ma'";
    // $result_danh_muc = mysqli_query($conn,$danh_muc);
    // $row_danh_muc = mysqli_fetch_assoc($result_danh_muc);

    try
    {
        $xoa_mon_hoc="DELETE FROM mon_hoc where mh_ma= '$this_mh_ma'";
        mysqli_query($conn,$xoa_mon_hoc); 
        echo "<script>alert('Bạn đã xóa môn học thành công!');</script>";  
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('Dữ liệu đang được sử dụng! Không thể xóa!');</script>"; 
    }
	header("Refresh: 0;url=QL_MonHoc.php");  
   

?>

