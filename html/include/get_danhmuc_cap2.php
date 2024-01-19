<?php
// Kết nối CSDL và thực hiện truy vấn SQL để lấy danh sách danh mục cấp 2
// Lưu ý: Đây là ví dụ giả định. Bạn cần thay thế nó với truy vấn SQL thực tế và xử lý dữ liệu tương ứng.
$monhoc_ma = $_POST['monhoc_ma'];
$sql = "SELECT * FROM danhmuc_phancap WHERE dm_cha = '$monhoc_ma'";
$result = mysqli_query($conn, $sql);

// Tạo danh sách tùy chọn cho select danh mục cấp 2
$options = '';
while ($row = mysqli_fetch_assoc($result)) {
    $options .= '<option value="' . $row['dm_con'] . '">' . $row['dm_con'] . '</option>';
}

echo $options; // Trả về danh sách tùy chọn cấp 2 dưới dạng HTML
?>
