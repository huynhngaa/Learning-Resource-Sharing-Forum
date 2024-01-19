<?php
session_start();
include("./includes/connect.php");

// if (isset($_GET['nd_nam'])) {
//     $nd_nam = $_GET['nd_nam'];
// } else {
//     $nd_nam = 2023;
// }

if (isset($_GET['bv_nam'])) {
    $bv_nam = $_GET['bv_nam'];
} else {
    $bv_nam = date('Y');
}

$dl_baiviet = "SELECT count(*) as soluong, MONTH(bv_ngaydang) as thang, YEAR(bv_ngaydang) as nam 
                FROM bai_viet 
                GROUP BY thang, nam
                HAVING nam = '$bv_nam'";
$result_dl_baiviet = mysqli_query($conn, $dl_baiviet);

$data_baiviet = array();
while ($row_dl_baiviet = mysqli_fetch_array($result_dl_baiviet)) {
    $data_baiviet[] = array(
        'thang' => "Tháng " . $row_dl_baiviet['thang'] . "",
        'nam' => $row_dl_baiviet['nam'],
        'soluong' => $row_dl_baiviet['soluong']
    );
}

// $dl_nguoidung = "SELECT count(*) as soluong, MONTH(nd_ngaytao) as thang, YEAR(nd_ngaytao) as nam
//                 FROM nguoi_dung 
//                 GROUP BY thang
//                 HAVING nam = '$nd_nam'";
// $result_dl_nguoidung = mysqli_query($conn, $dl_nguoidung);

// $data_nguoidung = array();
// while ($row_dl_nguoidung = mysqli_fetch_array($result_dl_nguoidung)) {
//     $data_nguoidung[] = array(
//         'thang' => "Tháng " . $row_dl_nguoidung['thang'] . "",
//         'nam' => $row_dl_nguoidung['nam'],
//         'soluong' => $row_dl_nguoidung['soluong']
//     );
// }

// $data = array(
//     'baiviet' => $data_baiviet,
//     'nguoidung' => $data_nguoidung
// );

echo json_encode($data_baiviet);
// echo json_encode($data_nguoidung);
?>
