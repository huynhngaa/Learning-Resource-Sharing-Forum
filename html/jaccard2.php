<?php
include "include/conn.php";
require 'vendor/autoload.php';

// Kết nối đến MongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
$collection = $database->tachtunoidung;
$baivietCollection = $database->baiviet;

// Hàm tính độ đo Jaccard
function jaccardSimilarity($set1, $set2)
{
    $intersection = array_intersect($set1, $set2);
    $union = array_unique(array_merge($set1, $set2));
    // Kiểm tra nếu cả hai tập hợp đều rỗng
    if (count($union) === 0) {
        return 0; // Trả về 0 hoặc giá trị mặc định tùy vào yêu cầu của bạn
    }
    $jaccard = count($intersection) / count($union);
    return $jaccard;
    // $set1Lower = array_map('strtolower', $set1);
    // $set2Lower = array_map('strtolower', $set2);

    // $intersection = array_intersect($set1Lower, $set2Lower);
    // $union = array_unique(array_merge($set1Lower, $set2Lower));

    // $jaccard = count($intersection) / count($union);
    // return $jaccard;
    //  Chuyển đổi mảng thành chuỗi
    

    // Loại bỏ các kí tự dấu cuối từ các từ trong set1 và set2
    // $set1 = preg_replace("/[.,!?()\[\]]/", "", $set1);
    // $set2 = preg_replace("/[.,!?()\[\]]/", "", $set2);
    // $set1Lower = array_map('strtolower', $set1);
    // $set2Lower = array_map('strtolower', $set2);
    // $intersection = array_intersect($set1Lower, $set2Lower);
    // $union = array_unique(array_merge($set1Lower, $set2Lower));
    // $jaccard = count($intersection) / count($union);
    // return $jaccard;
    
}

$bv = "SELECT a.*, b.*, c.*, d.*, e.*, t.*
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                WHERE k.tt_ma is null or k.tt_ma = 3";

$result_bv = mysqli_query($conn, $bv);
if (mysqli_num_rows($result_bv) > 0)
{
    while ($row_bv = mysqli_fetch_array($result_bv))
    {
        $dotuongdong = 0;
        $category = $row_bv['dm_ma'];
        $idCondition = intval($row_bv['bv_ma']);
        echo $idCondition;

        // Lấy danh sách các ID bài viết cùng danh mục từ bảng baiviet
        $baivietIdsCursor = $baivietCollection->find(['danhmuc' => $category, 'trangthai' => 'Đã duyệt']);

        // Chuyển kết quả từ cursor về mảng các ID bài viết
        $baivietIds = [];
        foreach ($baivietIdsCursor as $doc)
        {
            $baivietIds[] = $doc['id'];
        }
      
        $record = $collection->find(['doc_id' => $idCondition]);
     
        // // Khởi tạo một mảng để lưu trữ dữ liệu dựa trên ID
        $dataByID = array();
        $dataByID2 = array();

        // // Lặp qua dữ liệu từ cơ sở dữ liệu
        foreach ($record as $document) {
            $id = $document['doc_id'];

            // Kiểm tra xem đã có dữ liệu từ ID này trong mảng chưa
            if (!isset($dataByID[$id])) {
                $dataByID[$id] = array(); // Nếu chưa có, khởi tạo một mảng rỗng cho ID này
            }

            // Thêm dòng dữ liệu vào mảng tương ứng với ID
            $dataByID[$id][] = $document;
        }

        //Khai báo mảng $word để chứa dữ liệu từ $row['wordForm']
        $words1 = array();
        $words2 = array();

        // Hiển thị dữ liệu có trong mảng $dataByID
        foreach ($dataByID as $id => $data) {
         
            foreach ($data as $row) {
                // Thêm dữ liệu $row['wordForm'] vào mảng $word
                $words1[] = $row['wordForm'];
            }

        }

        // Chuyển mảng $word thành một mảng có dạng ['a', 'b', 'c']
        $words1 = array_values(array_unique($words1));



       
                // print_r($words1);
                // echo "<hr>";
              
                $cursor = $collection->find(['doc_id' => ['$ne' => $idCondition, '$in' => $baivietIds]]);

                foreach ($cursor as $document)
                {
                    $id = $document['doc_id'];
                    // Kiểm tra xem đã có dữ liệu từ ID này trong mảng chưa
                    if (!isset($dataByID2[$id])) {
                        $dataByID2[$id] = array(); // Nếu chưa có, khởi tạo một mảng rỗng cho ID này
                    }
        
                    // Thêm dòng dữ liệu vào mảng tương ứng với ID
                    $dataByID2[$id][] = $document;
                }
                // In ra các id của mảng
                // $ids = array_keys($dataByID2);
                // print_r($ids);

                 // // Hiển thị dữ liệu có trong mảng $dataByID
                foreach ($dataByID2 as $id => $data) {
                    foreach ($data as $row) {
                        $words2[] = $row['wordForm'];
                    }

                }

                // // Chuyển mảng $word thành một mảng có dạng ['a', 'b', 'c']
                $words2 = array_values(array_unique($words2));
                // echo "<br>";
                // print_r($words2);
                // echo "<hr>";

                
                    $similarity = jaccardSimilarity($words1, $words2);
                    if ($similarity >= 0)
                    {
                        $dotuongdong = round($similarity * 100);
                        $similarRows[] = 
                        [
                            'id' => $idCondition, 
                            'tieude' => $row_bv['bv_tieude'], 
                            'content1' => $words1,
                            'id2' => $document['doc_id'],
                            'content2' => $words2,
                            'similarity' => $dotuongdong,
                        ];

                    }
                
            }

}

