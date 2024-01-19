<?php
session_start();
include "includes/connect.php";
require '../vendor/autoload.php';

// Kết nối đến MongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
$collection = $database->tachtunoidung;
$baivietCollection = $database->baiviet;

//-------------------------------------- Xử lý tính độ trùng lặp bằng JACCARD -------------------------------------------------//

// Hàm tính độ đo Jaccard
function jaccardSimilarity($set1, $set2) {
    $set1 = preg_replace('/[(,;"\':?)]/', '',$set1);
    $set2 = preg_replace('/[(,;"\':?)]/', '',$set2);
     
    $intersection = array_intersect($set1, $set2);
    $union = array_unique(array_merge($set1, $set2));

    // Kiểm tra nếu tổng số phần tử của tập hợp kết hợp là 0
    if (count($union) === 0) {
        return 0;
    }
    
    $jaccard = count($intersection) / count($union);
    $jaccard = min($jaccard, 1);

    return $jaccard;
   
}

$bv = "SELECT a.*, b.*, c.*, d.*, e.*, t.*
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                WHERE k.tt_ma is null or k.tt_ma = 3";

$result_bv = mysqli_query($conn, $bv);
if (mysqli_num_rows($result_bv) > 0) {
    while ($row_bv = mysqli_fetch_array($result_bv)) {
        $idCondition = intval($row_bv['bv_ma']);
        // echo  "ID chờ duyệt: ".$idCondition."| ";
        // Lấy danh sách các ID bài viết cùng danh mục từ MongoDB
        $baivietIdsCursor = $baivietCollection->find(['danhmuc' => $row_bv['dm_ma'], 'trangthai' => 'Đã duyệt']);

        $baivietIds = [];

        $words1 = array(); // Tạo mảng để lưu trữ từng bài viết
        $words2 = array();

        $wordCountById2 = [];
        $wordCountById = [];

        $currentSentence = [];
        $currentSentence2 = [];

        foreach ($baivietIdsCursor as $doc) {
            $baivietIds[] = $doc['id'];
        }

        // Lấy dữ liệu từ MongoDB
        $record = $collection->find(['doc_id' => $idCondition]);

        foreach ($record as $doc) {
            $id = $doc['doc_id'];

            if (!isset($words1[$id])) {
                $words1[$id] = array(); // Nếu chưa có, khởi tạo một mảng rỗng cho ID này
            }

            if ($doc['wordForm'] !== ".") {
                $currentSentence[] = $doc['wordForm'];
            } else {
                $words1[$id][] = $currentSentence;
                $currentSentence = []; // Reset mảng câu hiện tại
            }

            if (!empty($currentSentence) && end($record) === $doc) {
                $words1[$id][] = $currentSentence; // Thêm câu cuối cùng vào mảng
            }

            if (!isset($wordCountById[$id])) {
                $wordCountById[$id] = 1; // Khởi tạo giá trị bằng 1 nếu chưa tồn tại
            } else {
                if ($doc['wordForm'] !== ".") {
                    $wordCountById[$id]++; // Tăng số lượng đếm lên 1 nếu đã tồn tại và wordForm không phải là "."
                }
            }
        }

        // Tạo cursor mới cho $cursor
        $cursor = $collection->find(['doc_id' => ['$ne' => $idCondition, '$in' => $baivietIds]]);


        foreach ($cursor as $document) {
            $id2 = $document['doc_id'];
            if (!isset($words2[$id2])) {
                $words2[$id2] = array(); // Nếu chưa có, khởi tạo một mảng rỗng cho ID này
            }

            if ($document['wordForm'] !== ".") {
                $currentSentence2[] = $document['wordForm'];
            } else {
                $words2[$id2][] = $currentSentence2;
                $currentSentence2 = []; // Reset mảng câu hiện tại
            }

            if (!empty($currentSentence2) && end($cursor) === $document) {
                $words2[$id2][] = $currentSentence2; // Thêm câu cuối cùng vào mảng
            }

            if (!isset($wordCountById[$id2])) {
                $wordCountById2[$id2] = 1; // Khởi tạo giá trị bằng 1 nếu chưa tồn tại
            } else {
                if ($document['wordForm'] !== ".") {
                    $wordCountById2[$id2]++; // Tăng số lượng đếm lên 1 nếu đã tồn tại và wordForm không phải là "."
                }
            }

        }

        // Duyệt qua từng phần tử của mảng $words1 và gán chỉ số của mảng con đó vào $index1, và giá trị của mảng con đó vào $subArray1.
        foreach ($words1 as $index1 => $subArray1) {
            // duyệt qua từng phần tử của mảng $words2 và lưu chỉ số vào $index2, giá trị vào $subArray2.
            foreach ($words2 as $index2 => $subArray2) {
                // Lặp qua từng phần tử của mảng con $subArray1 và lưu giá trị vào $subArray1Item.
                foreach ($subArray1 as $subArray1Item) {
                    // Lặp qua từng phần tử của mảng con $subArray2 và lưu giá trị vào $subArray2Item.
                    foreach ($subArray2 as $subArray2Item) {
                        $similarity = jaccardSimilarity($subArray1Item, $subArray2Item);
                        $dotuongdong = round($similarity * 100);
                        if ($similarity >= 0.3) {
                            $similarRows[] = [
                                'id' => $index1,
                                'content1' => $subArray1Item,
                                'id2' => $index2,
                                'content2' => $subArray2Item,
                                'similarity' => $dotuongdong,
                            ];

                        }
                    }
                }
            }
        }

    }
} else {
    echo "Không tìm thấy bản ghi!";
}

