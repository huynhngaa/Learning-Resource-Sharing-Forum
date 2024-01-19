<?php
session_start();

require_once './includes/connect.php'; // Adjust the path if needed
require 'vendor/autoload.php'; // Ensure the correct path to the PhpSpreadsheet library

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Create a new Spreadsheet
$spreadsheet = new Spreadsheet();

// Select the default sheet
$sheet = $spreadsheet->getActiveSheet();

// Set column headers
$sheet->setCellValue('A1', 'STT');
$sheet->setCellValue('B1', 'Người quản lý');
$sheet->setCellValue('C1', 'Danh mục');
$sheet->setCellValue('D1', 'Thời gian');

// Set formatting for headers
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

$danh_muc = "SELECT DISTINCT a.nd_username, b.nd_hoten
             FROM quan_ly a
             JOIN nguoi_dung b ON a.nd_username = b.nd_username";

$result_danh_muc = mysqli_query($conn, $danh_muc);
$stt = 0;

while ($row_danh_muc = mysqli_fetch_assoc($result_danh_muc)) {
    $stt++;

    // Fetch data for each user
    $danh_muc_ql = "SELECT a.nd_username, b.nd_hoten, c.dm_ma, c.dm_ten, a.tg_phancong
                    FROM nguoi_dung b
                    JOIN quan_ly a ON a.nd_username = b.nd_username
                    JOIN danh_muc c ON c.dm_ma = a.dm_ma
                    WHERE a.nd_username = '{$row_danh_muc['nd_username']}'";

    $result_danh_muc_ql = mysqli_query($conn, $danh_muc_ql);

    while ($row_danh_muc_ql = mysqli_fetch_assoc($result_danh_muc_ql)) {
        $data[] = [
            'STT' => $stt,
            'Người quản lý' => $row_danh_muc_ql['nd_hoten'],
            'Danh mục' => $row_danh_muc_ql['dm_ten'],
            'Thời gian' => date_format(date_create($row_danh_muc_ql['tg_phancong']), "d/m/Y (H:i:s)"),
        ];
    }
}

// Dump data into the Excel file
$row = 2;

foreach ($data as $row_data) {
    $column = 'A';
    
    foreach ($row_data as $value) {
        $sheet->setCellValue($column . $row, $value);
        $column++;
    }

    $row++;
}

// Set auto width for columns based on content
foreach (range('A', 'D') as $columnID) {
    $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}

// Create a Writer object to write data to the Excel file
$writer = new Xlsx($spreadsheet);

// Set headers to indicate that the file is an Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="bang_phancong_quanly_danhmuc.xlsx"');
header('Cache-Control: max-age=0');

// Write data to the output
$writer->save('php://output');
?>
