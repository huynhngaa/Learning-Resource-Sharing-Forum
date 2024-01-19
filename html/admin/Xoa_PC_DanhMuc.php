 <!-- Thông báo -->
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="sweetalert2.all.min.js"></script>

 <?php
     session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_user = $_GET['this_user'];

    // $bai_viet="SELECT * FROM  bai_viet where dm_ma= '$this_dm_ma'";
    // $result_bai_viet = mysqli_query($conn,$bai_viet);
    // $row_bai_viet = mysqli_fetch_assoc($result_bai_viet);

    try
    {
        $xoa_pc_danh_muc="DELETE FROM quan_ly where nd_username= '$this_user'";
        mysqli_query($conn,$xoa_pc_danh_muc); 
        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";  
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('Dữ liệu đang được sử dụng! Không thể xóa!');</script>"; 
    }
    header("Refresh: 0;url=PCQL_DanhMuc.php");  
   

?>