//-------------------------------------- Xử lý khi ấn nút áp dụng -------------------------------------------------//

$user = $_SESSION['Admin'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apdung'])) {
    $selectedAction = $_POST['tacvu'];
   
    $user = $_SESSION['Admin'];
    $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];

    if ($selectedAction == "1") {
        $_SESSION['xl'] = []; // Khởi tạo mảng trong session nếu cần thiết

        foreach ($selectedIds as $id) {
            $_SESSION['xl'][] = $id; // Thêm giá trị vào mảng trong session
            $kt="select *from kiem_duyet where bv_ma = '$id'";
            $result_kt = mysqli_query($conn,$kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if(mysqli_num_rows($result_kt) > 0){
                $huy_bv = "UPDATE kiem_duyet SET tt_ma = '$selectedAction', nd_username='$user', thoigian = now() WHERE bv_ma = '$id'";
                mysqli_query($conn, $huy_bv);  
            }else{
                $duyet_bv = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian) VALUE ('$id', '$user', '$selectedAction', now())";
                mysqli_query($conn, $duyet_bv);   
            }
                    
        }
        // echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>"; 
        // $_SESSION['thongbao_thucthi'] = "Phê duyệt bài viết thành công!";
        $_SESSION['bg_thongbao'] = "bg-success";
        $_SESSION['thongbao_thucthi'] = "Phê duyệt bài viết thành công!";
        header("Refresh: 2;url=xuly_dl_mongodb.php");  
    }
    elseif ($selectedAction == "2") {
        foreach ($selectedIds as $id) {

            $kt="select *from kiem_duyet where bv_ma = '$id'";
            $result_kt = mysqli_query($conn,$kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if(mysqli_num_rows($result_kt) > 0){
                $huy_bv = "UPDATE kiem_duyet SET tt_ma = '$selectedAction', nd_username='$user', thoigian = now() WHERE bv_ma = '$id'";
                mysqli_query($conn, $huy_bv);  
            }else{
                $duyet_bv = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian) VALUE ('$id', '$user', '$selectedAction', now())";
                mysqli_query($conn, $duyet_bv);   
            } 
        }
        // echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>"; 
        $_SESSION['bg_thongbao'] = "bg-success";
        $_SESSION['thongbao_thucthi'] = "Phê duyệt bài viết thành công!";
        header("Refresh: 2;url=KiemDuyet.php");  
    }
}

