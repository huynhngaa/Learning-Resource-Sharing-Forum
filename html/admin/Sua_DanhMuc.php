<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_dm_ma = $_GET['this_dm_ma'];

    $danh_muc = "SELECT * FROM danh_muc a, mon_hoc b WHERE a.mh_ma=b.mh_ma and dm_ma='$this_dm_ma'";
    $result_danh_muc = mysqli_query($conn,$danh_muc);
    $row_danh_muc = mysqli_fetch_assoc($result_danh_muc);

    if (isset($_POST['CapNhatDanhMuc'])) {
        $dm_ten = $_POST['dm_ten'];
        // $mh_ma = $_POST['mh_ma'];
        $mh_ma = isset($_POST['mh_ma']) ? $_POST['mh_ma'] : $row_danh_muc['mh_ma'];
       
        $cap_nhat_danh_muc = "UPDATE danh_muc SET dm_ten = '$dm_ten', mh_ma = '$mh_ma' WHERE dm_ma='$this_dm_ma'";
        mysqli_query($conn,$cap_nhat_danh_muc);           
        echo "<script>alert('Cập nhật danh mục thành công!');</script>"; 
        header("Refresh: 0;url=QL_DanhMuc.php"); 	
    }
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Cập nhật danh mục</title>

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
                        
                        <!-- Basic Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="index.php">Home</a>
                            </li> 
                            <li class="breadcrumb-item">
                                <a href="QL_DanhMuc.php">Quản lý danh mục</a>
                            </li> 
                            <li class="breadcrumb-item active">Cập nhật danh mục</li>
                            </ol>
                        </nav>

                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Cập nhật danh mục</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Tên danh mục</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                            class="fa fa-bookmark"></i></span>
                                                    <input name="dm_ten" type="text" class="form-control"
                                                        id="basic-icon-default-fullname" placeholder="Nhập vào tên danh mục"
                                                        aria-label="Nhập vào tên danh mục"
                                                        aria-describedby="basic-icon-default-fullname2" value = "<?php echo $row_danh_muc['dm_ten'] ?>" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Môn học</label>
                                                <select name="mh_ma" id="defaultSelect" class="form-select">
                                                    <option disabled selected value="<?php echo  $row_danh_muc['mh_ma']; ?>">
                                                        <?php echo  $row_danh_muc['mh_ten']; ?></option>
                                                    <?php
                                                        $mon_hoc = "select * from mon_hoc";
                                                        $result_mon_hoc = mysqli_query($conn, $mon_hoc);
                                                        while ($row_mon_hoc = mysqli_fetch_array($result_mon_hoc)) {
                                                        ?>
                                                    <option value="<?php echo $row_mon_hoc['mh_ma']; ?>">
                                                        <?php echo $row_mon_hoc['mh_ten']; ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>
                                        
                                         
                                            <button name="CapNhatDanhMuc" type="submit" class="btn btn-primary">Cập nhật</button>
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