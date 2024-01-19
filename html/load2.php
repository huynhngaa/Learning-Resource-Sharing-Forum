<?php
include "include/conn.php";

if (isset($_POST['offset']) && isset($_POST['limit'])) {
  $offset = $_POST['offset'];
  $limit = $_POST['limit'];

  // Continue the SQL query from where it left off
  $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
  if ($id > 0) {
    $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, kl_ten, CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
            FROM bai_viet bv
            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
            JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username
            JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma
            JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma
            JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma
            LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma
            WHERE kd.tt_ma = 1 AND bv.dm_ma = $id
            GROUP BY bv.bv_ma
            ORDER BY bv_ngaydang DESC LIMIT $offset, $limit;";
  } else {
    $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, kl_ten, CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
            FROM bai_viet bv
            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
            JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username
            JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma
            JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma
            JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma
            LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma
            WHERE kd.tt_ma = 1
            GROUP BY bv.bv_ma
            ORDER BY bv_ngaydang DESC LIMIT $offset, $limit;";
  }

  $result = mysqli_query($conn, $sql);

  while ($row = mysqli_fetch_array($result)) {
    // Output your post HTML here
  }
}
?>
