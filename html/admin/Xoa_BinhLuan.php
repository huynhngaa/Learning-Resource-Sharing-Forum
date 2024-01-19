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
    $this_bl_ma = $_GET['this_bl_ma'];

    // $bai_viet="SELECT * FROM  bai_viet where dm_ma= '$this_dm_ma'";
    // $result_bai_viet = mysqli_query($conn,$bai_viet);
    // $row_bai_viet = mysqli_fetch_assoc($result_bai_viet);

    try
    {
        


        $xoa_ls="DELETE FROM lich_su_xem where bl_ma= '$this_bl_ma'";
        mysqli_query($conn,$xoa_ls); 



        $xoa_rep_bl="DELETE FROM rep_bl where bl_cha = '$this_bl_ma' or bl_con = '$this_bl_ma'";
        mysqli_query($conn,$xoa_rep_bl); 

        $xoa_bin_luan="DELETE FROM binh_luan where bl_ma= '$this_bl_ma'";
        mysqli_query($conn,$xoa_bin_luan); 


        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";  
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('Dữ liệu đang được sử dụng! Không thể xóa!');</script>"; 
    }
    header("Refresh: 0;url=QL_BinhLuan.php");  
   

?>