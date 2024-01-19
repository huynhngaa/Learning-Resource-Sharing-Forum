<?php
include "include/conn.php";

$data = json_decode(file_get_contents("php://input"), true);
$rating = $data['rating'];
$bv_ma = $data['bv_ma'];
$nd_username = $data['nd_username'];



?>

</h2>
<?php

$checkQuery = "SELECT * FROM danh_gia WHERE nd_username = '$nd_username' AND bv_ma = $bv_ma";
$result = $conn->query($checkQuery);

if ($result->num_rows > 0) {
    // If record exists, perform an update
    $updateQuery = "UPDATE danh_gia SET dg_diem = $rating, dg_thoigian =NOW() WHERE nd_username = '$nd_username' AND bv_ma = $bv_ma";
    if ($conn->query($updateQuery) === TRUE) {
        echo "Rating updated successfully";
    } else {
        echo "Error: " . $updateQuery . "<br>" . $conn->error;
    }
} else {
    // If record doesn't exist, perform an insert
    $insertQuery = "INSERT INTO danh_gia (bv_ma, nd_username, dg_diem) VALUES ($bv_ma, '$nd_username', $rating)";
    if ($conn->query($insertQuery) === TRUE) {
        echo "Rating inserted successfully";
    } else {
        echo "Error: " . $insertQuery . "<br>" . $conn->error;
    }
}
$updateAVG = "UPDATE bai_viet 
              SET bv_diemtrungbinh = (SELECT ROUND(AVG(dg_diem), 1) FROM danh_gia WHERE bv_ma = $bv_ma)
              WHERE bv_ma = $bv_ma";

$resultAVG = $conn->query($updateAVG);
$conn->close();
?>
