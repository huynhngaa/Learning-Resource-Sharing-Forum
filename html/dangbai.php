<?php
include "include/conn.php";
if (!isset($_SESSION['user'])) {
    header("Location: 404.php");
}

require 'vendor/autoload.php';

use MongoDB\Client;

if (isset($_POST['gui'])) {

    $tendangnhap = $_POST['tendangnhap'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $danhmuc_ma = $_POST['danh-muc'];

    // Thêm dữ liệu vào bảng 'bai_viet' (MySQL)
    $sql1 = "INSERT INTO bai_viet (dm_ma, nd_username, bv_tieude, bv_noidung, bv_ngaydang) 
             VALUES ($danhmuc_ma, '$tendangnhap', '$tieude', '$noidung', NOW())";
    $res1 = mysqli_query($conn, $sql1);
    $last_insert_id = mysqli_insert_id($conn);

    // Check if any files are uploaded
    if (!empty($_FILES['formFileMultiple']['name'])) {
        $targetDir = "uploads/";
        $convertedText = ""; // Initialize the variable

        // Loop through each file
        foreach ($_FILES["formFileMultiple"]["name"] as $i => $tailieu) {
            $targetFile = $targetDir . basename($_FILES["formFileMultiple"]["name"][$i]);
            $uploadOk = 1;
            $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

            // Check if file is a valid format
            if ($fileType != "pdf" && $fileType != "docx") {
                echo "Sorry, only PDF and DOCX files are allowed for file " . ($i + 1) . ".<br>";
                $uploadOk = 0;
            }

            // Check if file was uploaded without errors
            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES["formFileMultiple"]["tmp_name"][$i], $targetFile)) {
                    echo "File " . ($i + 1) . ": " . basename($_FILES["formFileMultiple"]["name"][$i]) . " has been uploaded.<br>";

                    // Call Python script to convert the file
                    $command = escapeshellcmd("python docfile.py " . $targetFile);
                    $output = shell_exec($command);
                    exec("python topdf.py $targetFile");

                    if ($output === false) {
                        echo "Error executing Python script: $command<br>";
                    } else {
                        // Read and display the converted text from the PHP file
                        $convertedTextFile = "uploads/" . pathinfo($targetFile, PATHINFO_FILENAME) . ".txt";
                        if (file_exists($convertedTextFile)) {
                            $convertedText .= file_get_contents($convertedTextFile) . "<br>"; // Append to the existing content
                        } else {
                            echo "Error: Converted text file not found for file " . ($i + 1) . ".<br>";
                        }
                    }

                    // Insert data into 'tai_lieu' table
                    $originalFileName = $tailieu;
                    $extension = pathinfo($originalFileName, PATHINFO_EXTENSION);

                    // Change the extension to ".pdf" if it's ".docx"
                    if ($extension == "docx") {
                        $pdfFileName = pathinfo($originalFileName, PATHINFO_FILENAME) . ".pdf";
                    } else {
                        // Keep the original extension
                        $pdfFileName = $originalFileName;
                    }

                    $sql2 = "INSERT INTO tai_lieu (bv_ma, tl_tentaptin, tl_kichthuoc) 
                             VALUES ($last_insert_id, '$pdfFileName', 30)";
                    $res2 = mysqli_query($conn, $sql2);
                } else {
                    echo "Sorry, there was an error uploading your file " . ($i + 1) . ".<br>";
                }
            }
        }
    }
    

    // Thêm dữ liệu vào bảng 'baiviet' (MongoDB)
    $client = new Client("mongodb://localhost:27017");
    $mongodb = $client->selectDatabase("Test");
    $baivietCollection = $mongodb->baiviet;
    // $bangbaiviet = $mongodb->baivietdata;

    $noidung = strip_tags($noidung);
    $noidung2 = preg_replace("/[.,!?`~]/", " ", $noidung);
    $noidung = str_replace('&nbsp;', ' ', $noidung);

    $baivietData = [
        'id' => $last_insert_id,
        'danhmuc' => $danhmuc_ma,
        'trangthai' => "Chờ duyệt",
        'bv_noidung' => $noidung . $convertedText,
        'bv_tieude' => $tieude,
    ];

    $baivietCollection->insertOne($baivietData);

    // Tách từ bài viết
    $pythonScript = "C:/xampp/htdocs/VnCoreNLP-master/tachtu.py";
    $pythonScript2 = "C:/xampp/htdocs/VnCoreNLP-master/tachtieude.py";
    $pythonScript3 = "C:/xampp/htdocs/VnCoreNLP-master/tachnoidung.py";

    $command = "python $pythonScript";
    $command2 = "python $pythonScript2";
    $command3 = "python $pythonScript3";
    exec($command, $output, $return_var);
    exec($command2, $output, $return_var);
    exec($command3, $output, $return_var);
    // Trả về kết quả (nếu cần)
    echo json_encode(array("output" => $output, "return_var" => $return_var));
    if ($return_var !== 0) {
        echo "Error executing command. Return code: $return_var\n";
    }
    // Kết thúc tách từ bài viết

    if ($res1 || $res2) {
        $_SESSION['dangbai'] = true;
        $_SESSION['bv'] = $last_insert_id;
        header("Location: post.php");
    }
}
?>