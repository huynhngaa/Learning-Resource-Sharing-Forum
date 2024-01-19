<?php
// Kết nối CSDL
include "include/conn.php";

// Truy vấn để lấy dữ liệu từ bảng danh_muc
$sql = "SELECT dm_ma, dm_ten FROM danh_muc";
$result = $conn->query($sql);

$categories = array();

if ($result->num_rows > 0) {
    // Duyệt qua các dòng kết quả
    while($row = $result->fetch_assoc()) {
        // Tạo một mảng cho mỗi danh mục
        $category = array(
            'id' => $row['dm_ma'],
            'name' => $row['dm_ten'],
            'children' => array()
        );

        // Truy vấn để lấy danh mục con từ bảng danhmuc_phancap
        $subcategories_sql = "SELECT dm_con FROM danhmuc_phancap WHERE dm_cha = " . $row['dm_ma'];
        $subcategories_result = $conn->query($subcategories_sql);

        if ($subcategories_result->num_rows > 0) {
            while($sub_row = $subcategories_result->fetch_assoc()) {
                // Thêm danh mục con vào mảng children của danh mục cha tương ứng
                $subcategory = array(
                    'id' => $sub_row['dm_con'],
                    'name' => 'Danh Mục Con' // Có thể lấy tên của danh mục con từ bảng danh_muc
                );
                $category['children'][] = $subcategory;
            }
        }

        $categories[] = $category;
    }
}

// Chuyển mảng thành JSON và hiển thị
header('Content-Type: application/json');
echo json_encode($categories, JSON_PRETTY_PRINT);

// Đóng kết nối CSDL
$conn->close();
?>
