<?php
// Kết nối đến cơ sở dữ liệu
$connection = mysqli_connect("localhost", "root", "", "luanvan");

// Kiểm tra kết nối có thành công không
if (!$connection) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}

// Kiểm tra xem có dữ liệu được gửi từ trang web không
if (isset($_POST['parentId']) && isset($_POST['level'])) {
    $parentId = $_POST['parentId'];
    $level = $_POST['level'];

    // Hàm đệ quy để tạo select box dựa trên giá trị đã chọn
    function createSelect($connection, $parentId, $level) {
        $query = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
        FROM danh_muc d
        LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con where mh_ma = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "i", $parentId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $selectHtml = "<label for='select{$level}'>Select {$level}:</label>";
        $selectHtml .= "<select id='select{$level}' name='select{$level}' onchange='loadNextSelect({$level})'>";
        $selectHtml .= "<option value=''>Chọn {$level}</option>";

        while ($row = mysqli_fetch_assoc($result)) {
            $id = $row['dm_ma'];
            $name = $row['dm_ten'];
            $selectHtml .= "<option value='{$id}'>$name</option>";
        }

        $selectHtml .= "</select>";

        mysqli_stmt_close($stmt);

        return $selectHtml;
    }

    $nextSelectHtml = createSelect($connection, $parentId, $level);

    // Trả về mã HTML cho select box tiếp theo
    echo $nextSelectHtml;
}

// Đóng kết nối cơ sở dữ liệu
mysqli_close($connection);
?>
