<?php


// Include MongoDB PHP driver
require 'vendor/autoload.php';

use MongoDB\Client;

if (isset($_GET['noidung'])) {
    $noidung = $_GET['noidung'];


    // Thêm dữ liệu vào bảng 'baiviet' (MongoDB)
    $client = new Client("mongodb://localhost:27017");
    $mongodb = $client->selectDatabase("Test");
    $queryCollection = $mongodb->query;
    $tachtuqueryCollection = $mongodb->tachtuquery;
    $queryCollection->drop();
    $tachtuqueryCollection->drop();
    $noidung = [
       
        'noidung' => $noidung,
    
    ];

    $queryCollection->insertOne($noidung);

    
}



$pythonScript = "C:/xampp/htdocs/VnCoreNLP-master/tachtuquery.py";

$command = "python $pythonScript";

exec($command, $output, $return_var);





?>


