<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_mh_ma = $_GET['this_mh_ma'];

    $bai_viet = "SELECT * FROM bai_viet a, danh_muc b, nguoi_dung e WHERE a.dm_ma=b.dm_ma and a.nd_username=e.nd_username";
    $result_bai_viet = mysqli_query($conn,$bai_viet);

    // if (isset($_POST['CapNhatMonHoc'])) {
    //     $mh_ten = $_POST['mh_ten'];
    //     $kl_ma = $_POST['kl_ma'];
    //     $cap_nhat_mon_hoc = "UPDATE mon_hoc SET mh_ten = '$mh_ten', kl_ma = '$kl_ma' WHERE mh_ma='$this_mh_ma'";
    //     mysqli_query($conn,$cap_nhat_mon_hoc);           
    //     echo "<script>alert('Cập nhật môn học mới thành công!');</script>"; 
    //     header("Refresh: 0;url=QL_MonHoc.php"); 	
    // }
   
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
                        <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Quản lý bài viết/</span> Chi
                            tiết bài viết</h4>

                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Chi tiết bài viết</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Tên môn
                                                    học</label>
                                                <div class="input-group input-group-merge">
                                                    <span id="basic-icon-default-fullname2" class="input-group-text"><i
                                                            class="fa fa-braille"></i></span>
                                                    <input name="mh_ten" type="text" class="form-control"
                                                        id="basic-icon-default-fullname"
                                                        placeholder="Nhập vào tên môn học"
                                                        aria-label="Nhập vào tên môn học"
                                                        aria-describedby="basic-icon-default-fullname2"
                                                        value="<?php echo $row_mon_hoc['mh_ten'] ?>" />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Khối
                                                    lớp</label>
                                                <select name="kl_ma" id="defaultSelect" class="form-select">
                                                    <option disabled selected
                                                        value="<?php echo  $row_mon_hoc['kl_ma']; ?>">
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

                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Nội
                                                    dung</label>
                                                <textarea name="Noidung_Baiviet" cols="30"
                                                    rows="10"><?php echo $row['KM_NoiDung']; ?></textarea>

                                            </div>

                                            <button name="CapNhatMonHoc" type="submit" class="btn btn-primary">Cập
                                                nhật</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h5 class="mb-2">About this course</h5>
                                    <p class="mb-0 pt-1">Learn web design in 1 hour with 25+ simple-to-use rules and
                                        guidelines — tons
                                        of amazing web design resources included!</p>
                                    <hr class="my-4">
                                    <h5>By the numbers</h5>
                                    <div class="d-flex flex-wrap">
                                        <div class="me-5">
                                            <p class="text-nowrap"><i class="bx bx-check-double bx-sm me-2"></i>Skill
                                                level: All Levels</p>
                                            <p class="text-nowrap"><i class="bx bx-user bx-sm me-2"></i>Students: 38,815
                                            </p>
                                            <p class="text-nowrap"><i class="bx bxs-flag-alt bx-sm me-2"></i>Languages:
                                                English</p>
                                            <p class="text-nowrap "><i class="bx bx-file bx-sm me-2"></i>Captions: Yes
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-nowrap"><i class="bx bx-pencil bx-sm me-2"></i>Lectures: 19
                                            </p>
                                            <p class="text-nowrap "><i class="bx bxs-watch bx-sm me-2"></i>Video: 1.5
                                                total hours</p>
                                        </div>
                                    </div>
                                    <hr class="mb-4 mt-2">
                                    <h5>Description</h5>
                                    <p class="mb-4">
                                        The material of this course is also covered in my other course about web design
                                        and development
                                        with HTML5 &amp; CSS3. Scroll to the bottom of this page to check out that
                                        course, too!
                                        If you're already taking my other course, you already have all it takes to start
                                        designing beautiful
                                        websites today!
                                    </p>
                                    <p class="mb-4">
                                        "Best web design course: If you're interested in web design, but want more than
                                        just a "how to use WordPress" course,I highly recommend this one." — Florian
                                        Giusti
                                    </p>
                                    <p> "Very helpful to us left-brained people: I am familiar with HTML, CSS, JQuery,
                                        and Twitter Bootstrap, but I needed instruction in web design. This course gave
                                        me practical,
                                        impactful techniques for making websites more beautiful and engaging." — Susan
                                        Darlene Cain
                                    </p>
                                    <hr class="my-4">
                                    <h5>Instructor</h5>
                                    <div class="d-flex justify-content-start align-items-center user-name">
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm me-2"><img
                                                    src="../../assets/img/avatars/11.png" alt="Avatar"
                                                    class="rounded-circle"></div>
                                        </div>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">Devonne Wallbridge</span>
                                            <small class="text-muted">Web Developer, Designer, and Teacher</small>
                                        </div>
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

        <!-- Thanh công cụ soanj thảo -->
        <script src="./ckeditor/ckeditor/ckeditor.js"></script>
        <script src="./ckfinder/ckfinder/ckfinder.js"></script>

        <script>
        CKEDITOR.replace('Noidung_Baiviet', {
            filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
            filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'

        })
        </script>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>