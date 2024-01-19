<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}
$vt = $_GET['vt'];
$vai_tro = "SELECT * FROM vai_tro WHERE vt_ma='$vt'";
$result_vai_tro = mysqli_query($conn,$vai_tro);
$row_vai_tro = mysqli_fetch_assoc($result_vai_tro);

$nguoi_dung = "SELECT * FROM nguoi_dung WHERE vt_ma='$vt' order by nd_ngaytao DESC ";

$result_nguoi_dung = mysqli_query($conn,$nguoi_dung);



?>
<!DOCTYPE html>
<html>

<head>
    <title>In danh sách người dùng</title>
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

        <h2 class="text-center" style="margin-bottom:2rem; text-transform: uppercase;">DANH SÁCH
            <?php echo $row_vai_tro['vt_ten'] ?> <span><button id="print-button" class="btn btn-primary"
                    onclick="printPage()"><i class="fa fa-print"></i></button></span></h2>

    </div>
    <div>
        <table class="table" border="3">
            <thead style="background-color: grey;" class="text-center">
                <tr>
                    <th>STT</th>
                    <th>Username</th>
                    <th>Họ tên</th>
                    <th>Giới tính</th>
                    <th>Ngày sinh</th>
                    <th>Email</th>
                    <th>Di động</th>
                    <th>Địa chỉ</th>

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
                                                }else{
                                                    $row_nguoi_dung['nd_gioitinh'] = "(Chưa có dữ liệu)";
                                                }
                                            
                                                $stt = $stt + 1;
                                        ?>
                <tr>
                    <td> <?php echo $stt ?> </td>

                    <td><strong><?php echo  $row_nguoi_dung['nd_username']; ?></strong></td>
                    <td style="white-space: normal">
                        <?php echo  $row_nguoi_dung['nd_hoten']; ?>
                    </td>

                    <td style="white-space: normal">
                        <?php echo  "<i>".$row_nguoi_dung['nd_gioitinh']."</i>";?>
                    </td>

                    <td style="white-space: normal">

                        <?php 
                            if($row_nguoi_dung['nd_ngaysinh'] == '0000-00-00'){
                                $row_nguoi_dung['nd_ngaysinh'] = "(Chưa có dữ liệu)";
                                echo  "<i>".$row_nguoi_dung['nd_ngaysinh']."</i>";
                            }else{
                                echo date_format(date_create($row_nguoi_dung['nd_ngaysinh']), "d-m-Y");
                            }            
                        ?>
                    </td>

                    <td style="white-space: normal">

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
                        <?php
                              if($row_nguoi_dung['nd_diachi'] == ''){
                                $row_nguoi_dung['nd_diachi'] = "(Chưa có dữ liệu)";
                                echo  "<i>".$row_nguoi_dung['nd_diachi']."</i>";
                            }else{
                                echo  $row_nguoi_dung['nd_diachi']; 
                            }            
                        ?>

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