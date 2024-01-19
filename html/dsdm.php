<?php include "include/conn.php";
include "include/head.php";

$this_bv_ma =  $_GET['dm'];
$id =  $_GET['id'];
$bai_viet = "SELECT *  , CURRENT_TIMESTAMP() FROM bai_viet bv
JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
JOIN danh_muc dm on dm.dm_ma = bv.dm_ma
JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
JOIN khoi_lop  kl on kl.kl_ma= mh.kl_ma
            WHERE bv.dm_ma='$this_bv_ma' and nd.nd_username = '$id'";

$result_bai_viet = mysqli_query($conn,$bai_viet);

?>

<body>
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

                                    <div class="col-xl-12">
                                        <div class="position-fixed top-0 end-0" style="z-index: 11; margin-top:75px">
                                            <div id="capnhat-thanhcong" class="toast hide bg-success " role="alert" aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                    <strong class="me-auto">Cập nhật thành công</strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="position-fixed top-0 end-0" style="z-index: 11; margin-top:75px">
                                            <div id="capnhat-thatbai" class="toast hide bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                    <strong class="me-auto">Cập nhật thất bại</strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="nav-align-top mb-4 ">

                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                                                    <?php
                                                    $id = $_GET['id'];
                                                    $sql = "SELECT * FROM  nguoi_dung c, vai_tro d WHERE c.vt_ma = d.vt_ma  AND c.nd_username = '$id' ";
                                                    $result = mysqli_query($conn, $sql);

                                                    $nguoidung = mysqli_fetch_array($result) ?>
                                                    <div class="row">
                                                        <div class="card col-lg-3 col-sm-12">


                                                            <div class="crd ">
                                                                <div class="card-body">
                                                                    <div class="user-avatar-section">
                                                                        <div class=" d-flex align-items-center flex-column">
                                                                            <img class="img-fluid rounded my-4" src="../assets/img/avatars/<?php echo $nguoidung['nd_hinh'] ?>" height="110" width="110" alt="User avatar">
                                                                            <div class="user-info text-center">
                                                                                <h4 class="mb-2"><?php echo $nguoidung['nd_hoten'] ?></h4>
                                                                                <span class="badge bg-label-secondary"><?php echo $nguoidung['vt_ten']  ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <h5 class="pb-2 border-bottom mb-4">Details</h5>
                                                                    <div class="info-container">
                                                                        <ul class="list-unstyled">
                                                                            <li class="mb-3">
                                                                                <span class="fw-medium me-2">Username:</span>

                                                                                <span><?php echo $nguoidung['nd_username'] ?></span>
                                                                            </li>
                                                                            <li class="mb-3">
                                                                                <span class="fw-medium me-2">Email:</span>
                                                                                <span>
                                                                                    <?php
                                                                                    if (isset($nguoidung['nd_email']) && $nguoidung['nd_email'] !== null && $nguoidung['nd_email'] !== '') {
                                                                                        echo $nguoidung['nd_email'];
                                                                                    } else {
                                                                                        echo '<i>(Chưa có dữ liệu)</i>';
                                                                                    }
                                                                                    ?>
                                                                                </span>


                                                                            </li>
                                                                            <li class="mb-3">
                                                                                <span class="fw-medium me-2">Status:</span>
                                                                                <span class="badge bg-label-success">Active</span>
                                                                            </li>
                                                                            <li class="mb-3">
                                                                                <span class="fw-medium me-2">Role:</span>
                                                                                <span><?php echo $nguoidung['vt_ten'] ?></span>
                                                                            </li>
                                                                            <li class="mb-3">
                                                                                <span class="fw-medium me-2">Tax id:</span>
                                                                                <span>
                                                                                    <?php
                                                                                    if (isset($nguoidung['nd_sdt']) && $nguoidung['nd_sdt'] !== null && $nguoidung['nd_sdt'] !== '') {
                                                                                        echo $nguoidung['nd_sdt'];
                                                                                    } else {
                                                                                        echo '<i>(Chưa có dữ liệu)</i>';
                                                                                    }
                                                                                    ?>
                                                                                </span>

                                                                            </li>


                                                                            <li class="mb-3">
                                                                                <span class="fw-medium me-2">Country:</span>
                                                                                <span>
                                                                                    <?php
                                                                                    if (isset($nguoidung['nd_diachi']) && $nguoidung['nd_diachi'] !== null && $nguoidung['nd_diachi'] !== '') {
                                                                                        echo $nguoidung['nd_diachi'];
                                                                                    } else {
                                                                                        echo '<i>(Chưa có dữ liệu)</i>';
                                                                                    }
                                                                                    ?>
                                                                                </span>

                                                                            </li>
                                                                        </ul>

                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="demo-inline-spacing ms-5 col-lg-8">
                                                        <div class="content-wrapper">
                    <!-- Content -->
                    <?php
                                  $username = $_GET['id'];
                                 
                                  $nguoi_dung = "SELECT bv.*,nd.*, dm_ten, mh_ten, kl_ten,CURRENT_TIMESTAMP(), COUNT(bl.bl_ma) AS slbl
                                  FROM bai_viet bv
                                  LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
                                  JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
                                  JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
                                  JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
                                  JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
                                  LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
                                 
                                  where bv.nd_username = '$id'
                                  GROUP BY bv.bv_ma";
                                  $result_nguoi_dung = mysqli_query($conn, $nguoi_dung);

                                  $grouped_articles = array(); // Initialize an array to group articles by day

                                  while ($row = mysqli_fetch_assoc($result_nguoi_dung)) {
                                    $danhmuc = $row['dm_ten']; // Assuming ls_thoigian contains a date/time

                                    // Extract the date part from ls_thoigian
                                
                                    $currentTimestamp = strtotime($row['bv_ngaydang']);
                                    $current_time = strtotime($row['CURRENT_TIMESTAMP()']); // Get the current Unix timestamp
                                                $timeDifference =  $current_time - $currentTimestamp ;  // Calculate the time difference
                                                  
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
                                    if (!isset($grouped_articles[$danhmuc])) {
                                      $grouped_articles[$danhmuc] = array();
                                    }

                                    $grouped_articles[$danhmuc][] = $row;
                                  }
                                  
                                  if (mysqli_num_rows($result_nguoi_dung) > 0):
                                      foreach ($grouped_articles as $danhmuc => $articles):
                                  ?>
                                          <div style="margin-top: -10px;" class="card mb-3">
                                              <div class="card-body">
                                                  <div class="col-md-12 col-12">
                                                      <h5 style="color: #ED4245;;"><?= $danhmuc ?></h5>
                                  
                                                      <?php foreach ($articles as $key => $article): ?>
                                                        <a href="Xem_BaiViet.php?id=<?php echo $id ?>&bv=<?php echo $article['bv_ma'] ?>">
                                                          <div class="d-flex">
                                                              <div class="flex-grow-1 row">
                                                                  <div class="row mb-3">
                                                                      <div class="col-10 mb-sm-0 mb-2">
                                                                          <h6 class="mb-0 text-dark"><?= $article['bv_tieude'] ?></h6>
                                                                          <small><span class="badge bg-label-primary">#<?= $article['dm_ten'] ?></span></small>
                                                                          <small><span class="badge bg-label-primary">#<?= $article['mh_ten'] ?></span></small>
                                                                          <small><span class="badge bg-label-primary">#<?= $article['kl_ten'] ?></span></small><br>
                                                                          <small><?= $article['bv_diemtrungbinh'] ?> <i style="color:#FDCC0D;" class="fa-solid fa-star"></i></small>
                                                                          &ensp;
                                                                          <small><?= $article['bv_luotxem'] ?> <i class="fa-solid fa-eye"></i></small>
                                                                          &ensp;
                                                                          <small><?= $article['slbl'] ?> <i class="fa-solid fa-comment"></i></small>
                                                                          <br>
                                                                      </div>
                                  
                                                                      <div class="col-2 text-end">
                                                                          <small><?= $timeAgo ?></small>
                                                                          <br>
                                                                      </div>
                                                                  </div>
                                  
                                                                  <!-- Add <hr> except for the last article -->
                                                                  <?php if ($key < count($articles) - 1): ?>
                                                                      <hr>
                                                                  <?php endif; ?>
                                                              </div>
                                                          </div>
                                                        </a>
                                                      <?php endforeach; ?>
                                  
                                                  </div>
                                              </div>
                                          </div>
                                  <?php
                                      endforeach;
                                  else:
                                      echo 'Không có lịch sử xem';
                                  endif;
                                  ?>
                                  


                                  
                          
                               
                                
                              </div>
                              
                            </div>
                          </div>
                        </div>
                      </div>
                      </a>

                    </div>



                </div>

                                                        </div>
                                                    </div>






                                                </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear());
                                </script>
                                , made with ❤️ by
                                <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                            </div>
                            <div>
                                <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                                <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More Themes</a>

                                <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>

                                <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank" class="footer-link me-4">Support</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var emailInput = document.getElementById("basic-icon-default-email");
            var emailErrorMessage = document.getElementById("email-error-message");

            emailInput.addEventListener("input", function() {
                var email = emailInput.value.trim();
                if (!isValidEmail(email)) {
                    emailErrorMessage.textContent = "Email không hợp lệ hoặc chứa khoảng trắng.";
                } else {
                    emailErrorMessage.textContent = "";
                }
            });

            var allowedDomains = [
                "com",
                "vn",
                "zoho.com",
                "yandex.com",
                "outlook.com",
                "protonmail.com",
                "inbox.com",
                "icloud.com",
                "mail.com",
                "gmx.com",
                "gmail.com",
                "fastmail.fm",
                "yahoo.com",
                "aim.com",
                "goowy.com",
                "hotmail.com",
                "bigstring.com"
            ];

            var emailPattern = new RegExp("^[A-Z0-9._%+-]+@[A-Z0-9.-]+(" + allowedDomains.join("|") + ")$", "i");

            function isValidEmail(email) {
                return emailPattern.test(email);
            }

        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var phoneNumberInput = document.getElementById("basic-icon-default-phone");
            var errorMessage = document.getElementById("error-message");

            phoneNumberInput.addEventListener("input", function() {
                var phoneNumber = phoneNumberInput.value.trim();
                if (phoneNumber.length !== 10 || phoneNumber[0] !== "0" || phoneNumber.includes(" ")) {
                    errorMessage.textContent = "Số điện thoại không hợp lệ";
                } else {
                    errorMessage.textContent = "";
                }
            });
        });
    </script>

    <!-- / Layout wrapper -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if (isset($_SESSION['capnhat-hoso']) && $_SESSION['capnhat-hoso']) { ?>
                // Successful profile update
                const successToast = document.getElementById("capnhat-thanhcong");
                var successToastInstance = new bootstrap.Toast(successToast);
                successToastInstance.show();
                <?php
                unset($_SESSION['capnhat-hoso']);
                ?>
            <?php } else if (isset($_SESSION['capnhat-hoso']) && !$_SESSION['capnhat-hoso']) { ?>
                // Handle when capnhat-hoso is false (e.g., show an error message)
                const errorToast = document.getElementById("capnhat-thatbai");
                var errorToastInstance = new bootstrap.Toast(errorToast);
                errorToastInstance.show();
            <?php unset($_SESSION['capnhat-hoso']);
            } ?>
        });
    </script>



    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="../assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/js/pages-account-settings-account.js"></script>
    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
</body>

</html>