

<?php
require 'vendor/autoload.php';

use MongoDB\Client;

$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
$collection_tachtu = $database->tachtunoidung;
$collection = $database->chimucnoidung;
$collection_tachtu_tieude = $database->tachtutieude;
$collection_chimuc_tieude = $database->chimuctieude;

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

  include "timkiem.php";

    $topResults = [];
    $count = 0;

    foreach ($cosineSimilarities as $doc_id => $cosineSimilarity) {
        if ($cosineSimilarity > 0) {
            $sql = "SELECT bv.*, nd.*, dm_ten, mh_ten, kl_ten, CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
            FROM bai_viet bv
            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
            JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username
            JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma
            JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma
            JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma
            LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma
            WHERE kd.tt_ma = 1 AND bv.bv_ma = $doc_id
            GROUP BY bv.bv_ma";

            $result = mysqli_query($conn, $sql);

            while ($row = mysqli_fetch_array($result)) {
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

    if (!empty($topResults)) {
        foreach ($topResults as $result) {
            $cosineSimilarity = $result['cosineSimilarity'];
            $row = $result['row'];
            $timeAgo = $result['timeAgo'];
?>

            <div class="col-md-12 col-12 mb-3">
         
                <a href="chitietbv.php?id=<?php echo $row['bv_ma'] ?>">
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



<?php
        }
    } else {
        echo "Không có kết quả";
    }
}
 else {
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
        <div class="col-md-12 col-12 mb-3">
          <a href="chitietbv.php?id=<?php echo $row['bv_ma'] ?>">
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
    <?php   }
}
?>