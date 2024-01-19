<?php
include "include/conn.php";
if (!isset($_SESSION['user'])) {
    header("Location: 404.php");
}

if (isset($_POST['gui'])) {
    $tendangnhap = $_POST['tendangnhap'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $danhmuc_ma = $_POST['danh-muc'];
    $baiviet_id = $_POST['baiviet_id']; // Assuming you have this in the form

    // Check if files were submitted
    if (!empty($_FILES['formFileMultiple']['name'])) {
        $targetDir = "uploads/";
        $convertedText = ""; // Initialize the variable

        // Loop through each file
        for ($i = 0; $i < count($_FILES["formFileMultiple"]["name"]); $i++) {
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
                } else {
                    echo "Sorry, there was an error uploading your file " . ($i + 1) . ".<br>";
                }
            }
        }

        // Xóa dữ liệu cũ trong bảng 'tai_lieu' (MySQL)
        $sqlDelete = "DELETE FROM tai_lieu WHERE bv_ma = $baiviet_id";
        mysqli_query($conn, $sqlDelete);

        // Thêm dữ liệu mới vào bảng 'tai_lieu' cho mỗi tệp đính kèm (MySQL)
        foreach ($_FILES["formFileMultiple"]["name"] as $tailieu) {
            $sql2 = "INSERT INTO tai_lieu (bv_ma, tl_tentaptin, tl_kichthuoc) 
                     VALUES ($baiviet_id, '$tailieu', 30)";
            $res2 = mysqli_query($conn, $sql2);
        }
    } else {
        // If no new files are selected, retrieve the existing file name
        $existingFileName = isset($_POST['existingFile']) ? $_POST['existingFile'] : '';
        $tailieus[] = $existingFileName;
    }

    // Update the main content of the post
    $sql1 = "UPDATE bai_viet SET dm_ma = $danhmuc_ma, nd_username = '$tendangnhap', bv_tieude = '$tieude', bv_noidung = '$noidung' WHERE bv_ma = $baiviet_id";
    $res1 = mysqli_query($conn, $sql1);
    $sql3 = "DELETE FROM kiem_duyet WHERE bv_ma = $baiviet_id";
    $res3 = mysqli_query($conn, $sql3);
    // Tách từ bài viết (Optional: Uncomment if needed)
    /*
    $pythonScript = "C:/xampp/htdocs/VnCoreNLP-master/tachtu.py";
    $pythonScript2 = "C:/xampp/htdocs/VnCoreNLP-master/tachtieude.py";
    $pythonScript3 = "C:/xampp/htdocs/VnCoreNLP-master/tachnoidung.py";

    $command = "python $pythonScript";
    $command2 = "python $pythonScript2";
    $command3 = "python $pythonScript3";
    exec($command, $output, $return_var);
    exec($command2, $output, $return_var);
    exec($command3, $output, $return_var);
    echo json_encode(array("output" => $output, "return_var" => $return_var));
    if ($return_var !== 0) {
        echo "Error executing command. Return code: $return_var\n";
    }
    */

    if ($res1 || $res2 ) {
        $_SESSION['dangbai'] = true;
        header("Location: sua_baiviet.php?id=$tendangnhap&bv=$baiviet_id");
        exit; // Make sure to exit after sending the location header
    } 
}
?>