$sumSimilarity = [];
$countSimilarity = [];

foreach ($similarRows as $row)
{
    $id = $row['id'];
    $id2 = $row['id2'];
    $td1 = $row['tieude'];

    if (!isset($sumSimilarity[$id][$id2]))
    {
        $sumSimilarity[$id][$id2] = 0;
        $countSimilarity[$id][$id2] = 0;
    }

    $sumSimilarity[$id][$id2] += $row['similarity'];
    $countSimilarity[$id][$id2]++;
}

$averageSimilarity = [];

foreach ($sumSimilarity as $id => $idData)
{
    foreach ($idData as $id2 => $total)
    {
        $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2], 2);
        // echo "<ul><li>Bài viết có ID = <a href = 'Xem_BaiViet.php?this_bv_ma=$id'>$id</a> có thể trùng lặp với bài viết có ID = <a href = 'Xem_BaiViet.php?this_bv_ma=$id2'>$id2</a> với tỷ lệ: <b>" . $averageSimilarity[$id][$id2] . "%</b> </li></ul>";
        
    }
}

?>
<!-- <br> -->
<style>
                            .highlight {
                                background-color: yellow;
                                /* Màu nền nổi bật */
                                font-weight: bold;
                                /* Độ đậm */
                            }
                            </style>
<table border="2">
    <thead>
        <th>STT</th>

        <th style='white-space: normal'>Bài viết có nội dung được cho là trùng lặp
        </th>
        <th style='white-space: normal'>Bài viết có nội dung góc</th>
        <th>Độ tương đồng</th>

    </thead>
    <tbody>
        <?php
            $i = 0;

            foreach ($sumSimilarity as $id => $idData)
            {
                foreach ($idData as $id2 => $total)
                {
                    $bai = "SELECT * FROM bai_viet where bv_ma = $id";
                    $result_bai = mysqli_query($conn, $bai);
                    while ($row_bai = mysqli_fetch_array($result_bai))
                    {
                        $td = $row_bai['bv_tieude'];
                    }
                    $bai2 = "SELECT * FROM bai_viet where bv_ma = $id2";
                    $result_bai2 = mysqli_query($conn, $bai2);
                    while ($row_bai2 = mysqli_fetch_array($result_bai2))
                    {
                        $td2 = $row_bai2['bv_tieude'];
                    }

                    $i++;
                    $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2], 2);
                    echo '
                        <tr>
                            <td>' . $i . '</td>
                            <td style="white-space: normal"><b><a href="Xem_BaiViet.php?this_bv_ma=' . $id . '">#' . $id . '<a/> -</b> ' . $td . '</td>
                            <td style="white-space: normal"><b><a href="Xem_BaiViet.php?this_bv_ma=' . $id2 . '">#' . $id2 . '<a/> -</b> ' . $td2 . '</td>
                            <td>' . $averageSimilarity[$id][$id2] . '%</td>
                        </tr>';

                }
            }
        ?>
    </tbody>
</table>

<br>
<h4 style="text-align: center;">----------CHI TIẾT-----------</h4>

<table border="2">
    <thead>
        <th>#</th>
        <!-- <th>Mã</th> -->
        <th>Tiêu đề</th>
        <th style='white-space: normal'>Nội dung được cho là trùng
            lặp </th>
        <th>#</th>
        <th>Nội dung bài viết góc</th>
        <th>Độ tương đồng</th>

    </thead>
    <tbody>
        <?php
$i = 0;
if (!empty($similarRows))
{
    foreach ($similarRows as $row)
    {
        // $id = $row['id'];
        // $id2 = $row['id2'];
        // $nd = array();
        // $nd2 = array();

        //         $bai = "SELECT * FROM bai_viet where bv_ma = $id";
        //         $result_bai = mysqli_query($conn, $bai);
        //         while ($row_bai = mysqli_fetch_array($result_bai))
        //         {
        //             // $td = $row_bai['bv_tieude'];
        //             $nd[] = $row_bai['bv_noidung'];
        //         }
        //         $bai2 = "SELECT * FROM bai_viet where bv_ma = $id2";
        //         $result_bai2 = mysqli_query($conn, $bai2);
        //         while ($row_bai2 = mysqli_fetch_array($result_bai2))
        //         {
        //             // $td2 = $row_bai2['bv_tieude'];
        //             $nd2[] = $row_bai2['bv_noidung'];
        //         }
        
        $i++;
        $words1 = $row['content1'];
        $words2 = $row['content2'];

        // Thay thế ký tự "_" bằng khoảng trắng trong từng từ
        // foreach ($words1 as &$word) {
        //     $word = str_replace("_", " ", $word);
        // }
        // foreach ($words2 as &$word) {
        //     $word = str_replace("_", " ", $word);
        // }

        // print_r($words1);

        // Lấy các từ trùng nhau
        // $commonWords = array_intersect($words1, $words2);

        // Làm nổi bật các từ trùng nhau
    //     foreach ($commonWords as $commonWord) {
    //         $words1 = str_ireplace($commonWord, "<span class='highlight'>$commonWord</span>", $words1);
    //         $words2 = str_ireplace($commonWord, "<span class='highlight'>$commonWord</span>", $words2);
    //     }

        echo "
                <tr>
                    <td>{$row['id']}</td>
                    <td style='white-space: normal'>{$row['tieude']}</td>
                    <td style='white-space: normal'>".implode(" ", $words1)."</td>
                    <td>{$row['id2']}</td>
                    <td style='white-space: normal'>".implode(" ", $words2 )."</td>
                    <td>{$row['similarity']}%</td>
                </tr>
            ";

    }
}
?>
    </tbody>
</table>
