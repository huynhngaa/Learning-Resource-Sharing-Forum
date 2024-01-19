<?php
    session_start();
    include("./includes/connect.php");

    if (!isset($_SESSION['Admin'])) {
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }

     // Kiểm tra dữ liệu POST
     var_dump($_POST);


    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apdung'])) {
        $selectedAction = $_POST['tacvu'];
        $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];
        var_dump($selectedIds);


        if ($selectedAction == "xoa") {
            // Sử dụng transaction để đảm bảo toàn vẹn dữ liệu
            mysqli_begin_transaction($conn);

            try {
                foreach ($selectedIds as $id) {
                    $xoa_ls = "DELETE FROM lich_su_xem WHERE bl_ma = '$id'";
                    mysqli_query($conn, $xoa_ls);

                    $xoa_rep_bl = "DELETE FROM rep_bl WHERE bl_cha = '$id' OR bl_con = '$id'";
                    mysqli_query($conn, $xoa_rep_bl);

                    $xoa_bin_luan = "DELETE FROM binh_luan WHERE bl_ma = '$id'";
                    mysqli_query($conn, $xoa_bin_luan);
                    echo $xoa_ls, '<br>', $xoa_rep_bl, '<br>', $xoa_bin_luan;
                }

                // Nếu mọi thứ thành công, commit transaction
                mysqli_commit($conn);
                echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>"; 
                // header("Refresh: 0;url=10.php");  
            } catch (Exception $e) {
                // Nếu có lỗi xảy ra, rollback transaction và hiển thị thông báo lỗi
                mysqli_rollback($conn);
                echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>"; 
                echo "Error: " . $e->getMessage();
            }
        } elseif ($selectedAction == "duyet") {
            foreach ($selectedIds as $id) {
                $cap_nhat_danh_muc = "UPDATE binh_luan SET trangthai = '1' WHERE bl_ma = '$id'";
                mysqli_query($conn, $cap_nhat_danh_muc);           
            }
            echo "<script>alert('Cập nhật trạng thái bình luận thành công!');</script>"; 
            // header("Refresh: 0;url=10.php");  
        }
        elseif ($selectedAction == "huy") {
            foreach ($selectedIds as $id) {
                $huy_bl = "UPDATE binh_luan SET trangthai = '2' WHERE bl_ma = '$id'";
                mysqli_query($conn, $huy_bl);           
            }
            echo "<script>alert('Cập nhật trạng thái bình luận thành công!');</script>"; 
            // header("Refresh: 0;url=10.php");  
        }
    }

    $binh_luan = "SELECT * FROM binh_luan a 
                    LEFT JOIN bai_viet b ON a.bv_ma = b.bv_ma 
                    LEFT JOIN nguoi_dung c ON b.nd_username = c.nd_username 
                    LEFT JOIN trang_thai t ON t.tt_ma = a.trangthai";
   
    $result_binh_luan = mysqli_query($conn, $binh_luan);
        // Thêm đoạn mã sau vào cuối trang PHP
        if (!mysqli_commit($conn)) {
            echo "Commit failed: " . mysqli_error($conn);
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bình Luận</title>
    <!-- Các tài nguyên CSS và JS khác có thể được thêm vào đây -->
</head>
<body>
<form method="POST" enctype="multipart/form-data">
    <div style="margin-left: 0.1rem; margin-bottom: 1rem" class="col-lg-12 row">
        <!-- Form lọc -->
        <div class="col-md-2 col-lg-2 col-sm-6 col-6 ">
            <select name="tacvu" class="form-select" id="tacvu">
                <option value="Tất cả">Chọn tác vụ</option>
                <option value="xoa">Xoá</option>
                <option value="duyet">Duyệt</option>
                <option value="huy">Huỷ</option>
            </select>
        </div>
        <div class="col-md-2 col-lg-2 col-sm-6 col-6 ">
            <button name="apdung" type="submit" class="btn btn-primary" id="apdung">Áp dụng</button>
        </div>
    </div>

    <table class="table">
        <thead>
            <th>STT</th>
            <th>Mã</th>
            <th>Nội dung</th>
            <th>Trạng Thái</th>
        </thead>
        <tbody class="table-border-bottom-0" id="data-container">
            <?php
            $stt = 0;
            while ($row_binh_luan = mysqli_fetch_array($result_binh_luan)) {
                $stt = $stt + 1;
            ?>
            <tr>
                <td><input class="form-check-input" name="check[]" type="checkbox"
                        value="<?php echo $row_binh_luan['bl_ma'] ?>">
                </td>
                <td> <?php  echo $row_binh_luan['bl_ma'] ?> </td>
                <td> <?php echo $row_binh_luan['bl_noidung'] ?> </td>
                <td> <?php echo $row_binh_luan['trangthai'] ?> </td>
                <!-- ... (Các cột dữ liệu) ... -->
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>

<script>
    document.getElementById('apdung').addEventListener('click', function () {
        document.querySelector('form').submit();
    });
</script>

</body>
</html>
