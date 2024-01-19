<?php
include "include/conn.php";

if (isset($_GET['monhoc'])) {
    $selectedMonHoc = $_GET['monhoc'];

    // Fetch categories based on the selected course
    $data = getCategoryTree($selectedMonHoc, $conn);

    // Generate the recursive category select box
    echo buildCategorySelect($data);
}

function getCategoryTree($selectedMonHoc, $conn) {
    $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
            FROM danh_muc d
            LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con
            WHERE d.mh_ma = $selectedMonHoc";
    $result = mysqli_query($conn, $sql);

    $data = [];

    while ($row = mysqli_fetch_array($result)) {
        $data[] = [
            "id" => $row['dm_ma'],
            "name" => $row['dm_ten'],
            "parent" => $row['dm_cha']
        ];
    }

    return $data;
}

function buildCategorySelect($data, $parentId = 0, $level = 0) {
    $output = '<select id="categorySelect">';
    foreach ($data as $category) {
        if ($category['parent'] == $parentId) {
            $output .= '<option value="' . $category['id'] . '" data-level="' . $level . '">';
            $output .= str_repeat('&ensp;', $level * 2) . ' ' . $category['name']; // Add hyphens here for indentation
            $output .= '</option>';
            $output .= buildCategorySelect($data, $category['id'], $level + 1);
        }
    }
    $output .= '</select>';
    
    return $output;
}

?>