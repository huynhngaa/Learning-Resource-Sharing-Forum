<?php
    session_start();
    include("./includes/connect.php");
    $this_username = $_GET['this_username'];
    
    $nguoi_dung = "SELECT * FROM nguoi_dung a, vai_tro b WHERE a.vt_ma=b.vt_ma and nd_username='$this_username'";
    $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
	$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

    if (isset($_POST['Luu'])) {
        $error = [];
        $MK_Hientai = $_POST['MK_Hientai'];
        $MK_Moi = $_POST['MK_Moi'];
        $MK_Moi_2 = $_POST['MK_Moi_2'];

        $matkhau_bam = password_hash($_POST['MK_Moi'], PASSWORD_BCRYPT);
        
       
        // $passold=md5($_POST['passold']);
        // $newpass=md5($_POST['newpass']);
        // $repass=md5($_POST['repass']);

        // $sql="SELECT * FROM nguoi_dung where nd_matkhau = '$MK_Hientai' and nd_username= '$this_username'";  
        // $result = mysqli_query($conn,$sql);

        if (password_verify($MK_Hientai, $row_nguoi_dung['nd_matkhau'])) {
            if($MK_Moi != $MK_Moi_2){
                $error[] = 'Mật khẩu không khớp!';
    
            }else{
            $doi_mk="UPDATE nguoi_dung set nd_matkhau='$matkhau_bam' where nd_username= '$this_username'" ;
            mysqli_query($conn,$doi_mk);
            
            echo "<script>alert('Đổi mật khẩu thành công');</script>"; 
			header("Refresh: 0;url=TaiKhoan.php");  }
        }
        else{
            echo "<script>alert('Vui lòng kiểm tra lại mật khẩu cũ!!');</script>"; 
           
        }
      

        // if(mysqli_num_rows($result) == 1)
        // {
          
        //     if($MK_Moi != $MK_Moi_2){
        //         $error[] = 'Mật khẩu không khớp!';
    
        //     }else{
        //     $doi_mk="UPDATE nguoi_dung set nd_matkhau='$MK_Moi' where nd_username= '$this_username'" ;
        //     mysqli_query($conn,$doi_mk);
            
        //     echo "<script>alert('Đổi mật khẩu thành công');</script>"; 
		// 	header("Refresh: 0;url=TaiKhoan.php");  }
        // }
        // else{
        //     echo "<script>alert('Vui lòng kiểm tra lại mật khẩu cũ!!');</script>"; 
           
        // }

		
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
                                <a class="nav-link " href="TaiKhoan.php"><i class="bx bx-user me-1"></i>
                                    Tài khoản</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="javascript:void(0);"><i class="fa fa-lock"></i> Mật
                                    khẩu</a>
                            </li>

                        </ul>

                        <div class="card mb-4">
                            <h5 class="card-header">Đổi mật khẩu</h5>

                            <?php
                                if(isset($error)){
                                    foreach($error as $error){
                                        echo '<p style="color: red; margin-left:1.6rem	"><span class="error-msg">'.$error.'</span></p>';
                                    }
                                };				
                            ?>

                            <div class="card-body">
                                <form  enctype="multipart/form-data" id="formAccountSettings" method="POST"
                                    class="fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
                                    <div class="row">
                                        <div class="mb-3 col-md-6 form-password-toggle fv-plugins-icon-container">
                                            <label class="form-label" for="currentPassword">Mật khẩu hiện tại</label>
                                            <div class="input-group input-group-merge has-validation">
                                                <input class="form-control" type="password" name="MK_Hientai"
                                                    id="currentPassword" placeholder="Nhập mật khẩu hiện tại">
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="bx bx-hide"></i></span>
                                            </div>
                                            <div
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="mb-3 col-md-6 form-password-toggle fv-plugins-icon-container">
                                            <label class="form-label" for="newPassword">Mật khẩu mới</label>
                                            <div class="input-group input-group-merge has-validation">
                                                <input class="form-control" type="password" id="MK_Moi"
                                                    name="MK_Moi" placeholder="Nhập mật khẩu mới">
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="bx bx-hide"></i></span>
                                            </div>
                                            <div
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6 form-password-toggle fv-plugins-icon-container">
                                            <label class="form-label" for="confirmPassword">Xác nhận mật khẩu
                                                mới</label>
                                            <div class="input-group input-group-merge has-validation">
                                                <input class="form-control" type="password" name="MK_Moi_2"
                                                    id="confirmPassword" placeholder="Nhập lại mật khẩu mới">
                                                <span class="input-group-text cursor-pointer"><i
                                                        class="bx bx-hide"></i></span>
                                            </div>
                                            <div
                                                class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                            </div>
                                        </div>
                                        <div class="col-12 mb-4">
                                            <p class="fw-medium mt-2">Yêu cầu về mật khẩu:</p>
                                            <ul class="ps-3 mb-0">
                                                <li class="mb-1">
                                                    Dài tối thiểu 8 ký tự - càng nhiều càng tốt
                                                </li>
                                                <li class="mb-1">Ít nhất một ký tự chữ thường</li>
                                                <li>Ít nhất một số, ký hiệu hoặc ký tự khoảng trắng</li>
                                            </ul>
                                        </div>
                                        <div class="col-12 mt-1">
                                            <button name="Luu" type="submit" class="btn btn-primary me-2"
                                               >Lưu thay đổi</button>
                                            <button type="reset" class="btn btn-label-secondary">Hủy bỏ</button>
                                        </div>
                                    </div>
                                    <input type="hidden">
                                </form>
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

    <?php
        include_once("includes/ThongBao.php");
    ?>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>