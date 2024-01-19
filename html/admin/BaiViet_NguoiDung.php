<?php
    session_start();
    include("./includes/connect.php");
    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_username = $_GET['this_username'];
    $vt = $_GET['vt'];

    $vai_tro = "SELECT * FROM vai_tro WHERE vt_ma='$vt'";
    $result_vai_tro = mysqli_query($conn,$vai_tro);
    $row_vai_tro = mysqli_fetch_assoc($result_vai_tro);
    
    $nguoi_dung = "SELECT * FROM nguoi_dung a, vai_tro b WHERE a.vt_ma=b.vt_ma and nd_username='$this_username'";
    $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
	$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                    where a.nd_username = '$this_username'";
    $result_bai_viet = mysqli_query($conn,$bai_viet);
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý Tài khoản</title>

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
                    <!-- <div class="col-lg-6">
                            <div class="demo-inline-spacing mt-3">
                    <div class="list-group list-group-horizontal-md text-md-center">
                    <ul class="nav nav-pills flex-column flex-md-row mb-3">
                            <li class="nav-item">
                          <a
                            class=" nav-link list-group-item list-group-item-action active"
                            id="home-list-item"
                            data-bs-toggle="list"
                            href="#horizontal-home"
                            >Tài khoản</a
                          ></li><li class="nav-item">
                          <a
                            class=" nav-link list-group-item list-group-item-action"
                            id="profile-list-item"
                            data-bs-toggle="list"
                            href="#horizontal-profile"
                            >bài viết</a
                          >
                          </li>

</ul>
                          <div class="tab-content px-0 mt-0">
                          <div class="tab-pane fade show active" id="horizontal-home">
                           1
                          </div>
                          <div class="tab-pane fade" id="horizontal-profile">
                           2
                          </div>
                          
                        </div>
                    </div></div> -->
                        <!-- Basic Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.php">Home</a>
                                </li> 
                                <li class="breadcrumb-item">
                                    <a href="QL_NguoiDung.php?vt=<?php echo $row_vai_tro['vt_ma']?>">Quản lý <?php echo $row_vai_tro['vt_ten']?></a>
                                </li>  
                                <li class="breadcrumb-item active">Chi tiết <?php echo $row_vai_tro['vt_ten']?></li>
                            </ol>
                        </nav>
                 
                        <ul class="nav nav-pills flex-column flex-md-row mb-3">
                            <li class="nav-item">
                                <a class="nav-link "
                                    href="Xem_NguoiDung.php?this_username=<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>"><i
                                        class="bx bx-user me-1"></i>
                                    Tài khoản</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link "
                                    href="DoiMK_NguoiDung.php?this_username=<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>"><i
                                        class=" fa fa-lock me-1"></i>
                                    Mật khẩu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:void(0);"><i class="fa fa-file-text"
                                        aria-hidden="true"></i> Bài viết</a>
                            </li>

                        </ul>

                       

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <!-- <h5 class="card-header">Các bài viết đã đăng</h5> -->
                                    <div class="card-body">
                                        <!-- Basic Bootstrap Table -->
                                        <div class="card">
                                            <h5 class="card-header">Danh sách các bài viết của "<?php echo $row_nguoi_dung['nd_hoten']?>"</h5>
                                            <div class="table-responsive text-nowrap">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>STT</th>
                                                            <th>Trạng thái</th>
                                                            <th>Tiêu đề</th>
                                                            <th>Ngày đăng</th>
                                                            <th>Lượt xem</th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="table-border-bottom-0">
                                                        <?php
                                                            $stt = 0;
                                                            if(mysqli_num_rows($result_bai_viet) > 0){
                                                                while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                                                                    $stt = $stt + 1;
                                                        ?>
                                                        <tr>
                                                            <td> <?php echo $stt ?> </td>
                                                            <td>
                                                                <!-- <span class="badge bg-label-primary me-1">
                                                                    <?php 
                                                                        // $trang_thai_bv = "SELECT * FROM kiem_duyet c
                                                                        //                     RIGHT JOIN trang_thai d
                                                                        //                     ON c.tt_ma = d.tt_ma";

                                                                        // $result_trang_thai_bv = mysqli_query($conn,$trang_thai_bv);
                                                                        // $row_trang_thai_bv = mysqli_fetch_assoc($result_trang_thai_bv);
                                                                        // if($row_trang_thai_bv['bv_ma'] == $row_bai_viet['bv_ma']){
                                                                        //     echo $row_trang_thai_bv['tt_ten']; 
                                                                        // }else{
                                                                        //     echo "Chờ duyệt";
                                                                        // }
                    
                                                                    ?>
                                                                </span> -->
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
                                                            <td > <?php echo $row_bai_viet['bv_tieude'] ?> </td>
                                                            <td><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>
                                                            </td>
                                                           
                                                            <td> <?php echo $row_bai_viet['bv_luotxem'] ?> </td>

                                                            <td >
                                                                <a style="display:math; padding:0.1rem 0.6rem"
                                                                    class="dropdown-item"
                                                                    href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma']?>&tg=<?php echo $row_bai_viet['nd_username']?>">
                                                                    <i class="fa fa-eye"></i>
                                                                </a>

                                                            </td>
                                                        </tr>
                                                        <?php } } else{ echo "<tr><td colspan='6' class = 'text-center'><i>(Hiện tại chưa có bài viết nào)</i><td ></tr>";} ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!--/ Basic Bootstrap Table -->
                                    </div>
                                </div>
                            </div>
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
    <script src="./assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>