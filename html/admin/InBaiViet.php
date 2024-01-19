<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}
$user_name = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";
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

    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                    ORDER BY bv_ngaydang DESC ";
}
elseif($_SESSION['vaitro'] == 'Admin'){
    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                    LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
                    where f.nd_username = '$user_name'
                    ORDER BY bv_ngaydang DESC ";
} 



$result_bai_viet = mysqli_query($conn, $bai_viet);
?>
<!DOCTYPE html>
<html>

<head>
    <title>In danh sách bài viếtn</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/icon.png" />

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
    /* Ẩn nút in khi trang được in */
    @media print {
        #print-button {
            display: none;
        }
    }
    </style>
</head>

<body style="padding: 2rem; margin-left:5rem; margin-right:5rem">

    <div>

        <h2 class="text-center" style="margin-bottom:2rem">DANH SÁCH BÀI VIẾT <span><button id="print-button"
                    class="btn btn-primary" onclick="printPage()"><i class="fa fa-print"></i></button></span></h2>

    </div>
    <div>
        <table class="table" border="3">
            <thead style="background-color: grey;" class="text-center">
                <tr>
                    <th>STT</th>
                    <th>Trạng thái</th>
                    <th>Tiêu đề</th>
                    <th>Ngày đăng</th>

                    <th class="sort-header">Tác giả

                    </th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="data-container">
                <?php
                    $stt = 0;
                    while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                        $stt = $stt + 1;
                ?>
                <tr>
                    <td> <?php echo $stt ?> </td>

                    <td>

                    <?php
                            if ($row_bai_viet['tt_ma'] == 1) {
                                echo "<b >" . $row_bai_viet['tt_ten'] . '</b>';
                            } else if ($row_bai_viet['tt_ma'] == 2) {
                                echo "<b >" . $row_bai_viet['tt_ten'] . '</b>';
                            } else if ($row_bai_viet['tt_ma'] == 4) {
                                echo "<b >" . $row_bai_viet['tt_ten'] . '</b>';
                            } else {
                                echo "<b >Chờ duyệt</b>";
                            }
                            ?>
                    </td>
                    <td style="white-space: normal"> <?php echo $row_bai_viet['bv_tieude'] ?> </td>
                    <td><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

                    </td>
                    <td> <?php echo $row_bai_viet['nd_hoten'] ?> </td>


                </tr>
                <?php } ?>

            </tbody>
        </table>
    </div>

    <script>
    window.onload = function() {
        window.print(); // Tự động in trang khi trang đã tải hoàn toàn
    };

    function printPage() {
        window.print();
        // if (window.print()) {
        //     // Kiểm tra nếu người dùng chọn in, không làm gì cả
        // } else {
        //     // Nếu người dùng không chọn in, quay lại trang trước
        //     history.back();
        // }
    }
    </script>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>