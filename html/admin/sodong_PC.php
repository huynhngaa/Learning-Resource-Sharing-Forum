<?php
     session_start();
     include("./includes/connect.php");
     if(!isset($_SESSION['Admin'])){
         echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
         header("Refresh: 0;url=login.php");  
     }else{}
 
  
     $so_dong = 5;
     $danhmuc = 'Tất cả';
     $sap_xep = 'DESC';
     
     if (isset($_GET['sodong'])) {
         $so_dong = intval($_GET['sodong']);
     }
 
     if (isset($_GET['danhmuc'])) {
         $danhmuc = $_GET['danhmuc'];
     }
     
     
     
     if (isset($_GET['sort'])) {
         if ($_GET['sort'] === "asc") {
             $sap_xep = "asc";
         } elseif ($_GET['sort'] === "desc") {
             $sap_xep = "desc";
         }
     }
     
 
     if ($danhmuc == "Tất cả") {
         $danh_muc = "SELECT DISTINCT ( a.nd_username),nd_hoten
                         FROM quan_ly a, nguoi_dung b, danh_muc c
                         where a.nd_username = b.nd_username 
                         and c.dm_ma=a.dm_ma
                         order by a.nd_username $sap_xep 
                         LIMIT $so_dong";
     }else{
         $danh_muc = "SELECT DISTINCT ( a.nd_username),nd_hoten
                        FROM quan_ly a, nguoi_dung b, danh_muc c
                         where a.nd_username = b.nd_username 
                         and c.dm_ma=a.dm_ma
                         and a.dm_ma in('$danhmuc')
                         order by a.nd_username $sap_xep 
                         LIMIT $so_dong";
     }
 
    $result_danh_muc = mysqli_query($conn,$danh_muc);

     unset($_SESSION['sl_dong']);
    $sl_dong_hientai = mysqli_num_rows($result_danh_muc);
    $_SESSION['sl_dong'] = $sl_dong_hientai;

    if($danhmuc == 'Tất cả'){
        $kq="SELECT count(DISTINCT(nd_username)) as tong FROM quan_ly";
    }else{
        $kq="SELECT count(DISTINCT(nd_username)) as tong FROM quan_ly where dm_ma = '$danhmuc'";
    }
   
    $result_kq = mysqli_query($conn,$kq);
    $row_kq = mysqli_fetch_assoc($result_kq);
    $_SESSION['tong_sd'] = array();
    $_SESSION['tong_sd'] = $row_kq['tong'];
?>
<input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
<?php

     $stt = 0;
     while ($row_danh_muc = mysqli_fetch_array($result_danh_muc)) {
         $stt = $stt + 1;
         
 ?>
<tr>
    <td>
        <input class="form-check-input check-item" name="check[]" type="checkbox" value="<?php echo $row_danh_muc['nd_username'] ?>">
    </td>

    <td class="row-bai-viet"> <?php echo $stt ?> </td>
    <td><strong>
            <?php 
             echo  $row_danh_muc['nd_hoten']; 
         ?>
        </strong>
    </td>

    <td>
        <?php 
             $danh_muc_ql = "SELECT *
                             FROM nguoi_dung b
                             JOIN quan_ly a ON a.nd_username = b.nd_username
                             JOIN danh_muc c ON c.dm_ma = a.dm_ma";
             
             $result_danh_muc_ql = mysqli_query($conn, $danh_muc_ql);
             
           
             // Initialize an array to store the results
             $danhmuc_ql = array();
             
             // Fetch associative array
             while ($row_danh_muc_ql = mysqli_fetch_assoc($result_danh_muc_ql)) {
             // Use user as the key to group by user
             $danhmuc_ql[$row_danh_muc_ql['nd_username']]['hoten'] = $row_danh_muc_ql['nd_hoten'];
             $danhmuc_ql[$row_danh_muc_ql['nd_username']]['danh_muc'][] = array(
             'dm_ma' => $row_danh_muc_ql['dm_ma'],
             'dm_ten' => $row_danh_muc_ql['dm_ten']
             );
             }
             
             // Print or use the $userArray as needed
             foreach ($danhmuc_ql as $username => $userData) {
                 if($username == $row_danh_muc['nd_username']){
                     // echo "Username: " . $username . "<br>";
                     // echo "User: " . $userData['hoten'] . "<br>";
                     // echo "Categories: <br>";
                     foreach ($userData['danh_muc'] as $category) {
                     echo "- " . $category['dm_ten'] . "<br>";
                     }
                     echo "<br>";
                 }else{

                 }
             }
            
         ?>

    </td>
    <td>

        <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
            href="Sua_PhanCong.php?username=<?php echo $row_danh_muc['nd_username'];?>">
            <i class="bx bx-edit-alt me-1"></i>
        </a>
        <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="#"
            onclick="Xoa_Danhmuc('<?php echo $row_danh_muc['nd_username']?>');">
            <i class="bx bx-trash me-1"></i>
        </a>

    </td>
</tr>
<?php } ?>