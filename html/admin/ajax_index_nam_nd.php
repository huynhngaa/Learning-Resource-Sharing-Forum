<?php
session_start();
include("./includes/connect.php");

if (isset($_GET['nd_nam'])) {
    $nd_nam = $_GET['nd_nam'];
} else {
    $nd_nam = date('Y');
}

$dl_nguoidung = "SELECT count(*) as soluong, MONTH(nd_ngaytao) as thang, YEAR(nd_ngaytao) as nam
                FROM nguoi_dung 
                GROUP BY thang, nam
                HAVING nam = '$nd_nam'";
$result_dl_nguoidung = mysqli_query($conn, $dl_nguoidung);

$data_nguoidung = array();
while ($row_dl_nguoidung = mysqli_fetch_array($result_dl_nguoidung)) {
    $data_nguoidung[] = array(
        'thang' => "Tháng " . $row_dl_nguoidung['thang'] . "",
        'nam' => $row_dl_nguoidung['nam'],
        'soluong' => $row_dl_nguoidung['soluong']
    );
}

echo json_encode($data_nguoidung);

?>
