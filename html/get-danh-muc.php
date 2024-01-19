<?php
include "include/conn.php";

if (isset($_GET['monhoc'])) {
  $monHocValue = $_GET['monhoc'];
  $sql = "SELECT dm_ma, dm_ten FROM danh_muc WHERE dm_ma NOT IN (SELECT dm_con FROM danhmuc_phancap) and mh_ma = $monHocValue";
  $result = $conn->query($sql);

  $categoriesData = [];

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $category = [
        "id" => $row["dm_ma"],
        "name" => $row["dm_ten"],
        "children" => []
      ];

      $category["children"] = getChildren($category["id"], $conn);
      $categoriesData[] = $category;
    }
  }

  echo json_encode($categoriesData);
}

function getChildren($parentId, $conn) {
  $children = [];
  $sql = "SELECT dm_ma, dm_ten FROM danh_muc WHERE dm_ma IN (SELECT dm_con FROM danhmuc_phancap WHERE dm_cha = $parentId)";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $childCategory = [
        "id" => $row["dm_ma"],
        "name" => $row["dm_ten"],
        "children" => getChildren($row["dm_ma"], $conn)
      ];
      $children[] = $childCategory;
    }
  }

  return $children;
}

$conn->close();
?>
