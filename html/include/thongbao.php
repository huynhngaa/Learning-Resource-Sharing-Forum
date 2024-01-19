<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luanvan";

// Kết nối đến cơ sở dữ liệu
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $username = $user['nd_username'];
}
$results = [];

// Câu truy vấn 1
$query1 = "SELECT bv.bv_ma, bv_tieude, thoigian, kd.tt_ma, CURRENT_TIMESTAMP() 
    FROM bai_viet bv
    JOIN kiem_duyet kd ON bv.bv_ma = kd.bv_ma
    JOIN trang_thai tt ON kd.tt_ma = tt.tt_ma
    WHERE bv.nd_username = '$username'";

$result1 = $conn->query($query1);
$results = array_merge($results, $result1->fetch_all(MYSQLI_ASSOC));

// Câu truy vấn 2
$query2 = "SELECT nd_hoten,dg.nd_username, dg_diem, bv_tieude, dg.bv_ma, dg_thoigian as thoigian FROM danh_gia dg 
    JOIN nguoi_dung nd ON dg.nd_username = nd.nd_username
    JOIN bai_viet bv ON dg.bv_ma = bv.bv_ma
    WHERE bv.nd_username = '$username' and dg.nd_username !='$username'";

$result2 = $conn->query($query2);
$results = array_merge($results, $result2->fetch_all(MYSQLI_ASSOC));

// Câu truy vấn 3
$query3 = "SELECT nd_hoten, bl.nd_username ,bl_ma, bl_noidung, bv_tieude, bl.bv_ma as bv_ma,  bl_thoigian as thoigian
    FROM binh_luan bl 
    JOIN bai_viet bv ON bl.bv_ma = bv.bv_ma
    JOIN nguoi_dung nd ON nd.nd_username = bl.nd_username
    WHERE bv.nd_username = '$username' and bl.nd_username !='$username' and trangthai = 1";

$result3 = $conn->query($query3);
$results = array_merge($results, $result3->fetch_all(MYSQLI_ASSOC));

// Câu truy vấn 4
$query4 = "SELECT nd_hoten, bl_cha, bl.nd_username, bl.bl_ma, bl.bl_noidung, bl.bv_ma, bl.bl_thoigian as thoigian
FROM rep_bl r
JOIN binh_luan bl ON r.bl_con = bl.bl_ma
JOIN binh_luan bl2 ON r.bl_cha = bl2.bl_ma 
JOIN nguoi_dung nd ON bl.nd_username = nd.nd_username
WHERE bl2.nd_username = '$username' and bl.nd_username !='$username'
  ";

$result4 = $conn->query($query4);
$results = array_merge($results, $result4->fetch_all(MYSQLI_ASSOC));

// Đóng kết nối
$query5 = "SELECT trangthai,bl_noidung as noidung, bl_ma,bl.bv_ma, bv_tieude,  bl.bl_thoigian as thoigian
FROM binh_luan bl JOIN bai_viet bv 
on bl.bv_ma = bv.bv_ma
WHERE bl.nd_username = '$username' 
and trangthai !=3
";

$result5 = $conn->query($query5);
$results = array_merge($results, $result5->fetch_all(MYSQLI_ASSOC));

// Sắp xếp mảng kết quả theo thời gian giảm dần
usort($results, function ($a, $b) {
    return strtotime($b['thoigian']) - strtotime($a['thoigian']);
});




?>

<ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
  
        <a class="nav-link dropdown-toggle hide-arrow " href="javascript:void(0);" data-bs-toggle="dropdown"
            data-bs-auto-close="outside" aria-expanded="true">
            
            <i class="bx bx-bell bx-sm"></i>

            <span style="position: absolute;
        top: 25%;
        left: 65%;
        transform: translate(-50%, -50%);
        padding: 5px; /* Adjust the padding as needed */
        background-color: red; /* Red background color */
      
        border-radius: 50%; /* Make it a circle */
        width: 2px; /* Adjust the width as needed */
        height: 2px;">
        </a>
        <ul style="min-width: 22rem; overflow: hidden;" class="dropdown-menu dropdown-menu-end py-0"
            data-bs-popper="static">
            <li class="dropdown-menu-header border-bottom">
                <div class="dropdown-header d-flex align-items-center py-3">
                    <h5 class="text-body mb-0 me-auto">Thông báo</h5>
                    <a href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip"
                        data-bs-placement="top" aria-label="Mark all as read"
                        data-bs-original-title="Mark all as read"><i class="bx fs-4 bx-envelope-open"></i></a>
                </div>
            </li>
            <li class="dropdown-notifications-list scrollable-container ps">
                <ul style="max-height: 25rem; overflow: auto;" class="list-group list-group-flush">

                    <?php
                    if (!empty($results)) {


                        foreach ($results as $row) {

                            $currentTimestamp = strtotime($row['thoigian']);

                            date_default_timezone_set('Asia/Ho_Chi_Minh'); // Set the time zone to Vietnam
                    
                            $currentDateTime = date('Y-m-d H:i:s'); // Format: Year-Month-Day Hour:Minute:Second
                            $current_time = strtotime($currentDateTime);
                            $timeDifference = $current_time - $currentTimestamp;

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


                            if (isset($row['tt_ma'])) {
                                $tt = $row['tt_ma'];

                                if ($tt == 1) {
                                    echo '<a href="chitietbv.php?id= ' . $row['bv_ma'] . '">';
                                }
                               else { 
                               
                                    echo '<a href="Xem_BaiViet.php?id=' . $user['nd_username'] . '&bv='.$row['bv_ma'].'">';
                                }

                            } elseif (isset($row['dg_diem'])) {
                                echo '<a href="chitietbv.php?id= ' . $row['bv_ma'] . '">';


                            } elseif (isset($row['bl_noidung']) && isset($row['bv_tieude'])) {
                                echo '<a href="chitietbv.php?id=' . $row['bv_ma'] . '&bl_ma=' . $row['bl_ma'] . '">';
                            } elseif (isset($row['bl_cha'])) {
                                echo '<a href="chitietbv.php?id=' . $row['bv_ma'] . '&bl_ma=' . $row['bl_ma'] . '">';
                            }
                         elseif (isset($row['trangthai'])) {

                           $tt = $row['trangthai'];

                                if ($tt == 1) {
                                    echo '<a href="chitietbv.php?id=' . $row['bv_ma'] . '&bl_ma=' . $row['bl_ma'] . '">';
                                   
                                }
                               else { 
                                echo '<a href="ls-binhluan.php?id=' . $username . '">';
                               
                                }
                        }
                            
                            echo '<li class="list-group-item list-group-item-action dropdown-notifications-item">';
                            echo '<div class="d-flex">';
                            echo '<div class="flex-grow-1">';

                            if (isset($row['tt_ma'])) {
                                // Notification type 1
                                $trangthai = $row['tt_ma'];
                                if ($trangthai == 1)
                                    $trangthai = '<span style="color:	#42ba96">đã được duyệt!</span>';
                                elseif ($trangthai == 2)
                                    $trangthai = '<span style="color:	#f0ad4e">đã bị hủy!</span>';
                                elseif ($trangthai == 4)
                                    $trangthai = '<span style="color:	#d9534f">đã bị xóa!</span>';
                                echo " <h6 class='mb-1'> <span>Bài viết </span> <b>'{$row['bv_tieude']}'</b> của bạn {$trangthai}<span></span> </h6>";
                            } elseif (isset($row['dg_diem'])) {
                                // Notification type 2
                                echo " <h6 class='mb-1'> <b>{$row['nd_hoten']}</b> đã đánh giá <b>{$row['dg_diem']}</b> <i style='color:#f0ad4e' class='fa-solid fa-star'></i> cho bài viết <b>'{$row['bv_tieude']}'</b><span></span> </h6>";
                            } elseif (isset($row['bl_noidung']) && isset($row['bv_tieude'])) {
                                // Notification type 3
                                echo "<h6 class='mb-1'>  <b>{$row['nd_hoten']}</b> đã bình luận bài viết <b>'{$row['bv_tieude']}'</b> <br> <span> '{$row['bl_noidung']}'</span> </h6>";
                            } elseif (isset($row['bl_cha'])) {
                                // Notification type 4
                                echo "<h6 class='mb-1'>  <b>{$row['nd_hoten']}</b> đã trả lời bình luận của bạn: <br> <span style ='color:#5bc0de'> {$row['bl_noidung']} </span> </h6>";
                            }
                            elseif (isset($row['trangthai'])) {
                                // Notification type 1
                                $trangthai = $row['trangthai'];
                                if ($trangthai == 1)
                                    $trangthai = '<span style="color:	#42ba96">đã được duyệt!</span>';
                                elseif ($trangthai == 2)
                                    $trangthai = '<span style="color:	#f0ad4e">đã bị hủy!</span>';
                                
                                elseif ($trangthai == 4)
                                    $trangthai = '<span style="color:	#d9534f">đã bị xóa!</span>';
                                echo " <h6 class='mb-1'> <span>Bình luận của bạn về bài viết </span> <b>'{$row['bv_tieude']}'</b>  {$trangthai} <br> <br><span style ='color:#5bc0de'> {$row['noidung']} </span>   </h6>";
                            }

                            echo "<small class='text-muted'>{$timeAgo}</small>";
                            echo '</div>';
                            echo '<div class="flex-shrink-0 dropdown-notifications-actions">';
                            echo "<a href='javascript:void(0)' class='dropdown-notifications-archive'><span class='bx bx-x'></span></a>";
                            echo '</div>';
                            echo '</div>';
                            echo '</li>';
                            echo '</a>';
                        }

                    } else {
                        echo 'Không có kết quả.';
                    }



                    ?>



                </ul>

            </li>
            <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
            </div>
            <div class="ps__rail-y" style="top: 0px; right: 0px;">
                <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
            </div>
    </li>
    
</ul>
</li>
</ul>


<script>
    // Kiểm tra tham số trong URL
    const urlParams = new URLSearchParams(window.location.search);
    const commentId = urlParams.get("bl_ma");

    // Nếu có bl_ma trong URL, thực hiện focus
    if (commentId) {
        // Tìm bình luận dựa trên commentId và focus vào nó
        const commentElement = document.getElementById(`bl_ma=${commentId}`);
        if (commentElement) {
            commentElement.scrollIntoView({
                behavior: "smooth"
            });
        }
    }
</script>
<!-- 

while ($thongbao = mysqli_fetch_array($resulttb)) {
                        $trangthai = $thongbao['tt_ma'];
                        if ($trangthai ==1)   $trangthai = '<span style="color:	#42ba96">đã được duyệt!</span>';
                        elseif ($trangthai ==4)  $trangthai = '<span style="color:	#df4759">đã bị xóa!</span>';

                        
                        $currentTimestamp = strtotime($thongbao['thoigian']);
                        $current_time = strtotime($thongbao['CURRENT_TIMESTAMP()']); // Get the current Unix timestamp
                        $timeDifference =  $current_time - $currentTimestamp;  // Calculate the time difference

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
                        } -->