if (isset($_POST['duyetbai'])) {
        $id = $_POST['bv_ma'];
        $kt="select *from kiem_duyet where bv_ma = '$id'";
        $result_kt = mysqli_query($conn,$kt);
        $row_kt = mysqli_fetch_assoc($result_kt);

        if(mysqli_num_rows($result_kt) > 0){
            $huy_bv = "UPDATE kiem_duyet SET tt_ma = '1', nd_username='$user', thoigian = now() WHERE bv_ma = '$id'";
            mysqli_query($conn, $huy_bv);  
        }else{
            $duyet_bv = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian) VALUE ('$id', '$user', '1', now())";
            mysqli_query($conn, $duyet_bv);   
        } 
    // echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>"; 
    $_SESSION['bg_thongbao'] = "bg-success";
    $_SESSION['thongbao_thucthi'] = "Phê duyệt bài viết thành công!";
    header("Refresh: 2;url=xuly_dl_mongodb.php");   
}

if (isset($_POST['huybai'])) {
    $id = $_POST['bv_ma'];
    $kt="select *from kiem_duyet where bv_ma = '$id'";
            $result_kt = mysqli_query($conn,$kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if(mysqli_num_rows($result_kt) > 0){
                $huy_bv = "UPDATE kiem_duyet SET tt_ma = '2', nd_username='$user', thoigian = now() WHERE bv_ma = '$id'";
                mysqli_query($conn, $huy_bv);  
            }else{
                $duyet_bv = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian) VALUE ('$id', '$user', '2', now())";
                mysqli_query($conn, $duyet_bv);   
            } 
// echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>"; 
$_SESSION['bg_thongbao'] = "bg-warning";
$_SESSION['thongbao_thucthi'] = "Bạn đã huỷ bỏ bài viết!";
header("Refresh: 2;url=KiemDuyet.php");  
}

//-------------------------------------- Xử lý thanh lọc ngày, số dòng -------------------------------------------------//

$so_dong = 5;
$sap_xep = 'DESC';
$user_name = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";
$tungay = 2015-01-01;
$denngay = date('Y-m-d');

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
    
if (isset($_GET['tungay'])) {
    $tungay = $_GET['tungay'];
}

if (isset($_GET['denngay'])) {
    $denngay = $_GET['denngay'];
}

// Kiểm tra quyền truy cập
// Danh sách các trang quản lý của admin
$adminPages = array("QL_DanhMuc.php", "QL_KhoiLop.php", "QL_MonHoc.php", "QL_NguoiDung.php", "QL_BinhLuan.php", "PCQL_DanhMuc.php");

// Lấy đường dẫn hiện tại của người dùng
$currentPage = basename($_SERVER['SCRIPT_FILENAME']);

if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
    header("Refresh: 0;url=error.php");  
    exit;
}
if($_SESSION['vaitro'] == 'Super Admin'){
  
    $bai_viet = "SELECT a.*, b.*, c.*, d.*, e.*, t.*
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                WHERE (k.tt_ma is null or k.tt_ma = 3)
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";
}
elseif($_SESSION['vaitro'] == 'Admin'){
    $bai_viet = "SELECT a.*, b.*, c.*, d.*, e.*, t.*
    FROM bai_viet a
    LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
    LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
    LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
    LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
    LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
    LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
    LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
    WHERE (k.tt_ma is null or k.tt_ma = 3)
    And f.nd_username = '$user_name'
    And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";
} 

$result_bai_viet = mysqli_query($conn, $bai_viet);

// Tạo session chứa thông tin số lượng dòng đang hiển thị
unset($_SESSION['sl_dong']);
$sl_dong_hientai = mysqli_num_rows($result_bai_viet);
$_SESSION['sl_dong'] = $sl_dong_hientai;

