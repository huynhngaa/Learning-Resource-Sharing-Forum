<?php
    session_start();
    include("./includes/connect.php");
    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}

    if (isset($_POST['ThemDanhMuc'])) {
        $dm_ten = $_POST['dm_ten'];
        $mh_ma = $_POST['mh_ma'];
        $dm_ma = "";
        $them_danh_muc = "INSERT INTO danh_muc VALUES ('$dm_ma','$mh_ma','$dm_ten')";
        mysqli_query($conn,$them_danh_muc);           
        echo "<script>alert('Thêm danh mục mới thành công!');</script>"; 
        header("Refresh: 0;url=QL_DanhMuc.php"); 	
    }

    $so_dong = 5;
    $monhoc = 'Tất cả';
    $sap_xep = 'DESC';
    
    if (isset($_GET['sodong'])) {
        $so_dong = intval($_GET['sodong']);
    }

    if (isset($_GET['monhoc'])) {
        $monhoc = $_GET['monhoc'];
    }
    
    
    
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === "asc") {
            $sap_xep = "asc";
        } elseif ($_GET['sort'] === "desc") {
            $sap_xep = "desc";
        }
    }
    

    if ($monhoc == "Tất cả") {
        $danh_muc_cha = "SELECT * 
                        FROM danh_muc a, mon_hoc b, danhmuc_phancap c
                        where c.dm_cha=a.dm_ma and a.mh_ma = b.mh_ma
                        order by a.mh_ma $sap_xep 
                        LIMIT $so_dong";
    }else{
        $danh_muc_cha = "SELECT * 
                        FROM danh_muc a, mon_hoc b, danhmuc_phancap c
                        where c.dm_cha=a.dm_ma and a.mh_ma = b.mh_ma
                        and a.mh_ma = '$monhoc'
                        order by a.mh_ma $sap_xep 
                        LIMIT $so_dong";
    }

    $result_danh_muc_cha = mysqli_query($conn,$danh_muc_cha);
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý Danh mục</title>

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
                                <li class="breadcrumb-item active">Cấp danh mục</li>
                            </ol>
                        </nav>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Cấp danh mục</h5>

                            <style>
                            .btn-label-secondary {
                                color: #8592a3;
                                border-color: rgba(0, 0, 0, 0);
                                background: #ebeef0;
                            }

                            .btn-label-secondary:hover {
                                border-color: rgba(0, 0, 0, 0) !important;
                                background: #788393 !important;
                                color: #fff !important;
                                box-shadow: 0 0.125rem 0.25rem 0 rgba(133, 146, 163, .4) !important;
                                transform: translateY(-1px) !important;
                            }

                            .btn:hover {
                                color: var(--bs-btn-hover-color);
                                background-color: var(--bs-btn-hover-bg);
                                border-color: var(--bs-btn-hover-border-color);
                            }

                            .btn-primary {
                                color: #fff;
                                background-color: #696cff;
                                border-color: #696cff;
                                box-shadow: 0 0.125rem 0.25rem 0 rgba(105, 108, 255, .4);
                            }
                            </style>
                            <div class="card-header">
                              


                               

                                    <div >
                                        
                                     
                                        <button class=" dt-button add-new btn btn-primary"
                                            type="button" data-bs-toggle="modal" data-bs-target="#modalCenter">
                                            <span>
                                                <i class="bx bx-plus me-0 me-sm-1"></i>
                                                <span class="d-none d-lg-inline-block">Thêm phân cấp</span>
                                            </span>
                                        </button>



                                    </div>
                               

                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Thêm danh mục</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="nameWithTitle" class="form-label">Tên danh
                                                            mục</label>
                                                        <input name="dm_ten" type="text" id="nameWithTitle"
                                                            class="form-control" placeholder="Nhập vào tên danh mục" />
                                                    </div>

                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-fullname">Môn
                                                        học</label>
                                                    <select name="mh_ma" id="defaultSelect" class="form-select">
                                                        <option disabled selected value=""> Chọn môn học</option>
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

                                            </div>
                                            <div style="padding: 0 1rem 1rem 0" class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    Hủy
                                                </button>
                                                <button name="ThemDanhMuc" type="submit"
                                                    class="btn btn-primary">Thêm</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                          <div style="margin-left:5rem; margin-top:1rem">
                                <?php
                                    $data = array();

                                    $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
                                            FROM danh_muc d
                                            LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con ";
                                    $result = mysqli_query($conn, $sql);

                                    // Tạo mảng chứa danh mục cha
                                    $danhmuc_goc = array();

                                    while ($row = mysqli_fetch_assoc($result)) {
                                        $data[] = array(
                                            "id" => $row['dm_ma'],
                                            "name" => $row['dm_ten'],
                                            "parent" => $row['dm_cha']
                                        );

                                        // Lọc danh mục goc
                                        if ($row['dm_cha'] == 0) {
                                            $danhmuc_goc[] = $row;
                                        }
                                    }

                                    // Duyệt qua danh mục cha và hiển thị
                                    foreach ($danhmuc_goc as $goc) {
                                        echo '<b><i class="fa fa-folder-open" aria-hidden="true"></i>' . $goc['dm_ten'] . '<br>';
                                        // Gọi hàm đệ quy để hiển thị danh mục con của danh mục góc
                                        dequy($data, $goc['dm_ma']);
                                        echo '</b>';
                                    }

                                    function dequy($data, $parent, $level =0) {
                                        foreach ($data as $k => $value) {
                                            if ($value['parent'] == $parent) {
                                                echo '<p>|<i class="fa fa-window-minimize" aria-hidden="true"></i>';
                                                echo str_repeat('<i class="fa fa-window-minimize" aria-hidden="true"></i>', $level) . $value['name'];
                                                echo '</p>';
                                                unset($data[$k]);
                                                dequy($data, $value['id'], $level+1);
                                            }
                                        }
                                    }
                                    ?>
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

        <?php
            include_once("includes/ThongBao.php");
        ?>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
        $(document).ready(function() {
            $('.small-button').click(function() {
                // Remove the 'active' class from all buttons
                $('.small-button').removeClass('active');

                // Add the 'active' class to the clicked button
                $(this).addClass('active');
            });
        });
        </script>

        <script>
        $(document).ready(function() {
            // Xử lý khi số dòng hoặc trạng thái thay đổi
            $('#so_dong,#monhoc').change(function() {
                updateDataContainer();
            });

            // Xử lý khi nhấn nút sắp xếp
            $('#asc, #desc').click(function() {
                var sortOrder = $(this).attr('id'); // Lấy 'asc' hoặc 'desc' từ id của nút
                updateDataContainer(sortOrder);
                $(this).addClass('active');
                $('#desc, #asc').not(this).removeClass('active');
            });


            // Hàm cập nhật dữ liệu với tùy chọn sắp xếp
            function updateDataContainer(sortOrder) {
                var chon_gtri = $('#so_dong').val();
                var chon_monhoc = $('#monhoc').val();
                $.ajax({
                    url: 'sodong_DM.php',
                    data: {
                        sodong: chon_gtri,
                        monhoc: chon_monhoc,
                        sort: sortOrder
                    },
                    success: function(data) {
                        $('#data-container').html(data);
                    }
                });
            }
        });
        </script>
</body>

</html>