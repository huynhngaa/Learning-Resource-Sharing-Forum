<?php
require 'vendor/autoload.php';

// Assuming you have already connected to MongoDB and selected a database and collection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
// $collection_tachtu = $database->tachtutieude;
// $collection = $database->chimuctieude;
// $collection_noidung = $database->tachtunoidung;
// $collection_chimucnoidung = $database->chimucnoidung;
$collection_tachtu = $database->tachtunoidung;
$collection = $database->chimucnoidung;
// $collectionquery = $database->tachtuquery;
include "include/conn.php";
include "include/head.php";
?>

<body>
  <style>
    body {
      margin: 0;
      overflow: hidden;
    }

    .loader-container {
      width: 100vw;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      /* background: rgba(0, 0, 0, 0.5);  */
      background-color: #f5f5f9;
    }

    .loader {
      width: 48px;
      height: 48px;
      border: 5px solid #1774af;
      ;
      border-bottom-color: transparent;
      border-radius: 50%;
      display: inline-block;
      box-sizing: border-box;
      animation: rotation 1s linear infinite;
    }

    @keyframes rotation {
      0% {
        transform: rotate(0deg);
      }

      100% {
        transform: rotate(360deg);
      }
    }
  </style>

  <div class="loader-container" id="loaderContainer">
    <span class="loader"></span>
  </div>
  <?php include "include/navbar.php" ?>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <?php include "include/rightmenu.php" ?>

      <div style="margin-top: 50px;" class="layout-page">
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <?php
                  if (isset($_SESSION['user'])) {
                    $user = $_SESSION['user'];

                    $username = $user['nd_username'];
                  }
                  $checkQuery = "SELECT dm_ma, ls.bv_ma FROM lich_su_xem ls, bai_viet bv WHERE ls.bv_ma = bv.bv_ma AND ls.nd_username = '$username'";
                  $result = mysqli_query($conn, $checkQuery);

                  $danhMucDaXem = [];
                  $bvDaXem = [];
                  while ($row = mysqli_fetch_assoc($result)) {
                    $danhMucDaXem[] = $row['dm_ma'];
                    $bvDaXem[] = $row['bv_ma'];
                  }

                  if (!empty($danhMucDaXem)) {

                    $danhMucString = implode(',', $danhMucDaXem);
                    $bvString = implode(',', $bvDaXem);
                    $sql = "SELECT bv.*, nd.*, dm.*, mh_ten, kl_ten, kd.tt_ma, COUNT(bl.bl_ma) AS slbl,
            TIMESTAMPDIFF(SECOND, bv.bv_ngaydang, CURRENT_TIMESTAMP()) AS timeDifference
            FROM bai_viet bv
            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
            JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username
            JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma
            JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma
            JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma
            LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma
            WHERE kd.tt_ma = 1 AND bv.dm_ma IN ($danhMucString) AND bv.bv_ma NOT IN ($bvString)
            GROUP BY bv.bv_ma 
            ORDER BY bv.bv_ngaydang DESC";


                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                      echo '<h5>Bài viết dành cho bạn</h5>';
                    }
                  }
                  ?>
                  <div id="postContainer">
                    <!-- Posts will be appended here -->
                  </div>
                  <style>
                    #loadMoreBtn:hover {
                      color: #12486b
                    }
                  </style>
                  <?php
                  if (isset($_GET["noidung"]) && !empty($_GET["noidung"])) {
                    echo  ' <h5 class="mt-3">Kết quả tìm kiếm: "' . $_GET["noidung"] . '"</h5>';
                    echo '<script>showLoader();</script>';
                    include "include/tachtutimkiem.php";
                    include "timkiem.php";
                  ?>

                    <div id="postContainer">
                      <?php
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

                      // Sort the top results based on cosine similarity in descending order
                      usort($topResults, function ($a, $b) {
                        return $b['cosineSimilarity'] <=> $a['cosineSimilarity'];
                      });

                      // Display the top 5 results or "Không có kết quả" if no results
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

                                        <!-- <h6 class="mb-0 text-dark"><?php echo $highlightedTitle ?></h6> -->
                                        <small> <span class="badge bg-label-primary">#<?php echo $row['dm_ten'] ?></span></small>
                                        <small> <span class="badge bg-label-primary">#<?php echo $row['mh_ten'] ?></span></small>
                                        <small> <span class="badge bg-label-primary">#<?php echo $row['kl_ten'] ?></span></small>
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
                      ?>



                    </div>
                    <script>
                      $(document).ready(function() {
                        hideLoader(); // Hide loader when the search results are loaded
                      });
                    </script>

                    <?php } else {
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
                  } ?>
                      </div>


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="layout-overlay layout-menu-toggle"></div>
    </div>



    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
      function showLoader() {
        $("#loaderContainer").css("display", "flex");
      }

      function hideLoader() {
        $("#loaderContainer").css("display", "none");
      }
    </script>
      <script>
                      $(document).ready(function() {
                        hideLoader(); // Hide loader when the search results are loaded
                      });
                    </script>
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>