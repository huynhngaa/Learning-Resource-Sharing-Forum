<?php
session_start();
include("./includes/connect.php");
// Include MongoDB PHP driver

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}
$_SESSION['thongbao_thucthi'] = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apdung'])) {
    $selectedAction = $_POST['tacvu'];
   
    $user = $_SESSION['Admin'];
    $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];
    // var_dump($selectedIds);

    if ($selectedAction == "4") {
        // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
        mysqli_begin_transaction($conn);

        try {
            foreach ($selectedIds as $id) {
                $parts = explode('|', $id);
                $bv = $parts[0]; // Giá trị của bv_ma
                $tt = $parts[1]; // Giá trị của ghi_chu

                if( $tt == null){
                    $tt = 3;
                }else{
                    $tt = $parts[1];
                }

                $kt="select *from kiem_duyet where bv_ma = '$bv'";
                $result_kt = mysqli_query($conn,$kt);
                $row_kt = mysqli_fetch_assoc($result_kt);
    
                if($row_kt && $bv == $row_kt['bv_ma']){
                    $huy_bv = "UPDATE kiem_duyet SET tt_ma = '$selectedAction', nd_username='$user' , thoigian = now(), ghi_chu = '$tt' WHERE bv_ma = '$bv'";
                    mysqli_query($conn, $huy_bv);  
                }else{
                    $xoa_bai_viet = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian, ghi_chu) VALUE ('$bv', '$user', '$selectedAction', now(), ghi_chu = '$tt') ";
                    mysqli_query($conn, $xoa_bai_viet);  
                }

                
                // $xoa_bai_viet="DELETE FROM bai_viet where bv_ma= '$id'";
                // mysqli_query($conn,$xoa_bai_viet); 
            }

            // Nếu mọi thứ thành công, commit transaction
            mysqli_commit($conn);
           
            // echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";   
            $_SESSION['bg_thongbao'] = "bg-success";
            $_SESSION['thongbao_thucthi'] = "Bạn đã xóa dữ liệu thành công!";
            // header("Refresh: 0;url=QL_BaiViet.php");  
        } catch (Exception $e) {
            // Nếu có lỗi xảy ra, rollback transaction và hiển thị thông báo lỗi
            mysqli_rollback($conn);
            echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>"; 
            echo "Error: " . $e->getMessage();
        }
    } elseif ($selectedAction == "1") {
        $_SESSION['xl'] = []; // Khởi tạo mảng trong session nếu cần thiết

        foreach ($selectedIds as $id) {
            $_SESSION['xl'][] = $id; // Thêm giá trị vào mảng trong session
            $kt="select *from kiem_duyet where bv_ma = '$id'";
            $result_kt = mysqli_query($conn,$kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if(mysqli_num_rows($result_kt) > 0){
                $huy_bv = "UPDATE kiem_duyet SET tt_ma = '$selectedAction', nd_username='$user', thoigian = now() WHERE bv_ma = '$id'";
                mysqli_query($conn, $huy_bv);  
            }else{
                $duyet_bv = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian) VALUE ('$id', '$user', '$selectedAction', now())";
                mysqli_query($conn, $duyet_bv);   
            }

          
           

                    
        }
        // echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>"; 
        $_SESSION['bg_thongbao'] = "bg-success";
        $_SESSION['thongbao_thucthi'] = "Phê duyệt bài viết thành công!";
        header("Refresh: 2;url=xuly_dl_mongodb.php");  
    }
    elseif ($selectedAction == "2") {
        foreach ($selectedIds as $id) {

            $kt="select *from kiem_duyet where bv_ma = '$id'";
            $result_kt = mysqli_query($conn,$kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if(mysqli_num_rows($result_kt) > 0){
                $huy_bv = "UPDATE kiem_duyet SET tt_ma = '$selectedAction', nd_username='$user', thoigian = now() WHERE bv_ma = '$id'";
                mysqli_query($conn, $huy_bv);  
            }else{
                $duyet_bv = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian) VALUE ('$id', '$user', '$selectedAction', now())";
                mysqli_query($conn, $duyet_bv);   
            } 
        }
        // echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>"; 
        $_SESSION['bg_thongbao'] = "bg-success";
        $_SESSION['thongbao_thucthi'] = "Cập nhật trạng thái bài viết thành công!";
        header("Refresh: 0;url=QL_BaiViet.php");  
    }
}


