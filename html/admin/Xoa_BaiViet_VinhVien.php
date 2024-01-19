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
    $this_bv_ma = $_GET['this_bv_ma'];

   
    try
    {
        // $xoa_bai_viet="DELETE FROM bai_viet where bv_ma= '$this_bv_ma'";
        // mysqli_query($conn,$xoa_bai_viet); 

        $delete_query = "DELETE FROM kiem_duyet WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);

        $delete_query = "DELETE FROM tai_lieu WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);

        $delete_query = "DELETE FROM lich_su_xem WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);

        $delete_query = "DELETE FROM bai_viet WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);


        // $kt="select *from kiem_duyet where bv_ma = '$this_bv_ma'";
        // $result_kt = mysqli_query($conn,$kt);
        // $row_kt = mysqli_fetch_assoc($result_kt);

        // if(mysqli_num_rows($result_kt) == 0){
        //     $duyet_bv = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian) VALUE ('$this_bv_ma', '".$_SESSION['Admin']."', '4', now())";
        //     mysqli_query($conn, $duyet_bv);  
           
        // }else{
        //     $huy_bv = "UPDATE kiem_duyet SET tt_ma = '4', nd_username= '".$_SESSION['Admin']."', thoigian = now() WHERE bv_ma = '$this_bv_ma'";
        //     mysqli_query($conn, $huy_bv);  
        // }


        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";  
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('Dữ liệu đang được sử dụng! Không thể xóa!');</script>"; 
    }
    header("Refresh: 0;url=BaiViet_DaXoa.php");  
   

?>