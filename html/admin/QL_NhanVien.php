<?php
    include("./includes/connect.php");
    session_start();
    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $nhan_vien = "SELECT * FROM nguoi_dung WHERE vt_ma='2'";
    $result_nhan_vien = mysqli_query($conn,$nhan_vien);
    // $row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý Nhân viên</title>

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Quản lý Admin /</span> Danh
                            sách Admin
                        </h4>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Danh sách Admin</h5>
                            <div class="table-responsive text-nowrap">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Ảnh</th>
                                            <th>Username</th>
                                            <th>Họ tên</th>                                         
                                            <th>Giới tính</th>
                                            <th>Ngày sinh</th>
                                            <th>Email</th>
                                            <th>Di động</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-border-bottom-0">
                                        <?php
                                            $stt = 0;
                                            while ($row_nhan_vien = mysqli_fetch_array($result_nhan_vien)) {
                                                if($row_nhan_vien['nd_gioitinh'] == 1) {
                                                    $row_nhan_vien['nd_gioitinh'] = "Nam";
                                                }if($row_nhan_vien['nd_gioitinh'] == 2) {
                                                    $row_nhan_vien['nd_gioitinh'] = "Nữ";
                                                }
                                                // else{
                                                //     $row_hoc_sinh['nd_gioitinh'] = "0";
                                                // }
                                                $stt = $stt + 1;
                                        ?>
                                        <tr>
                                            <td> <?php echo $stt ?> </td>
                                            <td > 
                                                <img src="assets/img/avatars/<?php echo  $row_nhan_vien['nd_hinh']; ?>" alt="Avatar" class="rounded-circle avatar avatar-xs pull-up" /> 
                                            </td>
                                            <td><strong><?php echo  $row_nhan_vien['nd_username']; ?></strong></td>
                                            <td><?php echo  $row_nhan_vien['nd_hoten']; ?></td>
                                            <!-- <td>
                                                <ul
                                                    class="list-unstyled users-list m-0 avatar-group d-flex align-items-center">
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar avatar-xs pull-up"
                                                        title="Lilian Fuller">
                                                        <img src="assets/img/avatars/5.png" alt="Avatar"
                                                            class="rounded-circle" />
                                                    </li>
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar avatar-xs pull-up"
                                                        title="Sophia Wilkerson">
                                                        <img src="assets/img/avatars/6.png" alt="Avatar"
                                                            class="rounded-circle" />
                                                    </li>
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom"
                                                        data-bs-placement="top" class="avatar avatar-xs pull-up"
                                                        title="Christina Parker">
                                                        <img src="assets/img/avatars/7.png" alt="Avatar"
                                                            class="rounded-circle" />
                                                    </li>
                                                </ul>
                                            </td> -->
                                            <td>
                                                <span class="badge bg-label-primary me-1">
                                                    <?php 
                                                        if($row_nhan_vien['nd_gioitinh'] == '0'){
                                                            $row_nhan_vien['nd_gioitinh'] = "(Chưa có dữ liệu)";
                                                            echo  "<i>".$row_nhan_vien['nd_gioitinh']."</i>";
                                                        }else{
                                                            echo  $row_nhan_vien['nd_gioitinh']; 
                                                        }
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php 
                                                    if($row_nhan_vien['nd_ngaysinh'] == '0000-00-00'){
                                                        $row_nhan_vien['nd_ngaysinh'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nhan_vien['nd_ngaysinh']."</i>";
                                                    }else{
                                                        echo date_format(date_create($row_nhan_vien['nd_ngaysinh']), "d-m-Y"); 
                                                    }            
                                                ?>
                                            </td>
                                            <td>
                                                <?php //echo  $row_hoc_sinh['nd_email']; ?>
                                                <?php 
                                                    if($row_nhan_vien['nd_email'] == ''){
                                                        $row_nhan_vien['nd_email'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nhan_vien['nd_email']."</i>";
                                                    }else{
                                                        echo  $row_nhan_vien['nd_email']; 
                                                    }            
                                                ?>
                                            </td>
                                            <td>
                                                <?php //echo  $row_hoc_sinh['nd_sdt']; ?>
                                                <?php 
                                                    if($row_nhan_vien['nd_sdt'] == ''){
                                                        $row_nhan_vien['nd_sdt'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nhan_vien['nd_sdt']."</i>";
                                                    }else{
                                                        echo  $row_nhan_vien['nd_sdt']; 
                                                    }            
                                                ?>
                                            </td>
                                            <td>
                                                <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                    href="Xem_NguoiDung.php?this_username=<?php echo $row_nhan_vien['nd_username']?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                    href="Sua_NguoiDung.php?this_username=<?php echo $row_nhan_vien['nd_username']?>">
                                                    <i class="bx bx-edit-alt me-1"></i>
                                                </a>
                                                <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                    href="Xoa_NguoiDung.php?this_username=<?php echo $row_nhan_vien['nd_username']?>">
                                                    <i class="bx bx-trash me-1"></i>
                                                </a>

                                            </td>
                                        </tr>
                                        <?php } ?>

                                    </tbody>
                                </table>
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

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>