<?php
session_start();

require_once './includes/connect.php'; // Điều chỉnh đường dẫn nếu cần
require 'vendor/autoload.php'; // Đảm bảo rằng bạn đã đúng đường dẫn đến thư viện TCPDF

use TCPDF as PDF;

// Tạo một đối tượng PDF
$pdf = new PDF();

// Thiết lập thông tin tệp PDF
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Danh sách bình luận');

// Thêm một trang mới
$pdf->AddPage();

// Đặt tiêu đề cho trang
$pdf->SetFont('dejavusans', 'B', 16);
$pdf->Cell(0, 10, 'DANH SÁCH BÌNH LUẬN', 0, 1, 'C');

$binh_luan = "SELECT * FROM binh_luan a 
 LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
 LEFT JOIN nguoi_dung c ON b.nd_username=c.nd_username 
 LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
 ORDER BY bl_thoigian DESC ";

$result_binh_luan = mysqli_query($conn, $binh_luan);
$i = 0;
while ($row_binh_luan = mysqli_fetch_array($result_binh_luan)) { 
    $i = $i + 1;
    $data[] = [
        $i,
        $row_binh_luan['tt_ten'],
        $row_binh_luan['bv_tieude'],
        $row_binh_luan['nd_hoten'],
        $row_binh_luan['bl_noidung'],
        date_format(date_create($row_binh_luan['bl_thoigian']), "d-m-Y (H:i:s)")
    ];
}

// Tạo bảng dữ liệu
$pdf->SetFont('dejavusans', '', 12);
$pdf->Ln(10); // Di chuyển lên trên để bắt đầu bảng dữ liệu

$header = ['STT', 'Trạng thái', 'Bài viết', 'Người BL', 'Nội dung', 'Thời gian'];
$pdf->SetFont('dejavusans', 'B', 10);

// Vẽ cột tiêu đề sử dụng MultiCell để tự động xuống dòng nếu cần
foreach ($header as $col) {
    $pdf->Cell(30, 10, $col, 1);
}

$pdf->SetFont('dejavusans', '', 10);
$pdf->Ln(); // Xuống dòng

foreach ($data as $row_data) {
    foreach ($row_data as $col) {
        $pdf->Cell(30, 10, $col, 1);
    }
    $pdf->Ln();
}

// Ghi tệp PDF ra đầu ra
$pdf->Output('danh_sach_binh_luan.pdf', 'I');
?>
