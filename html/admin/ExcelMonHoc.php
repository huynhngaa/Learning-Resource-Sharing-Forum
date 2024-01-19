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
$sheet->setCellValue('B1', 'Mã môn');
$sheet->setCellValue('C1', 'Tên môn');
$sheet->setCellValue('D1', 'Khối lớp');


// Thiết lập định dạng cho tiêu đề
$sheet->getStyle('A1:D1')->applyFromArray([
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
$mon_hoc = "SELECT a.* ,kl_ten
FROM mon_hoc a,  khoi_lop b 
where a.kl_ma = b.kl_ma 
order by kl_ma ";

$result_mon_hoc = mysqli_query($conn,$mon_hoc);
$i = 0;
while ($row_mon_hoc = mysqli_fetch_array($result_mon_hoc)) { 
    $i= $i+1;
    $data[] = array(
        "stt" => $i,
        "ma" => $row_mon_hoc["mh_ma"],
        "tenmon" => $row_mon_hoc["mh_ten"],
        "khoilop" => $row_mon_hoc["kl_ten"]
       
        
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
foreach (range('A', 'D') as $columnID) {
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