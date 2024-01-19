<?php
    session_start();
    include("./includes/connect.php");
    
    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}


     // Kiểm tra dữ liệu POST
    //  var_dump($_POST);


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apdung'])) {
        $selectedAction = $_POST['tacvu'];
        $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];
        // var_dump($selectedIds);


        if ($selectedAction == "xoa") {
            // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
            mysqli_begin_transaction($conn);

            try {
                foreach ($selectedIds as $id) {
                    $xoa_ls = "DELETE FROM lich_su_xem WHERE bl_ma = '$id'";
                    mysqli_query($conn, $xoa_ls);

                    $xoa_rep_bl = "DELETE FROM rep_bl WHERE bl_cha = '$id' OR bl_con = '$id'";
                    mysqli_query($conn, $xoa_rep_bl);

                    $xoa_bin_luan = "DELETE FROM binh_luan WHERE bl_ma = '$id'";
                    mysqli_query($conn, $xoa_bin_luan);
                    // echo $xoa_ls, '<br>', $xoa_rep_bl, '<br>', $xoa_bin_luan;
                }

                // Nếu mọi thứ thành công, commit transaction
                mysqli_commit($conn);
                echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>"; 
                header("Refresh: 0;url=QL_BinhLuan.php");  
            } catch (Exception $e) {
                // Nếu có lỗi xảy ra, rollback transaction và hiển thị thông báo lỗi
                mysqli_rollback($conn);
                echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>"; 
                echo "Error: " . $e->getMessage();
            }
        } elseif ($selectedAction == "duyet") {
            foreach ($selectedIds as $id) {
                $cap_nhat_danh_muc = "UPDATE binh_luan SET trangthai = '1' WHERE bl_ma = '$id'";
                mysqli_query($conn, $cap_nhat_danh_muc);           
            }
            // echo "<script>alert('Cập nhật trạng thái bình luận thành công!');</script>"; 
            $_SESSION['bg_thongbao'] = "bg-success";
            $_SESSION['thongbao_thucthi'] = "Phê duyệt bài viết thành công!";
            // header("Refresh: 0;url=QL_BinhLuan.php");  
        }
        elseif ($selectedAction == "huy") {
            foreach ($selectedIds as $id) {
                $huy_bl = "UPDATE binh_luan SET trangthai = '2' WHERE bl_ma = '$id'";
                mysqli_query($conn, $huy_bl);           
            }
            // echo "<script>alert('Cập nhật trạng thái bình luận thành công!');</script>"; 
            $_SESSION['bg_thongbao'] = "bg-success";
            $_SESSION['thongbao_thucthi'] = "Bạn vừa huỷ bỏ bình luận!";
            // header("Refresh: 0;url=QL_BinhLuan.php");  
        }
    }

    if (isset($_POST['capnhat'])) {
        $id_bl = $_POST['id_binhluan'];
        $tt_bl = $_POST['trangthai_pd'];

        $capnhat_binhluan = "UPDATE binh_luan SET trangthai = '$tt_bl' WHERE bl_ma = '$id_bl'";
        mysqli_query($conn,$capnhat_binhluan);           
        echo "<script>alert('Cập nhật trạng thái bình luận thành công!');</script>"; 
        header("Refresh: 0;url=QL_BinhLuan.php"); 	
    }

    $so_dong = 5;
    $trangthai = isset($_GET['tt']) ? $_GET['tt'] : "Tất cả";
    $sap_xep = 'DESC';
    $tungay = 2022-01-01;
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
    
    if ($trangthai == "Tất cả") {
        $binh_luan = "SELECT * FROM binh_luan a 
                    LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                    LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                    LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                    where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'
                    ORDER BY a.bl_ma $sap_xep LIMIT $so_dong";
    }else{
        $binh_luan = "SELECT * FROM binh_luan a 
                    LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                    LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                    LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                    where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'
                    and a.trangthai = '$trangthai'
                    ORDER BY a.nd_username $sap_xep LIMIT $so_dong";
    }
    $result_binh_luan = mysqli_query($conn,$binh_luan);
    unset($_SESSION['sl_dong']);
    $sl_dong_hientai = mysqli_num_rows($result_binh_luan);
    $_SESSION['sl_dong'] = $sl_dong_hientai;
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý bình luận</title>

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
                                <li class="breadcrumb-item active">Quản lý bình luận</li>
                            </ol>
                        </nav>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Danh sách bình luận</h5>

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
                                            <option
                                                value="<?php if($trangthai == 3){ echo "3";}else{echo "Tất cả";}  ?>">
                                                <?php if($trangthai == 3){ echo "Chờ duyệt";}else{echo "Tất cả";}  ?>
                                            </option>
                                            <?php
                                            $tt = "select * from trang_thai where tt_ma in(1,2,3)";
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
                                            <input id="tungay" type="date" class="form-control" value="2022-01-01">

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
                                                    $sd="SELECT count(*) as tong FROM binh_luan";
                                                    $result_sd = mysqli_query($conn,$sd);
                                                    $row_sd = mysqli_fetch_assoc($result_sd);

                                                    $tong = $row_sd['tong'];

                                                    // In ra các số tròn chục nhỏ hơn tổng
                                                    // echo "Các số tròn chục nhỏ hơn tổng là: ";
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
                                            <a href="InBinhLuan.php" class="dt-button buttons-print dropdown-item"
                                                tabindex="0" type="button">
                                                <span><i class="bx bx-printer me-2"></i>Print</span>
                                            </a>
                                            <a href="ExcelBinhLuan.php"
                                                class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                                                <span><i class="bx bx-file me-2"></i>Excel</span>
                                            </a>

                                        </div>
                                    </div>
                                    <!-- <button class="dt-button add-new btn btn-primary" type="button"
                                        data-bs-toggle="modal" data-bs-target="#modalCenter">
                                        <span>
                                            <i class="bx bx-plus me-0 me-sm-1"></i>
                                            <span class="d-none d-sm-inline-block">Thêm bình luận</span>
                                        </span>
                                    </button> -->
                                </div>



                            </div>




                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Thêm bình luận</h5>
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
                                <div style="margin-left:0.1rem; margin-bottom:1rem" class="col-lg-12  row ">

                                    <div class="col-md-2 col-lg-2 col-sm-6 col-6 ">
                                        <select name="tacvu" class="form-select" id="tacvu">
                                            <option value="Tất cả">Chọn tác vụ</option>
                                            <option value="xoa">Xoá</option>
                                            <option value="duyet">Duyệt</option>
                                            <option value="huy">Huỷ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-6 col-6 ">
                                        <button name="apdung" type="submit" class="btn btn-primary" id="apdung">Áp
                                            dụng</button>
                                    </div>

                                    <!-- Giao diện -->
                                    <div class="col-md-2 col-lg-8 col-sm-3 row mt-4">
                                        <div style="display: flex; justify-content: right;" class="col-lg-12">
                                            <p>Đang hiển thị:
                                                <span id="so_dong_hien_tai">
                                                    <?php echo $_SESSION['sl_dong']; ?>
                                                </span>/
                                                <span id="tong-so-dong">
                                                    <?php
                                                        if($trangthai == 'Tất cả'){
                                                            $kq="SELECT count(*) as tong FROM binh_luan a 
                                                                        LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                                                                        LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                                                                        LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                                                                        where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'";
                                                        }else{
                                                            $kq="SELECT count(*) as tong FROM binh_luan a 
                                                                        LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                                                                        LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                                                                        LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                                                                        where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'
                                                                        and a.trangthai = '$trangthai'";
                                                        }
                                                       
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

                                                <th class="sort-header">Bài viết
                                                    <button id="asc" type="button" class="small-button"
                                                        data-sort="asc"><i class="fa fa-sort-asc"></i></button>
                                                    <button id="desc" type="button" class="small-button"
                                                        data-sort="desc"><i class="fa fa-sort-desc"></i></button>
                                                </th>
                                                <th>Người BL</th>
                                                <th>Nội dung</th>
                                                <th>Thời gian</th>

                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-container">
                                            <input type="hidden" id="tong_sd"
                                                value="<?php echo $_SESSION['tong_sd']; ?>">
                                            <?php
                                            $stt = 0;
                                            while ($row_binh_luan = mysqli_fetch_array($result_binh_luan)) {
                                               
                                                $stt = $stt + 1;
                                        ?>
                                            <tr>
                                                <!-- <form method="POST" enctype="multipart/form-data"> -->
                                                <td>
                                                    <input class="form-check-input check-item" name="check[]"
                                                        type="checkbox" value="<?php echo $row_binh_luan['bl_ma'] ?>">
                                                </td>
                                                <!-- </form> -->
                                                <td class="row-bai-viet"> <?php echo $stt ?> </td>

                                                <td>

                                                    <!-- <span class="badge bg-label-primary me-1"> -->
                                                    <?php 
                                                        // $trang_thai_bv = "SELECT * FROM kiem_duyet c
                                                        //                 RIGHT JOIN trang_thai d
                                                        //                 ON c.tt_ma = d.tt_ma";

                                                        // $result_trang_thai_bv = mysqli_query($conn,$trang_thai_bv);
                                                        // $row_trang_thai_bv = mysqli_fetch_assoc($result_trang_thai_bv);
                                                        // if($row_trang_thai_bv['bv_ma'] == $row_bai_viet['bv_ma']){
                                                        //     echo $row_trang_thai_bv['tt_ten']; 
                                                        // }else{
                                                            // echo $row_binh_luan['tt_ten'];
                                                        // }
                                                        if ( $row_binh_luan[ 'tt_ma' ] == 1 ) {

                                                            echo "<span class='badge bg-label-success me-1'>".$row_binh_luan[ 'tt_ten' ].'</span>';
                                            
                                                        } else if ( $row_binh_luan[ 'tt_ma' ] == 2 ) {
                                                            echo "<span class='badge bg-label-danger me-1'>".$row_binh_luan[ 'tt_ten' ].'</span>';
                                            
                                                        } else if ( $row_binh_luan[ 'tt_ma' ] == 4 ) {
                                            
                                                            echo "<span class='badge bg-label-dismissible me-1'>".$row_binh_luan[ 'tt_ten' ].'</span>';
                                                        } else {
                                                            echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
                                                        }
    
                                                    ?>
                                                    <!-- </span> -->
                                                </td>
                                                <td style="white-space: normal">
                                                    <?php echo $row_binh_luan['bv_tieude'] ?>
                                                </td>

                                                <td> <?php echo $row_binh_luan['nd_hoten'] ?> </td>
                                                <td style="white-space: normal">
                                                    <?php echo $row_binh_luan['bl_noidung'] ?>
                                                </td>
                                                <td><?php echo date_format(date_create($row_binh_luan['bl_thoigian']), "d-m-Y (H:i:s)"); ?>
                                                </td>

                                                <!-- Modal duyệt trạng thái-->
                                                <div class="modal fade" id="modal_duyet_bl" tabindex="-1"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalCenterTitle">Phê duyệt
                                                                    bình luận</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col mb-3">
                                                                            <label for="nameWithTitle"
                                                                                class="form-label">Trạng thái</label>
                                                                            <select name="trangthai_pd"
                                                                                class="form-select">
                                                                                <option value="Tất cả">Chọn trạng thái
                                                                                </option>
                                                                                <?php
                                                                                    $tt = "select * from trang_thai where tt_ma in(1,2,3)";
                                                                                    $result_tt = mysqli_query($conn, $tt);
                                                                                    while ($row_tt = mysqli_fetch_array($result_tt)) {
                                                                                    ?>
                                                                                <option
                                                                                    value="<?php echo $row_tt['tt_ma']; ?>">
                                                                                    <?php echo $row_tt['tt_ten']; ?>
                                                                                </option>
                                                                                <?php } ?>
                                                                            </select>
                                                                            <input name="id_binhluan" type="hidden"
                                                                                value="<?php echo $row_binh_luan['bl_ma']  ?>">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div style="padding: 0 1rem 1rem 0"
                                                                    class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        Hủy
                                                                    </button>
                                                                    <button name="capnhat" type="submit"
                                                                        class="btn btn-primary">Áp dụng</button>
                                                                </div>
                                                            </form>
                                                        </div>

                                                    </div>

                                                </div>

                                                <td>

                                                    <a id="dropdownHanhDong" data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        style="display:math; padding:0.1rem 0.6rem"
                                                        class="dropdown-item"
                                                        href="Xem_BinhLuan.php?tg=<?php echo $row_binh_luan['nd_username']?>&bl_ma=<?php echo $row_binh_luan['bl_ma']?>">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>

                                                    <div class="dt-button-collection dropdown-menu"
                                                        style="top: 55.9375px; left: 419.797px;min-width:7rem"
                                                        aria-labelledby="dropdownHanhDong">
                                                        <div role="menu">
                                                            <a href="Xem_BinhLuan.php?tg=<?php echo $row_binh_luan['nd_username']?>&bl_ma=<?php echo $row_binh_luan['bl_ma']?>"
                                                                class="dt-button buttons-print dropdown-item"
                                                                tabindex="0" type="button">
                                                                <span><i class=" fa fa-eye me-2"></i>Xem</span>
                                                            </a>
                                                            <!-- href="Sua_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>" -->
                                                            <a class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                type="button" data-bs-toggle="modal"
                                                                data-bs-target="#modal_duyet_bl">
                                                                <span><i class="bx bx-edit-alt me-2"></i>Phê
                                                                    duyệt</span>
                                                            </a>

                                                            <a href="#"
                                                                onclick="Xoa_Binhluan('<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>');"
                                                                class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                type="button">
                                                                <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                                            </a>

                                                        </div>
                                                    </div>

                                                    <!-- <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="Xem_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="Sua_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>">
                                                        <i class="bx bx-edit-alt me-1"></i>
                                                    </a>
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="#"
                                                        onclick="Xoa_Binhluan('<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>');">
                                                        <i class="bx bx-trash me-1"></i>
                                                    </a> -->

                                                </td>
                                            </tr>
                                            <?php } ?>

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
                url: 'sodong_BL.php',
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
                url: 'sodong_BL.php',
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
    <?php if (isset($_SESSION['thongbao_thucthi']) && $_SESSION['thongbao_thucthi']) { ?>
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("thongbao_thucthi");
        var successToastInstance = new bootstrap.Toast(successToast);
        $("#loader").hide();
        successToastInstance.show();
      });
    <?php
      unset($_SESSION['thongbao_thucthi']);
      unset($_SESSION['bg_thongbao']);
    }
    ?>
  </script>
</body>

</html>