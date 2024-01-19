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

// Mảng để lưu kết quả
$results = [];

// Câu truy vấn 1
$query1 = "SELECT bv.bv_ma, bv_tieude, thoigian, kd.tt_ma, CURRENT_TIMESTAMP() 
    FROM bai_viet bv
    JOIN kiem_duyet kd ON bv.bv_ma = kd.bv_ma
    JOIN trang_thai tt ON kd.tt_ma = tt.tt_ma
    WHERE bv.nd_username = 'nga'";

$result1 = $conn->query($query1);
$results = array_merge($results, $result1->fetch_all(MYSQLI_ASSOC));

// Câu truy vấn 2
$query2 = "SELECT nd_hoten,dg.nd_username, dg_diem, bv_tieude, dg.bv_ma, dg_thoigian as thoigian FROM danh_gia dg 
    JOIN nguoi_dung nd ON dg.nd_username = nd.nd_username
    JOIN bai_viet bv ON dg.bv_ma = bv.bv_ma
    WHERE bv.nd_username = 'nga'";

$result2 = $conn->query($query2);
$results = array_merge($results, $result2->fetch_all(MYSQLI_ASSOC));

// Câu truy vấn 3
$query3 = "SELECT nd_hoten, bl.nd_username , bl_noidung, bv_tieude,bl.bv_ma, bl_thoigian as thoigian
    FROM binh_luan bl 
    JOIN bai_viet bv ON bl.bv_ma = bv.bv_ma
    JOIN nguoi_dung nd ON nd.nd_username = bl.nd_username
    WHERE bv.nd_username = 'nga' and trangthai = 1";

$result3 = $conn->query($query3);
$results = array_merge($results, $result3->fetch_all(MYSQLI_ASSOC));

// Câu truy vấn 4
$query4 = "SELECT nd_hoten,bl_cha, bl.nd_username , bl_noidung,bl.bv_ma,bl_thoigian as thoigian
    FROM rep_bl rl  
    JOIN binh_luan bl ON rl.bl_con = bl.bl_ma
    JOIN nguoi_dung nd on bl.nd_username = nd.nd_username
    WHERE bl.nd_username = 'nga'";

$result4 = $conn->query($query4);
$results = array_merge($results, $result4->fetch_all(MYSQLI_ASSOC));

// Đóng kết nối
$conn->close();

// Sắp xếp mảng kết quả theo thời gian giảm dần
usort($results, function($a, $b) {
    return strtotime($b['thoigian']) - strtotime($a['thoigian']);
});

// In kết quả
echo '<div>';
if (!empty($results)) {
    echo '<ul>';
    foreach ($results as $row) {
        echo '<li>';
        // Hiển thị thông tin tùy thuộc vào loại câu truy vấn
        
        echo "<strong>Thời gian:</strong> {$row['thoigian']}, ";
        if (isset($row['tt_ma'])) {
            echo "<strong>Trạng thái:</strong> {$row['tt_ma']}, ";
            echo "<strong>Bài viết:</strong> {$row['bv_tieude']}, ";
        } elseif (isset($row['dg_diem'])) {
            echo "<strong>Bài viết:</strong> {$row['bv_tieude']}, ";
            echo "<strong>Người đánh giá:</strong> {$row['nd_hoten']}, ";
            echo "<strong>Điểm đánh giá:</strong> {$row['dg_diem']}, ";
            echo "<strong>Bài viết:</strong> {$row['bv_tieude']}, ";
            echo "<strong>Thời gian đánh giá:</strong> {$row['thoigian']}";
        } elseif (isset($row['bl_noidung']) && isset($row['bv_tieude'])) {
            echo "<strong>Bài viết:</strong> {$row['bv_tieude']}, ";
            echo "<strong>Người bình luận:</strong> {$row['nd_hoten']}, ";
            echo "<strong>Nội dung bình luận:</strong> {$row['bl_noidung']}, ";
           
            echo "<strong>Thời gian bình luận:</strong> {$row['thoigian']}";
        } elseif (isset($row['bl_cha'])) {
            echo "<strong>Người trả lời:</strong> {$row['nd_hoten']}, ";
            echo "<strong>Nội dung bình luận:</strong> {$row['bl_noidung']}, ";
            echo "<strong>Thời gian trả lời:</strong> {$row['thoigian']}";
        }
        echo '</li>';
    }
    echo '</ul>';
}
echo '</div>';
?>
