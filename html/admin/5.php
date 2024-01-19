<?php
session_start();
include("./includes/connect.php");

if (isset($_POST['danh_muc_cha'])) {
    $danh_muc_cha = $_POST['danh_muc_cha'];
    
    // Truy vấn danh mục con dựa trên danh mục cha
    $sql = "SELECT dm_ma, dm_ten FROM danh_muc a left join danhmuc_phancap b ON a.dm_ma = b.dm_con WHERE dm_cha = $danh_muc_cha";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Nếu có danh mục con, tạo thẻ option cho từng danh mục con
        echo '<option disabled selected>Chọn danh mục con</option>';
        while ($row = mysqli_fetch_assoc($result)) {
            echo '<option value="' . $row['dm_ma'] . '">' . $row['dm_ten'] . '</option>';
        }
    } else {
        // Nếu không có danh mục con, trả về thông báo
        echo '<option disabled selected>Không có danh mục con</option>';
    }
}
?>
