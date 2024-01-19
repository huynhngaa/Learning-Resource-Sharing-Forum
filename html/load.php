<?php
include "include/conn.php";

if (isset($_POST['offset']) && isset($_POST['limit'])) {
    
    $offset = $_POST['offset'];
    $limit = $_POST['limit'];
    if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $username = $user['nd_username'];
    }
    $checkQuery = "SELECT dm_ma, ls.bv_ma FROM lich_su_xem ls, bai_viet bv WHERE ls.bv_ma = bv.bv_ma AND ls.nd_username = '$username'";
    $result = mysqli_query($conn, $checkQuery);

    $danhMucDaXem = [];
    $bvDaXem = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $danhMucDaXem[] = $row['dm_ma'];
        $bvDaXem[] = $row['bv_ma'];
    }

    if (!empty($danhMucDaXem)) {
        
        $danhMucString = implode(',', $danhMucDaXem);
        $bvString = implode(',', $bvDaXem);
        $sql = "SELECT bv.*, nd.*, dm.*, mh_ten, kl_ten, kd.tt_ma, COUNT(bl.bl_ma) AS slbl,
                TIMESTAMPDIFF(SECOND, bv.bv_ngaydang, CURRENT_TIMESTAMP()) AS timeDifference
                FROM bai_viet bv
                LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
                JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username
                JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma
                JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma
                JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma
                LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma
                WHERE kd.tt_ma = 1 AND bv.dm_ma IN ($danhMucString) AND bv.bv_ma NOT IN ($bvString)
                GROUP BY bv.bv_ma 
                ORDER BY bv.bv_ngaydang DESC
                LIMIT $offset, $limit;";

        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_array($result)) {
            $timeDifference = $row['timeDifference'];

            if ($timeDifference < 60) {
                $timeAgo = 'Vừa xong';
            } elseif ($timeDifference < 3600) {
                $minutesAgo = floor($timeDifference / 60);
                $timeAgo = $minutesAgo . ' phút';
            } elseif ($timeDifference < 86400) {
                $hoursAgo = floor($timeDifference / 3600);
                $timeAgo = $hoursAgo . ' giờ';
            } else {
                $daysAgo = floor($timeDifference / 86400);
                $timeAgo = $daysAgo . ' ngày';
            }
            // Output your post HTML here as in your original code
            echo '<div class="col-md-12 col-12 mb-3 ">';
            echo '  <a href="chitietbv.php?id=' . $row["bv_ma"] . '">';
            echo '   <div class="card">';
            echo '      <div class="card-body">';
            echo '        <div class="d-flex">';
            echo '          <div class="flex-shrink-0">';
            echo '            <img src="../assets/img/avatars/' . $row["nd_hinh"] . '" alt="google" class="me-3 rounded-circle" height="40" />';
            echo '          </div>';
            echo '          <div class="flex-grow-1 row">';
            echo '            <div class="col-9 mb-sm-0 mb-2">';
            echo '              <h6 class="mb-0 text-dark">' . $row["bv_tieude"] . '</h6>';
            echo '              <small> <span class="badge bg-label-primary">#' . $row["dm_ten"] . '</span></small>';
            echo '              <small> <span class="badge bg-label-primary">#' . $row["mh_ten"] . '</span></small>';
            echo '              <small> <span class="badge bg-label-primary">#' . $row["kl_ten"] . '</span></small>';
            echo '              <!-- <small class="text-muted">' . $row["nd_hoten"] . '</small> -->';
            echo '              <br>';
            echo '            </div>';
            echo '            <div class="col-1">';
            echo '              <small> <i class="fa-solid fa-eye"></i> ' . $row["bv_luotxem"] . '</small>';
            echo '              <br>';
            echo '              <small> <i class="fa-solid fa-comment"> </i> ' . $row["slbl"] . '</small>';
            echo '            </div>';
            echo '            <div class="col-2 text-end">';
            echo '              <small class="">';
            echo '                ' . $timeAgo . ' </small> <br>';
            echo '              <small class="text-muted">';
            echo '                ' . $row['nd_hoten'] . '</small>';
            echo '            </div>';
            echo '          </div>';
            echo '        </div>';
            echo '      </div>';
            echo '    </div>';
            echo '  </a>';
            echo '</div>';
            
        }
    }
}
?>
