<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apdung'])) {
        $selectedAction = $_POST['tacvu'];
        $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];
        // var_dump($selectedIds);


        if ($selectedAction == "xoa") {
            // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
            mysqli_begin_transaction($conn);

            try {
                foreach ($selectedIds as $id) {
                    $xoa_khoi_lop="DELETE FROM khoi_lop where kl_ma= '$id'";
                    mysqli_query($conn,$xoa_khoi_lop); 
            
                    
                }

                // Nếu mọi thứ thành công, commit transaction
                mysqli_commit($conn);
                echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";   
                header("Refresh: 0;url=QL_DanhMuc.php");  
            }catch(mysqli_sql_exception $e){
                echo "<script>alert('Dữ liệu đang được sử dụng không thể xóa! Vui lòng kiểm tra lại');</script>"; 
            }
            header("Refresh: 0;url=QL_KhoiLop.php");  
        } 
    }


    $so_dong = 5;
    $monhoc = 'Tất cả';
    $sap_xep = 'DESC';
    
    if (isset($_GET['sodong'])) {
        $so_dong = intval($_GET['sodong']);
    }
  
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === "asc") {
            $sap_xep = "asc";
        } elseif ($_GET['sort'] === "desc") {
            $sap_xep = "desc";
        }
    }
    

    $khoi_lop = "SELECT * FROM khoi_lop order by kl_ma $sap_xep LIMIT $so_dong ";
    $result_khoi_lop = mysqli_query($conn,$khoi_lop);


    if (isset($_POST['ThemKhoiLop'])) {
        $ten = $_POST['name'];
        $ma = "";
        $them_khoi_lop = "INSERT INTO khoi_lop VALUES ('$ma', '$ten')";
        mysqli_query($conn,$them_khoi_lop);           
        echo "<script>alert('Thêm khối lớp mới thành công!');</script>"; 
        header("Refresh: 0;url=QL_KhoiLop.php"); 	
    }

    unset($_SESSION['sl_dong']);
    $sl_dong_hientai = mysqli_num_rows($result_khoi_lop);
    $_SESSION['sl_dong'] = $sl_dong_hientai;
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý khối lớp</title>

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
                                <li class="breadcrumb-item active">Quản lý khối lớp</li>
                            </ol>
                        </nav>



                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 class="card-header">Danh sách khối lớp</h5>

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
                            <div class="row card-header d-flex flex-wrap justify-content-between">



                                <div>

                                    <div class="row ">

                                        <style>
                                        @media (max-width: 300px) {
                                            #Export {
                                                /* Đặt kích thước của button thành 10px khi màn hình nhỏ hơn 400px */
                                                width: 20px;
                                                height: 40px;
                                            }
                                        }
                                        </style>


                                        <div style="display: flex; justify-content: right;">
                                            <button class=" dt-button add-new btn btn-primary" type="button"
                                                data-bs-toggle="modal" data-bs-target="#modalCenter">
                                                <span>
                                                    <i class="bx bx-plus"></i>
                                                    <span class="d-none d-sm-inline-block">Thêm khối lớp</span>
                                                </span>
                                            </button>

                                        </div>

                                    </div>
                                </div>
                            </div>


                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Thêm khối lớp</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <label for="nameWithTitle" class="form-label">Tên khối
                                                            lớp</label>
                                                        <input name="name" type="text" id="nameWithTitle"
                                                            class="form-control" placeholder="Nhập vào tên khối lớp" />
                                                    </div>
                                                </div>

                                            </div>
                                            <div style="padding: 0 1rem 1rem 0" class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    Hủy
                                                </button>
                                                <button name="ThemKhoiLop" type="submit"
                                                    class="btn btn-primary">Thêm</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <hr>
                            <form method="POST" enctype="multipart/form-data">
                                <div style="margin-left:0.1rem;" class="col-lg-12  row ">

                                    <div class="col-md-2 col-lg-2 col-sm-3 col-6 ">
                                        <select name="tacvu" class="form-select" id="tacvu">
                                            <option value="Tất cả">Chọn tác vụ</option>
                                            <option value="xoa">Xoá</option>
                                            <!-- <option value="1">Duyệt</option>
                                            <option value="2">Huỷ</option> -->
                                        </select>
                                    </div>
                                    <div class="col-md-6 col-lg-6 col-sm-6 col-6 ">
                                        <button name="apdung" type="submit" class="btn btn-primary" id="apdung">
                                            <span>
                                                <i class="fa fa-check me-2"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Áp dụng</span>

                                            </span>
                                        </button>

                                        <label>
                                            <select name="so_dong" class="form-select" id="so_dong">
                                                <?php
                                                                $sd="SELECT count(*) as tong FROM khoi_lop";
                                                                $result_sd = mysqli_query($conn,$sd);
                                                                $row_sd = mysqli_fetch_assoc($result_sd);

                                                                $tong = $row_sd['tong'];

                                                                // In ra các số tròn chục nhỏ hơn tổng
                                                                echo "Các số tròn chục nhỏ hơn tổng là: ";
                                                                for ($i = 5; $i <= $tong+5; $i += 5) {
                                                                    echo "<option value='".$i."'>".$i."</option>";
                                                                }
                                                            
                                                    
                                                    ?>
                                            </select>
                                        </label>


                                    </div>
                                    <!-- <div class="col-lg-8 col-sm-2 col-3">
                                        <form method="POST">
                                            <div class="dataTables_length mt-0 mt-md-0 ">

                                            </div>
                                        </form>
                                    </div> -->





                                    <!-- Giao diện -->
                                    <div class="col-md-4 col-lg-4 col-sm-3 row mt-4">
                                        <div style="display: flex; justify-content: right;" class="col-lg-12">
                                            <p>Đang hiển thị:
                                                <span id="so_dong_hien_tai">
                                                    <?php echo $_SESSION['sl_dong']; ?>
                                                </span>/<?php echo $tong; ?> (kết quả)
                                            </p>
                                        </div>
                                    </div>


                                </div>

                                <div class="table-responsive text-nowrap border-top">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input class="form-check-input" id="checkall" type="checkbox">
                                                </th>
                                                <th>STT</th>
                                                <style>
                                                .sort-header {
                                                    position: relative;
                                                }

                                                .small-button {
                                                    background-color: none;
                                                    background: none;
                                                    border: none;
                                                    font-size: 11px;
                                                    /* Reduce the font size */
                                                    padding: 5px 10px;
                                                    border-radius: 0.3rem;
                                                    cursor: pointer;
                                                    margin-left: 5rem;
                                                    color: #CCCCCC;
                                                }

                                                .sort-header button#asc {
                                                    position: absolute;
                                                    top: 0;
                                                    left: 0;
                                                }

                                                .sort-header button#desc {
                                                    position: absolute;
                                                    bottom: 0;
                                                    left: 0;
                                                }

                                                .active {
                                                    color: #666666;
                                                    /* Change the color to the desired active color */
                                                }
                                                </style>
                                                <th class="sort-header">Mã
                                                    <button id="asc" type="button"  class="small-button"
                                                        data-sort="asc"><i class="fa fa-sort-asc"></i></button>
                                                    <button id="desc" type="button"  class="small-button"
                                                        data-sort="desc"><i class="fa fa-sort-desc"></i></button>
                                                </th>
                                                <th>Tên khối lớp </th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-container">
                                            <?php
                                                        $stt = 0;
                                                        while ($row_khoi_lop = mysqli_fetch_array($result_khoi_lop)) {
                                                            $stt = $stt + 1;
                                                    ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input check-item" name="check[]"
                                                        type="checkbox" value="<?php echo $row_khoi_lop['kl_ma'] ?>">
                                                </td>
                                                <td class="row-bai-viet">
                                                    <?php echo $stt ?> </td>
                                                <td><strong><?php echo  $row_khoi_lop['kl_ma']; ?></strong></td>
                                                <td>
                                                    <?php echo  $row_khoi_lop['kl_ten']; ?>
                                                </td>

                                                <td>
                                                    <!-- <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="Xem_KhoiLop.php?this_kl_ma=<?php echo $row_khoi_lop['kl_ma']?>">
                                                                    <i class="fa fa-eye"></i>
                                                                </a> -->
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="Sua_KhoiLop.php?this_kl_ma=<?php echo $row_khoi_lop['kl_ma']?>">
                                                        <i class="bx bx-edit-alt me-1"></i>
                                                    </a>
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="#"
                                                        onclick="Xoa_Khoilop('<?php echo $row_khoi_lop['kl_ma']?>');">
                                                        <i class="bx bx-trash me-1"></i>
                                                    </a>

                                                </td>
                                            </tr>
                                            <?php } ?>

                                        </tbody>
                                    </table>
                                </div>
                            </form>
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

        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
            integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->

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
            $('#so_dong').change(function() {
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
                $.ajax({
                    url: 'sodong_KL.php',
                    data: {
                        sodong: chon_gtri,
                        sort: sortOrder
                    },
                    success: function(data) {
                        $('#data-container').html(data);
                        updateDisplayInfo();
                    }
                });
            }
            // Hàm cập nhật thông tin hiển thị
            function updateDisplayInfo() {
                var soDongHienTai = $('#data-container .row-bai-viet').length;
                $('#so_dong_hien_tai').text(soDongHienTai);
            }
        });
        </script>

        <!-- JavaScript để điều khiển hành vi -->
        <script>
        // Lắng nghe sự kiện khi checkbox chọn tất cả được thay đổi trạng thái
        document.getElementById('checkall').addEventListener('change', function() {
            // Lấy tất cả các checkbox cần chọn
            var checkboxes = document.querySelectorAll('.check-item');

            // Duyệt qua từng checkbox và thiết lập trạng thái chọn/bỏ chọn
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = document.getElementById('checkall').checked;
            });
        });
        </script>
</body>

</html>