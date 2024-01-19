<?php
session_start();
include( './includes/connect.php' );

$so_dong = 5;
$trangthai = isset($_GET['tt']) ? $_GET['tt'] : "Tất cả";
$sap_xep = 'DESC';
$user_name = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";
$tungay = "2000-01-01";
$denngay = date('Y-m-d');

if (isset($_GET['sodong'])) {
    $so_dong = intval($_GET['sodong']);
}



if (isset($_GET['sort'])) {
    if ($_GET['sort'] === "asc") {
        $sap_xep = "asc";
    } elseif ($_GET['sort'] === "desc") {
        $sap_xep = "desc";
    }
}

    
if (isset($_GET['tungay'])) {
    $tungay = $_GET['tungay'];
}

if (isset($_GET['denngay'])) {
    $denngay = $_GET['denngay'];
}

if (isset($_GET['trangthai'])) {
    $trangthai = $_GET['trangthai'];
}

// Kiểm tra quyền truy cập
// Danh sách các trang quản lý của admin
$adminPages = array("QL_DanhMuc.php", "QL_KhoiLop.php", "QL_MonHoc.php", "QL_NguoiDung.php", "QL_BinhLuan.php", "PCQL_DanhMuc.php");

// Lấy đường dẫn hiện tại của người dùng
$currentPage = basename($_SERVER['SCRIPT_FILENAME']);

if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
    header("Refresh: 0;url=error.php");  
    exit;
}
if($_SESSION['vaitro'] == 'Super Admin'){
  
    if ($trangthai == "3") {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.* FROM bai_viet a
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            where (c.tt_ma IS NULL OR c.tt_ma != 4)
             And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            and c.tt_ma IS NULL or c.tt_ma = 3
            ORDER BY a.nd_username $sap_xep LIMIT $so_dong";
    
    } elseif ($trangthai == "1" || $trangthai == "2" ) {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.* FROM bai_viet a
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            where (c.tt_ma IS NULL OR c.tt_ma != 4)
             And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            and c.tt_ma = '$trangthai'
            ORDER BY a.nd_username $sap_xep LIMIT $so_dong";
    
    } 
    elseif ($trangthai == "5") {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.* FROM bai_viet a
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            where c.ghi_chu = '5'
            And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
           
            ORDER BY a.nd_username $sap_xep LIMIT $so_dong";
    
    } 
    elseif($trangthai == "Tất cả"){
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.* FROM bai_viet a
             LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
             LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
             LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
             where (c.tt_ma IS NULL OR c.tt_ma != 4)
             And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";
    }
    else {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.* FROM bai_viet a
             LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
             LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
             LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
             where (c.tt_ma IS NULL OR c.tt_ma != 4)
             And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            ORDER BY a.nd_username $sap_xep LIMIT $so_dong";
    }
    
}
elseif($_SESSION['vaitro'] == 'Admin'){

    if ($trangthai == "3") {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.*,b.dm_ten,f.nd_username as quanly FROM bai_viet a
                        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                        LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                        LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                        where (c.tt_ma IS NULL OR c.tt_ma != 4)
                        And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
                        and c.tt_ma IS NULL or c.tt_ma = 3
                        And f.nd_username = '$user_name'
                        ORDER BY a.nd_username $sap_xep LIMIT $so_dong";

    } elseif ($trangthai == "1" || $trangthai == "2" ) {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.*,b.dm_ten,f.nd_username as quanly FROM bai_viet a
                        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                        LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                        LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                        where (c.tt_ma IS NULL OR c.tt_ma != 4)
                        And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
                        and c.tt_ma = '$trangthai'
                        And f.nd_username = '$user_name'
                        ORDER BY a.nd_username $sap_xep LIMIT $so_dong";

    }
    elseif ($trangthai == "5") {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.*,b.dm_ten,f.nd_username as quanly FROM bai_viet a
                        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                        LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                        LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                        where c.ghi_chu = '5'
                        And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
        
                        And f.nd_username = '$user_name'
                        ORDER BY a.nd_username $sap_xep LIMIT $so_dong";

    } 
  
    elseif($trangthai == "Tất cả"){
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.*,b.dm_ten,f.nd_username as quanly FROM bai_viet a
                        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                        LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                        LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                        where (c.tt_ma IS NULL OR c.tt_ma != 4)
                        And f.nd_username = '$user_name'
                        And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
                        ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";
    }
    else {
        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.ghi_chu, d.*,b.dm_ten,f.nd_username as quanly FROM bai_viet a
                        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                        LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                        LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                        where (c.tt_ma IS NULL OR c.tt_ma != 4)
                        And f.nd_username = '$user_name'
                        And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
                        ORDER BY a.nd_username $sap_xep LIMIT $so_dong";
    }

} 
$result_bai_viet = mysqli_query($conn, $bai_viet);
unset($_SESSION['sl_dong']);
$sl_dong_hientai = mysqli_num_rows($result_bai_viet);
$_SESSION['sl_dong'] = $sl_dong_hientai;

 // Kiểm tra quyền truy cập
 if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
    header("Refresh: 0;url=error.php");  
    exit;
}
if($_SESSION['vaitro'] == 'Super Admin'){

    if($trangthai == "Tất cả" ){
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
              
                where (c.tt_ma IS NULL OR c.tt_ma != 4)";
    }
    elseif($trangthai == "3" ){
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
             
                where (c.tt_ma IS NULL OR c.tt_ma != 4) 
                And c.tt_ma IS NULL or c.tt_ma = 3
               
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
    }

    elseif($trangthai == "5" ){
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
             
                where c.ghi_chu = '5' 
                
               
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
    }
    
    else{
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
              
                where (c.tt_ma IS NULL OR c.tt_ma != 4) 
                and c.tt_ma = '$trangthai'
        
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
    }
}
elseif($_SESSION['vaitro'] == 'Admin'){
    if($trangthai == "Tất cả" ){
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                where (c.tt_ma IS NULL OR c.tt_ma != 4)
                And f.nd_username = '$user_name'";
    }
    elseif($trangthai == "3" ){
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                where (c.tt_ma IS NULL OR c.tt_ma != 4) 
                And c.tt_ma IS NULL or c.tt_ma = 3
                And f.nd_username = '$user_name'
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
    }

    elseif($trangthai == "5" ){
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                where c.ghi_chu = '5'
                
                And f.nd_username = '$user_name'
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
    }
    
    else{
        $sd="SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                where (c.tt_ma IS NULL OR c.tt_ma != 4) 
                and c.tt_ma = '$trangthai'
                And f.nd_username = '$user_name'
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
    }
} 

