<?php
 require_once './includes/connect.php'; // Điều chỉnh đường dẫn nếu cần
require 'vendor/autoload.php'; // Đường dẫn đến autoload.php của PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

include("./includes/connect.php");
session_start();

// if(!isset($_SESSION['Admin'])){
//     echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
//     header("Refresh: 0;url=login.php");  
// }else{}

// Kiểm tra xem có tệp đã tải lên không
if (!empty($_FILES['file']['name'])) {
    // Đường dẫn đến thư mục lưu trữ tệp Excel tải lên
    $uploadDir = 'uploads/';
    $uploadedFile = $uploadDir . basename($_FILES['file']['name']);
    
    // Kiểm tra phần mở rộng của tệp
    $fileExtension = pathinfo($uploadedFile, PATHINFO_EXTENSION);
    if (in_array(strtolower($fileExtension), ['xlsx', 'xls'])) {
        // Di chuyển tệp Excel vào thư mục lưu trữ
        if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadedFile)) {
            
            // Đọc tệp Excel
            $spreadsheet = IOFactory::load($uploadedFile);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Lặp qua dữ liệu từ tệp Excel và thêm vào cơ sở dữ liệu
            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = $row->toArray();
            
                // Lấy dữ liệu từ cột tương ứng trong tệp Excel
                $name = $rowData[0]; // Cột tên sinh viên
                $email = $rowData[1]; // Cột email
            
                // Tạo username và mật khẩu tương ứng
                $username = strtolower(str_replace(' ', '', $name)); // Tạo username từ tên
                $password = password_hash($username, PASSWORD_DEFAULT); // Tạo mật khẩu từ username
            
                // Thêm dữ liệu vào cơ sở dữ liệu
                $sql = "INSERT INTO nguoi_dung (nd_hoten, nd_email, username, nd_matkhau) VALUES ('$name', '$email', '$username', '$password')";
                
                if ($conn->query($sql) === TRUE) {
                    echo "Thêm thành công: $name\n";
                } else {
                    echo "Lỗi khi thêm dữ liệu: " . $conn->error . "\n";
                }
            }
            
            // Đóng kết nối cơ sở dữ liệu
            $conn->close();
            
            // Xóa tệp Excel sau khi xử lý
            unlink($uploadedFile);
        } else {
            echo "Lỗi khi di chuyển tệp.";
        }
    } else {
        echo "Không hỗ trợ loại tệp này. Chỉ chấp nhận các tệp Excel (xlsx, xls).";
    }
} else {
    echo "Không có tệp nào được tải lên.";
}
?>
