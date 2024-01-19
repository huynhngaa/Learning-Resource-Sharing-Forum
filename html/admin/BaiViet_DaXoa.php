<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apdung'])) {
    $selectedAction = $_POST['tacvu'];

    $user = $_SESSION['Admin'];
    $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];

    if ($selectedAction == "xoa_vinhvien") {
        // Use a transaction to ensure data integrity
        // mysqli_begin_transaction($conn);

        try {
            foreach ($selectedIds as $id) {
               
                        // If the status is already 4, delete related records
                        $kt_ls="select *from lich_su_xem where bv_ma = '$id'";
                        $result_kt_ls = mysqli_query($conn,$kt_ls);
                        $row_kt_ls = mysqli_fetch_assoc($result_kt_ls);

                        if(mysqli_num_rows($result_kt_ls) > 0){
                            // while ($row_kt_ls = mysqli_fetch_array($result_kt_ls)) {
                               
                                $delete_query = "DELETE FROM lich_su_xem WHERE bv_ma = '".$row_kt_ls['bv_ma']."'";
                                mysqli_query($conn, $delete_query);
                            // }
                        }
                        

                        $kt="select *from binh_luan where bv_ma = '$id'";
                        $result_kt = mysqli_query($conn,$kt);
                        // $row_kt = mysqli_fetch_assoc($result_kt);

                        if(mysqli_num_rows($result_kt) > 0){
                            while ($row_kt = mysqli_fetch_array($result_kt)) {
                                

                                $kt2="select *from lich_su_xem where bl_ma = '".$row_kt['bl_ma']."'";
                                $result_kt2 = mysqli_query($conn,$kt2);
                                $row_kt2 = mysqli_fetch_assoc($result_kt2);

                                if(mysqli_num_rows($result_kt2) > 0){
                                    // while ($row_kt2 = mysqli_fetch_array($result_kt2)) {
                                       
                                        $delete_query = "DELETE FROM lich_su_xem WHERE bl_ma = '".$row_kt['bl_ma']."'";
                                        mysqli_query($conn, $delete_query);
                                    // }
                                }

                                $delete_query = "DELETE FROM rep_bl WHERE (bl_cha = '".$row_kt['bl_ma']."' or bl_con = '".$row_kt['bl_ma']."')";
                                mysqli_query($conn, $delete_query);

                                
                            }
                        }

                        $delete_query = "DELETE FROM danh_gia WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);

                        $delete_query = "DELETE FROM binh_luan WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);

                        $delete_query = "DELETE FROM kiem_duyet WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);

                        $delete_query = "DELETE FROM tai_lieu WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);

                       

                        $delete_query = "DELETE FROM bai_viet WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);
              
            }
            echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";
        } catch (Exception $e) {
            // If an error occurs, rollback the transaction and display an error message
            mysqli_rollback($conn);
            echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>";
            echo "Error: " . $e->getMessage();
        }
    } 
    // elseif ($selectedAction == "1" || $selectedAction == "3") {
    //     // Update the status for selected IDs
    //     foreach ($selectedIds as $id) {
    //         $kt = "SELECT * FROM kiem_duyet WHERE bv_ma = '$id'";
    //         $result_kt = mysqli_query($conn, $kt);
    //         $row_kt = mysqli_fetch_assoc($result_kt);

    //         if ($row_kt && $id == $row_kt['bv_ma']) {
    //             // If a record exists, update the status
    //             $update_query = "UPDATE kiem_duyet SET tt_ma = '$selectedAction' WHERE bv_ma = '$id'";
    //             mysqli_query($conn, $update_query);
    //         } else {
    //             // If no record exists, insert a new record
    //             $insert_query = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma) VALUES ('$id', '$user', '$selectedAction')";
    //             mysqli_query($conn, $insert_query);
    //         }
    //     }

    //     echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>";
    //     header("Refresh: 0;url=BaiViet_DaXoa.php");
    // }
    if ($selectedAction == 'phuchoi') {
        
        try {
            foreach ($selectedIds as $bv) {
                $kt = "SELECT * FROM kiem_duyet WHERE bv_ma = '$bv'";
                $result_kt = mysqli_query($conn, $kt);
                $row_kt = mysqli_fetch_assoc($result_kt);
    
                if (mysqli_num_rows($result_kt) > 0) {
                    $update_query = "UPDATE kiem_duyet SET tt_ma = '" . $row_kt['ghi_chu'] . "', nd_username='$user', ghi_chu='', thoigian = now() WHERE bv_ma = '$bv'";
                    $update_result = mysqli_query($conn, $update_query);
                   
                }
            }
    
            echo "<script>alert('Bạn đã khôi phục dữ liệu thành công!');</script>";
            
    
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "<script>alert('Có lỗi xảy ra trong quá trình khôi phục dữ liệu.');</script>";
            echo "Error: " . $e->getMessage();
        }
    }
    
}



