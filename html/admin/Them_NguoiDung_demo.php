<?php
    // require_once './includes/connect.php'; // Điều chỉnh đường dẫn nếu cần
    // require 'vendor/autoload.php'; // Bao gồm tệp autoload của PhpSpreadsheet

    // use PhpOffice\PhpSpreadsheet\IOFactory;
    // use PhpOffice\PhpSpreadsheet\Spreadsheet;

    // include("./includes/connect.php");
    // session_start();
    // if(!isset($_SESSION['Admin'])){
    //     echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
    //     header("Refresh: 0;url=login.php");  
    // }else{}
    // if (isset($_POST['Them'])) {
    //     $ten = $_POST['name'];
    //     $username = $_POST['username'];
    //     $vaitro = $_POST['vaitro'];
    //     $matkhau = $_POST['pass'];
    //     $img= 'unnamed.png';
    //     $matkhau_bam = password_hash($matkhau, PASSWORD_BCRYPT);

    //     $nguoi_dung = "SELECT * FROM nguoi_dung WHERE nd_username='$username'";
    //     $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
	// 	$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

    //     $vt = "SELECT * FROM vai_tro WHERE vt_ma='$vaitro'";
    //     $result_vt = mysqli_query($conn,$vt);
    //     $row_vt = mysqli_fetch_assoc($result_vt);



    //     if(mysqli_num_rows($result_nguoi_dung) == 1){
    //         $error[] ='Người dùng đã tồn tại! ';  
    //         // echo "<script>alert('Người dùng đã tồn tại!');</script>"; 

    //     }else{
    //         $them_nguoi_dung = "INSERT INTO nguoi_dung (nd_hoten, nd_username, nd_hinh, nd_matkhau, vt_ma) 
    //                             VALUES ('$ten','$username', '$img', '$matkhau_bam', '$vaitro')";
    //         mysqli_query($conn,$them_nguoi_dung); 
            
    //          // Đọc tệp Excel hiện có nếu có
    //         $inputFileName = './Excel/user_data.xlsx'; // Đường dẫn đến tệp Excel
    //         $spreadsheet = IOFactory::load($inputFileName);

    //         // Chọn bảng tính đầu tiên
    //         $worksheet = $spreadsheet->getActiveSheet();

    //         // Tìm hàng cuối cùng có dữ liệu
    //         $highestRow = $worksheet->getHighestRow();

    //         // Thêm dữ liệu người dùng vào hàng mới
    //         $nextRow = $highestRow + 1;
    //         $worksheet->setCellValue('A' . $nextRow, $ten);
    //         $worksheet->setCellValue('B' . $nextRow, $username);
    //         $worksheet->setCellValue('C' . $nextRow, $row_vt['vt_ten']);
    //         $worksheet->setCellValue('D' . $nextRow, $matkhau); // Mật khẩu được che kín vì lý do bảo mật

    //         // Lưu tệp Excel cập nhật
    //         $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //         $writer->save($inputFileName);

			
    //     }
    //     echo "<script>alert('Thêm người dùng mới thành công!');</script>"; 
	// 	header("Refresh: 0;url=QL_NguoiDung.php?vt=". urlencode($vaitro));  
        
        
    // }

    
    
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

    <!-- Bổ sung thư viện CSS và JavaScript của Dropzone.js -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script> -->

    <link rel="stylesheet" href="./assets/vendor/libs/dropzone/dropzone.css" />
    <script src="./assets/vendor/libs/dropzone/dropzone.js"></script>

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>



