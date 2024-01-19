<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}
$danh_muc = "SELECT DISTINCT ( a.nd_username),nd_hoten
FROM quan_ly a, nguoi_dung b, danh_muc c
 where a.nd_username = b.nd_username 
 and c.dm_ma=a.dm_ma";

$result_danh_muc = mysqli_query($conn,$danh_muc);
?>
<!DOCTYPE html>
<html>

<head>
    <title>In danh sách phân công quản lý danh mục</title>
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

        <h2 class="text-center" style="margin-bottom:2rem">BẢNG PHÂN CÔNG QUẢN LÝ DANH MỤC <span><button id="print-button"
                    class="btn btn-primary" onclick="printPage()"><i class="fa fa-print"></i></button></span></h2>

    </div>
    <div>
        <table class="table" border="3">
            <thead style="background-color: grey;" class="text-center">
                <tr>
                    <th>STT</th>
                    <th>Người quản lý</th>
                    <th>Danh mục</th>
                    <th>Thời gian phân công</th>
                </tr>
            </thead>
            <tbody >
                <?php
                    $stt = 0;
                    while ($row_danh_muc = mysqli_fetch_array($result_danh_muc)) {
                    $stt = $stt + 1;
                                                
                ?>
                <tr>

                    <td> <?php echo $stt ?> </td>
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
                                                    
                            $danhmuc_ql = array();
                                                    
                            while ($row_danh_muc_ql = mysqli_fetch_assoc($result_danh_muc_ql)) {
                                $danhmuc_ql[$row_danh_muc_ql['nd_username']]['hoten'] = $row_danh_muc_ql['nd_hoten'];
                                $danhmuc_ql[$row_danh_muc_ql['nd_username']]['danh_muc'][] = array(
                                    'dm_ma' => $row_danh_muc_ql['dm_ma'],
                                    'dm_ten' => $row_danh_muc_ql['dm_ten'],
                                    'thoigian'=>$row_danh_muc_ql['tg_phancong']
                                );
                            }
                                                    
                            foreach ($danhmuc_ql as $username => $userData) {
                                if($username == $row_danh_muc['nd_username']){
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
                        <?php
                            foreach ($danhmuc_ql as $username => $userData) {
                                if($username == $row_danh_muc['nd_username']){
                                                        
                                foreach ($userData['danh_muc'] as $category) {
                                    echo "- " . date_format(date_create($category['thoigian']), "d/m/Y (H:i:s)") . "<br>";
                                  
                                }
                                    echo "<br>";
                                }else{

                                }
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
      
    }
    </script>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>