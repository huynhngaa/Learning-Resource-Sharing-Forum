<?php
session_start();
include("./includes/connect.php");
// Include MongoDB PHP driver
    require '../vendor/autoload.php';
       
        print_r($_SESSION['xl']);
    foreach ($_SESSION['xl'] as $id) {
       // Cập nhật trạng thái bài viết (MongoDB)
       $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
       $database = $mongoClient->Test;
       $baivietCollection = $database->baiviet;
       
       $baivietCollection->updateOne(
           ['id' => intval($id)],
           ['$set' => ['trangthai' => "Đã duyệt"]]
       );

       // Lập chỉ mục bài viết 
       
       $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
       $database = $mongoClient->Test;
       $tachtuCollection = $database->tachtunoidung;
       $tieudeCollection = $database->tachtutieude;

       $chimuc2Collection = $database->chimucnoidung;
       $chimuctieudeCollection = $database->chimuctieude;
       $tachtu = $database->tachtu;
$chimuc = $database->chimuc;

$chimuc->drop();

$invertedIndex = [];
$cursor = $tachtu->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);
foreach ($cursor as $document) {
    $wordForm = strtolower($document['wordForm']); // Convert to lowercase
    $docId = $document['doc_id'];

    if (!isset($invertedIndex[$wordForm])) {
        $invertedIndex[$wordForm] = [];
    }

    $found = false;
    foreach ($invertedIndex[$wordForm] as &$entry) {
        if ($entry['doc_id'] == $docId) {
            $entry['count']++;
            $found = true;
            break;
        }
    }

    if (!$found) {
        $invertedIndex[$wordForm][] = [
            'doc_id' => $docId,
            'count' => 1
        ];
    }
}

foreach ($invertedIndex as $word => $docEntries) {
    $existingEntry = $chimuc->findOne(['word' => $word]);
    if ($existingEntry) {
        foreach ($docEntries as $newEntry) {
            $docId = $newEntry['doc_id'];
            $count = $newEntry['count'];

            $found = false;
            foreach ($existingEntry['doc'] as &$existingDocEntry) {
                if ($existingDocEntry['doc_id'] == $docId) {
                    $existingDocEntry['count'] += $count;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $existingEntry['doc'][] = [
                    'doc_id' => $docId,
                    'count' => $count
                ];
            }
        }
        $chimuc->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
    } else {
        $chimuc->insertOne([
            'word' => $word,
            'doc' => $docEntries
        ]);
    }
}
 // Tạo bảng chimuctieude
       $chimuctieudeCollection->drop();
       $chimuc2Collection->drop();
       $invertedIndexNoidung = [];
       $cursorNoidung = $tachtuCollection->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);

       foreach ($cursorNoidung as $document) {
           $wordForm = strtolower($document['wordForm']); // Convert to lowercase
           $docId = $document['doc_id'];

           if (!isset($invertedIndexNoidung[$wordForm])) {
               $invertedIndexNoidung[$wordForm] = [];
           }

           $found = false;
           foreach ($invertedIndexNoidung[$wordForm] as &$entry) {
               if ($entry['doc_id'] == $docId) {
                   $entry['count']++;
                   $found = true;
                   break;
               }
           }

           if (!$found) {
               $invertedIndexNoidung[$wordForm][] = [
                   'doc_id' => $docId,
                   'count' => 1
               ];
           }
       }

       foreach ($invertedIndexNoidung as $word => $docEntries) {
           $existingEntry = $chimuc2Collection->findOne(['word' => $word]);
           if ($existingEntry) {
               foreach ($docEntries as $newEntry) {
                   $docId = $newEntry['doc_id'];
                   $count = $newEntry['count'];

                   $found = false;
                   foreach ($existingEntry['doc'] as &$existingDocEntry) {
                       if ($existingDocEntry['doc_id'] == $docId) {
                           $existingDocEntry['count'] += $count;
                           $found = true;
                           break;
                       }
                   }
                   if (!$found) {
                       $existingEntry['doc'][] = [
                           'doc_id' => $docId,
                           'count' => $count
                       ];
                   }
               }
               $chimuc2Collection->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
           } else {
               $chimuc2Collection->insertOne([
                   'word' => $word,
                   'doc' => $docEntries
               ]);
           }
       }

       $invertedIndexTieude = [];
       $cursorTieude = $tieudeCollection->find([], ['projection' => ['wordForm' => 1, 'doc_id' => 1]]);

       foreach ($cursorTieude as $document) {
           $wordForm = strtolower($document['wordForm']); // Convert to lowercase
           $docId = $document['doc_id'];

           if (!isset($invertedIndexTieude[$wordForm])) {
               $invertedIndexTieude[$wordForm] = [];
           }

           $found = false;
           foreach ($invertedIndexTieude[$wordForm] as &$entry) {
               if ($entry['doc_id'] == $docId) {
                   $entry['count']++;
                   $found = true;
                   break;
               }
           }

           if (!$found) {
               $invertedIndexTieude[$wordForm][] = [
                   'doc_id' => $docId,
                   'count' => 1
               ];
           }
       }

       foreach ($invertedIndexTieude as $word => $docEntries) {
           $existingEntry = $chimuctieudeCollection->findOne(['word' => $word]);
           if ($existingEntry) {
               foreach ($docEntries as $newEntry) {
                   $docId = $newEntry['doc_id'];
                   $count = $newEntry['count'];

                   $found = false;
                   foreach ($existingEntry['doc'] as &$existingDocEntry) {
                       if ($existingDocEntry['doc_id'] == $docId) {
                           $existingDocEntry['count'] += $count;
                           $found = true;
                           break;
                       }
                   }
                   if (!$found) {
                       $existingEntry['doc'][] = [
                           'doc_id' => $docId,
                           'count' => $count
                       ];
                   }
               }
               $chimuctieudeCollection->updateOne(['word' => $word], ['$set' => ['doc' => $existingEntry['doc']]]);
           } else {
               $chimuctieudeCollection->insertOne([
                   'word' => $word,
                   'doc' => $docEntries
               ]);
           }
       }

    }
    header("Location: QL_BaiViet.php");



?>