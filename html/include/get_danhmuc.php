<?php
// Include your database connection here
include('conn.php');

if (isset($_GET['mh_ma'])) {
    $mh_ma = $_GET['mh_ma'];
    $categories = getCategories($mh_ma, $conn);
    echo json_encode($categories);
} else {
    echo json_encode([]);
}

function getCategories($mh_ma, $conn) {
    $categories = [];

    $sql = "SELECT dm_ma, dm_ten FROM danh_muc WHERE mh_ma = $mh_ma";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $category = [
            'id' => $row['dm_ma'],
            'name' => $row['dm_ten'],
            'children' => getSubcategories($row['dm_ma'], $conn)
        ];

        $categories[] = $category;
    }

    return $categories;
}

function getSubcategories($dm_ma, $conn) {
    $subcategories = [];

    $sql = "SELECT dm_ma, dm_ten FROM danh_muc WHERE dm_cha = $dm_ma";
    $result = mysqli_query($conn, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $subcategory = [
            'id' => $row['dm_ma'],
            'name' => $row['dm_ten'],
            'children' => getSubcategories($row['dm_ma'], $conn)
        ];

        $subcategories[] = $subcategory;
    }

    return $subcategories;
}

// Close the database connection if needed
mysqli_close($conn);
?>
