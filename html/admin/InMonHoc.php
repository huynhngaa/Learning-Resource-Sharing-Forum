<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}
$mon_hoc = "SELECT a.* ,kl_ten
FROM mon_hoc a,  khoi_lop b 
where a.kl_ma = b.kl_ma 
order by kl_ma ";

$result_mon_hoc = mysqli_query($conn,$mon_hoc);
?>
<!DOCTYPE html>
<html>

<head>
    <title>In danh sách môn học</title>
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

<body style="padding: 2rem; margin-left:8rem; margin-right:8rem">

    <div>
        
        <h2 class="text-center" style="margin-bottom:2rem">DANH SÁCH MÔN HỌC <span><button id="print-button" class="btn btn-primary" onclick="printPage()"><i class="fa fa-print"></i></button></span></h2>
        
    </div>
    <div>
        <table class="table" border="3">
            <thead style="background-color: grey;" class="text-center">
                <tr>
                    <th>STT</th>
                    <th>Mã môn</th>
                    <th>Tên môn</th>
                    <th>Khối lớp</th>
                   
                </tr>
            </thead>
            <tbody class="table-border-bottom-0 " id="data-container">
                                        <?php
                                                        $stt = 0;
                                                        while ($row_mon_hoc = mysqli_fetch_array($result_mon_hoc)) {
                                                            $stt = $stt + 1;
                                                    ?>
                                        <tr>
                                            <td>
                                                <?php echo $stt ?> </td>
                                            <td><strong><?php echo  $row_mon_hoc['mh_ma']; ?></strong></td>
                                            <td>
                                                <?php echo  $row_mon_hoc['mh_ten']; ?>
                                            </td>
                                            <td>
                                                <?php echo  $row_mon_hoc['kl_ten']; ?>

                                            </td>
                                           
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