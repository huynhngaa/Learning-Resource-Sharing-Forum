<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}
$binh_luan = "SELECT * FROM binh_luan a 
 LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
 LEFT JOIN nguoi_dung c ON b.nd_username=c.nd_username 
 LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
 ORDER BY bl_thoigian DESC";

$result_binh_luan = mysqli_query($conn, $binh_luan);
?>
<!DOCTYPE html>
<html>

<head>
    <title>In danh sách bình luận</title>
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/icon.png" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        
        <h2 class="text-center" style="margin-bottom:2rem">DANH SÁCH BÌNH LUẬN <span><button id="print-button" class="btn btn-primary" onclick="printPage()"><i class="fa fa-print"></i></button></span></h2>
        
    </div>
    <div>
        <table class="table" border="3">
            <thead style="background-color: grey;" class="text-center">
                <tr>
                    <th>STT</th>
                    <th>Trạng thái</th>
                    <th>Bài viết</th>
                    <th>Người BL</th>
                    <th>Nội dung</th>
                    <th>Thời gian</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0" id="data-container">
                <?php
                $stt = 0;
                while ($row_binh_luan = mysqli_fetch_array($result_binh_luan)) {
                    $stt = $stt + 1;
                ?>
                <tr>
                    <td><?php echo $stt ?></td>
                    <td>
                        <?php
                            if ($row_binh_luan['tt_ma'] == 1) {
                                echo "<b >" . $row_binh_luan['tt_ten'] . '</b>';
                            } else if ($row_binh_luan['tt_ma'] == 2) {
                                echo "<b >" . $row_binh_luan['tt_ten'] . '</b>';
                            } else if ($row_binh_luan['tt_ma'] == 4) {
                                echo "<b >" . $row_binh_luan['tt_ten'] . '</b>';
                            } else {
                                echo "<b >Chờ duyệt</b>";
                            }
                            ?>
                    </td>
                    <td style="white-space: normal"><?php echo $row_binh_luan['bv_tieude'] ?></td>
                    <td><?php echo $row_binh_luan['nd_hoten'] ?></td>
                    <td style="white-space: normal"><?php echo $row_binh_luan['bl_noidung'] ?></td>
                    <td><?php echo date_format(date_create($row_binh_luan['bl_thoigian']), "d-m-Y (H:i:s)"); ?></td>
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