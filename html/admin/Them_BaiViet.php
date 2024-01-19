<?php
session_start();

include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
    if (isset($_POST['Them'])) {
        $tieude = $_POST['tieude'];
        $danhmuc = $_POST['danhmuc'];
        $noidung = $_POST['noidung'];
        $username = $_SESSION['Admin'];
        $kichthuoc = 0; // Mặc định

        // if (isset($_FILES['file'])) {
        //     $file = $_FILES['file']['name'];
        //     $file_tmp_name = $_FILES['file']['tmp_name'];
        //     move_uploaded_file($file_tmp_name, './uploads/' . $file);

        //     // Lấy kích thước tệp tin (đơn vị bytes)
        //     $kichthuoc = filesize('./uploads/' . $file);
        // }

        $them_baiviet = "INSERT INTO bai_viet (dm_ma, nd_username, bv_tieude, bv_noidung) VALUES ('$danhmuc','$username','$tieude','$noidung')";
        mysqli_query($conn, $them_baiviet);

        $bv_ma = mysqli_insert_id($conn);

        // Kiểm tra xem tệp tin đã được tải lên hay không
        // if (!empty($file)) {
        //     $them_tailieu = "INSERT INTO tai_lieu (bv_ma, tl_tentaptin, tl_kichthuoc) VALUES ('$bv_ma','$file','$kichthuoc')";
        //     mysqli_query($conn, $them_tailieu);
        // }

        if (!empty(array_filter($_FILES['file']['name']))) {
            $files = array_filter($_FILES['file']['name']);

            foreach ($files as $key => $file_name) {
                $file_tmp_name = $_FILES['file']['tmp_name'][$key];
                $upload_directory = './uploads/';
                move_uploaded_file($file_tmp_name, $upload_directory . $file_name);

                $kichthuoc = filesize($upload_directory . $file_name);

                $them_tailieu = "INSERT INTO tai_lieu (bv_ma, tl_tentaptin, tl_kichthuoc) VALUES ('$bv_ma','$file_name','$kichthuoc')";
                mysqli_query($conn, $them_tailieu);
            }
        }

        echo "<script>alert('Thêm bài viết mới thành công!');</script>";
        // header("Refresh: 0;url=QL_Baiviet.php");
    }
}
?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Thêm Người dùng</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

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
                                <li class="breadcrumb-item"><a href="QL_BaiViet.php">Quản lý bài viết</a> </li>
                                <li class="breadcrumb-item active">Thêm bài viết</li>
                            </ol>
                        </nav>

                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Thêm bài viết</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Tiêu
                                                    đề</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                            class="bx bx-user"></i></span>
                                                    <input name="tieude" type="text" class="form-control"
                                                        id="basic-icon-default-fullname" placeholder="Nhập vào họ tên"
                                                        aria-label="Nhập vào họ tên"
                                                        aria-describedby="basic-icon-default-fullname2" />
                                                </div>
                                            </div>


                                            <div class="mb-3">
                                                <label for="defaultSelect" class="form-label">Khối lớp</label>
                                                <select name="khoilop" id="khoilop" class="form-select">
                                                    <option disabled selected>Chọn khối lớp</option>
                                                    <?php
                                                        $khoi_lop = "select * from khoi_lop";
                                                        $result_khoi_lop = mysqli_query($conn, $khoi_lop);
                                                        while ($row_khoi_lop = mysqli_fetch_array($result_khoi_lop)) {
                                                        ?>
                                                    <option value="<?php echo $row_khoi_lop['kl_ma']; ?>">
                                                        <?php echo $row_khoi_lop['kl_ten']; ?>
                                                    </option>
                                                    <?php }  ?>
                                                </select>
                                            </div>


                                            <div class="mb-3" id="data-monhoc">
                                                
                                            </div>

                                            <div class="mb-3" id="data-danhmuc">
                                                
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-company">Nội
                                                    dung</label>
                                                <div class="input-group input-group-merge">
                                                    
                                                    <textarea name="noidung" type="text" class="form-control"  ></textarea>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-company">Tập tin đính kèm</label>
                                                <div class="input-group input-group-merge">
                                                    
                                                    <input  name="file[]" type="file" id="basic-icon-default-company"
                                                        class="form-control" placeholder="Nhập vào username"
                                                        aria-label="Nhập vào username"
                                                        aria-describedby="basic-icon-default-company2" multiple  />
                                                </div>
                                            </div>




                                            <button name="Them" type="submit" class="btn btn-primary">Thêm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <script src="ckeditor/ckeditor/ckeditor.js"></script>
        <script src="ckfinder/ckfinder/ckfinder.js"></script>

        <script>
        CKEDITOR.replace('noidung', {
            filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
            filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'

        })
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
        $(document).ready(function() {
            // Xử lý khi năm thay đổi ở biểu đồ bài viết
            $('#khoilop').change(function() {
                HienthiMonHoc();
            });


            // Hàm cập nhật dữ liệu năm cho biểu đồ bài viết
            function HienthiMonHoc() {
                var chon_khoilop = $('#khoilop').val();
                
                $.ajax({
                    url: 'ajax_post_khoilop.php',
                    data: {
                        khoilop: chon_khoilop
                    },
                    success: function(data) {
                        $('#data-monhoc').html(data);
                    }
                });
            }

        });
        </script>


</body>

</html>