$so_dong = 5;

$sap_xep = 'DESC';

$tungay = "2000-01-01";
$denngay = date('Y-m-d');

$id = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";

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


        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.thoigian, d.* FROM bai_viet a
             LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
             LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
             LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
             where c.tt_ma = 4
             And c.nd_username = '$id'
             And DATE(thoigian) BETWEEN '$tungay' AND '$denngay'
            ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";

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

    <title>Quản lý bài viết đã xoá</title>

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
                                <li class="breadcrumb-item"><a href="QL_BaiViet.php">Quản lý bài viết</a></li>
                                <li class="breadcrumb-item active">Bài viết đã xoá</li>
                            </ol>
                        </nav>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Danh sách các bài viết đã xoá</h5>

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

                                    <div class="col-md-2">
                                        <div class="dataTables_filter">
                                            <label>Từ ngày</label>
                                            <input id="tungay" type="date" class="form-control" value="2000-01-01">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <span>Đến ngày</span>
                                        <input id="denngay" type="date" class="form-control"
                                            value="<?php echo date('Y-m-d'); ?>">

                                    </div>
                                    <div class="col-md-2">

                                        <br>
                                        <button id="loc_ngay" class="btn btn-primary"><i
                                                class="fa fa-search"></i></button>
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
                                                            where  c.tt_ma = 4
                                                            And DATE(thoigian) BETWEEN '$tungay' AND '$denngay'
                                                             And c.nd_username = '$id'";
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

                                    <?php
                                        // Kiểm tra quyền truy cập
                                        if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                            header("Refresh: 0;url=error.php");  
                                            exit;
                                        }
                                        if($_SESSION['vaitro'] == 'Super Admin'){?>

                                    <a href="2.php" class="dt-button add-new btn btn-primary">
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
                                            <option value="xoa_vinhvien">Xoá vĩnh viễn</option>
                                            <!-- <option value="1">Duyệt lại</option>
                                            <option value="3">Chờ duyệt</option> -->
                                            <option value="phuchoi">Phục hồi</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-6 col-sm-6 col-6 ">
                                        <button name="apdung" type="submit" class="btn btn-primary" id="apdung">
                                            <span>
                                                <i class="fa fa-check me-2"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Áp dụng</span>

                                            </span>
                                        </button>

                                    </div>



                                    <!-- Giao diện -->
                                    <div class="col-md-2 col-lg-4 col-sm-3 row mt-4">
                                        <div style="display: flex; justify-content: right;" class="col-lg-12">
                                            <p >Đang hiển thị: 
                                                <span id="so_dong_hien_tai">
                                                    <?php echo $_SESSION['sl_dong']; ?>
                                                </span>/
                                                <span id="tong-so-dong">
                                                    <?php
                                                    
                                                        
                                                            $kq="SELECT count(*) as tong
                                                                    FROM bai_viet a
                                                                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                                                                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where c.tt_ma = 4

                                                                    And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
                                                                    And c.nd_username = '".$_SESSION['Admin']."'";
                                                        
                                                        $result_kq = mysqli_query($conn,$kq);
                                                        $row_kq = mysqli_fetch_assoc($result_kq);
                                                        $_SESSION['tong_sd'] = array();
                                                        $_SESSION['tong_sd'] = $row_kq['tong'];

                                                        echo $_SESSION['tong_sd']; 
                                                    ?>
                                                </span>
                                                (kết quả)
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
                                                <th>Thời gian xoá</th>
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
                                                    <button id="asc" type="button"  class="small-button"
                                                        data-sort="asc"><i class="fa fa-sort-asc"></i></button>
                                                    <button id="desc" type="button"  class="small-button"
                                                        data-sort="desc"><i class="fa fa-sort-desc"></i></button>
                                                </th>

                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-container">
                                        <input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
                                            <?php
                                                $stt = 0;
                                                $sl_baiviet= array();
                                                while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                                                    $sl_baiviet= array(
                                                        "tong"=> $tong,
                                                        "hientai"=> $stt + 1
                                                    );
                                                    // $baiviet_arry[]= array(
                                                    //     "ma"=> $row_bai_viet['bv_ma'],
                                                    //     "trangthai"=> $row_bai_viet['tt_ma'],
                                                    //     "tieude"=> $row_bai_viet['bv_tieude'],
                                                    //     "ngaydang"=> date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"),
                                                    //     "tacgia"=> $row_bai_viet['nd_hoten'],
                                                    //     "user"=> $row_bai_viet['nd_username']
                                                    // );

                                                    // if($row_bai_viet['tt_ma'] == '4'){

                                                    // }else{

                                                   

                                                    $stt = $stt + 1;
                                            ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input check-item" name="check[]" type="checkbox"
                                                        value="<?php echo $row_bai_viet['bv_ma'] ?>">
                                                </td>
                                                <td class="row-bai-viet"> <?php echo $stt ?> </td>

                                                <td>

                                                    <?php

                                                    if ($row_bai_viet['tt_ma'] == 1) {

                                                        echo "<span class='badge bg-label-success me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else if ($row_bai_viet['tt_ma'] == 2) {
                                                        echo "<span class='badge alert-warning  me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else if ($row_bai_viet['tt_ma'] == 4) {

                                                        echo "<span class='badge bg-label-danger me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
                                                    }
                                                    ?>

                                                </td>
                                                <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['thoigian']), "d-m-Y (H:i:s)"); ?>
                                                <td style="white-space: normal">
                                                    <?php echo $row_bai_viet['bv_tieude'] ?>

                                                </td>

                                                <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

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
                                                                <span><i class="bx bx-edit-alt me-2"></i>Sửa</span>
                                                            </a>

                                                            <a href="#"
                                                                onclick="Xoa_Baiviet_VV('<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>');"
                                                                class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                type="button">
                                                                <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                                            </a>

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

    <!-- <script>
        document.getElementById("so_dong").addEventListener("change", function() {
            updateDataContainer();
        });

        document.getElementById("trangthai").addEventListener("change", function() {
            updateDataContainer();
        });

        document.getElementById("asc").addEventListener("click", function() {
            updateDataContainer("asc"); // Truyền giá trị "asc" khi nhấn nút "asc"
        });

        document.getElementById("desc").addEventListener("click", function() {
            updateDataContainer("desc"); // Truyền giá trị "desc" khi nhấn nút "desc"
        });

        document.getElementById("tungay").addEventListener("change", function() {
            updateDataContainer("tungay"); // Truyền giá trị "tungay" khi nhấn nút "desc"
        });

        document.getElementById("denngay").addEventListener("change", function() {
            updateDataContainer("denngay"); // Truyền giá trị "denngay" khi nhấn nút "desc"
        });

        function updateDataContainer(sortOrder) {
            var chon_gtri = document.getElementById("so_dong").value;
            var chon_trangthai = document.getElementById("trangthai").value;



            // Sử dụng AJAX để gửi yêu cầu đến tệp PHP và tải lại dữ liệu
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("data-container").innerHTML = xhr.responseText;
                }
            };

            // Bây giờ sử dụng giá trị được truyền vào hàm
            xhr.open("GET", "sodong_BV.php?sodong=" + chon_gtri + "&trangthai=" + chon_trangthai + "&sort=" + sortOrder,
                true);
            xhr.send();
        }
        </script> -->
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
                url: 'sodong_BV_DaXoa.php',
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
                url: 'sodong_BV_DaXoa.php',
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
    <script>
    var data = <?php echo json_encode($baiviet_arry); ?>;
    console.log(data);
    </script>


</body>

</html>