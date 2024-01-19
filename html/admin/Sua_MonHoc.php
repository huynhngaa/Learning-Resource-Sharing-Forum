<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_mh_ma = $_GET['this_mh_ma'];

    $mon_hoc = "SELECT * FROM mon_hoc a, khoi_lop b WHERE a.kl_ma=b.kl_ma and mh_ma='$this_mh_ma'";
    $result_mon_hoc = mysqli_query($conn,$mon_hoc);
    $row_mon_hoc = mysqli_fetch_assoc($result_mon_hoc);

    if (isset($_POST['CapNhatMonHoc'])) {
        $mh_ten = $_POST['mh_ten'];
        $kl_ma = $_POST['kl_ma'];
        $cap_nhat_mon_hoc = "UPDATE mon_hoc SET mh_ten = '$mh_ten', kl_ma = '$kl_ma' WHERE mh_ma='$this_mh_ma'";
        mysqli_query($conn,$cap_nhat_mon_hoc);           
        echo "<script>alert('Cập nhật môn học mới thành công!');</script>"; 
        header("Refresh: 0;url=QL_MonHoc.php"); 	
    }
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Cập nhật môn học</title>

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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Quản lý môn học/</span> Cập nhật môn học</h4>

                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                            
                                    <!-- Basic Breadcrumb -->
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                        <li class="breadcrumb-item">
                                            <a href="index.php">Home</a>
                                        </li> 
                                        <li class="breadcrumb-item">
                                            <a href="QL_MonHoc.php">Quản lý Môn học</a>
                                        </li> 
                                        <li class="breadcrumb-item active">Cập nhật Môn học</li>
                                        </ol>
                                    </nav>

                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Tên môn học</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                    class="fa fa-braille"></i></span>
                                                    <input name="mh_ten" type="text" class="form-control"
                                                        id="basic-icon-default-fullname" placeholder="Nhập vào tên môn học"
                                                        aria-label="Nhập vào tên môn học"
                                                        aria-describedby="basic-icon-default-fullname2" value = "<?php echo $row_mon_hoc['mh_ten'] ?>" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Khối lớp</label>
                                                <select name="kl_ma" id="defaultSelect" class="form-select">
                                                    <option disabled selected value="<?php echo  $row_mon_hoc['kl_ma']; ?>">
                                                        <?php echo  $row_mon_hoc['kl_ten']; ?></option>
                                                    <?php
                                                        $khoi_lop = "select * from khoi_lop";
                                                        $result_khoi_lop = mysqli_query($conn, $khoi_lop);
                                                        while ($row_khoi_lop = mysqli_fetch_array($result_khoi_lop)) {
                                                        ?>
                                                    <option value="<?php echo $row_khoi_lop['kl_ma']; ?>">
                                                        <?php echo $row_khoi_lop['kl_ten']; ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        
                                         
                                            <button name="CapNhatMonHoc" type="submit" class="btn btn-primary">Cập nhật</button>
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