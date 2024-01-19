<?php
session_start();
include("./includes/connect.php");
$this_username = $_SESSION['Admin'];

$nguoi_dung = "SELECT * FROM nguoi_dung a, vai_tro b WHERE a.vt_ma = b.vt_ma AND nd_username = '$this_username'";
$result_nguoi_dung = mysqli_query($conn, $nguoi_dung);
$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

if (isset($_POST['Luu'])) {
    $MK_Hientai = $_POST['MK_Hientai'];
    $MK_Moi = $_POST['MK_Moi'];
    $MK_Moi_2 = $_POST['MK_Moi_2'];

    $sql = "SELECT * FROM nguoi_dung WHERE nd_username = '$this_username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        $hashed_current_password = $user['nd_matkhau'];

        if (password_verify($MK_Hientai, $hashed_current_password)) {
            if ($MK_Moi == $MK_Moi_2) {
                $hashed_new_password = password_hash($MK_Moi, PASSWORD_DEFAULT);
                $doi_mk = "UPDATE nguoi_dung SET nd_matkhau = '$hashed_new_password' WHERE nd_username = '$this_username'";
                if (mysqli_query($conn, $doi_mk)) {
                    echo "<script>alert('Đổi mật khẩu thành công');</script>";
                    header("Refresh: 0;url=TaiKhoan.php");
                } else {
                    echo "<script>alert('Lỗi khi cập nhật mật khẩu');</script>";
                }
            } else {
                echo "<script>alert('Mật khẩu mới không khớp!');</script>";
            }
        } else {
            echo "<script>alert('Vui lòng kiểm tra lại mật khẩu cũ!');</script>";
        }
    } else {
        echo "<script>alert('Người dùng không tồn tại!');</script>";
    }
}
?>
