<?php
    session_start();
    include("./includes/connect.php");
    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_username = $_SESSION['Admin'];
    
    $nguoi_dung = "SELECT * FROM nguoi_dung a, vai_tro b WHERE a.vt_ma=b.vt_ma and nd_username='$this_username'";
    $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
	$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

    if (isset($_POST['CapNhat'])) {
        $ten = $_POST['name'];
        
        $sdt = $_POST['phone'];
        $gioitinh = $_POST['gioitinh'];
        $vaitro = $_POST['vaitro'];
        $email = $_POST['email'];
        $diachi = $_POST['address'];

        $gioitinh = $_POST['gioitinh'];
        $ngaysinh = $_POST['ngaysinh'];

      
    $img = $row_nguoi_dung['nd_hinh']; // Mặc định sử dụng ảnh cũ

    if (!empty($_FILES['img']['name'])) {
        // Nếu có tệp ảnh mới được tải lên
        $img = $_FILES['img']['name'];
        $img_tmp_name = $_FILES['img']['tmp_name'];
        move_uploaded_file($img_tmp_name, './assets/img/avatars/' . $img); 
    }

    
        $cap_nhat_nguoi_dung = "UPDATE nguoi_dung 
                                SET nd_hoten = '$ten', 
                                    nd_gioitinh = '$gioitinh', 
                                    nd_ngaysinh = '$ngaysinh',
                                    nd_sdt = '$sdt', 
                                    nd_email = '$email', 
                                    nd_diachi = '$diachi', 
                                    nd_hinh = '$img'
                                WHERE nd_username = '$this_username'";
        mysqli_query($conn,$cap_nhat_nguoi_dung); 
         
		echo "<script>alert('Cập nhật tài khoản thành công!');</script>"; 
        header("Refresh: 0;url=TaiKhoan.php");   
    }
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
                                  
                                <li class="breadcrumb-item active">Cài đặt tài khoản</li>
                            </ol>
                        </nav>

                        <ul class="nav nav-pills flex-column flex-md-row mb-3">
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:void(0);"><i class="bx bx-user me-1"></i>
                                    Tài khoản</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="DoiMatKhau.php?this_username=<?php echo $row_nguoi_dung['nd_username']; ?>"><i class="fa fa-lock"></i> Mật khẩu</a>
                            </li>

                        </ul>

                        <div class="row">
                            <div class="col-md-12">

                                <div class="card mb-4">
                                    <h5 class="card-header">Thông tin tài khoản</h5>
                                    <!-- Account -->

                                    <div class="card-body">
                                        <form id="formAccountSettings" method="POST" enctype="multipart/form-data">
                                            <div class="card-body">
                                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                    <img src="./assets/img/avatars/<?php echo $row_nguoi_dung['nd_hinh']; ?>"
                                                        alt="user-avatar" class="d-block rounded" height="100"
                                                        width="100" id="uploadedAvatar" />
                                                    <div class="button-wrapper">
                                                        <label for="upload" class="btn btn-primary me-2 mb-4"
                                                            tabindex="0">
                                                            <span class="d-none d-sm-block">Tải ảnh lên</span>
                                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                                            <input name="img" type="file" id="upload"
                                                                class="account-file-input" hidden
                                                                accept="image/png, image/jpeg" />
                                                        </label>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary account-image-reset mb-4">
                                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Đặt lại</span>
                                                        </button>
                                                        <p class="text-muted mb-0">Được phép JPG, GIF hoặc PNG. Kích
                                                            thước tối
                                                            đa 800K</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr class="my-0" /> <br>
                                            <div class="row">

                                                <div class="mb-3 col-md-6">
                                                    <label for="lastName" class="form-label">Họ tên</label>
                                                    <input class="form-control" type="text" name="name" id="lastName"
                                                        value="<?php echo $row_nguoi_dung['nd_hoten']; ?>"
                                                        placeholder="Nhập vào họ tên" />
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="organization" class="form-label">Giới tính</label>
                                                    <br>
                                                    <div class="form-check form-check-inline mt-3">
                                                        <input class="form-check-input" type="radio" name="gioitinh"
                                                            id="inlineRadio1" value="1"
                                                            <?php if ($row_nguoi_dung['nd_gioitinh'] == '1') echo 'checked'; ?> />
                                                        <label class="form-check-label" for="inlineRadio1">Nam</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="gioitinh"
                                                            id="inlineRadio2" value="2"
                                                            <?php if ($row_nguoi_dung['nd_gioitinh'] == '2') echo 'checked'; ?> />
                                                        <label class="form-check-label" for="inlineRadio2">Nữ</label>
                                                    </div>
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="firstName" class="form-label">Username</label>
                                                    <input disabled class="form-control" type="text" id="firstName"
                                                        placeholder="Nhập vào username" name="username"
                                                        value="<?php echo $row_nguoi_dung['nd_username']; ?>"
                                                        autofocus />
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="firstName" class="form-label">Ngày sinh</label>
                                                    <input class="form-control" type="date" id="firstName"
                                                        name="ngaysinh"
                                                        value="<?php echo $row_nguoi_dung['nd_ngaysinh']; ?>"
                                                        autofocus />
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="email" class="form-label">E-mail</label>
                                                    <input class="form-control" type="text" id="email" name="email"
                                                        value="<?php echo $row_nguoi_dung['nd_email']; ?>"
                                                        placeholder="Nhập vào email" />
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label class="form-label" for="phoneNumber">Số điện thoại</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text">VN (+84)</span>
                                                        <input type="text" id="phoneNumber" name="phone"
                                                            placeholder="Nhập vào số điện thoại" class="form-control"
                                                            value="<?php echo $row_nguoi_dung['nd_sdt']; ?>" />
                                                    </div>
                                                </div>
                                                <div class="mb-3 col-md-6">
                                                    <label for="address" class="form-label">Địa chỉ</label>
                                                    <input type="text" class="form-control" id="address" name="address"
                                                        placeholder="Nhập vào địa chỉ"
                                                        value="<?php echo $row_nguoi_dung['nd_diachi']; ?>" />
                                                </div>

                                                <div class="mb-3 col-md-6">
                                                    <label for="email" class="form-label">Vai trò</label>
                                                    <input disabled class="form-control" type="text" id="vaitro" name="vaitro"
                                                        value="<?php echo $row_nguoi_dung['vt_ten']; ?>"
                                                        placeholder="Nhập vào vai trò" />
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button name="CapNhat" type="submit" class="btn btn-primary me-2">Lưu
                                                    chỉnh sửa</button>
                                                <button name="Huy" type="reset"
                                                    class="btn btn-outline-secondary">Hủy</button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- /Account -->
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