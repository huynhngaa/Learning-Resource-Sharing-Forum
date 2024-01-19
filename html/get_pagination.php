<?php  
include "include/conn.php";
 $record_per_page = 5;
 $page_query = "SELECT nd_hoten, bv.nd_username, bl_noidung, nd_hinh, bl.bv_ma, bv_tieude, bl_thoigian FROM binh_luan bl, nguoi_dung nd, bai_viet bv WHERE bv.bv_ma = bl.bv_ma AND nd.nd_username = bv.nd_username";
 $page_result = mysqli_query($conn, $page_query);
 $total_records = mysqli_num_rows($page_result);
 $total_pages = ceil($total_records / $record_per_page);
 $output = '';
 
 for ($i = 1; $i <= $total_pages; $i++) {
     $output .= '<span class="pagination_link" style="cursor:pointer; padding:6px; border:1px solid #ccc;" id="' . $i . '">' . $i . '</span>';
 }
 
 echo $output;
 
 ?>  