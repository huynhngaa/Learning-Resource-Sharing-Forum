<?php
include "conn.php";

if (isset($_POST['danhMucChaMa'])) {
    $danhMucChaMa = $_POST['danhMucChaMa'];

    // Truy vấn CSDL để lấy danh mục con tương ứng với danh mục cha đã chọn
    $sql = "SELECT dm_ma FROM danhmuc_phancap  WHERE dm_cha = $danhMucChaMa";

    $result = mysqli_query($conn, $sql);

    // Bắt đầu thẻ select danh mục con
    $danhMucConOptions = '<select class="form-select" name="danhmuccon" id="danhmuccon">';

    // $danhMucConOptions = '<option value="">Chọn danh mục con</option>';

    // Tạo các tùy chọn cho danh sách thả xuống danh mục con
    while ($danhmuc = mysqli_fetch_array($result)) {
        $danhMucConOptions .= '<option value="' . $danhmuc['dm_con'] . '">' . $danhmuc['dm_con'] . '</option>';
    }
    $danhMucConOptions .= '</select>';
    // Trả về danh sách danh mục con dưới dạng HTML
    echo $danhMucConOptions;
}
?>
