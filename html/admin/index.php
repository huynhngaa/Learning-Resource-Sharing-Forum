<?php
    session_start();
    include("./includes/connect.php");

    // if(!isset($_SESSION['Admin'])){
    //     echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
    //     header("Refresh: 0;url=login.php");  
    // }else{}
    //  $dl_baiviet="SELECT MONTH(bv_ngaydang) AS thang, COUNT(*) AS so_bai_viet
    //                 FROM bai_viet
    //                 GROUP BY thang";
    // $result_dl_baiviet = mysqli_query($conn,$dl_baiviet);
   
    // $data = array();
    // while ($row_dl_baiviet = mysqli_fetch_array($result_dl_baiviet)) {
    //     $data[] = array(
    //         'thang' => $row_dl_baiviet['thang'],
           
    //     );
    // }

    unset($data_nguoidung);
    unset($data_trangthai);
    unset($data);
    unset($data_dl_vaitro);


    $trangthai="SELECT tt_ten, count(*) as soluong FROM bai_viet a 
                LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                RIGHT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                GROUP BY c.tt_ma;";
    $result_trangthai = mysqli_query($conn,$trangthai);
   
    $data_trangthai = array();
    while ($row_trangthai = mysqli_fetch_array($result_trangthai)) {
        $data_trangthai[] = array(
            'trangthai' => $row_trangthai['tt_ten'],
            'soluong' => $row_trangthai['soluong']

        );
    }

    $dl_vaitro="SELECT vt_ten, count(*) as soluong FROM nguoi_dung a 
                    LEFT JOIN vai_tro c ON c.vt_ma = a.vt_ma
                    where a.vt_ma in(1,2,3,4)
                    GROUP BY c.vt_ten";
    $result_dl_vaitro = mysqli_query($conn,$dl_vaitro);

    $data_dl_vaitro = array();
    while ($row_dl_vaitro = mysqli_fetch_array($result_dl_vaitro)) {
        $data_dl_vaitro[] = array(
        'vaitro' => $row_dl_vaitro['vt_ten'],
        'soluong' => $row_dl_vaitro['soluong']

        );
    }

    $nam = date('Y');

    $dl_baiviet="SELECT  count(*) as soluong, MONTH(bv_ngaydang) as thang, YEAR(bv_ngaydang) as nam
                    FROM bai_viet a
                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                    where (c.tt_ma IS NULL OR c.tt_ma != 4)
                    GROUP BY thang, nam
                    having nam = '$nam'";
    $result_dl_baiviet = mysqli_query($conn,$dl_baiviet);

    $data = array();
    while ($row_dl_baiviet = mysqli_fetch_array($result_dl_baiviet)) {
        $data[] = array(
            'thang' => "Tháng ".$row_dl_baiviet['thang']."",
            'nam' => $row_dl_baiviet['nam'],
            'soluong' => $row_dl_baiviet['soluong']

        );
    }

    $dl_nguoidung="SELECT  count(*) as soluong, MONTH(nd_ngaytao) as thang, YEAR(nd_ngaytao) as nam
                    FROM nguoi_dung 
                    where vt_ma in(3,4)
                    GROUP BY thang, nam
                    having nam = '$nam'";
    $result_dl_nguoidung = mysqli_query($conn,$dl_nguoidung);

    $data_nguoidung = array();
    while ($row_dl_nguoidung = mysqli_fetch_array($result_dl_nguoidung)) {
        $data_nguoidung[] = array(
            'thang' => "Tháng ".$row_dl_nguoidung['thang']."",
            'nam' => $row_dl_nguoidung['nam'],
            'soluong' => $row_dl_nguoidung['soluong']

        );
    }


