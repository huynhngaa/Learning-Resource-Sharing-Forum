<?php
    session_start();
    require_once './includes/connect.php'; // Điều chỉnh đường dẫn nếu cần
    require 'vendor/autoload.php'; // Bao gồm tệp autoload của PhpSpreadsheet

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

    include("./includes/connect.php");
    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    if (isset($_POST['Them'])) {
        $ten = $_POST['name'];
        $username = $_POST['username'];
        $vaitro = $_POST['vaitro'];
        $matkhau = $_POST['pass'];
        $img= 'unnamed.png';
        $matkhau_bam = password_hash($matkhau, PASSWORD_BCRYPT);

        $nguoi_dung = "SELECT * FROM nguoi_dung WHERE nd_username='$username'";
        $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
		$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

        $vt = "SELECT * FROM vai_tro WHERE vt_ma='$vaitro'";
        $result_vt = mysqli_query($conn,$vt);
        $row_vt = mysqli_fetch_assoc($result_vt);



        if(mysqli_num_rows($result_nguoi_dung) == 1){
            $error[] ='Người dùng đã tồn tại! ';  
            // echo "<script>alert('Người dùng đã tồn tại!');</script>"; 

        }else{
            $them_nguoi_dung = "INSERT INTO nguoi_dung (nd_hoten, nd_username, nd_hinh, nd_matkhau, vt_ma) 
                                VALUES ('$ten','$username', '$img', '$matkhau_bam', '$vaitro')";
            mysqli_query($conn,$them_nguoi_dung); 
            
             // Đọc tệp Excel hiện có nếu có
            $inputFileName = './Excel/user_data.xlsx'; // Đường dẫn đến tệp Excel
            $spreadsheet = IOFactory::load($inputFileName);

            // Chọn bảng tính đầu tiên
            $worksheet = $spreadsheet->getActiveSheet();

            // Tìm hàng cuối cùng có dữ liệu
            $highestRow = $worksheet->getHighestRow();

            // Thêm dữ liệu người dùng vào hàng mới
            $nextRow = $highestRow + 1;
            $worksheet->setCellValue('A' . $nextRow, $ten);
            $worksheet->setCellValue('B' . $nextRow, $username);
            $worksheet->setCellValue('C' . $nextRow, $row_vt['vt_ten']);
            $worksheet->setCellValue('D' . $nextRow, $matkhau); // Mật khẩu được che kín vì lý do bảo mật

            // Lưu tệp Excel cập nhật
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($inputFileName);

			
        }
        echo "<script>alert('Thêm người dùng mới thành công!');</script>"; 
		header("Refresh: 0;url=QL_NguoiDung.php?vt=". urlencode($vaitro));  
        
        
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
                                <li class="breadcrumb-item active">Thêm người dùng</li>
                            </ol>
                        </nav>

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
                                                    tên</label><span style="color: red;font-weight: bold;"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                            class="bx bx-user"></i></span>
                                                    <input name="name" type="text" class="form-control"
                                                        id="basic-icon-default-fullname" placeholder="Nhập vào họ tên"
                                                        aria-label="Nhập vào họ tên"
                                                        aria-describedby="basic-icon-default-fullname2" required/>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label"
                                                    for="basic-icon-default-company">Username</label><span style="color: red;font-weight: bold;"> *</span>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-company2" class="input-group-text"><i
                                                            class="fa fa-id-card"></i></span>
                                                    <input name="username" type="text" id="basic-icon-default-company"
                                                        class="form-control" placeholder="Nhập vào username"
                                                        aria-label="Nhập vào username"
                                                        aria-describedby="basic-icon-default-company2" required/>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="defaultSelect" class="form-label">Cấp quyền</label><span style="color: red;font-weight: bold;"> *</span>
                                                <select name="vaitro" id="defaultSelect" class="form-select" required>
                                                    <option value="" hidden>Chọn quyền</option>
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
                                                    <label  class="form-label" for="basic-default-password32">Mật
                                                        khẩu</label><span style="color: red;font-weight: bold;"> *</span>
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
                                                            value="<?php echo  $mat_khau; ?>" required/>
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
</body>

</html>