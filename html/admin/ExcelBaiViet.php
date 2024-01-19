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
$sheet->setCellValue('C1', 'Tiêu đề');
$sheet->setCellValue('D1', 'Ngày đăng');
$sheet->setCellValue('E1', 'Tác giả');
// $sheet->setCellValue('F1', 'Nội dung');

// Thiết lập định dạng cho tiêu đề
$sheet->getStyle('A1:E1')->applyFromArray([
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
$user_name = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";
// Kiểm tra quyền truy cập
// Danh sách các trang quản lý của admin
$adminPages = array("QL_DanhMuc.php", "QL_KhoiLop.php", "QL_MonHoc.php", "QL_NguoiDung.php", "QL_BinhLuan.php", "PCQL_DanhMuc.php");

// Lấy đường dẫn hiện tại của người dùng
$currentPage = basename($_SERVER['SCRIPT_FILENAME']);
if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
    header("Refresh: 0;url=error.php");  
    exit;
}
if($_SESSION['vaitro'] == 'Super Admin'){
    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
    ORDER BY bv_ngaydang DESC ";
    
}elseif($_SESSION['vaitro'] == 'Admin'){
    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
    LEFT JOIN quan_ly f ON f.dm_ma = b.dm_ma
    where f.nd_username = '$user_name'
    ORDER BY bv_ngaydang DESC ";
    
}

$result_bai_viet = mysqli_query($conn, $bai_viet);
$i = 0;
while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) { 
    $i= $i+1;
    if ($row_bai_viet['tt_ma'] == 1 || $row_bai_viet['tt_ma'] == 2 || $row_bai_viet['tt_ma'] == 4) {
        $row_bai_viet['tt_ten'] =  $row_bai_viet['tt_ten'];
    } else {
        $row_bai_viet['tt_ten'] = "Chờ duyệt";
    }
    
    $data[] = array(
        "stt" => $i,
        "trangthai" => $row_bai_viet["tt_ten"],
        "tieude" => $row_bai_viet["bv_tieude"],
        "thoigian" => date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"),
        "username" => $row_bai_viet["nd_hoten"],
        // "noidung" => strip_tags($row_bai_viet["bv_noidung"])
       
        
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
foreach (range('A', 'E') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Tạo một đối tượng Writer để ghi dữ liệu ra tệp Excel
$writer = new Xlsx($spreadsheet);

// Đặt header để trình duyệt hiểu tệp là một tệp Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="danh_sach_bai_viet.xlsx"');
header('Cache-Control: max-age=0');

// Ghi dữ liệu ra đầu ra
$writer->save('php://output');

?>