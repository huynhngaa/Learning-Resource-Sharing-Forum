<?php
// Include your database connection code, e.g., include "include/conn.php";

// Check if the POST request contains the selected Môn Học value
if (isset($_POST['monhoc'])) {
    // Sanitize and validate the input if necessary
    $selectedMonHoc = mysqli_real_escape_string($conn, $_POST['monhoc']);

    // Perform a database query to fetch the Danh Mục Cha options based on the selected Môn Học
    $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
    FROM danh_muc d
    LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con
    WHERE d.mh_ma = = $selectedMonHoc";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $danhmucchaOptions = '<option value="">Chọn danh mục cha</option>';
        while ($danhmuccha = mysqli_fetch_array($result)) {
            $danhmucchaOptions .= '<option value="' . $danhmuccha['dm_ma'] . '">' . $danhmuccha['dm_ten'] . '</option>';
        }
        echo $danhmucchaOptions;
    } else {
        echo '<option value="">Không có danh mục cha</option>';
    }
} else {
    echo '<option value="">Không có dữ liệu</option>';
}
?>
