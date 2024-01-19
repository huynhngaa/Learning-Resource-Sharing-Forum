<?php
    session_start();
    include("./includes/connect.php");


  // if (isset($_SESSION['Admin'])){
  //   echo "<script>alert('Bạn đã đăng nhập tài khoản! Nếu muốn tiếp tục quay trở lại trang Đăng nhập trước hết bạn hãy Đăng xuất tài khoản hiện tại để tiếp tục');</script>"; 
  //   header("Refresh: 0;url=index1.php");
  // }
  if(isset($_POST['DangNhap'])){
    $username = $_POST['username'];
    // $pass = md5($_POST['pass']);
    $pass = $_POST['pass'];
    // $pass = password_hash($_POST['pass'], PASSWORD_BCRYPT);

    $error = [];
    // $sql_login = "SELECT * FROM nguoi_dung WHERE nd_username='$username' and nd_matkhau='$pass'";
    $sql_login = "SELECT * FROM nguoi_dung WHERE nd_username='$username'";
    $result_login = mysqli_query($conn,$sql_login);
    $row_login = mysqli_fetch_assoc($result_login);

    if ($row_login && isset($row_login['nd_matkhau']) && password_verify($pass, $row_login['nd_matkhau'])) {
        // Mật khẩu người dùng khớp với mật khẩu đã băm trong cơ sở dữ liệu
        if($row_login['vt_ma'] == '1'){
            $_SESSION['Admin'] = "$username";	
            $_SESSION['vaitro'] = "Super Admin";			
            header('location:index.php?vt=superadmin');
        }
        elseif($row_login['vt_ma'] == '2'){
            $_SESSION['Admin'] = "$username";
            $_SESSION['vaitro'] = "Admin";					
            header('location:index.php?vt=admin');
        }else{
            $error[] = 'Username đăng nhập hoặc mật khẩu không đúng!';  
        }   
    } else {
        // Mật khẩu không khớp, xử lý lỗi hoặc thông báo sai mật khẩu
        $error[] = 'Username đăng nhập hoặc mật khẩu không đúngyy!'; 
    }
   

    // if(mysqli_num_rows($result_login) == 1)
    // {
    //   if($row_login['vt_ma'] == '1'){
    //     $_SESSION['Admin'] = "$username";	
    //     $_SESSION['vaitro'] = "Super Admin";			
    //     header('location:index.php?vt=superadmin');
    //   }
    //   if($row_login['vt_ma'] == '2'){
    //     $_SESSION['Admin'] = "$username";
    //     $_SESSION['vaitro'] = "Admin";					
    //     header('location:index.php?vt=admin');
    //   }else{
    //     $error[] = 'Username đăng nhập hoặc mật khẩu không đúng!';  
    //     echo  $pass;
    //   }            
    // }
    // else {
    //     $error[] = 'Username đăng nhập hoặc mật khẩu không đúng!';  
    //     echo  $pass;     
    // }
  } 
?>
<!DOCTYPE html>

<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Đăng nhập</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon/icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="./assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="./assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <!-- <a href="index.html" class="app-brand-link gap-2">
                  <span class="app-brand-logo demo">
                  <img src="assets/img/favicon/logo.png" alt class="w-px-50 h-auto rounded-circle" />
                  </span> -->
                            <h3 class="app-brand-text text-body fw-bolder">ĐĂNG NHẬP</h3>
                            <!-- </a> -->
                        </div>
                        <!-- /Logo -->
                        <!-- <p class="mb-4">Vui lòng đăng nhập vào tài khoản của bạn để bắt đầu quản lý hệ thống!</p> -->
                        <?php
                  if(isset($error)){
                    foreach($error as $error){
                      echo ' <p class="mb-4" style="color: red; font-size: 15px; margin-top:-2rem	"> <span  > <i class="fa fa-exclamation-triangle"></i> '.$error.'</span> </p>';                           
                    }                          
                };		 	
              ?>

                        <form class="mb-3" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Username</label>
                                <input type="text" class="form-control" id="email" name="username"
                                    placeholder="Nhập vào username ..." autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Mật khẩu</label>
                                    <a href="auth-forgot-password-basic.html">
                                        <small>Bạn quên mật khẩu?</small>
                                    </a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input name="pass" type="password" class="form-control"
                                        id="basic-default-password32" placeholder="Nhập vào mật khẩu ..."
                                        aria-describedby="basic-default-password" />
                                    <span class="input-group-text cursor-pointer" >
                                        <i class="bx bx-hide"></i>
                                    </span>
                                </div>
                            </div>
                            <!-- <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" />
                                    <label class="form-check-label" for="remember-me"> Ghi nhớ tôi </label>
                                </div>
                            </div> -->
                            <div class="mb-3 mt-5">
                                <button name="DangNhap" class="btn btn-primary d-grid w-100" type="submit">Đăng nhập</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="./assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>