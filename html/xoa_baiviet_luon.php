 <!-- Thông báo -->
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="sweetalert2.all.min.js"></script>

 <?php
    include("include/conn.php");
    $this_bv_ma = $_GET['this_bv_ma'];
    $id = $_GET['id'];

   
    try
    {
      

        $delete_query = "DELETE FROM kiem_duyet WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);

        $delete_query = "DELETE FROM tai_lieu WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);

        $delete_query = "DELETE FROM lich_su_xem WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);

        $delete_query = "DELETE FROM bai_viet WHERE bv_ma = '$this_bv_ma'";
        mysqli_query($conn, $delete_query);


        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";  
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('Dữ liệu đang được sử dụng! Không thể xóa!');</script>"; 
    }
    header("Refresh: 0;url=baiviet_daxoa.php?id=".$id);  
   

?>