<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
$collection_tachtu_tieude = $database->tachtutieude;
$collection = $database->chimuctieude;

$collection_tachtu_noidung = $database->tachtunoidung;
$collection_chimuc_noidung = $database->chimucnoidung;




$servername = "localhost";
$username = "root";
$password = "";
$dbname = "luanvan";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Không kết nối: " . $conn->connect_error);
}
mysqli_set_charset($conn, 'UTF8');
include "include/head.php";

if (isset($_GET['noidung']) && !empty($_GET['noidung'])) {
    // $noidung = htmlspecialchars($_GET['noidung']);
    $noidung = $_GET['noidung'];
   
    $client = new Client("mongodb://localhost:27017");
    $mongodb = $client->selectDatabase("Test");
    $queryCollection = $mongodb->query;
    $tachtuqueryCollection = $mongodb->tachtuquery;
    $queryCollection->drop();
    $tachtuqueryCollection->drop();
    $noidungData = ['noidung' => $noidung];
    $queryCollection->insertOne($noidungData);
    $pythonScript = "C:/xampp/htdocs/VnCoreNLP-master/tachtuquery.py";
    $command = "python $pythonScript";
    exec($command, $output, $return_var);
    $collectionquery = $database->selectCollection('tachtuquery');
    $query = [];
    $document = $collectionquery->findOne($query);
    $wordForms = $document['wordForms'];
   
    $finalString = '';
    foreach ($wordForms as $wordForm) {
        $finalString .= $wordForm . ' ';
    }

    strtolower(trim($finalString));
    $truyvan_terms = explode(' ', $finalString);
   
    include "cosin.php";
    $topResults = [];
    $count = 0;
    foreach ($resultArray as $doc_id => $cosineSimilarity) {
        if ($cosineSimilarity > 0) {
            $sql = "SELECT 
            bv.*, 
            nd.*, 
            dm.*, 
            mh.mh_ten, 
            dg.dg_diem, 
            kl.*, 
            CURRENT_TIMESTAMP(), 
            COUNT(DISTINCT CASE WHEN bl.trangthai = 1 THEN bl.bl_ma END) AS slbl, 
            SUM(DISTINCT bv.bv_luotxem) AS luotxem 
            FROM bai_viet bv 
            JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username 
            JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma 
            JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma 
            JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma 
            LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma 
            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma 
            LEFT JOIN danh_gia dg ON bv.bv_ma = dg.bv_ma  WHERE 
            kd.tt_ma = 1 AND bv.bv_ma = $doc_id GROUP BY  bv.bv_ma";
if (isset($_GET['loc']) && !empty($_GET['loc'])) {
    $loc = $_GET['loc'];
    if ($loc == 'newest') {
        $sql .= ' ORDER BY bv.bv_ngaydang DESC';
    } elseif ($loc == 'rating') {
        $sql .= ' ORDER BY dg.dg_diem DESC';
    } elseif ($loc == 'comments') {
        $sql .= ' ORDER BY slbl DESC';
    } elseif ($loc == 'views') {
        $sql .= ' ORDER BY luotxem DESC';
    }
} else {
    // Mặc định sắp xếp theo ngày đăng mới nhất
    $sql .= ' ORDER BY bv.bv_ngaydang DESC';
}
            $result = mysqli_query($conn, $sql);
            while ($row = mysqli_fetch_array($result)) {
                $dm = $row['dm_ma'];
                $currentTimestamp = strtotime($row['bv_ngaydang']);
                $current_time = strtotime($row['CURRENT_TIMESTAMP()']);
                $timeDifference = $current_time - $currentTimestamp;
                if ($timeDifference < 60) {
                    $timeAgo = 'Vừa xong';
                } elseif ($timeDifference < 3600) {
                    $minutesAgo = floor($timeDifference / 60);
                    $timeAgo = $minutesAgo . ' phút';
                } elseif ($timeDifference < 86400) {
                    $hoursAgo = floor($timeDifference / 3600);
                    $timeAgo = $hoursAgo . ' giờ';
                } else {
                    $daysAgo = floor($timeDifference / 86400);
                    $timeAgo = $daysAgo . ' ngày';
                }
                $topResults[] = [
                    'cosineSimilarity' => $cosineSimilarity,
                    'row' => $row,
                    'timeAgo' => $timeAgo,
                ];
                $count++;
            }
            if ($count >= 5) {
                break;
            }
        }
    }
    usort($topResults, function ($a, $b) {
        return $b['cosineSimilarity'] <=> $a['cosineSimilarity'];
    });
    echo '<script>showLoader();</script>';
    if (!empty($topResults)) {
        foreach ($topResults as $result) {
            $cosineSimilarity = $result['cosineSimilarity'];
            $row = $result['row'];
            $timeAgo = $result['timeAgo'];
            ?>
            <div>
                <style>
                .baiviet:hover {
                    background-color: rgb(123, 125, 125, 0.16);
                }
                </style>
                <div class="col-md-12 col-12 mb-3 ">
                    <a class="baiviet" target="_blank" href="chitietbv.php?id=<?php echo $row['bv_ma'] ?>">
                        <div class="card baiviet">
                            <div class="card-body">
                                <div class="d-flex">
                                    <!-- <div class="flex-shrink-0">
                                    <img src="../assets/img/avatars/<?php echo $row['nd_hinh'] ?>" alt="google" class="me-3 rounded-circle" height="40" /> </div> -->
                                    <div class="flex-grow-1 row">
                                        <div class="col-12 mb-sm-0 mb-2">
                                            <h4 style="color:#1774af" class="mb-0  "><b> <?php echo $row['bv_tieude'] ?></b></h4> <br>
                                            <small class="text-dark">Tác giả: <b> <?php echo $row['nd_hoten'] ?></b></small> <br>
                                            <small class="text-dark">Lớp: <?php echo $row['kl_ten'] ?></small> <br>
                                            <small class="text-dark">Môn: <?php echo $row['mh_ten'] ?></small> <br>
                                            <?php
                                            $tag = "WITH RECURSIVE danh_muc_recursive AS (
                                        SELECT dm_cha, dm_con
                                        FROM danhmuc_phancap
                                        WHERE dm_con = $dm
                                        UNION ALL
                                        SELECT dp.dm_cha, dp.dm_con
                                        FROM danhmuc_phancap dp
                                        JOIN danh_muc_recursive dr ON dp.dm_con = dr.dm_cha
                                        )
                                        SELECT DISTINCT  dm_ten
                                        FROM danh_muc_recursive dmpc, danh_muc dm
                                        where dmpc.dm_cha = dm.dm_ma  
                                        ";
                                            $resulttag = mysqli_query($conn, $tag);
                                            ?>
                                            <small class="text-dark"><em> Tag: <?php echo $row['dm_ten'] ?> <?php while ($tag = mysqli_fetch_array($resulttag)) {
                                                                                                                echo ', ' . $tag['dm_ten'] . '';
                                                                                                            } ?> </em></small>
                                            <br>
                                            <small class="text-dark"><i class="fa-solid fa-calendar-days"></i> <?php echo $timeAgo ?> </small> &nbsp;
                                            <small class="text-dark"><i class="fa-solid fa-star" style="color: #FDCC0D;"></i>
                                                <?php echo  $row['bv_diemtrungbinh'] ?></small> &nbsp;
                                            <small class="text-dark"> <i class="fa-solid fa-eye"></i> <?php echo $row['bv_luotxem'] ?></small> &nbsp;
                                            <small class="text-dark"> <i class="fa-solid fa-comment"> </i> <?php echo $row['slbl'] ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        <?php
        }
    } else {
        echo "Không có kết quả";
    }
    echo '<script>hideLoader();</script>';
} else {
    // Display all posts when there is no search input
    $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, kl_ten,CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
FROM bai_viet bv
LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
WHERE kd.tt_ma = 1 -- Join the mon_hoc table
GROUP BY bv.bv_ma
ORDER BY bv_ngaydang DESC;";

    $result = mysqLi_query($conn, $sql);
    while ($row = mysqli_fetch_array($result)) {
        $dm = $row['dm_ma'];
        $currentTimestamp = strtotime($row['bv_ngaydang']);
        $current_time = strtotime($row['CURRENT_TIMESTAMP()']); // Get the current Unix timestamp
        $timeDifference =  $current_time - $currentTimestamp;  // Calculate the time difference

        if ($timeDifference < 60) {
            $timeAgo = 'Vừa xong';
        } elseif ($timeDifference < 3600) {
            $minutesAgo = floor($timeDifference / 60);
            $timeAgo = $minutesAgo . ' phút';
        } elseif ($timeDifference < 86400) {
            $hoursAgo = floor($timeDifference / 3600);
            $timeAgo = $hoursAgo . ' giờ';
        } else {
            $daysAgo = floor($timeDifference / 86400);
            $timeAgo = $daysAgo . ' ngày';
        }
        ?>

        <div>
            <style>
                .baiviet:hover {
                    background-color: rgb(123, 125, 125, 0.16);
                }
            </style>
            <div class="col-md-12 col-12 mb-3">
                <a  target="_blank" href="chitietbv.php?id=<?php echo $row['bv_ma'] ?>">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">

                                <div class="flex-shrink-0">
                                    <img src="../assets/img/avatars/<?php echo $row['nd_hinh'] ?>" alt="google" class="me-3 rounded-circle" height="40" />
                                </div>
                                <div class="flex-grow-1 row">
                                    <div class="col-9 mb-sm-0 mb-2">
                                        <h6 class="mb-0 text-dark"><?php echo $row['bv_tieude'] ?></h6>
                                        <small> <span class="badge bg-label-primary">#<?php echo $row['dm_ten'] ?></span></small>
                                        <small> <span class="badge bg-label-primary">#<?php echo $row['mh_ten'] ?></span></small>
                                        <small> <span class="badge bg-label-primary">#<?php echo $row['kl_ten'] ?></span></small>
                                        <!-- <small class="text-muted"><?php echo $row['nd_hoten'] ?></small> -->
                                        <br>
                                    </div>
                                    <div class="col-1">
                                        <small> <i class="fa-solid fa-eye"></i> <?php echo $row['bv_luotxem'] ?></small>
                                        <br>
                                        <small> <i class="fa-solid fa-comment"> </i> <?php echo $row['slbl'] ?></small>
                                    </div>
                                    <div class="col-2 text-end">
                                        <small class="">
                                            <?php echo $timeAgo ?> </small> <br>
                                        <small class="text-muted">
                                            <?php echo $row['nd_hoten'] ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
<?php   }
}
?>
<!-- Thêm mã JavaScript để quản lý loader -->
<script>
function showLoader() {
  document.getElementById("loader").style.display = "block";
}

function hideLoader() {
  document.getElementById("loader").style.display = "none";
}
</script>