?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard</title>

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
                include("includes/menu.php");
            ?>

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php
                    // include_once("includes/navbar.php");
                    include_once("includes/navbar.php");
                ?>

                <!-- / Navbar -->

                <style>
                .card.card-border-shadow-primary::after {
                    border-bottom-color: #c3c4ff;
                }

                .card[class*=card-border-shadow-] {
                    position: relative;
                    border-bottom: none;
                    transition: all .2s ease-in-out;
                    z-index: 1;
                }

                .card[class*=card-border-shadow-]:hover::after {
                    border-bottom-width: 3px;
                }

                .card.card-border-shadow-danger:hover::after {
                    border-bottom-color: #ff3e1d;
                }

                .card[class*=card-border-shadow-]::after {
                    content: "";
                    position: absolute;
                    bottom: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    border-bottom-width: 2px;
                    border-bottom-style: solid;
                    border-radius: 0.5rem;
                    transition: all .2s ease-in-out;
                    z-index: -1;
                }

                .card {
                    background-clip: padding-box;
                    box-shadow: 0 2px 6px 0 rgba(67, 89, 113, .12);
                }

                .card.card-border-shadow-danger::after {
                    border-bottom-color: #ffb2a5;
                }

                .card.card-border-shadow-info::after {
                    border-bottom-color: #9ae7f7;
                }
                </style>

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row text-center">
                            <div class="col-md-12 col-lg-4 mb-4">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="d-flex align-items-end row">
                                        <div>
                                            <div class="card-body">
                                                <h6 class="card-title mb-1 text-nowrap">NGƯỜI DÙNG</h6>

                                                <a href="QL_NguoiDung.php?vt=4">
                                                    <h2 class="card-title text-primary mb-1"><i class="fa fa-users"></i>
                                                        <?php 
                                                            $nd="SELECT count(*) as tong FROM nguoi_dung where vt_ma in(3,4)";
                                                            $result_nd = mysqli_query($conn,$nd);
                                                            $row_nd = mysqli_fetch_assoc($result_nd);
        
                                                            echo $row_nd['tong'];
                                                        ?>
                                                    </h2>
                                                </a>


                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>


                            <div class="col-md-12 col-lg-4 mb-4">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="d-flex align-items-end row">
                                        <div>
                                            <div class="card-body">
                                                <h6 class="card-title mb-1 text-nowrap">BÀI VIẾT</h6>
                                                <a href="QL_BaiViet.php">
                                                    <h2 class="card-title text-primary mb-1"><i
                                                            class="fa fa-file-text"></i>
                                                        <?php 
                                                            $bv="SELECT count(*) as tong FROM bai_viet";
                                                            $result_bv = mysqli_query($conn,$bv);
                                                            $row_bv = mysqli_fetch_assoc($result_bv);
        
                                                            echo $row_bv['tong'];
                                                        ?>
                                                    </h2>
                                                </a>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-4 mb-4">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="d-flex align-items-end row">
                                        <div>
                                            <div class="card-body">
                                                <h6 class="card-title mb-1 text-nowrap">BÌNH LUẬN</h6>
                                                <a href="QL_BinhLuan.php">
                                                    <h2 class="card-title text-primary mb-1"><i
                                                            class="fa fa-comments"></i>
                                                        <?php 
                                                            $bl="SELECT count(*) as tong FROM binh_luan";
                                                            $result_bl = mysqli_query($conn,$bl);
                                                            $row_bl = mysqli_fetch_assoc($result_bl);
        
                                                            echo $row_bl['tong'];
                                                        ?>
                                                    </h2>
                                                </a>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>



                            <div class="col-md-12 col-lg-4">
                                <div class="row">
                                    <div class="col-lg-6 col-md-3 col-6 mb-4">
                                        <div class="card">
                                            <a href="QL_BaiViet.php?tt=1">
                                                <div class="card-body">

                                                    <span class="d-block"><i class="fa fa-check-square"></i> Đã
                                                        duyệt</span>
                                                    <h4 class="card-title mb-1">
                                                        <?php 
                                                         $daduyet="SELECT count(*) as bv_daduyet FROM bai_viet a
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where c.tt_ma = '1';";
                                                         $result_daduyet = mysqli_query($conn,$daduyet);
                                                         $row_daduyet = mysqli_fetch_assoc($result_daduyet);
     
                                                         echo $row_daduyet['bv_daduyet'];
                                                    ?>
                                                    </h4>

                                                    <!-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i>
                                                Số bài viết đã duyệt</small> -->
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3 col-6 mb-4">
                                        <div class="card">
                                            <a href="QL_BaiViet.php?tt=2">
                                                <div class="card-body">
                                                    <span class="d-block"><i class="fa fa-cancel"></i> Đã hủy</span>
                                                    <h4 class="card-title mb-1">
                                                        <?php 
                                                         $dahuy="SELECT count(*) as bv_dahuy FROM bai_viet a
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where c.tt_ma = '2';";
                                                         $result_dahuy = mysqli_query($conn,$dahuy);
                                                         $row_dahuy = mysqli_fetch_assoc($result_dahuy);
     
                                                         echo $row_dahuy['bv_dahuy'];
                                                    ?>
                                                    </h4>
                                                    <!-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i>
                                                Số bài viết chờ duyệt</small> -->
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3 col-6 mb-4">
                                        <div class="card">
                                            <a href="KiemDuyet.php">
                                                <div class="card-body">
                                                    <span class="d-block"><i class="fa fa-spinner"></i> Bài viết Chờ
                                                        duyệt</span>
                                                    <h4 class="card-title mb-1">
                                                        <?php 
                                                         $choduyet="SELECT count(*) as bv_choduyet FROM bai_viet a
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where c.tt_ma is null;";
                                                         $result_choduyet = mysqli_query($conn,$choduyet);
                                                         $row_choduyet = mysqli_fetch_assoc($result_choduyet);
     
                                                         echo $row_choduyet['bv_choduyet'];
                                                    ?>
                                                    </h4>
                                                    <!-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i>
                                                Số bài viết chờ duyệt</small> -->
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-3 col-6 mb-4">
                                        <div class="card">
                                            <a href="QL_BinhLuan.php?tt=3">
                                                <div class="card-body">
                                                    <span class="d-block"><i class="fa fa-pencil-square"></i> Bình luận
                                                        chờ
                                                        duyệt</span>
                                                    <h4 class="card-title mb-1">
                                                        <?php 
                                                         $bl_choduyet="SELECT count(*) as bl_choduyet FROM binh_luan a 
                                                                        LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                                                                        where t.tt_ma= '3';";
                                                         $result_bl_choduyet = mysqli_query($conn,$bl_choduyet);
                                                         $row_bl_choduyet = mysqli_fetch_assoc($result_bl_choduyet);
     
                                                         echo $row_bl_choduyet['bl_choduyet'];
                                                    ?>
                                                    </h4>
                                                    <!-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i>
                                                Số bài viết chờ duyệt</small> -->
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-lg-12  mb-4">
                                        <div class="card h-100 ">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <h6 class="card-title m-0 me-2">Tỷ lệ (%) người dùng trên hệ thống</h6>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="performanceId"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="performanceId">
                                                        <a class="dropdown-item" href="javascript:void(0);">Last 28
                                                            Days</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">Last
                                                            Month</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">Last
                                                            Year</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <!-- <div class="col-6">
                                                        <small>Earnings: <span class="fw-medium">$846.17</span></small>
                                                    </div>
                                                    <div class="col-6">
                                                        <small>Sales: <span class="fw-medium">25.7M</span></small>
                                                    </div> -->

                                                    <div id="tyle_nguoidung"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 col-lg-12 mb-4">
                                        <div class="card h-100">
                                            <div class="card-header d-flex align-items-center justify-content-between">
                                                <div class="card-title mb-0">
                                                    <h6 class="m-0 me-2">Top bài viết có đánh giá cao nhất</h6>
                                                    <!-- <small class="text-muted">Compared To Last Month</small> -->
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn p-0" type="button" id="conversionRate"
                                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end"
                                                        aria-labelledby="conversionRate">
                                                        <a class="dropdown-item" href="javascript:void(0);">Select
                                                            All</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                                        <a class="dropdown-item" href="javascript:void(0);">Share</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">

                                                <ul class="p-0 m-0">
                                                    <?php 
                                                    $top_danhgia = "SELECT a.*,b.*, ROUND(AVG(dg_diem)) as diem_TB FROM bai_viet a
                                                                        LEFT JOIN nguoi_dung b ON a.nd_username=b.nd_username
                                                                        LEFT JOIN danh_gia c ON a.bv_ma=c.bv_ma
                                                                        group by a.bv_ma
                                                                        having diem_TB > 0
                                                                        order by diem_TB DESC
                                                                        LIMIT 3 ";
                                                    $result_top_danhgia = mysqli_query($conn, $top_danhgia);
                                                    $stt = 0;
                                                    while ($row_top_danhgia = mysqli_fetch_array($result_top_danhgia)) {
            
                                                        $stt = $stt + 1;
                                                ?>
                                                    <li class="d-flex mb-4">
                                                        <div
                                                            class="d-flex w-100 flex-wrap justify-content-between gap-2">
                                                            <div style="text-align: left " class="me-2 col-lg-6">
                                                                <h6 style="text-align: left" class="mb-0">
                                                                    <?php echo $row_top_danhgia['bv_tieude']?></h6>
                                                                <small style="text-align: left" class="text-muted">Tác
                                                                    giả:
                                                                    <?php echo $row_top_danhgia['nd_hoten']?></small>
                                                            </div>
                                                            <div class="user-progress">
                                                                <!-- <i class=" fa fa-star  text-success me-2"></i> -->
                                                                <!-- <span><?php echo $row_top_danhgia['diem_TB']?></span> -->
                                                                <?php
                                                                    $diem_trung_binh = $row_top_danhgia['diem_TB'];
                                                                    $so_sao = round($diem_trung_binh); // Làm tròn điểm

                                                                    // Duyệt qua từng sao và in ra
                                                                    for ($i = 1; $i <= 5; $i++) {
                                                                        if ($i <= $so_sao) {
                                                                            echo '<i stye="font-size:10px;" class="fa fa-star text-success me-1"></i>';
                                                                        } else {
                                                                            echo '<i stye="font-size:10px" class="fa fa-star me-1"></i>';
                                                                        }
                                                                    }
                                                                    ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>



                            <!-- New Visitors & Activity -->
                            <div class="col-lg-8 mb-4">
                                <div class="card">
                                    <div class="card-body row g-4">
                                        <div class="col-md-6 pe-md-4 card-separator">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <h5 class="mb-0">Số bài viết của mỗi tháng</h5>
                                                <!-- <small>Last Week</small> -->
                                                <style>
                                                .btn-label-primary {
                                                    color: #696cff;
                                                    border-color: rgba(0, 0, 0, 0);
                                                    background: #e7e7ff;
                                                }
                                                </style>
                                                <div class="text-center">
                                                    <div class="dropdown">

                                                        <select
                                                            class="form-select btn btn-sm btn-label-primary dropdown-toggle"
                                                            id="bv_nam">

                                                            <?php
                                                            $nam = date('Y');
                                                            for($i = $nam; $i >= 2021; $i--){
                                                                echo "<option value='".$i."'>".$i."</option>";
                                                            }
                                                            ?>



                                                        </select>

                                                    </div>
                                                </div>

                                            </div>
                                            <div id="sl_baiviet"></div>

                                        </div>
                                        <div class="col-md-6 pe-md-4 ">
                                            <div class="card-title d-flex align-items-start justify-content-between">
                                                <h5 class="mb-0">Số người dùng của mỗi tháng</h5>
                                                <!-- <small>Last Week</small> -->
                                                <style>
                                                .btn-label-primary {
                                                    color: #696cff;
                                                    border-color: rgba(0, 0, 0, 0);
                                                    background: #e7e7ff;
                                                }
                                                </style>
                                                <div class="text-center">
                                                    <div class="dropdown">
                                                        <select
                                                            class="form-select btn btn-sm btn-label-primary dropdown-toggle"
                                                            id="nd_nam">
                                                            <?php
                                                            $nam = date('Y');
                                                            for($i = $nam; $i >= 2021; $i--){
                                                                echo "<option value='".$i."'>".$i."</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                            <div id="sl_nguoidung"></div>


                                        </div>

                                    </div>


                                </div>


                                <div class="col-md-12 col-lg-12 mb-4 mb-md-0 mt-4">

                                    <div class="card">
                                        <div class="card-header d-flex  justify-content-between">
                                            <h5 class="card-title m-0 me-2">Top 3 bài viết có lượt xem cao nhất</h5>
                                        </div>
                                        <div class="table-responsive ">

                                            <table style="text-align: left" class="table  border-top">
                                                <thead>
                                                    <tr>
                                                        <th>STT</th>
                                                        <th>Tiêu đề</th>
                                                        <th>Tác giả</th>
                                                        <th>Lượt xem</th>
                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0">
                                                    <?php 
                                        $top_bai_viet = "SELECT * FROM bai_viet a
                                                            LEFT JOIN nguoi_dung b ON a.nd_username=b.nd_username
                                                            where bv_luotxem > 0
                                                            order by bv_luotxem DESC
                                                            LIMIT 3 ";
                                        $result_top_bai_viet = mysqli_query($conn, $top_bai_viet);
                                        $stt = 0;
                                        while ($row_top_bai_viet = mysqli_fetch_array($result_top_bai_viet)) {
 
                                             $stt = $stt + 1;
                                    ?>
                                                    <tr>
                                                        <td>
                                                            <?php echo $stt ?>
                                                            <!-- <div class="d-flex align-items-center">
                                            
                                            <div class="d-flex flex-column">
                                                <span class="fw-medium lh-1">OnePlus 7Pro</span>
                                                <small class="text-muted">OnePlus</small>
                                            </div>
                                            </div> -->
                                                        </td>
                                                        <td>
                                                            <!-- <span class="badge bg-label-primary rounded-pill badge-center p-3 me-2">
                                                <i class="bx bx-mobile-alt bx-xs"></i>
                                            </span>  -->
                                                            <?php echo $row_top_bai_viet['bv_tieude'] ?>
                                                        </td>
                                                        <td>
                                                            <!-- <div class="text-muted lh-1"><span class="text-primary fw-medium">$120</span>/499</div>
                                            <small class="text-muted">Partially Paid</small> -->
                                                            <?php echo $row_top_bai_viet['nd_hoten'] ?>
                                                        </td>
                                                        <td><span
                                                                class="badge bg-label-primary"><?php echo $row_top_bai_viet['bv_luotxem'] ?></span>
                                                        </td>
                                                        <td>
                                                            <a style="display:math; padding:0.1rem 0.6rem"
                                                                class="dropdown-item"
                                                                href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_top_bai_viet['bv_ma'] ?>&tg=<?php echo $row_top_bai_viet['nd_username'] ?>">
                                                                <i style="color:darkorange" class="fa fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 col-lg-12 mb-4 mt-4">
                                    <div class=" row ">
                                        <div class="col-md-6 ">
                                            <div class="card ">
                                                <div
                                                    class="card-header d-flex align-items-center justify-content-between">
                                                    <div class="card-title mb-0">
                                                        <h6 class="m-0 me-2">Top bài viết có bình luận cao nhất</h6>
                                                        <!-- <small class="text-muted">Compared To Last Month</small> -->
                                                    </div>

                                                </div>
                                                <div class="card-body">

                                                    <ul class="p-0 m-0">
                                                        <?php 
                                                    $top_binhluan = "SELECT a.bv_ma, a.bv_tieude, b.nd_hoten, count(*) as tong FROM bai_viet a
                                                                        LEFT JOIN nguoi_dung b ON a.nd_username=b.nd_username
                                                                        right JOIN binh_luan c ON a.bv_ma=c.bv_ma
                                                                        group by a.bv_ma
                                                                        order by tong DESC
                                                                        LIMIT 3;";
                                                    $result_top_binhluan = mysqli_query($conn, $top_binhluan);
                                                    $stt = 0;
                                                    while ($row_top_binhluan = mysqli_fetch_array($result_top_binhluan)) {
            
                                                     
                                                ?>
                                                        <li class="d-flex mb-4">
                                                            <div
                                                                class="d-flex w-100 flex-wrap justify-content-between gap-2">
                                                                <div style="text-align: left " class="me-2 col-lg-6">
                                                                    <h6 style="text-align: left" class="mb-0">
                                                                        <?php echo $row_top_binhluan['bv_tieude']?></h6>
                                                                    <small style="text-align: left"
                                                                        class="text-muted">Tác giả:
                                                                        <?php echo $row_top_binhluan['nd_hoten']?></small>
                                                                </div>
                                                                <div class="user-progress">
                                                                    <i style="color:#696cff"
                                                                        class=" fa fa-comments me-2"></i>
                                                                    <span><?php echo $row_top_binhluan['tong']?></span>

                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="card h-100">
                                                <div
                                                    class="card-header d-flex align-items-center justify-content-between">
                                                    <div class="card-title mb-0">
                                                        <h6 class="m-0 me-2">Top bình luận</h6>
                                                        <!-- <small class="text-muted">Compared To Last Month</small> -->
                                                    </div>

                                                </div>
                                                <div class="card-body">

                                                    <ul class="p-0 m-0">
                                                        <?php 
                                                    $top_nd_binhluan = "SELECT c.nd_username, b.nd_hoten, count(*) as tong  
                                                                        FROM binh_luan  a
                                                                        left JOIN bai_viet c ON a.bv_ma=c.bv_ma
                                                                        left JOIN nguoi_dung b ON a.nd_username=b.nd_username
                                                                        group by a.nd_username
                                                                        order by tong DESC
                                                                        LIMIT 5 ";
                                                    $result_top_nd_binhluan = mysqli_query($conn, $top_nd_binhluan);
                                                    $stt = 0;
                                                    while ($row_top_nd_binhluan = mysqli_fetch_array($result_top_nd_binhluan)) {
            
                                                ?>
                                                        <li class="d-flex mb-4">
                                                            <div
                                                                class="d-flex w-100 flex-wrap justify-content-between gap-2">
                                                                <div style="text-align: left " class="me-2 col-lg-6">
                                                                    <h6 style="text-align: left" class="mb-0">
                                                                        <?php echo $row_top_nd_binhluan['nd_hoten']?>
                                                                    </h6>
                                                                    <!-- <small style="text-align: left"
                                                                    class="text-muted"><?php echo $row_top_nd_binhluan['nd_hoten']?></small> -->
                                                                </div>
                                                                <div class="user-progress">
                                                                    <i class=" fa fa-comments  text-success me-2"></i>
                                                                    <span><?php echo $row_top_nd_binhluan['tong']?></span>

                                                                </div>
                                                            </div>
                                                        </li>
                                                        <?php } ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </div>

                            <!--/ Total Income -->
                        </div>

                    </div>
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

    <!-- <div class="buy-now">
      <a
        href="https://themeselection.com/products/sneat-bootstrap-html-admin-template/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div> -->

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

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script>
    // Biến dữ liệu PHP thành JavaScript
    var chartData = <?php echo json_encode($data); ?>;
    var chart_Data_Nguoidung = <?php echo json_encode($data_nguoidung); ?>;
    var chart_Data_Trangthai = <?php echo json_encode($data_trangthai); ?>;
    var chart_Data_Vaitro = <?php echo json_encode($data_dl_vaitro); ?>;
    console.log(chartData);
    console.log(chart_Data_Nguoidung);
    console.log(chart_Data_Vaitro);


    Highcharts.chart('sl_baiviet', {
        chart: {
            type: 'column'
        },
        credits: {
            enabled: false // Loại bỏ nhãn "Highcharts.com"
        },
        title: {
            text: null
        },
        xAxis: {
            categories: chartData.map(item => item.thang)
        },
        yAxis: {
            title: {
                text: 'Số lượng'
            }
        },
        series: [{
            name: "SL bài viết",
            data: chartData.map(item => parseInt(item.soluong)),
            color: 'rgb(0, 66, 145)'
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 200
                },
                chartOptions: {
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    },

                    yAxis: {
                        labels: {
                            align: 'left',
                            x: 0,
                            y: -5
                        },
                        title: {
                            text: null
                        }
                    },
                    subtitle: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    }
                }
            }]
        }
    });

    // Tạo biểu đồ "Activity"
    Highcharts.chart('sl_nguoidung', {
        chart: {
            type: 'line'
        },
        credits: {
            enabled: false // Loại bỏ nhãn "Highcharts.com"
        },
        title: {
            text: null
        },
        xAxis: {
            categories: chart_Data_Nguoidung.map(item => item.thang)
        },
        yAxis: {
            title: {
                text: 'Số lượng'
            }
        },
        series: [{
            name: 'SL người dùng',
            data: chart_Data_Nguoidung.map(item => parseInt(item.soluong)),
            color: 'rgb(0, 103, 182)'
        }],
        responsive: {
            rules: [{
                condition: {
                    maxWidth: 250
                },
                chartOptions: {
                    legend: {
                        align: 'center',
                        verticalAlign: 'bottom',
                        layout: 'horizontal'
                    },

                    yAxis: {
                        labels: {
                            align: 'left',
                            x: 0,
                            y: -5
                        },
                        title: {
                            text: null
                        }
                    },
                    subtitle: {
                        text: null
                    },
                    credits: {
                        enabled: false
                    }
                }
            }]
        }
    });

    // Data retrieved from https://netmarketshare.com/
    // Make monochrome colors
    const colors = Highcharts.getOptions().colors.map((c, i) =>
        // Start out with a darkened base color (negative brighten), and end
        // up with a much brighter color
        Highcharts.color(Highcharts.getOptions().colors[0])
        .brighten((i - 3) / 7)
        .get()
    );

    // Build the chart
    Highcharts.chart('tyle_nguoidung', {
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        credits: {
            enabled: false // Loại bỏ nhãn "Highcharts.com"
        },
        title: {
            text: null,
            align: 'left'
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                colors,
                borderRadius: 5,
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b><br>{point.percentage:.1f} %',
                    distance: -50,
                    filter: {
                        property: 'percentage',
                        operator: '>',
                        value: 4
                    }
                }
            }
        },
        series: [{
            name: 'Tỷ lệ',
            data: chart_Data_Vaitro.map(item => ({
                name: item.vaitro,
                y: parseInt(item.soluong)
            }))
        }]
    });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        // Xử lý khi năm thay đổi ở biểu đồ bài viết
        $('#bv_nam').change(function() {
            updateBaiviet();
        });

        // Xử lý khi năm thay đổi ở biểu đồ người dùng
        $('#nd_nam').change(function() {
            updateNguoidung();
        });

        // Hàm cập nhật dữ liệu năm cho biểu đồ bài viết
        function updateBaiviet() {
            var chon_bv_nam = $('#bv_nam').val();
            $.ajax({
                url: 'ajax_index_nam.php',
                data: {
                    bv_nam: chon_bv_nam
                },
                success: function(data) {
                    var chartData = JSON.parse(data);
                    updateBaivietChart(chartData);
                }
            });
        }

        // Hàm cập nhật biểu đồ bài viết
        function updateBaivietChart(data) {
            var chartBaiviet = Highcharts.charts[0]; // Biểu đồ bài viết (cột)

            chartBaiviet.xAxis[0].setCategories(data.map(item => item.thang));
            chartBaiviet.series[0].setData(data.map(item => parseInt(item.soluong)));
        }

        // Hàm cập nhật dữ liệu năm cho biểu đồ người dùng
        function updateNguoidung() {
            var chon_nd_nam = $('#nd_nam').val();
            $.ajax({
                url: 'ajax_index_nam_nd.php',
                data: {
                    nd_nam: chon_nd_nam
                },
                success: function(data) {
                    var chartData = JSON.parse(data);
                    updateNguoidungChart(chartData);
                }
            });
        }

        // Hàm cập nhật biểu đồ người dùng
        function updateNguoidungChart(data) {
            var chartNguoidung = Highcharts.charts[1]; // Biểu đồ người dùng (đường)

            chartNguoidung.xAxis[0].setCategories(data.map(item => item.thang));
            chartNguoidung.series[0].setData(data.map(item => parseInt(item.soluong)));
        }
    });
    </script>




</body>

</html>