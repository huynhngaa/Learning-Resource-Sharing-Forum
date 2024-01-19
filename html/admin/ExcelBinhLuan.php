<?php
 session_start();

 require_once './includes/connect.php'; // Điều chỉnh đường dẫn nếu cần
require 'vendor/autoload.php'; // Đảm bảo rằng bạn đã đúng đường dẫn đến thư viện PhpSpreadsheet

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Tạo một Spreadsheet mới
$spreadsheet = new Spreadsheet();

// Chọn trang tính mặc định (sheet)
$sheet = $spreadsheet->getActiveSheet();

// Đặt tiêu đề cho các cột
$sheet->setCellValue('A1', 'STT');
$sheet->setCellValue('B1', 'Trạng thái');
$sheet->setCellValue('C1', 'Bài viết');
$sheet->setCellValue('D1', 'Người bình luận');
$sheet->setCellValue('E1', 'Nội dung');
$sheet->setCellValue('F1', 'Thời gian');

// Thiết lập định dạng cho tiêu đề
$sheet->getStyle('A1:F1')->applyFromArray([
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'FFFF00'], 
    ],
]);

$binh_luan = "SELECT * FROM binh_luan a 
 LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
 LEFT JOIN nguoi_dung c ON b.nd_username=c.nd_username 
 LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
 ORDER BY bl_thoigian DESC ";

$result_binh_luan = mysqli_query($conn, $binh_luan);
$i = 0;
while ($row_binh_luan = mysqli_fetch_array($result_binh_luan)) { 
    $i= $i+1;
    $data[] = array(
        "stt" => $i,
        "trangthai" => $row_binh_luan["tt_ten"],
        "baiviet" => $row_binh_luan["bv_tieude"],
        "username" => $row_binh_luan["nd_hoten"],
        "noidung" => $row_binh_luan["bl_noidung"],
        "thoigian" => date_format(date_create($row_binh_luan['bl_thoigian']), "d-m-Y (H:i:s)")
        
        );
}
// print_r($data);
// Đổ dữ liệu vào tệp Excel
$row = 2; // Dòng bắt đầu của dữ liệu
foreach ($data as $row_data) {
    $column = 'A';
    foreach ($row_data as $value) {
        $sheet->setCellValue($column . $row, $value);
        $column++;
    }
    $row++;
}

// Thiết lập tự động điều chỉnh chiều rộng của cột dựa trên nội dung
foreach (range('A', 'F') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Tạo một đối tượng Writer để ghi dữ liệu ra tệp Excel
$writer = new Xlsx($spreadsheet);

// Đặt header để trình duyệt hiểu tệp là một tệp Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="danh_sach_binh_luan.xlsx"');
header('Cache-Control: max-age=0');

// Ghi dữ liệu ra đầu ra
$writer->save('php://output');

?>