?>

<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý bài viết</title>

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
                                <li class="breadcrumb-item active">Quản lý bài viết</li>
                            </ol>
                        </nav>

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

                        <!-- Basic Bootstrap Table -->
                        <div class="card ">
                            <h5 class="card-header ">Kiểm duyệt bài viết</h5>
                            <div class="row card-header d-flex flex-wrap py-3 px-3 justify-content-between">

                                <div style="margin-bottom:1rem; margin-top:-1rem" class="col-lg-12  row">

                                    <div class="col-md-3">
                                        <div class="dataTables_filter">
                                            <label>Từ ngày</label>
                                            <input id="tungay" type="date" class="form-control" value="2015-01-01">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <span>Đến ngày</span>
                                        <input id="denngay" type="date" class="form-control"
                                            value="<?php echo date('Y-m-d'); ?>">

                                    </div>
                                    <div class="col-md-2">

                                        <br>
                                        <button id="loc_ngay" class="btn btn-primary">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>



                                <div style="padding-left:10px"
                                    class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end gap-3 gap-sm-2 flex-wrap flex-sm-nowrap pt-0">

                                    <form method="POST">
                                        <div class=" dataTables_length mt-0 mt-md-0  me-2">
                                            <label>
                                                <select name="so_dong" class="form-select" id="so_dong">
                                                    <?php
                                                    $sd="SELECT count(*) as tong
                                                            FROM bai_viet a
                                                            LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                                                            LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                                                            LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                                                            LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                                                            LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                                                            LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                                                            LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                                                            WHERE (k.tt_ma is null or k.tt_ma = 3)";
                                                    $result_sd = mysqli_query($conn,$sd);
                                                    $row_sd = mysqli_fetch_assoc($result_sd);

                                                    $tong = $row_sd['tong'];

                                                    for ($i = 5; $i <= $tong+5; $i += 5) {
                                                        echo "<option value='".$i."'>".$i."</option>";
                                                    }
                                                
                                                ?>
                                                </select>
                                            </label>
                                        </div>
                                    </form>

                                    <style>
                                    @media (max-width: 300px) {
                                        #Export {
                                            /* Đặt kích thước của button thành 10px khi màn hình nhỏ hơn 400px */
                                            width: 20px;
                                            height: 40px;
                                        }
                                    }
                                    </style>


                                    <!-- <button id="Export"
                                        class="col-lg-2  col-sm-3 col-2 dt-button buttons-collection  btn btn-label-secondary ms-sm-0 me-3"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">

                                        <span>
                                            <i class="bx bx-export "></i>
                                            <span class="dt-down-arrow d-none d-xl-inline-block">Export ▼</span>

                                        </span>

                                    </button> -->
                                    <div class="dt-button-collection dropdown-menu"
                                        style="top: 55.9375px; left: 419.797px;" aria-labelledby="dropdownMenuButton">
                                        <div role="menu">
                                            <a href="InBaiViet.php" class="dt-button buttons-print dropdown-item"
                                                tabindex="0" type="button">
                                                <span><i class="bx bx-printer me-2"></i>Print</span>
                                            </a>
                                            <a href="ExcelBaiViet.php"
                                                class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                                                <span><i class="bx bx-file me-2"></i>Excel</span>
                                            </a>

                                        </div>
                                    </div>


                                </div>



                            </div>

                                <?php

                                
                                    $sumSimilarity = [];
                                    $countSimilarity = [];
                                    if(!empty($similarRows)){


                                        foreach ($similarRows as $row)
                                        {
                                            $id = $row['id'];
                                            $id2 = $row['id2'];
                                            // $td1 = $row['tieude'];

                                            if (!isset($sumSimilarity[$id][$id2]))
                                            {
                                                $sumSimilarity[$id][$id2] = 0;
                                                $countSimilarity[$id][$id2] = 0;
                                            }

                                            $sumSimilarity[$id][$id2] += $row['similarity'];
                                            $countSimilarity[$id][$id2]++;
                                        }
                                    }
                                    $averageSimilarity = [];

                                    foreach ($sumSimilarity as $id => $idData)
                                    {
                                        foreach ($idData as $id2 => $total)
                                        {
                                            $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2]);
                                           
                                            
                                        }
                                    }

                            ?>
                               
                            <br>
                            <style>
                            .highlight {
                                background-color: yellow;
                                /* Màu nền nổi bật */
                                font-weight: bold;
                                /* Độ đậm */
                            }
                            </style>
                            <!-- Modal -->
                            <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-xl" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                           
                                            
                                            
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            
                                        </div>
                                        <div class="text-center ms-4 me-4" >
                                        <h5 class="modal-title text-center" id="modalCenterTitle">CHI TIẾT KIỂM TRA</h5>
                                            <p>(Theo kiểm tra thì tài liệu này có 
                                                <?php 
                                                    foreach ($similarRows as $row){
                                                        $socau = $row['content1'];
                                                    }
                                                    $cau = count($socau); echo $cau; 
                                                ?> 
                                            câu được cho là trùng lặp được liệt kê ở bảng bên dưới.)  </p>
                                            </div>
                                       
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="ms-4 me-4">
                                                <button name="huybai"  type="submit" class="btn btn-outline-secondary"
                                                    data-bs-dismiss="modal">Huỷ bài viết
                                                  
                                                </button>
                                                <button name="duyetbai" type="submit" class="btn btn-primary">Duyệt bài</button>
                                            </div>
                                            
                                            <div class="modal-body" id="modalBody">
                                                <!-- Nội dung sẽ được điền tự động từ AJAX -->
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

                                                    <option value="1">Duyệt</option>
                                                    <option value="2">Huỷ</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-lg-6 col-sm-6 col-6 ">
                                                <button name="apdung" type="submit" class="btn btn-primary" id="apdung">
                                                    <span>
                                                        <i class="fa fa-check me-2"></i>
                                                        <span class="dt-down-arrow d-none d-xl-inline-block">Áp
                                                            dụng</span>

                                                    </span>
                                                </button>


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
                                                         // Kiểm tra quyền truy cập
                                                        if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                                            header("Refresh: 0;url=error.php");  
                                                            exit;
                                                        }
                                                        if($_SESSION['vaitro'] == 'Super Admin'){

                                                          
                                                                $sd="SELECT count(*) as tong
                                                                        FROM bai_viet a
                                                                        LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                                                                        LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                                                                        LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                                                                        LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                                                                        LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                                                                        LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                                                                        LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                                                                        WHERE (k.tt_ma is null or k.tt_ma = 3)";
                                                           
                                                        }
                                                        elseif($_SESSION['vaitro'] == 'Admin'){
                                                           
                                                                $sd="SELECT count(*) as tong
                                                                         FROM bai_viet a
                                                                        LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                                                                        LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                                                                        LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                                                                        LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                                                                        LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                                                                        LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                                                                        LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                                                                        WHERE (k.tt_ma is null or k.tt_ma = 3)
                                                                        And f.nd_username = '$user_name'";
                                                           
                                                        } 
                                                        
                                                       
                                                        $result_sd = mysqli_query($conn,$sd);
                                                        $row_sd = mysqli_fetch_assoc($result_sd);

                                                        $_SESSION['tong_sd'] = array();
                                                        $_SESSION['tong_sd'] = $row_sd['tong'];

                                                        echo $_SESSION['tong_sd']; 
                                                    ?>
                                                        </span>(kết quả)
                                                    </p>
                                                </div>
                                            </div>


                                        </div>

                                        

                                        <div class="table-responsive text-nowrap border-top">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <input class="form-check-input" id="checkall"
                                                                type="checkbox">
                                                        </th>
                                                        <th>STT</th>
                                                        <!-- <th>Mã</th> -->
                                                        <th>Trạng thái</th>
                                                        <th>Tiêu đề</th>
                                                        <th>Ngày đăng</th>

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
                                                        <th class="sort-header">Tác giả
                                                            <button id="asc" type="button" class="small-button"
                                                                data-sort="asc"><i class="fa fa-sort-asc"></i></button>
                                                            <button id="desc" type="button" class="small-button"
                                                                data-sort="desc"><i
                                                                    class="fa fa-sort-desc"></i></button>
                                                        </th>
                                                        <th>Tỷ lệ trùng lặp</th>

                                                        <th>Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="table-border-bottom-0" id="data-container">
                                                    <input type="hidden" id="tong_sd"
                                                        value="<?php echo $_SESSION['tong_sd']; ?>">
                                                    <?php
                                                $stt = 0;
                                                
                                                while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                                                    // $_SESSION['sl_dong'] = mysqli_num_rows($result_bai_viet);

                                                    $stt = $stt + 1;
                                            ?>
                                                    <tr>
                                                        <td>
                                                            <input class="form-check-input check-item" name="check[]"
                                                                type="checkbox"
                                                                value="<?php echo $row_bai_viet['bv_ma'] . '|' . $row_bai_viet['tt_ma']; ?>">
                                                        </td>
                                                        <td class="row-bai-viet"> <?php echo $stt ?> </td>

                                                        <td>

                                                            <?php

                                                    if ($row_bai_viet['tt_ma'] == 1) {

                                                        echo "<span class='badge bg-label-success me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else if ($row_bai_viet['tt_ma'] == 2) {
                                                        echo "<span class='badge alert-warning me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else if ($row_bai_viet['tt_ma'] == 4) {

                                                        echo "<span class='badge bg-label-danger me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    } else {
                                                        echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
                                                    }
                                                    ?>

                                                        </td>
                                                        <td style="white-space: normal">
                                                            <?php echo $row_bai_viet['bv_tieude'] ?>

                                                        </td>

                                                        <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

                                                        </td>
                                                        <td style="white-space: normal"> <?php echo $row_bai_viet['nd_hoten'] ?> </td>
                                                        <td style="white-space: normal"> 
                                                            <?php
                                                             
                                                             if(!empty($similarRows)){
                                                                foreach ($sumSimilarity as $id => $idData)
                                                                {
                                                                    foreach ($idData as $id2 => $total)
                                                                    {
                                                                        if($id ==  $row_bai_viet['bv_ma']){

                                                                            // $bai = "SELECT * FROM bai_viet where bv_ma = $id2";
                                                                            // $result_bai = mysqli_query($conn, $bai2);
                                                                            // while ($row_bai = mysqli_fetch_array($result_bai2)) {
                                                                            //     $td2 = $row_bai['bv_tieude'];
                                                                            // }
                                                                           
                                                                            $bai2 = "SELECT * FROM bai_viet where bv_ma = $id2";
                                                                            $result_bai2 = mysqli_query($conn, $bai2);
                                                                            while ($row_bai2 = mysqli_fetch_array($result_bai2)) {
                                                                                $td2 = $row_bai2['bv_tieude'];
                                                                            }
                                                                        
                        
                                                                            $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2]);

                                                                            echo ''.$averageSimilarity[$id][$id2].'% (<a href = Xem_BaiViet.php?this_bv_ma='.$id2.'>'.$td2.'</a>) 
                                                                            <button  onclick="openModal('.$id.','.$id2.')"
                                                                            class="dt-button buttons-print dropdown-item" tabindex="0"
                                                                            type="button" data-bs-toggle="modal" data-bs-target="#modalCenter"">
                                                                            <span><i class=" fa fa-eye me-2"></i></span></button><br><br>';
                                                                           
                                                                        }
                                                                        
                                                                    }
                                                                } 
                                                            }
                                                               
                                                            ?>
                                                        </td>


                                                        <td>
                                                            <a id="dropdownHanhDong" data-bs-toggle="dropdown"
                                                                aria-expanded="false"
                                                                style="display:math; padding:0.1rem 0.6rem"
                                                                class="dropdown-item"
                                                                href="Xem_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>">
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>

                                                            <div class="dt-button-collection dropdown-menu"
                                                                style="top: 55.9375px; left: 419.797px;min-width:7rem"
                                                                aria-labelledby="dropdownHanhDong">
                                                                <div role="menu">
                                                                    <a href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                                                                        class="dt-button buttons-print dropdown-item"
                                                                        tabindex="0" type="button">
                                                                        <span><i class=" fa fa-eye me-2"></i>Xem</span>
                                                                    </a>
                                                                    <a href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                                                                        class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                        type="button">
                                                                        <span><i class="bx bx-edit-alt me-2"></i>Kiểm
                                                                            duyệt</span>
                                                                    </a>

                                                                    <?php
                                                                // Kiểm tra quyền truy cập
                                                                if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                                                    header("Refresh: 0;url=error.php");  
                                                                    exit;
                                                                }
                                                                if($_SESSION['vaitro'] == 'Super Admin'){?>
                                                                    <a href="#"
                                                                        onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>');"
                                                                        class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                        type="button">
                                                                        <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                                                    </a>
                                                                    <?php }
                                                                elseif($_SESSION['vaitro'] == 'Admin'){} 
                                                            ?>



                                                                </div>
                                                            </div>



                                                        </td>
                                                    </tr>
                                                    <?php }  ?>

                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                    
                                
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
    <!-- Script Ajax -->
    <script>
    function openModal(id, id2) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("modalBody").innerHTML = this.responseText;
                $('#modalCenter').modal('show');
            }
        };
        xhttp.open("POST", "get_kiemduyet.php", true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

        // Dữ liệu cần gửi lên server
        var data = {
            id: id,
          
            id2: id2,
            similarRows: <?php echo json_encode($similarRows); ?>
        };

        xhttp.send(JSON.stringify(data));
    }

    console.log(<?php echo json_encode($similarRows); ?>);
    </script>   <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

    // Xử lý khi nhấn nút lọc ngày
    $('#loc_ngay').click(function() {
        var chon_gtri = $('#so_dong').val();
        var tungay = $('#tungay').val();
        var denngay = $('#denngay').val();
        
        
        $.ajax({
            url: 'sodong_KD.php',
            method: 'POST',
            data: {
                tungay: tungay,
                sodong: chon_gtri,
                denngay: denngay,
                similarRows: JSON.stringify(<?php echo json_encode($similarRows); ?>), // Chuyển đổi thành chuỗi JSON
                loc_ngay: true // Thêm tham số loc_ngay
            },
            success: function(data) {
                $('#data-container').html(data);
                updateDisplayInfo();
            }
        });
    });

    // Hàm cập nhật dữ liệu với tùy chọn sắp xếp
    function updateDataContainer(sortOrder) {
        var chon_gtri = $('#so_dong').val();
        var tungay = $('#tungay').val();
        var denngay = $('#denngay').val();

        
        $.ajax({
            url: 'sodong_KD.php',
            method: 'POST',
            data: {
                tungay: tungay,
                denngay: denngay,
                sodong: chon_gtri,
                similarRows: JSON.stringify(<?php echo json_encode($similarRows); ?>), // Chuyển đổi thành chuỗi JSON
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
    // Hàm cập nhật thông tin hiển thị
    function updateDisplayInfo() {
        var soDongHienTai = $('#data-container .row-bai-viet').length;
        var Tong = $('#tong_sd').val(); // Lấy giá trị từ biến ẩn
            $('#so_dong_hien_tai').text(soDongHienTai);
            $('#tong-so-dong').text(Tong);
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