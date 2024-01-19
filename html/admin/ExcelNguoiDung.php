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
$sheet->setCellValue('B1', 'Username');
$sheet->setCellValue('C1', 'Họ tên');
$sheet->setCellValue('D1', 'Giới tính');
$sheet->setCellValue('E1', 'Ngày sinh');
$sheet->setCellValue('F1', 'Email');
$sheet->setCellValue('G1', 'Di động');
$sheet->setCellValue('H1', 'Địa chỉ');

// Thiết lập định dạng cho tiêu đề
$sheet->getStyle('A1:H1')->applyFromArray([
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

$vt = $_GET['vt'];
$vai_tro = "SELECT * FROM vai_tro WHERE vt_ma='$vt'";
$result_vai_tro = mysqli_query($conn,$vai_tro);
$row_vai_tro = mysqli_fetch_assoc($result_vai_tro);

$nguoi_dung = "SELECT * FROM nguoi_dung WHERE vt_ma='$vt' order by nd_ngaytao DESC ";

$result_nguoi_dung = mysqli_query($conn,$nguoi_dung);

$i = 0;
while ($row_nguoi_dung = mysqli_fetch_array($result_nguoi_dung)) { 
    $i= $i+1;
    if($row_nguoi_dung['nd_ngaysinh'] == '0000-00-00'){
        $row_nguoi_dung['nd_ngaysinh'] = "(Chưa có dữ liệu)";
       
    }else{
        $row_nguoi_dung['nd_ngaysinh'] = date_format(date_create($row_nguoi_dung['nd_ngaysinh']), "d-m-Y");
    }
    if($row_nguoi_dung['nd_email'] == ''){
        $row_nguoi_dung['nd_email'] = "(Chưa có dữ liệu)";
    }
    if($row_nguoi_dung['nd_sdt'] == ''){
        $row_nguoi_dung['nd_sdt'] = "(Chưa có dữ liệu)";
    }
    if($row_nguoi_dung['nd_diachi'] == ''){
        $row_nguoi_dung['nd_diachi'] = "(Chưa có dữ liệu)";
    }

    $data[] = array(
        "stt" => $i,
        "username" => $row_nguoi_dung["nd_username"],
        "hoten" => $row_nguoi_dung["nd_hoten"],
        "gioitinh" => $row_nguoi_dung["nd_gioitinh"],
        "ngaysinh" => $row_nguoi_dung['nd_ngaysinh'],
        "email" => $row_nguoi_dung["nd_email"],
        "sdt" => $row_nguoi_dung["nd_sdt"],
        "diachi" => $row_nguoi_dung["nd_diachi"]
      
        
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
foreach (range('A', 'H') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Tạo một đối tượng Writer để ghi dữ liệu ra tệp Excel
$writer = new Xlsx($spreadsheet);

// Đặt header để trình duyệt hiểu tệp là một tệp Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
// Xác định tên tệp tải về dựa trên dữ liệu từ cơ sở dữ liệu
$fileName = 'danh_sach_' . $row_vai_tro['vt_ten'] . '.xlsx';

// Đặt header cho tệp tải về
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

// Ghi dữ liệu ra đầu ra
$writer->save('php://output');

?>