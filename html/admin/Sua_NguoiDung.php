<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_username = $_GET['this_username'];
    
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
        $matkhau = $_POST['pass'];
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
                                    nd_hinh = '$img', 
                                    nd_matkhau = '$matkhau', 
                                    vt_ma = '$vaitro' 
                                WHERE nd_username = '$this_username'";
        mysqli_query($conn,$cap_nhat_nguoi_dung); 
        // move_uploaded_file($img_tmp_name, './assets/img/avatars/' . $img);          
		echo "<script>alert('Cập nhật người dùng thành công!');</script>"; 

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

    <title>Cập nhật Người dùng</title>

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
                            <li class="breadcrumb-item">
                                <a href="QL_NguoiDung.php?vt=<?php echo $row_nguoi_dung['vt_ma']?>">Quản lý <?php echo $row_nguoi_dung['vt_ten']?></a>
                            </li> 
                            <li class="breadcrumb-item active">Cập nhật <?php echo $row_nguoi_dung['vt_ten']?></li>
                            </ol>
                        </nav>

                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Cập nhật thông tin <?php echo $row_nguoi_dung['vt_ten']?></h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data">

                                            <div class="mb-3 ">
                                                <div class="d-flex align-items-start align-items-sm-center gap-4">
                                                    <img src="./assets/img/avatars/<?php echo $row_nguoi_dung['nd_hinh']; ?>"
                                                        alt="user-avatar" class="d-block rounded" height="100"
                                                        width="100" id="uploadedAvatar" />
                                                    <div class="button-wrapper">
                                                        <label for="upload" class="btn btn-primary me-2 mb-4"
                                                            tabindex="0">
                                                            <span class="d-none d-sm-block">Chọn ảnh</span>
                                                            <i class="bx bx-upload d-block d-sm-none"></i>
                                                            <input name="img" type="file" id="upload"
                                                                class="account-file-input" hidden
                                                                accept="image/png, image/jpeg" />
                                                        </label>
                                                        <button type="button"
                                                            class="btn btn-outline-secondary account-image-reset mb-4">
                                                            <i class="bx bx-reset d-block d-sm-none"></i>
                                                            <span class="d-none d-sm-block">Chọn lại</span>
                                                        </button>

                                                        <p class="text-muted mb-0">Được phép JPG, GIF hoặc PNG. Kích
                                                            thước tối đa 800K</p>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Họ
                                                    tên</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                            class="bx bx-user"></i></span>
                                                    <input name="name" type="text" class="form-control"
                                                        id="basic-icon-default-fullname" placeholder="Nhập vào họ tên"
                                                        aria-label="Nhập vào họ tên"
                                                        aria-describedby="basic-icon-default-fullname2"
                                                        value="<?php echo $row_nguoi_dung['nd_hoten']; ?>" />
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
                                                        aria-describedby="basic-icon-default-company2"
                                                        value="<?php echo  $row_nguoi_dung['nd_username']; ?>"
                                                        disabled />
                                                </div>
                                            </div>

                                            <!-- <div class="mb-3">
                                                <label for="defaultSelect" class="form-label">Giới tính</label>
                                                <select name="vaitro" id="defaultSelect" class="form-select">
                                                    <option>Nam</option>
                                                    <option>Nữ</option>
                                                </select>
                                            </div> -->

                                            <div class="mb-3">
                                                <label for="defaultSelect" class="form-label">Giới tính</label> <br>
                                                <?php 
                                                    // $gioitinh = $row_nguoi_dung['nd_gioitinh']; // Lấy giá trị giới tính từ cơ sở dữ liệu
                                                ?>
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
                                            <div class="mb-3">
                                                <label for="form-label" class="col-md-2 col-form-label">Ngày
                                                    sinh</label>
                                                <div>
                                                    <input name="ngaysinh" class="form-control" type="date"
                                                        value="<?php echo  $row_nguoi_dung['nd_ngaysinh']; ?>"
                                                        id="html5-date-input" />
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-email">Email</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                    <input name="email" type="text" id="basic-icon-default-email"
                                                        class="form-control" placeholder="Nhập vào địa chỉ email"
                                                        aria-label="Nhập vào địa chỉ email"
                                                        aria-describedby="basic-icon-default-email2"
                                                        value="<?php echo  $row_nguoi_dung['nd_email']; ?>" />
                                                    <!-- <span id="basic-icon-default-email2"
                                                        class="input-group-text">@gmail.com</span> -->
                                                </div>
                                                <!-- <div class="form-text">You can use letters, numbers & periods</div> -->
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-phone">Di động</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-phone2" class="input-group-text"><i
                                                            class="bx bx-phone"></i></span>
                                                    <input name="phone" type="text" id="basic-icon-default-phone"
                                                        class="form-control phone-mask"
                                                        placeholder="Nhập vào số điện thoại"
                                                        aria-label="Nhập vào số điện thoại"
                                                        aria-describedby="basic-icon-default-phone2"
                                                        value="<?php echo  $row_nguoi_dung['nd_sdt']; ?>" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-phone">Địa chỉ</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-phone2" class="input-group-text"><i
                                                            class="fa fa-map"></i></span>
                                                    <input name="address" type="text" id="basic-icon-default-phone"
                                                        class="form-control phone-mask" placeholder="Nhập vào địa chỉ"
                                                        aria-label="Nhập vào địa chỉ"
                                                        aria-describedby="basic-icon-default-phone2"
                                                        value="<?php echo  $row_nguoi_dung['nd_diachi']; ?>" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="defaultSelect" class="form-label">Cấp quyền</label>
                                                <select name="vaitro" id="defaultSelect" class="form-select">
                                                    <option value="<?php echo  $row_nguoi_dung['vt_ma']; ?>">
                                                        <?php echo  $row_nguoi_dung['vt_ten']; ?></option>
                                                    <?php
                                                        $vai_tro = "select * from vai_tro";
                                                        $result_vai_tro = mysqli_query($conn, $vai_tro);
                                                        while ($row_vai_tro = mysqli_fetch_array($result_vai_tro)) {
                                                        ?>
                                                    <option value="<?php echo $row_vai_tro['vt_ma']; ?>">
                                                        <?php echo $row_vai_tro['vt_ten']; ?></option>
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
                                                            value="<?php echo  $row_nguoi_dung['nd_matkhau']; ?>" />
                                                        <span class="input-group-text cursor-pointer"
                                                            id="basic-default-password">
                                                            <i class="bx bx-hide"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class="mb-3">
                                                <label for="formFile" class="form-label">Ảnh</label>
                                                <input name = "img" class="form-control" type="file" id="formFile" />
                                            </div> -->

                                            <!-- <div class="mb-3">
                                                <label class="form-label"
                                                    for="basic-icon-default-message">Message</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-message2" class="input-group-text"><i
                                                            class="bx bx-comment"></i></span>
                                                    <textarea id="basic-icon-default-message" class="form-control"
                                                        placeholder="Hi, Do you have a moment to talk Joe?"
                                                        aria-label="Hi, Do you have a moment to talk Joe?"
                                                        aria-describedby="basic-icon-default-message2"></textarea>
                                                </div>
                                            </div> -->
                                            <button name="CapNhat" type="submit" class="btn btn-primary">Cập
                                                nhật</button>
                                            <a href="QL_NguoiDung.php?vt=<?php echo $row_nguoi_dung['vt_ma']?>"
                                                class="btn btn-outline-secondary">Hủy</a>
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
        <script src="./assets/js/pages-account-settings-account.js"></script>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>