<body>
    <style>
    .dropzone {
        width: 100%;
        position: relative;
        padding: 1.5rem;
        cursor: pointer;
        border-radius: .5rem
    }

    .dropzone:not(.dz-clickable) {
        opacity: .5;
        cursor: not-allowed
    }

    .dropzone.dz-drag-hover {
        border-style: solid
    }

    .dropzone.dz-drag-hover .dz-message {
        opacity: .5
    }

    .dz-message {
        font-size: 1.625rem
    }

    .dz-message .note {
        font-size: .9375rem
    }

    .dz-browser-not-supported.dropzone-box {
        min-height: auto !important;
        border: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        width: auto !important;
        cursor: default !important;
        transition: none
    }

    .dz-browser-not-supported .dz-message {
        display: none !important
    }

    .dz-started .dz-message {
        display: none
    }

    .dz-message {
        margin: 5rem 0;
        font-weight: 500;
        text-align: center
    }

    .dz-message .note {
        font-weight: 400;
        display: block;
        margin-top: .625rem
    }

    .dz-preview {
        position: relative;
        vertical-align: top;
        margin: .5rem;
        background: #fff;
        font-size: .8125rem;
        box-sizing: content-box;
        cursor: default
    }

    .dz-filename {
        position: absolute;
        width: 100%;
        overflow: hidden;
        padding: .625rem .625rem 0 .625rem;
        background: #fff;
        white-space: nowrap;
        text-overflow: ellipsis
    }

    .dz-filename:hover {
        white-space: normal;
        text-overflow: inherit
    }

    .dz-size {
        padding: 1.875rem .625rem .625rem .625rem;
        font-size: .6875rem;
        font-style: italic
    }

    .dz-preview .progress,
    .dz-preview .progess-bar {
        height: .5rem
    }

    .dz-preview .progress {
        position: absolute;
        left: 1.25rem;
        right: 1.25rem;
        top: 50%;
        margin-top: -0.25rem;
        z-index: 30
    }

    .dz-complete .progress {
        display: none
    }

    .dz-thumbnail {
        position: relative;
        padding: .625rem;
        height: 7.5rem;
        text-align: center;
        box-sizing: content-box
    }

    .dz-thumbnail>img,
    .dz-thumbnail .dz-nopreview {
        top: 50%;
        position: relative;
        transform: translateY(-50%) scale(1);
        margin: 0 auto;
        display: block
    }

    .dz-thumbnail>img {
        max-height: 100%;
        max-width: 100%
    }

    .dz-nopreview {
        font-weight: 500;
        text-transform: uppercase;
        font-size: .6875rem
    }

    .dz-thumbnail img[src]~.dz-nopreview {
        display: none
    }

    .dz-remove {
        display: block;
        text-align: center;
        padding: .375rem 0;
        font-size: .75rem
    }

    .dz-remove:hover,
    .dz-remove:focus {
        text-decoration: none;
        border-top-color: rgba(0, 0, 0, 0)
    }

    .dz-error-mark,
    .dz-success-mark {
        position: absolute;
        left: 50%;
        top: 50%;
        display: none;
        margin-left: -1.875rem;
        margin-top: -1.875rem;
        height: 3.75rem;
        width: 3.75rem;
        border-radius: 50%;
        background-position: center center;
        background-size: 1.875rem 1.875rem;
        background-repeat: no-repeat;
        box-shadow: 0 0 1.25rem rgba(0, 0, 0, .06)
    }

    .dz-success-mark {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 8 8'%3E%3Cpath fill='%235cb85c' d='M2.3 6.73L.6 4.53c-.4-1.04.46-1.4 1.1-.8l1.1 1.4 3.4-3.8c.6-.63 1.6-.27 1.2.7l-4 4.6c-.43.5-.8.4-1.1.1z'/%3E%3C/svg%3E")
    }

    .dz-error-mark {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='%23d9534f' viewBox='-2 -2 7 7'%3E%3Cpath stroke='%23d9534f' d='M0 0l3 3m0-3L0 3'/%3E%3Ccircle r='.5'/%3E%3Ccircle cx='3' r='.5'/%3E%3Ccircle cy='3' r='.5'/%3E%3Ccircle cx='3' cy='3' r='.5'/%3E%3C/svg%3E")
    }

    .dz-error-message {
        position: absolute;
        top: -1px;
        left: -1px;
        bottom: -1px;
        right: -1px;
        display: none;
        color: #fff;
        z-index: 40;
        padding: .75rem;
        text-align: left;
        overflow: auto;
        font-weight: 500
    }

    [dir=rtl] .dz-error-message {
        text-align: right
    }

    .dz-error .dz-error-message {
        display: none
    }

    .dz-error .dz-error-mark {
        display: block
    }

    .dz-error:hover .dz-error-message {
        display: block
    }

    .dz-error:hover .dz-error-mark {
        display: none
    }

    .dz-success .dz-success-mark {
        display: block
    }

    [dir=rtl] .dz-hidden-input {
        left: auto !important;
        right: 0 !important
    }

    .light-style .dropzone {
        border: 2px dashed #d9dee3
    }

    .light-style .dz-preview {
        border: 0 solid #d9dee3;
        border-radius: .375rem;
        box-shadow: 0 2px 6px 0 rgba(67, 89, 113, .12)
    }

    .light-style .dz-message {
        color: #566a7f
    }

    .light-style .dz-message .note {
        color: #697a8d
    }

    .light-style .dz-thumbnail {
        border-bottom: 1px solid #d9dee3;
        background: rgba(67, 89, 113, .025);
        border-top-left-radius: calc(0.375rem - 1px);
        border-top-right-radius: calc(0.375rem - 1px)
    }

    .light-style .dz-size {
        color: #a1acb8
    }

    .light-style .dz-remove {
        color: #697a8d;
        border-top: 1px solid #d9dee3;
        border-bottom-right-radius: calc(0.375rem - 1px);
        border-bottom-left-radius: calc(0.375rem - 1px)
    }

    .light-style .dz-remove:hover,
    .light-style .dz-remove:focus {
        color: #697a8d;
        background: rgba(67, 89, 113, .1)
    }

    .light-style .dz-nopreview {
        color: #a1acb8
    }

    .light-style .dz-error-mark,
    .light-style .dz-success-mark {
        background-color: rgba(35, 52, 70, .5)
    }

    .light-style .dz-error-message {
        background: rgba(255, 62, 29, .8);
        border-top-left-radius: .375rem;
        border-top-right-radius: .375rem
    }

    @media(min-width: 576px) {
        .light-style .dz-preview {
            display: inline-block;
            width: 11.25rem
        }

        .light-style .dz-thumbnail {
            width: 10rem
        }
    }

    .dark-style .dropzone {
        border: 2px dashed #444564
    }

    .dark-style .dz-preview {
        background: #2b2c40;
        border: 0 solid #444564;
        border-radius: .375rem;
        box-shadow: 0 .125rem .5rem 0 rgba(0, 0, 0, .16)
    }

    .dark-style .dz-message {
        color: #566a7f
    }

    .dark-style .dz-message .note {
        color: #a3a4cc
    }

    .dark-style .dz-filename {
        background: #2b2c40;
        padding-top: .25rem;
        padding-bottom: .25rem;
        border-bottom: 0 solid #444564
    }

    .dark-style .dz-size {
        color: #7071a4
    }

    .dark-style .dz-thumbnail {
        border-bottom: 1px solid #444564;
        background: rgba(255, 255, 255, .015);
        border-top-left-radius: calc(0.375rem - 1px);
        border-top-right-radius: calc(0.375rem - 1px)
    }

    .dark-style .dz-nopreview {
        color: #7071a4
    }

    .dark-style .dz-remove {
        color: #a3a4cc;
        border-top: 1px solid #444564;
        border-bottom-right-radius: calc(0.375rem - 1px);
        border-bottom-left-radius: calc(0.375rem - 1px)
    }

    .dark-style .dz-remove:hover,
    .dark-style .dz-remove:focus {
        color: #a3a4cc;
        background: rgba(255, 255, 255, .8)
    }

    .dark-style .dz-error-mark,
    .dark-style .dz-success-mark {
        background-color: rgba(107, 108, 157, .5)
    }

    .dark-style .dz-error-message {
        background: rgba(255, 62, 29, .8);
        border-top-left-radius: .375rem;
        border-top-right-radius: .375rem
    }

    @media(min-width: 576px) {
        .dark-style .dz-preview {
            display: inline-block;
            width: 11.25rem
        }

        .dark-style .dz-thumbnail {
            width: 10rem
        }
    }
    </style>
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Quản lý người dùng/</span> Thêm
                            người dùng</h4>

                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Thêm học người dùng</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Họ
                                                    tên</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                            class="bx bx-user"></i></span>
                                                    <input name="name" type="text" class="form-control"
                                                        id="basic-icon-default-fullname" placeholder="Nhập vào họ tên"
                                                        aria-label="Nhập vào họ tên"
                                                        aria-describedby="basic-icon-default-fullname2" />
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label"
                                                    for="basic-icon-default-company">Username</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-company2" class="input-group-text"><i
                                                            class="fa fa-id-card"></i></span>
                                                    <input name="username" type="text" id="basic-icon-default-company"
                                                        class="form-control" placeholder="Nhập vào username"
                                                        aria-label="Nhập vào username"
                                                        aria-describedby="basic-icon-default-company2" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="defaultSelect" class="form-label">Cấp quyền</label>
                                                <select name="vaitro" id="defaultSelect" class="form-select">
                                                    <option disabled selected>Chọn quyền</option>
                                                    <?php
                                                        $vai_tro = "select * from vai_tro";
                                                        $result_vai_tro = mysqli_query($conn, $vai_tro);
                                                        while ($row_vai_tro = mysqli_fetch_array($result_vai_tro)) {
                                                        ?>
                                                    <option value="<?php echo $row_vai_tro['vt_ma']; ?>">
                                                        <?php echo $row_vai_tro['vt_ten']; ?>
                                                    </option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>


                                            <div class="mb-3">
                                                <div class="form-password-toggle">
                                                    <label class="form-label" for="basic-default-password32">Mật
                                                        khẩu</label>
                                                    <div class="input-group input-group-merge">
                                                        <?php
                                                           
                                                            // Danh sách các ký tự có thể sử dụng để tạo mật khẩu, bao gồm chữ cái (in hoa và in thường), chữ số và ký tự đặc biệt
                                                            $danh_sach_ky_tu = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&';

                                                            // Độ dài của mật khẩu mong muốn
                                                            $do_dai = 8;

                                                            // Tạo một mật khẩu ngẫu nhiên bằng cách lấy ngẫu nhiên các ký tự từ danh sách và ghép lại chúng
                                                            $mat_khau = '';
                                                            $so_ky_tu = strlen($danh_sach_ky_tu) - 1;

                                                            // Tạo một ký tự đặc biệt ngẫu nhiên
                                                            $ky_tu_dac_biet = '!@#$%^&';
                                                            $ky_tu_dac_biet_ngau_nhien = $ky_tu_dac_biet[rand(0, strlen($ky_tu_dac_biet) - 1)];

                                                            for ($i = 0; $i < $do_dai - 1; $i++) {
                                                                $vi_tri_ngau_nhien = rand(0, $so_ky_tu);
                                                                $mat_khau .= $danh_sach_ky_tu[$vi_tri_ngau_nhien];
                                                            }

                                                            // Thêm ký tự đặc biệt vào mật khẩu
                                                            $mat_khau .= $ky_tu_dac_biet_ngau_nhien;

                                                            // Trộn ngẫu nhiên mật khẩu
                                                            $mat_khau = str_shuffle($mat_khau);
                                                        ?>
                                                        <input name="pass" type="password" class="form-control"
                                                            id="basic-default-password32"
                                                            placeholder="Nhập vào mật khẩu"
                                                            aria-describedby="basic-default-password"
                                                            value="<?php echo  $mat_khau; ?>" />
                                                        <span class="input-group-text cursor-pointer"
                                                            id="basic-default-password">
                                                            <i class="bx bx-hide"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <button name="Them" type="submit" class="btn btn-primary">Thêm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="card">
                                <h5 class="card-header">Tải file lên</h5>
                                <div class="card-body">
                                    <form method="post"  enctype="multipart/form-data" action="Them_ND_Upload.php" class="dropzone needsclick dz-clickable" id="dropzone-multi">
                                        <div class="dz-message needsclick">
                                            Thả tập tin vào đây hoặc bấm vào để tải lên
                                            <span class="note needsclick">(Chỉ tải lên những file có định dạng excel.)</span>
                                        </div>
                                        <button name="Them" type="submit" class="btn btn-primary">Thêm</button>
                                    </form>
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

        <!-- Đoạn mã JavaScript để cấu hình Dropzone.js -->
        <script>
        "use strict";
        ! function() {
            var e = `<div class="dz-preview dz-file-preview">
                    <div class="dz-details">
                    <div class="dz-thumbnail">
                        <img data-dz-thumbnail>
                        <span class="dz-nopreview">Không có xem trước</span>
                        <div class="dz-success-mark"></div>
                        <div class="dz-error-mark"></div>
                        <div class="dz-error-message"><span data-dz-errormessage></span></div>
                        <div class="progress">
                        <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                        </div>
                    </div>
                    <div class="dz-filename" data-dz-name></div>
                    <div class="dz-size" data-dz-size></div>
                    </div>
                    </div>`,
                a = document.querySelector("#dropzone-basic"),
                a = (a && new Dropzone(a, {
                    previewTemplate: e,
                    parallelUploads: 1,
                    maxFilesize: 5,
                    addRemoveLinks: !0,
                    maxFiles: 1
                }), document.querySelector("#dropzone-multi"));
                a && new Dropzone(a, {
                previewTemplate: e,
                parallelUploads: 1,
                maxFilesize: 5,
                addRemoveLinks: !0
            })
        }();
        </script>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>