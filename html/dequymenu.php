<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luanvan";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Không kết nối: " . $conn->connect_error);
}

mysqli_set_charset($conn, 'UTF8');
session_start();

$data = array(); // Initialize an empty array to store the data.

$sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
FROM danh_muc d
LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con;
";
$result = mysqli_query($conn, $sql);

while ($monhoc = mysqli_fetch_array($result)) {
    // Create an associative array for each row and add it to the $data array.
    $data[] = array(
        "id" => $monhoc['dm_ma'],
        "name" => $monhoc['dm_ten'],
        "parent" => $monhoc['dm_cha']
    );
}



function dequy($data, $parent = 0, $text = "--") {
    foreach ($data as $k => $value) {
        if ($value['parent'] == $parent) {
            echo $text . $value['name'] . "<br  />";
            $id = $value['id'];
            unset($data[$k]);
            dequy($data, $id, $text . "--");
        }
    }
}

dequy($data);

$conn->close();
?>
