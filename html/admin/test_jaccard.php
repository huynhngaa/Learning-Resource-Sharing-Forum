<?php
include "../include/conn.php";
require '../vendor/autoload.php';

// Kết nối đến MongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
$collection = $database->tachcaunoidung;
$baivietCollection = $database->baiviet;

// Hàm tính độ đo Jaccard
function jaccardSimilarity($set1, $set2) {
    // $intersection = array_intersect($set1, $set2);
    // $union = array_unique(array_merge($set1, $set2));
    // $jaccard = count($intersection) / count($union);
    // return $jaccard;

    $set1Lower = array_map('strtolower', $set1);
    $set2Lower = array_map('strtolower', $set2);

    $intersection = array_intersect($set1Lower, $set2Lower);
    $union = array_unique(array_merge($set1Lower, $set2Lower));
    
    $jaccard = count($intersection) / count($union);
    return $jaccard;
    //  Chuyển đổi mảng thành chuỗi


    // Loại bỏ các kí tự dấu cuối từ các từ trong set1 và set2

    // $set1 = preg_replace("/[.,!?()\[\]]/", "", $set1);
    // $set2 = preg_replace("/[.,!?()\[\]]/", "", $set2);
    
    // $set1Lower = array_map('strtolower', $set1);
    // $set2Lower = array_map('strtolower', $set2);
    
    // $intersection = array_intersect($set1Lower, $set2Lower);
    // $union = array_unique(array_merge($set1Lower, $set2Lower));
    
    // $jaccard = count($intersection) / count($union);
    // return $jaccard;
   
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
if(mysqli_num_rows($result_bv) > 0){
    while ($row_bv = mysqli_fetch_array($result_bv)) {
        $dotuongdong = 0;
        $category = $row_bv['dm_ma'];
        $idCondition = intval($row_bv['bv_ma']);

        // Lấy danh sách các ID bài viết cùng danh mục từ bảng baiviet
        $baivietIdsCursor = $baivietCollection->find([
            'danhmuc' => $category,
            'trangthai' => 'Đã duyệt'
        ]);
       
        // Chuyển kết quả từ cursor về mảng các ID bài viết
        $baivietIds = [];
        foreach ($baivietIdsCursor as $doc) {
            $baivietIds[] = $doc['id'];
        }
        // echo $category;
        // print_r($baivietIds);


        $record = $collection->find(['doc_id' => $idCondition]);
        // print_r($record);

        if ($record) {
            foreach ($record as $record) {
                $content1 = $record['wordForm']; 
                $words1 = explode(" ", $content1);
                $words1 = array_unique($words1);

                // $content1 = $record['wordForm'];
                // $content1 = preg_replace("/[.,!?:;()\[\]]/", "", $content1); // Loại bỏ các ký tự dấu cuối
                // $words1 = explode(" ", $content1);
                // $words1 = array_unique($words1);


                // $cursor = $collection->find(['doc_id' => ['$ne' => $idCondition]]);
                $cursor = $collection->find([
                    'doc_id' => [
                        '$ne' => $idCondition,
                        '$in' => $baivietIds
                    ]
                ]);
                

                // print_r($cursor);

                foreach ($cursor as $document) {
                    $content2 = $document['wordForm'];
                    $words2 = explode(" ", $content2);
                    $words2 = array_unique($words2);

                    // $content2 = $document['wordForm'];
                    // $content2 = preg_replace("/[.,!?:;()\[\]]/", "", $content2); // Loại bỏ các ký tự dấu cuối
                    // $words2 = explode(" ", $content2);
                    // $words2 = array_unique($words2); 


                    $similarity = jaccardSimilarity($words1, $words2);
                    if ($similarity >= 0.25) {
                        $dotuongdong = round($similarity * 100);
                        $similarRows[] = [
                            'id' => $idCondition,
                            'tieude' => $row_bv['bv_tieude'],
                            'content1' => $content1,
                            'id2' => $document['doc_id'],
                            'content2' =>  $content2,
                            'similarity' => $dotuongdong,
                        ];

                    
                    
                    }
                }
            }
        
        } else {
            echo "Không tìm thấy bản ghi với ID " . $idCondition . " <br>";
        }
    }
}


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
                WHERE k.tt_ma is null or k.tt_ma = 3";
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
    WHERE k.tt_ma is null or k.tt_ma = 3
    And f.nd_username = '$user_name'";
} 

