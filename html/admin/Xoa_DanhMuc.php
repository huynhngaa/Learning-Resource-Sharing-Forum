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
    $this_dm_ma = $_GET['this_dm_ma'];

    // $bai_viet="SELECT * FROM  bai_viet where dm_ma= '$this_dm_ma'";
    // $result_bai_viet = mysqli_query($conn,$bai_viet);
    // $row_bai_viet = mysqli_fetch_assoc($result_bai_viet);

    try
    {
        

        $xoa_cap_danh_muc="DELETE FROM danhmuc_phancap where dm_cha= '$this_dm_ma' or dm_con= '$this_dm_ma'";
        mysqli_query($conn,$xoa_cap_danh_muc); 

        $xoa_danh_muc="DELETE FROM danh_muc where dm_ma= '$this_dm_ma'";
        mysqli_query($conn,$xoa_danh_muc); 

        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";  
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('Dữ liệu đang được sử dụng! Không thể xóa!');</script>"; 
    }
    header("Refresh: 0;url=QL_DanhMuc.php");  
   

?>