<?php
include "include/conn.php";

// Include MongoDB PHP driver
require 'vendor/autoload.php';

use MongoDB\Client;

if (isset($_POST['gui'])) {
    $tendangnhap = $_POST['tendangnhap'];
    $tieude = $_POST['tieude'];
    $noidung = $_POST['noidung'];
    $danhmuc_ma = $_POST['danh-muc']; // Lấy giá trị của nút radio button đã chọn (dm_ma)

    // Xóa các thẻ HTML từ noidung
    $noidung = strip_tags($noidung);

    // Xử lý tệp đính kèm
    $tailieus = array(); // Mảng này sẽ lưu trữ tên tệp đính kèm
    if (!empty($_FILES['formFileMultiple']['name'])) {
        foreach ($_FILES['formFileMultiple']['name'] as $key => $filename) {
            // Xử lý và lưu trữ từng tệp vào thư mục lưu trữ
            $tmp_name = $_FILES['formFileMultiple']['tmp_name'][$key];
            $target_dir = 'uploads/';
            $target_file = $target_dir . basename($filename);
            if (move_uploaded_file($tmp_name, $target_file)) {
                // Áp dụng basename cho từng tệp riêng biệt và lưu vào mảng $tailieus
                $tailieus[] = basename($filename);
            }
        }
    }

    // Thêm dữ liệu vào bảng 'bai_viet' (MySQL)
    $sql1 = "INSERT INTO bai_viet (dm_ma, nd_username, bv_tieude, bv_noidung, bv_ngaydang) 
             VALUES ($danhmuc_ma, '$tendangnhap', '$tieude', '$noidung', NOW())";
    $res1 = mysqli_query($conn, $sql1);
    // $dm = "SELECT dm_ten from danh_muc where dm_ma =  $danhmuc_ma";
    // $res2 = mysqli_query($conn, $dm);
    //  $danhmuc = mysqli_fetch_assoc($res2)  ; 
    //  $tendm = $danhmuc['dm_ten'];
    // Lấy giá trị tự tạo của khóa chính sau khi thêm vào bảng 'bai_viet'
    $last_insert_id = mysqli_insert_id($conn);

    // Thêm dữ liệu vào bảng 'tailieu' cho mỗi tệp đính kèm (MySQL)
    foreach ($tailieus as $tailieu) {
        $sql2 = "INSERT INTO tai_lieu (bv_ma, tl_tentaptin, tl_kichthuoc) 
                 VALUES ($last_insert_id, '$tailieu', 30)";
        $res2 = mysqli_query($conn, $sql2);
    }

    // Thêm dữ liệu vào bảng 'baiviet' (MongoDB)
    $client = new Client("mongodb://localhost:27017");
    $mongodb = $client->selectDatabase("Test");
    $baivietCollection = $mongodb->baiviet;

    $baivietData = [
        'id' => $last_insert_id,
        'danhmuc' => $danhmuc_ma,
        'trangthai' => "Chờ duyệt",
        'bv_noidung' => $noidung,
        'bv_tieude' =>$tieude,
        // Add other MongoDB fields as needed
    ];

    $baivietCollection->insertOne($baivietData);

    //  // Tách từ bài viết
    //         $pythonScript = "C:/xampp/htdocs/VnCoreNLP-master/tachtu.py";
    //         $pythonScript2 = "C:/xampp/htdocs/VnCoreNLP-master/tachtieude.py";
    //         $pythonScript3 = "C:/xampp/htdocs/VnCoreNLP-master/tachcau.py";
    //         $command = "python $pythonScript";
    //         $command2 = "python $pythonScript2";
    //         $command3 = "python $pythonScript3";
    //         exec($command, $output, $return_var);
    //         exec($command2, $output, $return_var);
    //         exec($command3, $output, $return_var);
    //         // Trả về kết quả (nếu cần)
    //         echo json_encode(array("output" => $output, "return_var" => $return_var));
    //         if ($return_var !== 0) {
    //         echo "Error executing command. Return code: $return_var\n";
    //         }

    //         // Lập chỉ mục bài viết 
            
    //         $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    //         $database = $mongoClient->Test;
    //         $tachtuCollection = $database->tachtunoidung;
    //         $tieudeCollection = $database->tachtutieude;

    //         $chimuc2Collection = $database->chimucnoidung;
    //         $chimuctieudeCollection = $database->chimuctieude; // Tạo bảng chimuctieude
    //         $chimuctieudeCollection->drop();
    //         $chimuc2Collection->drop();
    //         $invertedIndexNoidung = [];
    //         $cursorNoidung = $tachtuCollection->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);

    //         foreach ($cursorNoidung as $document) {
    //             $wordForm = strtolower($document['wordForm']); // Convert to lowercase
    //             $docId = $document['doc_id'];

    //             if (!isset($invertedIndexNoidung[$wordForm])) {
    //                 $invertedIndexNoidung[$wordForm] = [];
    //             }

    //             $found = false;
    //             foreach ($invertedIndexNoidung[$wordForm] as &$entry) {
    //                 if ($entry['doc_id'] == $docId) {
    //                     $entry['count']++;
    //                     $found = true;
    //                     break;
    //                 }
    //             }

    //             if (!$found) {
    //                 $invertedIndexNoidung[$wordForm][] = [
    //                     'doc_id' => $docId,
    //                     'count' => 1
    //                 ];
    //             }
    //         }

    //         foreach ($invertedIndexNoidung as $word => $docEntries) {
    //             $existingEntry = $chimuc2Collection->findOne(['word' => $word]);
    //             if ($existingEntry) {
    //                 foreach ($docEntries as $newEntry) {
    //                     $docId = $newEntry['doc_id'];
    //                     $count = $newEntry['count'];

    //                     $found = false;
    //                     foreach ($existingEntry['doc'] as &$existingDocEntry) {
    //                         if ($existingDocEntry['doc_id'] == $docId) {
    //                             $existingDocEntry['count'] += $count;
    //                             $found = true;
    //                             break;
    //                         }
    //                     }
    //                     if (!$found) {
    //                         $existingEntry['doc'][] = [
    //                             'doc_id' => $docId,
    //                             'count' => $count
    //                         ];
    //                     }
    //                 }
    //                 $chimuc2Collection->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
    //             } else {
    //                 $chimuc2Collection->insertOne([
    //                     'word' => $word,
    //                     'doc' => $docEntries
    //                 ]);
    //             }
    //         }

    //         $invertedIndexTieude = [];
    //         $cursorTieude = $tieudeCollection->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);

    //         foreach ($cursorTieude as $document) {
    //             $wordForm = strtolower($document['wordForm']); // Convert to lowercase
    //             $docId = $document['doc_id'];

    //             if (!isset($invertedIndexTieude[$wordForm])) {
    //                 $invertedIndexTieude[$wordForm] = [];
    //             }

    //             $found = false;
    //             foreach ($invertedIndexTieude[$wordForm] as &$entry) {
    //                 if ($entry['doc_id'] == $docId) {
    //                     $entry['count']++;
    //                     $found = true;
    //                     break;
    //                 }
    //             }

    //             if (!$found) {
    //                 $invertedIndexTieude[$wordForm][] = [
    //                     'doc_id' => $docId,
    //                     'count' => 1
    //                 ];
    //             }
    //         }

    //         foreach ($invertedIndexTieude as $word => $docEntries) {
    //             $existingEntry = $chimuctieudeCollection->findOne(['word' => $word]);
    //             if ($existingEntry) {
    //                 foreach ($docEntries as $newEntry) {
    //                     $docId = $newEntry['doc_id'];
    //                     $count = $newEntry['count'];

    //                     $found = false;
    //                     foreach ($existingEntry['doc'] as &$existingDocEntry) {
    //                         if ($existingDocEntry['doc_id'] == $docId) {
    //                             $existingDocEntry['count'] += $count;
    //                             $found = true;
    //                             break;
    //                         }
    //                     }
    //                     if (!$found) {
    //                         $existingEntry['doc'][] = [
    //                             'doc_id' => $docId,
    //                             'count' => $count
    //                         ];
    //                     }
    //                 }
    //                 $chimuctieudeCollection->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
    //             } else {
    //                 $chimuctieudeCollection->insertOne([
    //                     'word' => $word,
    //                     'doc' => $docEntries
    //                 ]);
    //             }
    //         }

    //         // Kết thúc lập chỉ mục bài viết vừa đăng



    // // Tách câu bài viết

    $pythonScript2 = "C:/xampp/htdocs/VnCoreNLP-master/tachcau.py";

    $command2 = "python $pythonScript2";

    exec($command2, $output, $return_var);
    // Trả về kết quả (nếu cần)
    echo json_encode(array("output" => $output, "return_var" => $return_var));
    if ($return_var !== 0) {
    echo "Error executing command. Return code: $return_var\n";
    }

    if ($res1 || $res2) {
        $_SESSION['dangbai'] = true;
        header("Location: post.php");
    }


    
}
?>