$result_bai_viet = mysqli_query($conn, $bai_viet);
unset($_SESSION['sl_dong']);
$sl_dong_hientai = mysqli_num_rows($result_bai_viet);
$_SESSION['sl_dong'] = $sl_dong_hientai;
// Hiển thị bảng với các dòng được gộp lại
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

                                    <div class="col-md-2">
                                        <div class="dataTables_filter">
                                            <label>Từ ngày</label>
                                            <input id="tungay" type="date" class="form-control" value="2015-01-01">

                                        </div>
                                    </div>
                                    <div class="col-md-2">
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
                                                            WHERE k.tt_ma is null or k.tt_ma = 3";
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


                                    <button id="Export"
                                        class="col-lg-2  col-sm-3 col-2 dt-button buttons-collection  btn btn-label-secondary ms-sm-0 me-3"
                                        id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">

                                        <span>
                                            <i class="bx bx-export "></i>
                                            <span class="dt-down-arrow d-none d-xl-inline-block">Export ▼</span>

                                        </span>

                                    </button>
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

                            <hr>
                            <!-- </div> -->
                            <!-- <h1 style="text-align: center;">KIỂM TRA TRÙNG LẶP</h1> -->


                            <div class="accordion-item card">
                                <h2 class="accordion-header text-body d-flex justify-content-between"
                                    id="accordionIconOne">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#accordionIcon-1" aria-controls="accordionIcon-1">
                                        <h5 class="card-header">Các bài đăng được kiểm tra trùng lặp - <span
                                                style="font-size: 15px;"><i>(Ngưỡng kiểm tra tỷ lệ trùng lặp > = 25
                                                    %)</i></span></h5>
                                    </button>
                                </h2>


                                <?php

                                $sumSimilarity = [];
                                $countSimilarity = [];

                                    foreach ($similarRows as $row) {
                                        $id = $row['id'];
                                        $id2 = $row['id2'];
                                        $td1 =  $row['tieude'];
                                        
                                        
                                        if (!isset($sumSimilarity[$id][$id2])) {
                                            $sumSimilarity[$id][$id2] = 0;
                                            $countSimilarity[$id][$id2] = 0;
                                        }
                                        
                                        $sumSimilarity[$id][$id2] += $row['similarity'];
                                        $countSimilarity[$id][$id2]++;
                                    }
                                    
                                    $averageSimilarity = [];

                                    foreach ($sumSimilarity as $id => $idData) {
                                        foreach ($idData as $id2 => $total) {
                                            $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2], 2);
                                            // echo "<ul><li>Bài viết có ID = <a href = 'Xem_BaiViet.php?this_bv_ma=$id'>$id</a> có thể trùng lặp với bài viết có ID = <a href = 'Xem_BaiViet.php?this_bv_ma=$id2'>$id2</a> với tỷ lệ: <b>" . $averageSimilarity[$id][$id2] . "%</b> </li></ul>";
                                        }
                                    }



                            ?>
                                <!-- <br> -->
                                <div id="accordionIcon-1" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionIcon">
                                    <div class="table-responsive text-nowrap border-top">
                                        <table class="table">
                                            <thead>
                                                <th>STT</th>
                                              
                                                <th style='white-space: normal'>Bài viết có nội dung được cho là trùng
                                                    lặp
                                                </th>
                                                <!-- <th>Mã bài viết góc</th> -->
                                                <th style='white-space: normal'>Bài viết có nội dung góc</th>
                                                <th>Độ tương đồng</th>
                                                <th>Hành động</th>
                                            </thead>
                                            <tbody>
                                                <?php
                                            $i = 0;
                                        
                                            foreach ($sumSimilarity as $id => $idData) {
                                                foreach ($idData as $id2 => $total) { 
                                                    $bai = "SELECT * FROM bai_viet where bv_ma = $id";
                                                    $result_bai = mysqli_query($conn, $bai);
                                                    while ($row_bai = mysqli_fetch_array($result_bai)) {
                                                        $td = $row_bai['bv_tieude'];
                                                    }
                                                    $bai2 = "SELECT * FROM bai_viet where bv_ma = $id2";
                                                    $result_bai2 = mysqli_query($conn, $bai2);
                                                    while ($row_bai2 = mysqli_fetch_array($result_bai2)) {
                                                        $td2 = $row_bai2['bv_tieude'];
                                                    }
                                                

                                                    $i ++;
                                                    $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2], 2);
                                                    echo '
                                                    <tr>
                                                        <td>'.$i.'</td>
                                                        <td style="white-space: normal"><b><a href="Xem_BaiViet.php?this_bv_ma='.$id.'">#'.$id.'<a/> -</b> '.$td.'</td>
                                                        <td style="white-space: normal"><b><a href="Xem_BaiViet.php?this_bv_ma='.$id2.'">#'.$id2.'<a/> -</b> '.$td2.'</td>
                                                        <td>'. $averageSimilarity[$id][$id2] .'%</td>
                                                        <td>
                                                            <a id="dropdownHanhDong" data-bs-toggle="dropdown"
                                                            aria-expanded="false"
                                                            style="display:math; padding:0.1rem 0.6rem"
                                                            class="dropdown-item"
                                                        >
                                                                <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                            </a>
                                                            <div class="dt-button-collection dropdown-menu"
                                                                style="top: 55.9375px; left: 419.797px;min-width:7rem"
                                                                aria-labelledby="dropdownHanhDong">
                                                                <div role="menu">
                                                                    <button onclick="openModal('.$id.','.$id2.')"
                                                                        class="dt-button buttons-print dropdown-item" tabindex="0"
                                                                        type="button" data-bs-toggle="modal" data-bs-target="#modalCenter"">
                                                                        <span><i class=" fa fa-eye me-2"></i>Chi tiết</span>
                                                                    </button>
                                                                    <a href="Sua_BaiViet.php?this_bv_ma='.$id.'"
                                                                        class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                        type="button">
                                                                        <span><i class="bx bx-edit-alt me-2"></i>Kiểm duyệt</span>
                                                                    </a>';
                                                    
                                                                    // Kiểm tra điều kiện
                                                                    if ($_SESSION['vaitro'] === 'Super Admin') {
                                                                        echo '
                                                                            <a href="#"
                                                                                onclick="Xoa_Baiviet(\''.$id.'\');"
                                                                                class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                                type="button">
                                                                                <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                                                            </a>';
                                                                    } elseif ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                                                                        header("Refresh: 0;url=error.php");  
                                                                        exit;
                                                                    }
                                                    
                                                    echo '
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>';
                
                                                }
                                            }
                                        ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
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
                                            <h5 class="modal-title" id="modalCenterTitle">Chi tiết</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary"
                                                data-bs-dismiss="modal">
                                                Duyệt
                                            </button>
                                            <button type="button" class="btn btn-primary">Huỷ bài viết</button>
                                        </div>
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="modal-body" id="modalBody">
                                                <!-- Nội dung sẽ được điền tự động từ AJAX -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item card">
                                <h2 class="accordion-header text-body d-flex justify-content-between"
                                    id="accordionIconOne">
                                    <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#choduyet" aria-controls="accordionIcon-1">
                                        <h5 class="card-header">Các bài viết chờ duyệt</h5>
                                    </button>
                                </h2>


                                <div id="choduyet" class="accordion-collapse collapse"
                                    data-bs-parent="#accordionIcon">
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
                                                                        WHERE k.tt_ma is null or k.tt_ma = 3";
                                                           
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
                                                                        WHERE k.tt_ma is null or k.tt_ma = 3
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

                                                        <td><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

                                                        </td>
                                                        <td> <?php echo $row_bai_viet['nd_hoten'] ?> </td>
                                                        <td style="white-space: normal"> 
                                                            <?php
                                                             
                                                                 foreach ($sumSimilarity as $id => $idData) {
                                                                    foreach ($idData as $id2 => $total) { 
                                                                        if($id ==  $row_bai_viet['bv_ma']){

                                                                           
                                                                            $bai2 = "SELECT * FROM bai_viet where bv_ma = $id2";
                                                                            $result_bai2 = mysqli_query($conn, $bai2);
                                                                            while ($row_bai2 = mysqli_fetch_array($result_bai2)) {
                                                                                $td2 = $row_bai2['bv_tieude'];
                                                                            }
                                                                        
                        
                                                                            $i ++;
                                                                            $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2], 2);
                                                                            echo ''.$averageSimilarity[$id][$id2].'% (<a href = Xem_BaiViet.php?this_bv_ma='.$id2.'>'.$td2.'</a>) 
                                                                            <button  onclick="openModal('.$id.','.$id2.')"
                                                                            class="dt-button buttons-print dropdown-item" tabindex="0"
                                                                            type="button" data-bs-toggle="modal" data-bs-target="#modalCenter"">
                                                                            <span><i class=" fa fa-eye me-2"></i></span></button><br><br>';
                                                                           
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
    </script>

</body>

</html>