$so_dong = 5;
$trangthai = isset($_GET['tt']) ? $_GET['tt'] : "Tất cả";
$sap_xep = 'DESC';
$user_name = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";
$tungay = 2015-01-01;
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

// if (isset($_POST['tungay']) && isset($_POST['denngay'])) {
//     $tungay = date('Y-m-d', strtotime($_POST['tungay']));
//     $denngay = date('Y-m-d', strtotime($_POST['denngay']));
// }

    
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
        $bai_viet = "SELECT a.*, e.*,c.tt_ma,c.ghi_chu, d.* FROM bai_viet a
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



?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý bài viết</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />
    <!-- Thông báo -->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php
            include_once("includes/menu.php");
            ?>

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php
                include_once("includes/navbar.php");
                ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Basic Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.php">Home</a>
                                </li>
                                <li class="breadcrumb-item active">Quản lý bài viết</li>
                            </ol>
                        </nav>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Danh sách bài viết</h5>

                            <style>
                            .btn-label-secondary {
                                color: #8592a3;
                                border-color: rgba(0, 0, 0, 0);
                                background: #ebeef0;
                            }

                            .btn-label-secondary:hover {
                                border-color: rgba(0, 0, 0, 0) !important;
                                background: #788393 !important;
                                color: #fff !important;
                                box-shadow: 0 0.125rem 0.25rem 0 rgba(133, 146, 163, .4) !important;
                                transform: translateY(-1px) !important;
                            }

                            .btn:hover {
                                color: var(--bs-btn-hover-color);
                                background-color: var(--bs-btn-hover-bg);
                                border-color: var(--bs-btn-hover-border-color);
                            }

                            .btn-primary {
                                color: #fff;
                                background-color: #696cff;
                                border-color: #696cff;
                                box-shadow: 0 0.125rem 0.25rem 0 rgba(105, 108, 255, .4);
                            }
                            </style>



                            <div class="row card-header d-flex flex-wrap py-3 px-3 justify-content-between">

                                <div style="margin-bottom:1rem; margin-top:-1rem" class="col-lg-12  row">
                                    <div class="col-md-2 product_status">
                                        <label>Trạng thái </label>
                                        <select name="trangthai" class="form-select" id="trangthai">
                                            <?php
                                            // $t = isset($_GET['tt']) ? $_GET['tt'] : "Tất cả";
                                            // $trangthai = isset($_GET['this_bv_ma']) ? $_GET['this_bv_ma'] : 0;
                                            ?>

                                            <option value="<?php echo $trangthai; ?>">
                                                <?php
                                                if ($trangthai == 3) {
                                                    echo "Chờ duyệt";
                                                } elseif ($trangthai == 2) {
                                                    echo "Đã bị huỷ";
                                                } elseif ($trangthai == 1) {
                                                    echo "Đã duyệt";
                                                } else {
                                                    echo "Tất cả";
                                                }
                                                ?>
                                            </option>
                                            <?php
                                                if($trangthai != "Tất cả"){
                                                    echo '<option value="Tất cả">Tất cả</option>';
                                                }else{}
                                            ?>

                                            <?php
                                            $tt = "select * from trang_thai where tt_ma in(1,2,3,5) ";
                                            $result_tt = mysqli_query($conn, $tt);
                                            while ($row_tt = mysqli_fetch_array($result_tt)) {
                                            ?>
                                            <option value="<?php echo $row_tt['tt_ma']; ?>">
                                                <?php echo $row_tt['tt_ten']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="dataTables_filter">
                                            <label>Từ ngày</label>
                                            <input id="tungay" type="date" class="form-control" value="2015-01-01">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Đến ngày</span>
                                        <input id="denngay" type="date" class="form-control"
                                            value="<?php echo date('Y-m-d'); ?>">

                                    </div>
                                    <div class="col-md-2">

                                        <br>
                                        <button id="loc_ngay" class="btn btn-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>



                                <div style="padding-left:10px"
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end gap-3 gap-sm-2 flex-wrap flex-sm-nowrap pt-0">

                                    <form method="POST">
                                        <div class=" dataTables_length mt-0 mt-md-0  me-2">
                                            <label>
                                                <select name="so_dong" class="form-select" id="so_dong">
                                                    <?php
                                                    $sd="SELECT count(*) as tong
                                                            FROM bai_viet a
                                                            LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                                                            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                                                            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                            where (c.tt_ma IS NULL OR c.tt_ma != 4)";
                                                    $result_sd = mysqli_query($conn,$sd);
                                                    $row_sd = mysqli_fetch_assoc($result_sd);

                                                    $tong = $row_sd['tong'];

                                                    for ($i = 5; $i <= $tong+5; $i += 5) {
                                                        echo "<option value='".$i."'>".$i."</option>";
                                                    }
                                                
                                                ?>
                                                </select>
                                            </label>
                                        </div>
                                    </form>

                                    <style>
                                    @media (max-width: 300px) {
                                        #Export {
                                            /* Đặt kích thước của button thành 10px khi màn hình nhỏ hơn 400px */
                                            width: 20px;
                                            height: 40px;
                                        }
                                    }
                                    </style>


                                    <button id="Export"
                                        class="col-lg-2  col-sm-3 col-2 dt-button buttons-collection  btn btn-label-secondary ms-sm-0 me-3"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">

                                        <span>
                                            <i class="bx bx-export "></i>
                                            <span class="dt-down-arrow d-none d-xl-inline-block">Export ▼</span>

                                        </span>

                                    </button>
                                    <div class="dt-button-collection dropdown-menu"
                                        style="top: 55.9375px; left: 419.797px;" aria-labelledby="dropdownMenuButton">
                                        <div role="menu">
                                            <a href="InBaiViet.php" class="dt-button buttons-print dropdown-item"
                                                tabindex="0" type="button">
                                                <span><i class="bx bx-printer me-2"></i>Print</span>
                                            </a>
                                            <a href="ExcelBaiViet.php"
                                                class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                                                <span><i class="bx bx-file me-2"></i>Excel</span>
                                            </a>

                                        </div>
                                    </div>

                                    <?php
                                        // Kiểm tra quyền truy cập
                                        if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                            header("Refresh: 0;url=error.php");  
                                            exit;
                                        }
                                        if($_SESSION['vaitro'] == 'Super Admin'){?>

                                    <a href="Them_BaiViet.php" class="dt-button add-new btn btn-primary" type="button">
                                        <span>
                                            <i class="bx bx-plus me-0 me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Thêm bài viết</span>
                                        </span>
                                    </a>
                                    <?php }
                                        elseif($_SESSION['vaitro'] == 'Admin'){?>

                                    <?php } ?>
                                </div>



                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Thêm bài viết</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="nameWithTitle" class="form-label">Tên khối
                                                            lớp</label>
                                                        <input name="name" type="text" id="nameWithTitle"
                                                            class="form-control" placeholder="Nhập vào tên khối lớp" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div style="padding: 0 1rem 1rem 0" class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    Hủy
                                                </button>
                                                <button name="ThemKhoiLop" type="submit"
                                                    class="btn btn-primary">Thêm</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <form method="POST" enctype="multipart/form-data">
                                <div style="margin-left:0.1rem;" class="col-lg-12  row ">

                                    <div class="col-md-2 col-lg-2 col-sm-3 col-6 ">
                                        <select name="tacvu" class="form-select" id="tacvu">
                                            <option value="Tất cả">Chọn tác vụ</option>
                                            <!-- <option value="6">Hiện bài viết</option>
                                            <option value="5">Ẩn bài viết</option> -->
                                            <option value="1">Duyệt</option>
                                            <option value="2">Huỷ</option>
                                            <?php
                                                // Kiểm tra quyền truy cập
                                                if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                                    header("Refresh: 0;url=error.php");  
                                                    exit;
                                                }
                                                if($_SESSION['vaitro'] == 'Super Admin'){

                                                    echo '<option value="4">Xoá</option>';
                                                }
                                                elseif($_SESSION['vaitro'] == 'Admin'){} 
                                            ?>



                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-6 col-sm-6 col-6 ">
                                        <button name="apdung" type="submit" class="btn btn-primary" id="apdung">
                                            <span>
                                                <i class="fa fa-check me-2"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Áp dụng</span>

                                            </span>
                                        </button>

                                        <?php
                                        // Kiểm tra quyền truy cập
                                        if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                            header("Refresh: 0;url=error.php");  
                                            exit;
                                        }
                                        if($_SESSION['vaitro'] == 'Super Admin'){

                                            echo '<a href="BaiViet_DaXoa.php" name="thungrac" type="submit"
                                                class="btn btn-label-secondary ">
                                                <span>
                                                    <i class="bx bx-trash"></i>
                                                    <span class="dt-down-arrow d-none d-xl-inline-block">Thùng rác</span>

                                                </span>
                                            </a>';
                                        }
                                        elseif($_SESSION['vaitro'] == 'Admin'){} 
                                        ?>
                                    </div>

                                    <!-- Giao diện -->
                                    <div class="col-md-2 col-lg-4 col-sm-3 row mt-4">
                                        <div style="display: flex; justify-content: right;" class="col-lg-12">
                                            <p>Đang hiển thị:
                                                <span id="so_dong_hien_tai">
                                                    <?php echo $_SESSION['sl_dong']; ?>
                                                </span>/
                                                <span id="tong-so-dong">
                                                    <?php
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

                                                        echo $_SESSION['tong_sd']; 
                                                    ?>
                                                </span>(kết quả)
                                            </p>
                                        </div>
                                    </div>


                                </div>

                                <div class="table-responsive text-nowrap border-top">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" id="checkall" type="checkbox">
                                                </th>
                                                <th>STT</th>
                                                <!-- <th>Mã</th> -->
                                                <th>Trạng thái</th>
                                                <th>Ẩn</th>
                                                <th>Tiêu đề</th>

                                                <th>Ngày đăng</th>

                                                <style>
                                                .sort-header {
                                                    position: relative;
                                                }

                                                .small-button {
                                                    background-color: none;
                                                    background: none;
                                                    border: none;
                                                    font-size: 11px;
                                                    /* Reduce the font size */
                                                    padding: 5px 10px;
                                                    border-radius: 0.3rem;
                                                    cursor: pointer;
                                                    margin-left: 5rem;
                                                    color: #CCCCCC;
                                                }

                                                .sort-header button#asc {
                                                    position: absolute;
                                                    top: 0;
                                                    left: 0;
                                                }

                                                .sort-header button#desc {
                                                    position: absolute;
                                                    bottom: 0;
                                                    left: 0;
                                                }

                                                .active {
                                                    color: #666666;
                                                    /* Change the color to the desired active color */
                                                }
                                                </style>
                                                <th class="sort-header">Tác giả
                                                    <button id="asc" type="button" class="small-button"
                                                        data-sort="asc"><i class="fa fa-sort-asc"></i></button>
                                                    <button id="desc" type="button" class="small-button"
                                                        data-sort="desc"><i class="fa fa-sort-desc"></i></button>
                                                </th>

                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-container">
                                            <input type="hidden" id="tong_sd"
                                                value="<?php echo $_SESSION['tong_sd']; ?>">
                                            <?php
                                                $stt = 0;
                                                
                                                while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                                                    // $_SESSION['sl_dong'] = mysqli_num_rows($result_bai_viet);

                                                    $stt = $stt + 1;
                                            ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input check-item" name="check[]"
                                                        type="checkbox"
                                                        value="<?php echo $row_bai_viet['bv_ma'] . '|' . $row_bai_viet['tt_ma']; ?>">
                                                </td>
                                                <td class="row-bai-viet"> <?php echo $stt ?> </td>

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
                                                    <a id="dropdownHanhDong" data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        style="display:math; padding:0.1rem 0.6rem"
                                                        class="dropdown-item"
                                                        href="Xem_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>

                                                    <div class="dt-button-collection dropdown-menu"
                                                        style="top: 55.9375px; left: 419.797px;min-width:7rem"
                                                        aria-labelledby="dropdownHanhDong">
                                                        <div role="menu">
                                                            <a href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                                                                class="dt-button buttons-print dropdown-item"
                                                                tabindex="0" type="button">
                                                                <span><i class=" fa fa-eye me-2"></i>Xem</span>
                                                            </a>
                                                            <a href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                                                                class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                type="button">
                                                                <span><i class="bx bx-edit-alt me-2"></i>Kiểm
                                                                    duyệt</span>
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
                                            <?php }  ?>

                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!--/ Basic Bootstrap Table -->
                    <!-- / Content -->

                    <!-- Footer -->

                    <?php
                        include("includes/footer.php");
                        ?>

                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/dashboards-analytics.js"></script>

    <?php
        include_once("includes/ThongBao.php");
        ?>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.small-button').click(function() {
            // Remove the 'active' class from all buttons
            $('.small-button').removeClass('active');

            // Add the 'active' class to the clicked button
            $(this).addClass('active');
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // Xử lý khi số dòng hoặc trạng thái thay đổi
        $('#so_dong,#trangthai').change(function() {
            updateDataContainer();
        });

        // Xử lý khi nhấn nút sắp xếp
        $('#asc, #desc').click(function() {
            var sortOrder = $(this).attr('id'); // Lấy 'asc' hoặc 'desc' từ id của nút
            updateDataContainer(sortOrder);
            $(this).addClass('active');
            $('#desc, #asc').not(this).removeClass('active');
        });

        // Xử lý khi nhấn nút lọc ngày
        $('#loc_ngay').click(function() {
            var chon_gtri = $('#so_dong').val();
            var tungay = $('#tungay').val();
            var denngay = $('#denngay').val();
            var trangthai = $('#trangthai').val();
            $.ajax({
                url: 'sodong_BV.php',
                method: 'GET',
                data: {
                    tungay: tungay,
                    sodong: chon_gtri,
                    denngay: denngay,
                    trangthai: trangthai,
                    loc_ngay: true // Thêm tham số loc_ngay
                },
                // success: function(data) {
                //     $('#data-container').html(data);
                // }
                success: function(data) {
                    $('#data-container').html(data);
                    updateDisplayInfo();
                }
            });
        });

        // Hàm cập nhật dữ liệu với tùy chọn sắp xếp
        function updateDataContainer(sortOrder) {
            var chon_gtri = $('#so_dong').val();
            var chon_trangthai = $('#trangthai').val();
            var tungay = $('#tungay').val();
            var denngay = $('#denngay').val();
            $.ajax({
                url: 'sodong_BV.php',
                data: {
                    tungay: tungay,
                    denngay: denngay,
                    sodong: chon_gtri,
                    trangthai: chon_trangthai,
                    sort: sortOrder
                },
                // success: function(data) {
                //     $('#data-container').html(data);
                // }
                success: function(data) {
                    $('#data-container').html(data);
                    updateDisplayInfo();
                }
            });
        }
        // Hàm cập nhật thông tin hiển thị
        function updateDisplayInfo() {
            var soDongHienTai = $('#data-container .row-bai-viet').length;
            var Tong = $('#tong_sd').val(); // Lấy giá trị từ biến ẩn
            $('#so_dong_hien_tai').text(soDongHienTai);
            $('#tong-so-dong').text(Tong);
        }
    });
    </script>

    <script>
    function changePostState(checkbox) {
        var postId = checkbox.value;
        var trangthai = checkbox.checked ? 5 : 6;

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    console.log(xhr.responseText);
                } else {
                    console.error('Đã xảy ra lỗi: ' + xhr.status);
                }
            }
        };

        xhr.open("GET", "get_an_hien.php?postId=" + postId + "&an_hien=" + trangthai, true);
        xhr.send();
    }
    </script>


    <!-- <script>
        function changePostState(checkbox) {
            if (checkbox.checked) {
                var postId = checkbox.value; // Lấy giá trị của checkbox khi nó được chọn
                // Sử dụng postId để thực hiện các hành động cần thiết (ví dụ: gửi yêu cầu Ajax)
                console.log("ID của bài viết là: " + postId);
                // Gọi các hàm khác hoặc thực hiện các thao tác cần thiết ở đây
            }
            else {
                var postId = checkbox.value; // Lấy giá trị của checkbox khi nó được chọn
                // Nếu checkbox không được chọn, xoá log postId
                // console.clear(); // Xoá tất cả log trong console (tùy chọn)
                // Hoặc xoá log cụ thể
                // Xoá log chỉ khi checkbox không được chọn và postId đã được log trước đó
                console.log("Checkbox đã được huỷ, Xoá ID của bài viết: " + postId);
            }
        }
    </script> -->

    <!-- JavaScript để điều khiển hành vi -->
    <script>
    // Lắng nghe sự kiện khi checkbox chọn tất cả được thay đổi trạng thái
    document.getElementById('checkall').addEventListener('change', function() {
        // Lấy tất cả các checkbox cần chọn
        var checkboxes = document.querySelectorAll('.check-item');

        // Duyệt qua từng checkbox và thiết lập trạng thái chọn/bỏ chọn
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = document.getElementById('checkall').checked;
        });
    });
    </script>

    <!-- <script>
        var data = <?php echo json_encode($baiviet_arry) ?>;
        console.log(data);
    </script> -->

    <script>
    <?php if (isset($_SESSION['thongbao_thucthi']) && $_SESSION['thongbao_thucthi']) { ?>
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("thongbao_thucthi");
        var successToastInstance = new bootstrap.Toast(successToast);
        $("#loader").hide();
        successToastInstance.show();
      });
    <?php
      // Reset the login_success session variable
      unset($_SESSION['thongbao_thucthi']);
      unset($_SESSION['bg_thongbao']);
    }
    ?>
  </script>


</body>

</html>