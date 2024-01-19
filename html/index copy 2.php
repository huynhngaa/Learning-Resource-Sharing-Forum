<?php
require 'vendor/autoload.php';

// Assuming you have already connected to MongoDB and selected a database and collection
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");
$database = $mongoClient->Test;
// $collection_tachtu = $database->tachtutieude;
// $collection = $database->chimuctieude;
// $collection_noidung = $database->tachtunoidung;
// $collection_chimucnoidung = $database->chimucnoidung;
$collection_tachtu = $database->tachtu;
$collection = $database->chimuc;
// $collectionquery = $database->tachtuquery;
include "include/conn.php";
include "include/head.php";
?>

<body>
  <style>
    body {
      margin: 0;
    
    }

    .loader-container {
      width: 100vw;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      /* background: rgba(0, 0, 0, 0.5);  */
      /* background-color: #f5f5f9; */
      background-color:rgba(192,192,192,0.3);
      
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
                      $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, dg_diem, kl.*,CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
    FROM bai_viet bv
    LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
    JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
    JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
    JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
    JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
    LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
    left join danh_gia dg on bv.bv_ma = dg.bv_ma
            WHERE kd.tt_ma = 1 AND bv.dm_ma IN ($danhMucString) AND bv.bv_ma NOT IN ($bvString)
            GROUP BY bv.bv_ma 
            ORDER BY bv.bv_ngaydang DESC";


                    $result = mysqli_query($conn, $sql);

                    if (mysqli_num_rows($result) > 0) {
                      echo '<h5>Bài viết dành cho bạn</h5>';
                    }
                  }
                  ?>
              
                  <style>
                    #loadMoreBtn:hover {
                      color: #12486b
                    }
                  </style>
                

                    <div id="searchResult">
                     
                    <?php

if (isset($_GET['noidung']) && !empty($_GET['noidung'])) {
include "search-tm.php";

}
 
  if (isset($_GET['khoilop']) && !empty($_GET['khoilop'])) {
    $khoilop = $_GET['khoilop'];
    $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, dg_diem, kl.*,CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
    FROM bai_viet bv
    LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
    JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
    JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
    JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
    JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
    LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
    left join danh_gia dg on bv.bv_ma = dg.bv_ma
    WHERE kd.tt_ma = 1 and kl.kl_ma = $khoilop
    GROUP BY bv.bv_ma
    ORDER BY bv_ngaydang DESC;";
}

elseif (isset($_GET['monhoc']) && !empty($_GET['monhoc'])) {
  $monhoc = $_GET['monhoc'];
  $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, dg_diem, kl.*,CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
  FROM bai_viet bv
  LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
  JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
  JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
  JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
  JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
  LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
  left join danh_gia dg on bv.bv_ma = dg.bv_ma
  WHERE kd.tt_ma = 1 and mh.mh_ma = $monhoc
  GROUP BY bv.bv_ma
  ORDER BY bv_ngaydang DESC;";
}

elseif (isset($_GET['danhmuc']) && !empty($_GET['danhmuc'])) {
  $danhmuc = $_GET['danhmuc'];
  $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, dg_diem, kl.*,CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
  FROM bai_viet bv
  LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
  JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
  JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
  JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
  JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
  LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
  left join danh_gia dg on bv.bv_ma = dg.bv_ma
  WHERE kd.tt_ma = 1 and dm.dm_ma = $danhmuc
  GROUP BY bv.bv_ma
  ORDER BY bv_ngaydang DESC;";
} else { 
  $sql = "SELECT bv.*,nd.*, dm_ten, mh_ten, dg_diem, kl.*,CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
  FROM bai_viet bv
  LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
  JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
  JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
  JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
  JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
  LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
  left join danh_gia dg on bv.bv_ma = dg.bv_ma
WHERE kd.tt_ma = 1 
GROUP BY bv.bv_ma
ORDER BY bv_ngaydang DESC;";
}
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
                        <style>
                          .baiviet:hover{
                            background-color:  #F4FBFF   ;
                          }
                        </style>
                        <div class="col-md-12 col-12 mb-3">
                          <a class="baiviet" href="chitietbv.php?id=<?php echo $row['bv_ma'] ?>">
                            <div class="card baiviet">
                              <div class="card-body">
                                <div class="d-flex">

                                  <div class="flex-shrink-0">
                                    <img src="../assets/img/avatars/<?php echo $row['nd_hinh'] ?>" alt="google" class="me-3 rounded-circle" height="40" />
                                  </div>
                                  <div class="flex-grow-1 row">
                                    <div class="col-9 mb-sm-0 mb-2">
                                      <h4 style="color:#1774af" class="mb-0  "><?php echo $row['bv_tieude'] ?></h4>
                                      <small> <span class="badge bg-label-primary">#<?php echo $row['dm_ten'] ?></span></small>
                                      <small> <span class="badge bg-label-primary">#<?php echo $row['mh_ten'] ?></span></small>
                                      <small> <span class="badge bg-label-primary">#<?php echo $row['kl_ten'] ?></span></small>
                                      <!-- <small class="text-muted"><?php echo $row['nd_hoten'] ?></small> -->
                                      <br>
                                      <small><i class="fa-solid fa-star" style="color: #FDCC0D;"></i> 
                                      <?php echo isset($row['dg_diem']) ? $row['dg_diem'] : 0; ?></small> &nbsp;
                                      <small> <i class="fa-solid fa-eye"></i> <?php echo $row['bv_luotxem'] ?></small> &nbsp;
                                      <small> <i class="fa-solid fa-comment"> </i> <?php echo $row['slbl'] ?></small>
                                    </div>
                                   
                                    <div class="col-3 text-end">
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
                    <?php   }    ?>


                    </div>
                  
                 
                      <!-- </div> -->


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

<script>
    $(document).ready(function() {
        var timeout; // Biến để lưu trữ ID của timeout
        var searchInput = $("#searchInput");

        // Lấy giá trị từ truy vấn trong URL khi trang được load
        var initialKeyword = getParameterByName("noidung");
        searchInput.val(initialKeyword);

        searchInput.on("input", function() {
            showLoader();
            var keyword = $(this).val();

            // Xóa timeout hiện tại nếu có
            clearTimeout(timeout);

            // Tạo một timeout mới để trì hoãn việc gửi yêu cầu Ajax
            timeout = setTimeout(function() {
                // Cập nhật truy vấn trong URL với giá trị từ ô input
                window.history.pushState({}, '', '?noidung=' + keyword);

                // Gửi yêu cầu Ajax đến trang PHP khi giá trị trong ô input thay đổi
                $.ajax({
                    type: "GET",
                    url: "search-tm.php",
                    data: { noidung: keyword }, // Truyền giá trị noidung cho PHP
                    success: function(response) {
                        // Hiển thị kết quả tìm kiếm
                        $("#searchResult").html(response);
                        hideLoader();
                    }
                });
            }, 400); 
        });

        // Hàm để lấy giá trị từ truy vấn trong URL
        function getParameterByName(name) {
            var url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
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