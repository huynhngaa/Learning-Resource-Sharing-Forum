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
                    $xoa_pc_danh_muc="DELETE FROM quan_ly where nd_username= '$id'";
                    mysqli_query($conn,$xoa_pc_danh_muc); 
            
                    
                }

                // Nếu mọi thứ thành công, commit transaction
                mysqli_commit($conn);
                echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";   
                header("Refresh: 0;url=PCQL_DanhMuc.php");  
            }catch(mysqli_sql_exception $e){
                echo "<script>alert('Dữ liệu đang được sử dụng! Không thể xóa!');</script>"; 
            }
            header("Refresh: 0;url=PCQL_DanhMuc.php");  
           
        } 
    }

    if (isset($_POST['Them'])) {
        $user = isset($_POST['user']) ? $_POST['user'] : "";
        $dm_ma_array = isset($_POST['dm_ma']) ? $_POST['dm_ma'] : "";
        // if($user == ""){
        //     $_SESSION['bg_thongbao'] = "bg-danger";
        //     $_SESSION['thongbao_thucthi'] = "Thêm phân công thất bại. Lý do: Không có người dùng nào được chọn!"; 
        // }
        // Kiểm tra danh sách danh mục không trống và là một mảng
        if (!empty($dm_ma_array) && is_array($dm_ma_array)) {
            $danhmuc_dachon = array();
            // Lặp qua từng danh mục được chọn từ form
            foreach ($dm_ma_array as $dm_ma) {
                // Kiểm tra xem người dùng đã được gán vào danh mục chưa
                $check_assignment_sql = "SELECT * FROM quan_ly q 
                                            INNER JOIN danh_muc d 
                                            ON q.dm_ma = d.dm_ma 
                                            WHERE q.dm_ma = '$dm_ma' 
                                            AND q.nd_username = '$user'";
                $check_assignment_result = mysqli_query($conn, $check_assignment_sql);
                // Nếu người dùng đã được phân công vào danh mục đó, thì thêm tên danh mục vào mảng $danhmuc_dachon
                if (mysqli_num_rows($check_assignment_result) > 0) {
                    $row = mysqli_fetch_assoc($check_assignment_result);
                    $danhmuc_dachon[] = $row["dm_ten"]; // Thêm tên danh mục vào danh sách nếu đã được gán
                } 
                // Nếu người dùng chưa được phân công vào danh mục đó, thêm một bản ghi mới vào bảng quan_ly
                else {
                    $them_ql_danh_muc = "INSERT INTO quan_ly (dm_ma, nd_username) VALUES ('$dm_ma', '$user')";
                    mysqli_query($conn, $them_ql_danh_muc);
                }
            }
        
            if (empty($danhmuc_dachon)) {
                // echo "<script>alert('Thêm người quản lý danh mục thành công!');</script>";
                // header("Refresh: 0;url=PCQL_DanhMuc.php");
                $_SESSION['bg_thongbao'] = "bg-success";
                $_SESSION['thongbao_thucthi'] = "Thêm người quản lý danh mục thành công!";
            }
            // Nếu danh sách $danhmuc_dachon không trống, hiển thị thông báo với các tên danh mục đã chọn trước đó.
            else {
                $danhmuc_da_phancong = implode(', ', $danhmuc_dachon);
                // echo "<script>alert('Danh mục \"$danhmuc_da_phancong\" đã được phân công trước đó. Vui lòng kiểm tra lại.');</script>";
                // header("Refresh: 0;url=PCQL_DanhMuc.php");
                $_SESSION['bg_thongbao'] = "bg-warning";
                $_SESSION['thongbao_thucthi'] = "Danh mục \"$danhmuc_da_phancong\", đã được phân công trước đó. Vui lòng kiểm tra lại.";
            }
        } else {
            // echo "<script>alert('Không có danh mục nào được chọn!');</script>";
            $_SESSION['bg_thongbao'] = "bg-danger";
            $_SESSION['thongbao_thucthi'] = "Thêm phân công thất bại. Lý do: Các trường bắt buộc không được bỏ trống!";
        }
    }
    
    
   

    $so_dong = 5;
    $danhmuc = 'Tất cả';
    $sap_xep = 'DESC';
    
    if (isset($_GET['sodong'])) {
        $so_dong = intval($_GET['sodong']);
    }

    if (isset($_GET['danhmuc'])) {
        $danhmuc = $_GET['danhmuc'];
    }
    
    
    
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === "asc") {
            $sap_xep = "asc";
        } elseif ($_GET['sort'] === "desc") {
            $sap_xep = "desc";
        }
    }
    

    if ($danhmuc == "Tất cả") {
        $danh_muc = "SELECT DISTINCT ( a.nd_username),nd_hoten
                        FROM quan_ly a, nguoi_dung b, danh_muc c
                        where a.nd_username = b.nd_username 
                        and c.dm_ma=a.dm_ma
                        order by a.tg_phancong $sap_xep 
                        LIMIT $so_dong";
    }else{
        $danh_muc = "SELECT DISTINCT ( a.nd_username),nd_hoten
                       FROM quan_ly a, nguoi_dung b, danh_muc c
                        where a.nd_username = b.nd_username 
                        and c.dm_ma=a.dm_ma
                        and a.dm_ma in('$danhmuc')
                        order by a.nd_username $sap_xep 
                        LIMIT $so_dong";
    }

    $result_danh_muc = mysqli_query($conn,$danh_muc);

    unset($_SESSION['sl_dong']);
    $sl_dong_hientai = mysqli_num_rows($result_danh_muc);
    $_SESSION['sl_dong'] = $sl_dong_hientai;
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý môn học</title>

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
                                <li class="breadcrumb-item active">Phân công người quản lý danh mục</li>
                            </ol>
                        </nav>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 style="padding: 1.5rem 0 0 1.5rem" class="card-header">Danh sách phân công người quản lý
                                danh mục</h5>

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
                                <div class="row col-lg-5 col-md-5">
                                    <div class="d-flex align-items-center col-lg-4 col-md-4">
                                        <label>Danh mục: </label>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <select id="danhmuc" class="form-select">
                                            <option value="Tất cả">Tất cả</option>
                                            <?php

                                                $data = array();

                                                $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
                                                FROM danh_muc d
                                                LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con";
                                                $result = mysqli_query($conn, $sql);



                                                while ($monhoc = mysqli_fetch_array($result)) {

                                                        $data[] = array(
                                                            "id" => $monhoc['dm_ma'],
                                                            "name" => $monhoc['dm_ten'],
                                                            "parent" => $monhoc['dm_cha']
                                                        
                                                        );

                                                
                                                }

                                                function dequy3($data, $parent = 0, $level = 0) {
                                                    foreach ($data as $k => $value) {
                                                        if ($value['parent'] == $parent ) {
                                                            echo '<option value="' . $value['id'] . '">';
                                                            echo str_repeat('- ', $level) . $value['name'];
                                                            echo '</option>';
                                                            unset($data[$k]);
                                                            dequy3($data, $value['id'], $level + 1);
                                                        }
                                                    }
                                                }

                                                dequy3($data);
                                                ?>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-6 col-md-6 mt-2">

                                    <div class="row">
                                        <div class="col-lg-2 col-sm-2 col-4">
                                            <form method="POST">
                                                <div class="dataTables_length mt-0 mt-md-0">
                                                    <label>
                                                        <select name="so_dong" class="form-select" id="so_dong">
                                                            <?php
                                                                $sd="SELECT count(DISTINCT(nd_username)) as tong FROM quan_ly";
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
                                            </form>
                                        </div>
                                        <style>
                                        @media (max-width: 300px) {
                                            #Export {
                                                /* Đặt kích thước của button thành 10px khi màn hình nhỏ hơn 400px */
                                                width: 20px;
                                                height: 40px;
                                            }
                                        }
                                        </style>
                                        <div class="btn-group col-lg-4  col-sm-3 col-4  ms-sm-0 me-3">
                                            <button class="dt-button btn btn-label-secondary " data-bs-toggle="dropdown"
                                                aria-haspopup="true" aria-expanded="false">

                                                <span>
                                                    <i class="bx bx-export "></i>
                                                    <span class="dt-down-arrow d-none d-xl-inline-block">Export ▼</span>

                                                </span>

                                            </button>
                                            <div class="dt-button-collection dropdown-menu"
                                                style="top: 55.9375px; left: 419.797px;"
                                                aria-labelledby="dropdownMenuButton">
                                                <div role="menu">
                                                    <a href="InBangPhanCong_DanhMuc.php"
                                                        class="dt-button buttons-print dropdown-item" tabindex="0"
                                                        type="button">
                                                        <span><i class="bx bx-printer me-2"></i>Print</span>
                                                    </a>
                                                    <a href="ExcelBangPhanCong_DanhMuc.php"
                                                        class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                        type="button">
                                                        <span><i class="bx bx-file me-2"></i>Excel</span>
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                        <button class="col-lg-5  col-sm-5 col-2 dt-button add-new btn btn-primary"
                                            type="button" data-bs-toggle="modal" data-bs-target="#modalCenter">
                                            <span>
                                                <i class="bx bx-plus me-0 me-sm-1"></i>
                                                <span class="d-none d-lg-inline-block">Thêm phân công</span>
                                            </span>
                                        </button>

                                    </div>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalCenterTitle">Thêm phân công người quản lý
                                                danh mục</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <!-- <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="basic-icon-default-fullname">Tên
                                                            danh
                                                            mục</label>
                                                        <select name="dm_ma" id="defaultSelect" class="form-select">
                                                            <option disabled selected value=""> Chọn danh mục</option>
                                                            <?php
                                                                // $danhmuc = "select * from danh_muc";
                                                                // $result_danhmuc= mysqli_query($conn, $danhmuc);
                                                                // while ($row_danhmuc = mysqli_fetch_array($result_danhmuc)) {
                                                            ?>
                                                            <option value="<?php // echo $row_danhmuc['dm_ma']; ?>">
                                                                <?php // echo $row_danhmuc['dm_ten']; ?></option>
                                                            <?php // }
                                                    ?>
                                                        </select>
                                                    </div>
                                                </div> -->

                                                <div class="row">
                                                    <div class="mb-3">
                                                        <label class="form-label"
                                                            for="basic-icon-default-fullname">Người quản lý</label><span style="color: red;font-weight: bold;"> *</span>
                                                        <select name="user" id="defaultSelect" class="form-select" require>
                                                            <option value="" disabled selected value=""> Chọn người quản lý
                                                            </option>
                                                            <?php
                                                        $nd = "select * from nguoi_dung where vt_ma = '2'";
                                                        $result_nd = mysqli_query($conn, $nd);
                                                        while ($row_nd = mysqli_fetch_array($result_nd)) {
                                                        ?>
                                                            <option value="<?php echo $row_nd['nd_username']; ?>">
                                                                <?php echo $row_nd['nd_hoten']; ?></option>
                                                            <?php }
                                                    ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <style>
                                                .tree {
                                                    list-style-type: none;
                                                    margin-left: 0;
                                                    padding-left: 1em;

                                                }

                                                .tree>li {
                                                    padding-left: 0.5rem;
                                                    position: relative;
                                                }

                                                .tree>li:before {
                                                    content: "";
                                                    position: absolute;
                                                    left: -1em;
                                                    top: 0.5em;
                                                    border-left: 1px solid #000;
                                                    /* Thay đổi màu sắc và chiều dài của đường kẻ */
                                                }
                                                </style>

                                                <!-- <div class="row"> -->
                                                <label class="form-label" for="basic-icon-default-fullname">Chọn
                                                    danh mục phân công</label><span style="color: red;font-weight: bold;"> *</span>
                                                <?php
                                                        // $danhmuc = "SELECT * FROM danh_muc";
                                                        // $result_danhmuc = mysqli_query($conn, $danhmuc);
                                                        // $total_rows = mysqli_num_rows($result_danhmuc);
                                                        // $half_rows = ceil($total_rows / 2);
                                                        // $count = 0;

                                                        // while ($row_danhmuc = mysqli_fetch_array($result_danhmuc)) {
                                                        //     if ($count % $half_rows == 0) {
                                                        //         echo '</div><div class="row">';
                                                        //     }
                                                            ?>
                                                <!-- <div class="col-md-6"> -->
                                                <!-- <div class="form-check"> -->

                                                <!-- <input class="form-check-input" type="checkbox"
                                                                name="dm_ma[]"
                                                                value="<?php echo $row_danhmuc['dm_ma']; ?>">
                                                            <label class="form-check-label">
                                                                <?php echo $row_danhmuc['dm_ten']; ?>
                                                            </label> -->
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
                                                            
                                                                // Lọc danh mục gốc
                                                                if ($row['dm_cha'] == 0) {
                                                                    $danhmuc_goc[] = $row;
                                                                }
                                                            }
                                                            
                                                            // Duyệt qua danh mục gốc và hiển thị
                                                            foreach ($danhmuc_goc as $goc) {
                                                                echo '<ul class="tree">';
                                                                echo '<li><label><input  name="dm_ma[]" class="category form-check-input" type="checkbox" value="' . $goc['dm_ma'] . '">  ' . $goc['dm_ten'] . '</label>';
                                                                // Gọi hàm đệ quy để hiển thị danh mục con của danh mục gốc
                                                                dequy($data, $goc['dm_ma']);
                                                                echo '</li></ul>';
                                                            }
                                                            
                                                            function dequy($data, $parent) {
                                                                echo '<ul class="tree">';
                                                                foreach ($data as $k => $value) {
                                                                    if ($value['parent'] == $parent) {
                                                                        echo '<li><label><input  name="dm_ma[]" class="category form-check-input" type="checkbox" value="' . $value['id'] . '"> ' . $value['name'] . '</label>';
                                                                        dequy($data, $value['id']);
                                                                        echo '</li>';
                                                                        unset($data[$k]);
                                                                    }
                                                                }
                                                                echo '</ul>';
                                                            
                                                            // Duyệt qua danh mục gốc và hiển thị
                                                            // foreach ($danhmuc_goc as $goc) {
                                                            //     echo '<div >';
                                                            //     echo '<input class="category" type="checkbox" value="' . $goc['dm_ma'] . '">  ';
                                                            //     echo '<label>' . $goc['dm_ten'] . '</label>';
                                                            //     // Gọi hàm đệ quy để hiển thị danh mục con của danh mục gốc
                                                            //     dequy($data, $goc['dm_ma']);
                                                            //     echo '</div>';
                                                            // }

                                                            // function dequy($data, $parent)
                                                            // {
                                                            //     echo '<div >';
                                                            //     foreach ($data as $k => $value) {
                                                            //         if ($value['parent'] == $parent) {
                                                            //             echo '<div>';
                                                            //             echo '<input class="category" type="checkbox" value="' . $value['id'] . '"> ';
                                                            //             echo '<label>' . $value['name'] . '</label>';
                                                            //             dequy($data, $value['id']);
                                                            //             echo '</div>';
                                                            //             unset($data[$k]);
                                                            //         }
                                                            //     }
                                                            //     echo '</div>';
                                                            }
                                                         
                                                            
                                                            ?>
                                                <!-- </div> -->
                                                <!-- </div> -->
                                                <?php
                                                    //     $count++;
                                                    // }
                                                    ?>
                                                <!-- </div> -->




                                            </div>

                                            <div style="padding: 0 1rem 1rem 0" class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    Hủy
                                                </button>
                                                <button name="Them" type="submit" class="btn btn-primary">Thêm</button>
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
                                    <div class="col-md-2 col-lg-6 col-sm-6 col-6 ">
                                        <button name="apdung" type="submit" class="btn btn-primary" id="apdung">
                                            <span>
                                                <i class="fa fa-check me-2"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Áp dụng</span>

                                            </span>
                                        </button>

                                        <!-- <a href="BaiViet_DaXoa.php" name="thungrac" type="submit"
                                            class="btn btn-label-secondary ">
                                            <span>
                                                <i class="bx bx-trash"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Thùng rác</span>

                                            </span>

                                        </a> -->
                                    </div>



                                    <!-- Giao diện -->
                                    <div class="col-md-2 col-lg-4 col-sm-3 row mt-4">
                                        <div style="display: flex; justify-content: right;" class="col-lg-12">
                                            <p>Đang hiển thị:
                                                <span id="so_dong_hien_tai">
                                                    <?php echo $_SESSION['sl_dong']; ?>
                                                </span>/
                                                <span id="tong-so-dong">
                                                    <?php
                                                        if($danhmuc == 'Tất cả'){
                                                            $kq="SELECT count(DISTINCT(nd_username)) as tong FROM quan_ly";
                                                        }else{
                                                            $kq="SELECT count(DISTINCT(nd_username)) as tong FROM quan_ly where dm_ma = '$danhmuc'";
                                                        }
                                                       
                                                        $result_kq = mysqli_query($conn,$kq);
                                                        $row_kq = mysqli_fetch_assoc($result_kq);
                                                        $_SESSION['tong_sd'] = array();
                                                        $_SESSION['tong_sd'] = $row_kq['tong'];

                                                        echo $_SESSION['tong_sd']; 
                                                    ?>
                                                </span>
                                                (kết quả)
                                            </p>
                                        </div>
                                    </div>


                                </div>



                                <div class="table-responsive text-nowrap border-top">
                                    <table class="table ">
                                        <thead>

                                            <tr>
                                                <th>
                                                    <!-- <input class="form-check-input" name="checkall" type="checkbox" value=""> -->
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

                                                <th class="sort-header">Họ tên
                                                    <button id="asc" type="button"  class="small-button"
                                                        data-sort="asc"><i class="fa fa-sort-asc"></i></button>
                                                    <button id="desc" type="button"  class="small-button"
                                                        data-sort="desc"><i class="fa fa-sort-desc"></i></button>
                                                </th>

                                                <th>Danh mục quản lý</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-container">
                                        <input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
                                            <?php
                                            $stt = 0;
                                            while ($row_danh_muc = mysqli_fetch_array($result_danh_muc)) {
                                                $stt = $stt + 1;
                                                
                                        ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" name="check[]" type="checkbox"
                                                        value="<?php echo $row_danh_muc['nd_username'] ?>">
                                                </td>

                                                <td class="row-bai-viet"> <?php echo $stt;?> </td>
                                                <td><strong>
                                                        <?php 
                                                    echo  $row_danh_muc['nd_hoten']; 
                                                ?>
                                                    </strong>
                                                </td>

                                                <td>
                                                    <?php 
                                                    $danh_muc_ql = "SELECT *
                                                                    FROM nguoi_dung b
                                                                    JOIN quan_ly a ON a.nd_username = b.nd_username
                                                                    JOIN danh_muc c ON c.dm_ma = a.dm_ma";
                                                    
                                                    $result_danh_muc_ql = mysqli_query($conn, $danh_muc_ql);
                                                    
                                                  
                                                    // Initialize an array to store the results
                                                    $danhmuc_ql = array();
                                                    
                                                    // Fetch associative array
                                                    while ($row_danh_muc_ql = mysqli_fetch_assoc($result_danh_muc_ql)) {
                                                    // Use user as the key to group by user
                                                    $danhmuc_ql[$row_danh_muc_ql['nd_username']]['hoten'] = $row_danh_muc_ql['nd_hoten'];
                                                    $danhmuc_ql[$row_danh_muc_ql['nd_username']]['danh_muc'][] = array(
                                                    'dm_ma' => $row_danh_muc_ql['dm_ma'],
                                                    'dm_ten' => $row_danh_muc_ql['dm_ten']
                                                    );
                                                    }
                                                    
                                                    // Print or use the $userArray as needed
                                                    foreach ($danhmuc_ql as $username => $userData) {
                                                        if($username == $row_danh_muc['nd_username']){
                                                            // echo "Username: " . $username . "<br>";
                                                            // echo "User: " . $userData['hoten'] . "<br>";
                                                            // echo "Categories: <br>";
                                                            foreach ($userData['danh_muc'] as $category) {
                                                            echo "- " . $category['dm_ten'] . "<br>";
                                                            }
                                                            echo "<br>";
                                                        }else{

                                                        }
                                                    }
                                                   
                                                ?>

                                                </td>
                                                <td>

                                                    <!-- Edit
                                                <div class="modal fade" id="edit" tabindex="-1" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="modalCenterTitle">Cập nhật
                                                                    phân công quản lý
                                                                    danh mục</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <form method="POST" enctype="multipart/form-data">
                                                                <div class="modal-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6 mb-3">
                                                                            <label for="nameWithTitle"
                                                                                class="form-label">Chọn danh
                                                                                mục</label>
                                                                            <?php 
                                                            $dm2 = "SELECT * FROM danh_muc";
                                                            $result_dm2 = mysqli_query($conn, $dm2);
                                                            
                                                            $total_rows = mysqli_num_rows($result_dm2);
                                                            $halfway_point = ceil($total_rows / 2);
                                                            $count = 0;

                                                            while ($row_dm2 = mysqli_fetch_array($result_dm2)) {
                                                               
                                                                // Check if it's time to start the second column
                                                                if ($count == $halfway_point) {
                                                                    echo '</div><div class="col-md-6 mb-3 mt-4">';
                                                                }
                                                        ?>
                                                                            <div class="form-check mt-3">
                                                                                <?php
                                                                 $ql = "SELECT * FROM quan_ly where dm_ma = '".$row_dm2['dm_ma']."' and nd_username ='".$row_danh_muc['nd_username']."'";
                                                                 $result_ql = mysqli_query($conn, $ql);
                                                                 if(mysqli_num_rows($result_ql) == 1){
                                                                     while ($row_ql = mysqli_fetch_array($result_ql)) {?>
                                                                                <input checked type="checkbox"
                                                                                    class="form-check-input"
                                                                                    value="<?php echo $row_dm2['dm_ma']; ?>" />
                                                                                <label for="nameWithTitle"
                                                                                    class="form-check-label"><?php echo $row_dm2['dm_ten']; ?></label>
                                                                                <?php }
                                                                 }else{
                                                            ?>
                                                                                <input type="checkbox"
                                                                                    class="form-check-input"
                                                                                    value="<?php echo $row_dm2['dm_ma']; ?>" />
                                                                                <label for="nameWithTitle"
                                                                                    class="form-check-label"><?php echo $row_dm2['dm_ten']; ?></label>
                                                                                <?php } ?>
                                                                            </div>
                                                                            <?php 
                                                                $count++;
                                                            }
                                                        ?>
                                                                        </div>
                                                                    </div>




                                                                </div>

                                                                <div style="padding: 0 1rem 1rem 0"
                                                                    class="modal-footer">
                                                                    <button type="button"
                                                                        class="btn btn-outline-secondary"
                                                                        data-bs-dismiss="modal">
                                                                        Hủy
                                                                    </button>
                                                                    <button name="Sua" type="submit"
                                                                        class="btn btn-primary">Cập
                                                                        nhật</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div> -->

                                                    <!-- &dm= -->
                                                    <?php
                                                                                        //  foreach ($danhmuc_ql as $username => $userData) {
                                                                                        //     if ($username == $row_danh_muc['nd_username']) {
                                                                                        //         foreach ($userData['danh_muc'] as $category) {
                                                                                        //             echo $category['dm_ma'] . "+";
                                                                                        //         }
                                                                                        //     }
                                                                                        // }
                                                                                    ?>

                                                    <a href="Sua_PhanCong.php?username=<?php echo $row_danh_muc['nd_username'];?>"
                                                        style="display:math; padding:0.1rem 0.6rem"
                                                        class="dropdown-item">
                                                        <i class="bx bx-edit-alt me-1"></i>
                                                    </a>
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="#"
                                                        onclick="Xoa_PC_DanhMuc('<?php echo $row_danh_muc['nd_username']?>');">
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
            $('#so_dong,#danhmuc').change(function() {
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
                var chon_danhmuc = $('#danhmuc').val();
                $.ajax({
                    url: 'sodong_PC.php',
                    data: {
                        sodong: chon_gtri,
                        danhmuc: chon_danhmuc,
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
                var Tong = $('#tong_sd').val(); // Lấy giá trị từ biến ẩn
                $('#so_dong_hien_tai').text(soDongHienTai);
                $('#tong-so-dong').text(Tong);
            }
        });
        </script>
        <script>
        const categoryCheckboxes = document.querySelectorAll('.category');

        categoryCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const allChildCheckboxes = this.parentElement.querySelectorAll(
                    'input[type="checkbox"]');
                allChildCheckboxes.forEach(function(childCheckbox) {
                    childCheckbox.checked = checkbox.checked;
                });
                // Kiểm tra xem checkbox được chọn có là danh mục cha không
                const isParentCategory = checkbox.parentElement.nextElementSibling && checkbox
                    .parentElement
                    .nextElementSibling.tagName === 'UL';
                if (isParentCategory) {
                    // Nếu là danh mục cha, thì chọn/deselect tất cả checkbox con
                    const childCheckboxes = checkbox.parentElement.nextElementSibling.querySelectorAll(
                        'input[type="checkbox"]');
                    childCheckboxes.forEach(function(childCheckbox) {
                        childCheckbox.checked = checkbox.checked;
                    });
                }
            });
        });
        </script>
        <script>
    <?php if (isset($_SESSION['thongbao_thucthi']) && $_SESSION['thongbao_thucthi']) { ?>
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("thongbao_thucthi");
        var successToastInstance = new bootstrap.Toast(successToast);
        $("#loader").hide();
        successToastInstance.show();
      });
    <?php
      unset($_SESSION['thongbao_thucthi']);
      unset($_SESSION['bg_thongbao']);
    }
    ?>
  </script>
</body>

</html>