<?php
    session_start();

    require_once './includes/connect.php'; // Điều chỉnh đường dẫn nếu cần
    require 'vendor/autoload.php'; // Bao gồm tệp autoload của PhpSpreadsheet

    use PhpOffice\PhpSpreadsheet\IOFactory;
    use PhpOffice\PhpSpreadsheet\Spreadsheet;

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
                    $xoa_danhgia="DELETE FROM danh_gia where nd_username= '$id'";
                    mysqli_query($conn,$xoa_danhgia); 

                    $xoa_quanly="DELETE FROM quan_ly where nd_username= '$id'";
                    mysqli_query($conn,$xoa_quanly); 

                    $xoa_ls_xem="DELETE FROM lich_su_xem where nd_username= '$id'";
                    mysqli_query($conn,$xoa_ls_xem); 

                    $xoa_kiemduyet="DELETE FROM kiem_duyet where nd_username= '$id'";
                    mysqli_query($conn,$xoa_kiemduyet); 

                    $xoa_binhluan="DELETE FROM binh_luan where nd_username= '$id'";
                    mysqli_query($conn,$xoa_binhluan); 

                    $xoa_baiviet="DELETE FROM bai_viet where nd_username= '$id'";
                    mysqli_query($conn,$xoa_baiviet); 
            
                    $xoa_nguoidung="DELETE FROM nguoi_dung where nd_username= '$id'";
                    mysqli_query($conn,$xoa_nguoidung); 
            
                    
                }

                // Nếu mọi thứ thành công, commit transaction
                mysqli_commit($conn);
                echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";   
                // header("Refresh: 0;url=QL_DanhMuc.php");  
            } catch (Exception $e) {
                // Nếu có lỗi xảy ra, rollback transaction và hiển thị thông báo lỗi
                mysqli_rollback($conn);
                echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>"; 
                echo "Error: " . $e->getMessage();
            }
        }
    } 

    if (isset($_POST['ThemNguoiDung'])) {
        $ten = $_POST['name'];
        $username = $_POST['username'];
        $vaitro = $_POST['vaitro'];
        $matkhau = $_POST['pass'];
        $img= 'unnamed.png';
        $matkhau_bam = password_hash($matkhau, PASSWORD_BCRYPT);

        $nguoi_dung = "SELECT * FROM nguoi_dung WHERE nd_username='$username'";
        $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
		$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

        $vt = "SELECT * FROM vai_tro WHERE vt_ma='$vaitro'";
        $result_vt = mysqli_query($conn,$vt);
        $row_vt = mysqli_fetch_assoc($result_vt);



        if(mysqli_num_rows($result_nguoi_dung) == 1){
            $error[] ='Người dùng đã tồn tại! ';  
            $_SESSION['bg_thongbao'] = "bg-danger";
            $_SESSION['thongbao_thucthi'] = "Thêm người dùng thất bại. Lý do dữ liệu người dùng đã có trong hệ thống!";
            // echo "<script>alert('Người dùng đã tồn tại!');</script>"; 

        }else{
            $them_nguoi_dung = "INSERT INTO nguoi_dung (nd_hoten, nd_username, nd_hinh, nd_matkhau, vt_ma) 
                                VALUES ('$ten','$username', '$img', '$matkhau_bam', '$vaitro')";
            mysqli_query($conn,$them_nguoi_dung); 
            
             // Đọc tệp Excel hiện có nếu có
            $inputFileName = './Excel/user_data.xlsx'; // Đường dẫn đến tệp Excel
            $spreadsheet = IOFactory::load($inputFileName);

            // Chọn bảng tính đầu tiên
            $worksheet = $spreadsheet->getActiveSheet();

            // Tìm hàng cuối cùng có dữ liệu
            $highestRow = $worksheet->getHighestRow();

            // Thêm dữ liệu người dùng vào hàng mới
            $nextRow = $highestRow + 1;
            $worksheet->setCellValue('A' . $nextRow, $ten);
            $worksheet->setCellValue('B' . $nextRow, $username);
            $worksheet->setCellValue('C' . $nextRow, $row_vt['vt_ten']);
            $worksheet->setCellValue('D' . $nextRow, $matkhau); // Mật khẩu được che kín vì lý do bảo mật

            // Lưu tệp Excel cập nhật
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($inputFileName);

			// echo "<script>alert('Thêm người dùng mới thành công!');</script>"; 
            $_SESSION['bg_thongbao'] = "bg-success";
            $_SESSION['thongbao_thucthi'] = "Thêm người dùng thành công!";
            header("Refresh: 5;url=QL_NguoiDung.php?vt=". urlencode($vaitro));  
        }
        
        
        
    }

    $so_dong = 5;
    $gioitinh = 'Tất cả';
    $sap_xep = 'DESC';
    
    
    if (isset($_GET['sodong'])) {
        $so_dong = intval($_GET['sodong']);
    }

    if (isset($_GET['gioitinh'])) {
        $gioitinh = $_GET['gioitinh'];
    }
    
    
    
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === "asc") {
            $sap_xep = "asc";
        } elseif ($_GET['sort'] === "desc") {
            $sap_xep = "desc";
        }
    }
    
    
    $vt = $_GET['vt'];

    if ($gioitinh == "Tất cả") {
        $nguoi_dung = "SELECT * FROM nguoi_dung 
                        WHERE vt_ma='$vt' 
                        order by nd_ngaytao $sap_xep 
                        LIMIT $so_dong";
    }else{
        $nguoi_dung = "SELECT * FROM nguoi_dung 
                        WHERE vt_ma='$vt'
                        and nd_gioitinh = '$gioitinh' 
                        order by nd_hoten $sap_xep 
                        LIMIT $so_dong";
    }

    $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);

    $vai_tro = "SELECT * FROM vai_tro WHERE vt_ma='$vt'";
    $result_vai_tro = mysqli_query($conn,$vai_tro);
    $row_vai_tro = mysqli_fetch_assoc($result_vai_tro);

    unset($_SESSION['sl_dong']);
    $sl_dong_hientai = mysqli_num_rows($result_nguoi_dung);
    $_SESSION['sl_dong'] = $sl_dong_hientai;
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý Người dùng</title>

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
                                <li class="breadcrumb-item active">Quản lý <?php echo $row_vai_tro['vt_ten']?> </li>
                            </ol>
                        </nav>

                        <!-- Basic Bootstrap Table -->
                        <div class="card">
                            <h5 style="padding: 1.5rem 0 0 1.5rem" class="card-header">Danh sách
                                <?php echo $row_vai_tro['vt_ten']?></h5>

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
                                <div class="row col-lg-4 col-md-4">
                                    <div class="d-flex align-items-center col-lg-4 col-md-4">
                                        <label>Giới tính: </label>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <select name="gioitinh" class="form-select" id="gioitinh">
                                            <option value="Tất cả">Tất cả</option>
                                            <option value="1">Nữ</option>
                                            <option value="2">Nam</option>
                                            <option value="0">Chưa xác định</option>

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
                                                        $sd="SELECT count(*) as tong FROM nguoi_dung where vt_ma = '$vt'";
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
                                                    <a href="InNguoiDung.php?vt=<?php echo $vt ?>"
                                                        class="dt-button buttons-print dropdown-item" tabindex="0"
                                                        type="button">
                                                        <span><i class="bx bx-printer me-2"></i>Print</span>
                                                    </a>
                                                    <a href="ExcelMonHoc.php"
                                                        class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                        type="button">
                                                        <span><i class="bx bx-file me-2"></i>Excel</span>
                                                    </a>
                                                    <!-- <button class="dt-button buttons-pdf buttons-html5 dropdown-item"  type="button">
                                                <span><i class="bx bxs-file-pdf me-2"></i>Pdf</span>
                                            </button>
                                            <button class="dt-button buttons-copy buttons-html5 dropdown-item" type="button">
                                                <span><i class="bx bx-copy me-2"></i>Copy</span>
                                            </button>  -->
                                                </div>
                                            </div>
                                        </div>
                                        <button class="col-lg-5  col-sm-5 col-3 dt-button add-new btn btn-primary"
                                            type="button" type="button" data-bs-toggle="modal"
                                            data-bs-target="#modalCenter">
                                            <span>
                                                <i class="bx bx-plus me-0 me-sm-1"></i>
                                                <span class="d-none d-lg-inline-block">Thêm người dùng</span>
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
                                            <h5 class="modal-title" id="modalCenterTitle">Thêm người dùng </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col mb-3">
                                                        <div class="mb-3">
                                                            <label for="nameWithTitle" class="form-label">Họ tên
                                                            </label><span style="color: red;font-weight: bold;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span id="basic-icon-default-fullname2"
                                                                    class="input-group-text"><i
                                                                        class="bx bx-user"></i></span>
                                                                <input name="name" type="text" class="form-control"
                                                                    id="basic-icon-default-fullname"
                                                                    placeholder="Nhập vào họ tên"
                                                                    aria-label="Nhập vào họ tên"
                                                                    aria-describedby="basic-icon-default-fullname2"required />
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="basic-icon-default-company">Username</label><span style="color: red;font-weight: bold;"> *</span>
                                                            <div class="input-group input-group-merge">
                                                                <span id="basic-icon-default-company2"
                                                                    class="input-group-text"><i
                                                                        class="fa fa-id-card"></i></span>
                                                                <input name="username" type="text"
                                                                    id="basic-icon-default-company" class="form-control"
                                                                    placeholder="Nhập vào username"
                                                                    aria-label="Nhập vào username"
                                                                    aria-describedby="basic-icon-default-company2"required />
                                                            </div>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="defaultSelect" class="form-label">Cấp
                                                                quyền</label><span style="color: red;font-weight: bold;"> *</span>
                                                            <select name="vaitro" id="defaultSelect"
                                                                class="form-select" required>
                                                                <option  value="" hidden>Chọn quyền</option>
                                                                <?php
                                                                    $vai_tro = "select * from vai_tro";
                                                                    $result_vai_tro = mysqli_query($conn, $vai_tro);
                                                                    while ($row_vai_tro = mysqli_fetch_array($result_vai_tro)) {
                                                                    ?>
                                                                <option value="<?php echo $row_vai_tro['vt_ma']; ?>">
                                                                    <?php echo $row_vai_tro['vt_ten']; ?>
                                                                </option>
                                                                <?php }
                                                        ?>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 form-password-toggle">
                                                            <label class="form-label" for="basic-default-password32">Mật
                                                                khẩu</label><span style="color: red;font-weight: bold;"> *</span>
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
                                                                    value="<?php echo  $mat_khau; ?>" required/>
                                                                <span class="input-group-text cursor-pointer"
                                                                    id="basic-default-password">
                                                                    <i class="bx bx-hide"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div style="padding: 0 1rem 1rem 0" class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">
                                                    Hủy
                                                </button>
                                                <button name="ThemNguoiDung" type="submit"
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
                                    <div class="col-md-2 col-lg-6 col-sm-6 col-6 ">
                                        <button name="apdung" type="submit" class="btn btn-primary" id="apdung">
                                            <span>
                                                <i class="fa fa-check me-2"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Áp dụng</span>

                                            </span>
                                        </button>

                                        <!-- <a href="NguoiDung_DaXoa.php" name="thungrac" type="submit"
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
                                                <th>Ảnh</th>
                                                <th>Username</th>
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


                                                <!-- <th>Giới tính</th>
                                            <th>Ngày sinh</th> -->
                                                <th>Email</th>
                                                <th>Di động</th>
                                                <th>Hành động</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0" id="data-container">
                                            <?php
                                            $stt = 0;
                                            while ($row_nguoi_dung = mysqli_fetch_array($result_nguoi_dung)) {
                                                if($row_nguoi_dung['nd_gioitinh'] == 2) {
                                                    $row_nguoi_dung['nd_gioitinh'] = "Nam";
                                                }if($row_nguoi_dung['nd_gioitinh'] == 1) {
                                                    $row_nguoi_dung['nd_gioitinh'] = "Nữ";
                                                }
                                                // else{
                                                //     $row_hoc_sinh['nd_gioitinh'] = "0";
                                                // }
                                                $stt = $stt + 1;
                                        ?>
                                            <tr>
                                                <td>
                                                    <input class="form-check-input check-item" name="check[]"
                                                        type="checkbox"
                                                        value="<?php echo $row_nguoi_dung['nd_username'] ?>">
                                                </td>
                                                <td class="row-bai-viet"> <?php echo $stt ?> </td>
                                                <td>
                                                    <img src="assets/img/avatars/<?php echo  $row_nguoi_dung['nd_hinh']; ?>"
                                                        alt="Avatar" class="rounded-circle avatar avatar-xs pull-up" />
                                                </td>
                                                <td><strong><?php echo  $row_nguoi_dung['nd_username']; ?></strong></td>
                                                <td style="white-space: normal">
                                                    <?php echo  $row_nguoi_dung['nd_hoten']; ?>
                                                </td>
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
                                                <!-- <td>
                                                <span class="badge bg-label-primary me-1">
                                                    <?php 
                                                        if($row_nguoi_dung['nd_gioitinh'] == '0'){
                                                            $row_nguoi_dung['nd_gioitinh'] = "(Chưa có dữ liệu)";
                                                            echo  "<i>".$row_nguoi_dung['nd_gioitinh']."</i>";
                                                        }else{
                                                            echo  $row_nguoi_dung['nd_gioitinh']; 
                                                        }
                                                    ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php 
                                                    if($row_nguoi_dung['nd_ngaysinh'] == '0000-00-00'){
                                                        $row_nguoi_dung['nd_ngaysinh'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nguoi_dung['nd_ngaysinh']."</i>";
                                                    }else{
                                                        echo date_format(date_create($row_nguoi_dung['nd_ngaysinh']), "d-m-Y"); 
                                                    }            
                                                ?>
                                            </td> -->
                                                <td style="white-space: normal">
                                                    <?php //echo  $row_hoc_sinh['nd_email']; ?>
                                                    <?php 
                                                    if($row_nguoi_dung['nd_email'] == ''){
                                                        $row_nguoi_dung['nd_email'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nguoi_dung['nd_email']."</i>";
                                                    }else{
                                                        echo  $row_nguoi_dung['nd_email']; 
                                                    }            
                                                ?>
                                                </td>
                                                <td>
                                                    <?php //echo  $row_hoc_sinh['nd_sdt']; ?>
                                                    <?php 
                                                    if($row_nguoi_dung['nd_sdt'] == ''){
                                                        $row_nguoi_dung['nd_sdt'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nguoi_dung['nd_sdt']."</i>";
                                                    }else{
                                                        echo  $row_nguoi_dung['nd_sdt']; 
                                                    }            
                                                ?>
                                                </td>
                                                <td>
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="Xem_NguoiDung.php?this_username=<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="Sua_NguoiDung.php?this_username=<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>">
                                                        <i class="bx bx-edit-alt me-1"></i>
                                                    </a>
                                                    <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                        href="#"
                                                        onclick="Xoa_Nguoidung('<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>');">
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
            $('#so_dong,#gioitinh').change(function() {
                updateDataContainer();
            });

            // Xử lý khi nhấn nút sắp xếp
            $('#asc, #desc').click(function() {
                var sortOrder = $(this).attr('id'); // Lấy 'asc' hoặc 'desc' từ id của nút
                updateDataContainer(sortOrder);
                $(this).addClass('active');
                $('#desc, #asc').not(this).removeClass('active');
            });

            // Xử lý khi nhấn nút lọc ngày
            // $('#loc_ngay').click(function() {
            //     var chon_gtri = $('#so_dong').val();
            //     var trangthai = $('#gioitinh').val();
            //     $.ajax({
            //         url: 'sodong_ND.php?vt=<?php echo $vt;?>',
            //         method: 'GET',
            //         data: {
            //             sodong: chon_gtri,
            //             gioitinh: trangthai,
            //             loc_ngay: true // Thêm tham số loc_ngay
            //         },
            //         success: function(data) {
            //             $('#data-container').html(data);
            //         }
            //     });
            // });

            // Hàm cập nhật dữ liệu với tùy chọn sắp xếp
            function updateDataContainer(sortOrder) {
                var chon_gtri = $('#so_dong').val();
                var chon_gioitinh = $('#gioitinh').val();
                $.ajax({
                    url: 'sodong_ND.php?vt=<?php echo $vt; ?>',
                    data: {
                        sodong: chon_gtri,
                        gioitinh: chon_gioitinh,
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