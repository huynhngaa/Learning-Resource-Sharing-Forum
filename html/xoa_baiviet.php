 <!-- Thông báo -->
 <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
 <script src="sweetalert2.all.min.js"></script>

 <?php
    
    include("include/conn.php");

    $this_bv_ma = $_GET['this_bv_ma'];
    $id = $_GET['id'];
    $tt = $_GET['tt'];

    if( $tt == null){
        $tt = 3;
    }

   
    try
    {

        $kt="select *from kiem_duyet where bv_ma = '$this_bv_ma'";
        $result_kt = mysqli_query($conn,$kt);
        $row_kt = mysqli_fetch_assoc($result_kt);

        if(mysqli_num_rows($result_kt) == 0){
            $xoa_bai_viet = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian, ghi_chu) VALUE ('$this_bv_ma', '$id', '4', now(), '$tt')";
            mysqli_query($conn, $xoa_bai_viet);
           
        }else{
            $huy_bv = "UPDATE kiem_duyet SET tt_ma = '4', nd_username='$id', ghi_chu='$tt' , thoigian = now() WHERE bv_ma = '$this_bv_ma'";
            mysqli_query($conn, $huy_bv); 
        }


        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";  
    }catch(mysqli_sql_exception $e){
        echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu!');</script>"; 
    }
    header("Refresh: 0;url=baiviet.php?id=".$id);  
   

?>