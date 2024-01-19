<?php
include "include/conn.php";
$username = $_POST['username'];
$nguoi_dung = "SELECT * FROM nguoi_dung a, vai_tro b WHERE a.vt_ma=b.vt_ma and nd_username='$username'";
$result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

if (isset($_POST['capnhat'])) {
    $hoten = $_POST['hoten'];
    $gioitinh = $_POST['gioitinh'];
    $email = $_POST['email'];
    $sdt = $_POST['sdt'];
    $diachi = $_POST['diachi'];
    $tinhtp= $_POST['tinhtp'];
    $combo = $diachi . ' ' . $tinhtp;
    $img = $row_nguoi_dung['nd_hinh'];  // Mặc định sử dụng ảnh cũ
    $ngaysinh = $_POST['ngaysinh'];
    if (!empty($_FILES['img']['name'])) {
        // Nếu có tệp ảnh mới được tải lên
        $img = $_FILES['img']['name'];
        $img_tmp_name = $_FILES['img']['tmp_name'];
        move_uploaded_file($img_tmp_name, '../assets/img/avatars/' . $img); 
    }
    // Add a condition to check the phone number's validity
    if (isValidPhoneNumber($sdt) &&  isValidEmail($email) ) {
      
      
        $sql = "UPDATE nguoi_dung SET nd_hoten='$hoten', nd_gioitinh='$gioitinh', nd_email='$email', nd_sdt='$sdt', nd_diachi='$combo',nd_ngaysinh = '$ngaysinh',nd_hinh = '$img' WHERE nd_username='$username'";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['capnhat-hoso'] = true;
            $current_page = $_SERVER['HTTP_REFERER'];
            header("Location: $current_page");
            exit;
        } else {
            echo "Update failed: " . $conn->error;
        }
    } else {
        $_SESSION['capnhat-hoso'] = false;
            $current_page = $_SERVER['HTTP_REFERER'];
            header("Location: $current_page");
    }

    $conn->close();
}

// Function to check if the phone number is valid
function isValidPhoneNumber($phoneNumber) {
    $phoneNumber = trim($phoneNumber);
    return strlen($phoneNumber) === 10 && $phoneNumber[0] === '0' && !preg_match('/\s/', $phoneNumber);
}

function isValidEmail($email) {
    // Use a regular expression to validate the email format
    $emailPattern = '/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$/i';
    return preg_match($emailPattern, $email) && !preg_match('/\s/', $email);
}

?>