$result_sd = mysqli_query($conn,$sd);
$row_sd = mysqli_fetch_assoc($result_sd);

$_SESSION['tong_sd'] = array();
$_SESSION['tong_sd'] = $row_sd['tong'];




?>

<input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
<?php
                                                $stt = 0;
                                            
                                                while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                                                   
                                                        $stt = $stt + 1;
                                            ?>
<tr>
    <td>
        <input class="form-check-input check-item" name="check[]" type="checkbox"
            value="<?php echo $row_bai_viet['bv_ma'] . '|' . $row_bai_viet['tt_ma']; ?>">
    </td>
    <td class="row-bai-viet"> <?php echo $stt ; ?> </td>


    <td>


        <?php

                                                    if ($row_bai_viet['tt_ma'] == 1) {

                                                        echo "<span class='badge bg-label-success me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else if ($row_bai_viet['tt_ma'] == 2) {
                                                        echo "<span class='badge alert-warning me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else if ($row_bai_viet['tt_ma'] == 4) {

                                                        echo "<span class='badge bg-label-danger me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
                                                    }
                                                    ?>

    </td>
    <td>
                                                    <div class="form-check form-switch mb-2">
                                                        <input name="an-hien"
                                                            value="<?php echo $row_bai_viet['bv_ma'] . '|' . $row_bai_viet['tt_ma']; ?>"
                                                            class="form-check-input" type="checkbox" id="an-hien"
                                                            onchange="changePostState(this)" <?php if( $row_bai_viet['ghi_chu'] == '5' ){ echo "checked";}else{} ?> 
                                                            <?php if( $row_bai_viet['tt_ma'] != '1'){ echo "disabled";}else{} ?>/>
                                                    </div>
                                                </td>
    <td style="white-space: normal">
        <?php echo $row_bai_viet['bv_tieude'] ?>
    </td>
    <td><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

    </td>
    <td> <?php echo $row_bai_viet['nd_hoten'] ?> </td>


    <td>
        <a id="dropdownHanhDong" data-bs-toggle="dropdown" aria-expanded="false"
            style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
            href="Xem_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
        </a>

        <div class="dt-button-collection dropdown-menu" style="top: 55.9375px; left: 419.797px;min-width:7rem"
            aria-labelledby="dropdownHanhDong">
            <div role="menu">
                <a href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                    class="dt-button buttons-print dropdown-item" tabindex="0" type="button">
                    <span><i class=" fa fa-eye me-2"></i>Xem</span>
                </a>
                <a href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                    class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                    <span><i class="bx bx-edit-alt me-2"></i>Kiểm duyệt</span>
                </a>

                <?php
                                                                // Kiểm tra quyền truy cập
                                                                if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                                                    header("Refresh: 0;url=error.php");  
                                                                    exit;
                                                                }
                                                                if($_SESSION['vaitro'] == 'Super Admin'){?>
                                                                    <a href="#"
                                                                        onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>');"
                                                                        class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                        type="button">
                                                                        <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                                                    </a>
                                                            <?php }
                                                                elseif($_SESSION['vaitro'] == 'Admin'){} 
                                                            ?>

            </div>
        </div>



    </td>
</tr>
<?php } ?>