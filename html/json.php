<?php
// Kết nối CSDL
$conn = mysqli_connect("localhost", "root", "", "luanvan");

// Truy vấn dữ liệu từ cơ sở dữ liệu
$sql = "SELECT dm_ma, dm_ten FROM danh_muc WHERE dm_ma NOT IN (SELECT dm_con FROM danhmuc_phancap)";
$result = $conn->query($sql);

// Mảng để lưu trữ danh sách danh mục
$categoriesData = [];

// Kiểm tra kết quả trả về từ truy vấn
if ($result->num_rows > 0) {
    // Lặp qua từng hàng dữ liệu
    while ($row = $result->fetch_assoc()) {
        $category = [
            "id" => $row["dm_ma"],
            "name" => $row["dm_ten"],
            "children" => [] // Mảng con để lưu trữ danh mục con
        ];

        // Hàm đệ quy để lấy danh mục con
        $category["children"] = getChildren($category["id"], $conn);

        $categoriesData[] = $category;
    }
}

// Chuyển mảng dữ liệu thành định dạng JSON
$jsonData = json_encode($categoriesData, JSON_PRETTY_PRINT);
echo $jsonData;

// Đóng kết nối cơ sở dữ liệu
$conn->close();

// Hàm đệ quy để lấy danh mục con
function getChildren($parentId, $conn) {
    $children = [];

    $sql = "SELECT dm_ma, dm_ten FROM danh_muc WHERE dm_ma IN (SELECT dm_con FROM danhmuc_phancap WHERE dm_cha = $parentId)";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $childCategory = [
                "id" => $row["dm_ma"],
                "name" => $row["dm_ten"],
                "children" => getChildren($row["dm_ma"], $conn) // Đệ quy để lấy danh mục con của danh mục con
            ];

            $children[] = $childCategory;
        }
    }

    return $children;
}
?>