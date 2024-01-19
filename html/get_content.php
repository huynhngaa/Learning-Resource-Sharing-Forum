<?php 
include "include/conn.php";
$record_per_page = 5;
$page = isset($_POST["page"]) ? $_POST["page"] : 1;
$start_from = ($page - 1) * $record_per_page;
$query = "SELECT nd_hoten, bv.nd_username, bl_noidung, nd_hinh, bl.bv_ma, bv_tieude, bl_thoigian FROM binh_luan bl, nguoi_dung nd, bai_viet bv WHERE bv.bv_ma = bl.bv_ma AND nd.nd_username = bv.nd_username LIMIT $start_from, $record_per_page";
$result = mysqli_query($conn, $query);
$output = '<table class="table table-bordered"><tr><th width="50%">Name</th><th width="50%">Phone</th></tr>';

while ($row = mysqli_fetch_array($result)) {
    $output .= '<tr><td>' . $row["bv_tieude"] . '</td><td>' . $row["bv_tieude"] . '</td></tr>';
}

$output .= '</table><br /><br />';
echo $output;

?>