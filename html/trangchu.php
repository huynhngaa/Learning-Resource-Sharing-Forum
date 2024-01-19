<?php include "include/conn.php";
include "include/head.php";
$id = $_GET['id'];

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
                                        <div class="nav-align-top mb-4 mt-3">

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
                                                            <div class="list-group list-group-horizontal-md text-md-center">

                                                                <a class="list-group-item list-group-item-action active" id="home-list-item" data-bs-toggle="list" href="#horizontal-home"><i class="fa-solid fa-house"></i> Trang Chủ</a>
                                                                <a class="list-group-item list-group-item-action" id="profile-list-item" data-bs-toggle="list" href="#horizontal-profile"><i class="fa-solid fa-image-portrait"></i> Hồ Sơ</a>

                                                                <a class="list-group-item list-group-item-action" id="hoso-list-item" data-bs-toggle="list" href="#horizontal-hoso"><i class="fa-solid fa-newspaper"></i> Bài Viết</a>
                                                                <?php
                                                                $id = $_GET['id'];
                                                                if (isset($_SESSION['user'])) {
                                                                    $user = $_SESSION['user'];
                                                                    if ($user['nd_username'] == $id) { ?>
                                                                        <a class="list-group-item list-group-item-action" id="messages-list-item" data-bs-toggle="list" href="#horizontal-hoatdong"><i class="fa-solid fa-clock-rotate-left"></i> Nhật Ký Hoạt Động</a>

                                                                <?php }
                                                                } ?>
                                                            </div>
                                                            <div class="tab-content px-0 mt-0">
                                                                <div class="tab-pane fade" id="horizontal-hoso">

                                                                    <?php include "include/baiviet.php" ?>
                                                                </div>
                                                                <div class="tab-pane fade" id="horizontal-profile" role="tabpanel">
                                                                    <?php include "include/hoso.php" ?>
                                                                </div>


                                                                <div class="tab-pane fade active show" id="horizontal-home">
                                                                    <h4>Tổng quan</h4>
                                                                    <div class="row">
                                                                        <div class="col-4">
                                                                            <div class="card mb-4">
                                                                                <div class="card-body">
                                                                                    <p>Bài viết</p>
                                                                                    <?php
                                                                                    $sql = "SELECT count(*) as sl from bai_viet where nd_username = '$username'";
                                                                                    $result = mysqLi_query($conn, $sql);
                                                                                    $baiviet = mysqli_fetch_assoc($result)
                                                                                    ?>
                                                                                    <h4> <?php echo  $baiviet['sl'] ?></h4>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-4">
                                                                            <div class="card mb-4 ms-3">
                                                                                <div class="card-body">
                                                                                    <p>Bình luận</p>
                                                                                    <?php
                                                                                    $sql = "SELECT count(*) as slbl from binh_luan where nd_username = '$username'";
                                                                                    $result = mysqLi_query($conn, $sql);
                                                                                    $binhluan = mysqli_fetch_assoc($result)
                                                                                    ?>
                                                                                    <h4> <?php echo   $binhluan['slbl'] ?></h4>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-4">
                                                                            <div class="card mb-4 ms-3">
                                                                                <div class="card-body">
                                                                                    <p>Đánh giá</p>
                                                                                    <?php
                                                                                    $sql = "SELECT count(*) as sldg from danh_gia where nd_username = '$username'";
                                                                                    $result = mysqLi_query($conn, $sql);
                                                                                    $danhgia = mysqli_fetch_assoc($result)
                                                                                    ?>
                                                                                    <h4> <?php echo   $danhgia['sldg'] ?></h4>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row">

                                                                        <div class="col-6">


                                                                            <h4 class="list-group">Bài viết <br>
                                                                                <a style="border: none; background-color: transparent !important;" class="list-group-item list-group-item-action" id="profile-list-item" data-bs-toggle="list" href="#horizontal-profile">
                                                                                    <span style="font-size: 13px; font-weight: 100; color:black">Xem chi tiết</span>
                                                                                </a>
                                                                            </h4>

                                                                            <div class="card mb-4">

                                                                                <ul class="list-group list-group-flush border">
                                                                                    <?php
                                                                                    $bv = "SELECT *,CURRENT_TIMESTAMP() FROM bai_viet WHERE nd_username = '$id' LIMIT 5";
                                                                                    $rs = mysqli_query($conn, $bv);

                                                                                    while ($rowbv = mysqli_fetch_array($rs)) {
                                                                                        $currentTimestamp = strtotime($rowbv['bv_ngaydang']);
                                                                                        $current_time = strtotime($rowbv['CURRENT_TIMESTAMP()']); // Get the current Unix timestamp
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
                                                                                        $tieude = $rowbv['bv_tieude'];
                                                                                        $max_words = 9;
                                                                                        $words = explode(' ', $tieude);
                                                                                        if (count($words) > $max_words) {
                                                                                            $tieude = implode(' ', array_slice($words, 0, $max_words)) . ' ...';
                                                                                        }
                                                                                        echo '<a href="#" class="list-group-item ">
                                            <div style="margin-bottom:-15px" class="row">
                                            <p class="col-1">' . $rowbv['bv_diemtrungbinh'] . '<i style="color:#FDCC0D;" class="fa-solid fa-star"></i>  </p>
                                            <p class="col-9"> &nbsp;' . $tieude . '</p>
                                              <p class="col-2">' . $timeAgo . '</p>
                                            </div>
                                           
                                        
                                          </a>';
                                                                                    }
                                                                                    ?>





                                                                                </ul>

                                                                            </div>
                                                                        </div>

                                                                        <div class="col-6">
                                                                            <h4 class="ms-2">Danh mục<br> <a href="dsdm.php?id=<?php echo $id ?> "><span style="font-size:13px; font-weight:100">Xem chi tiết</span></a></h4>

                                                                            <div class="card mb-4 ms-2">

                                                                                <ul class="list-group list-group-flush border">
                                                                                    <?php
                                                                                    $dm = "SELECT dm.dm_ma,dm_ten, COUNT(*) as soluong FROM bai_viet bv, danh_muc dm WHERE dm.dm_ma = bv.dm_ma and nd_username = '$id' group by dm_ten,dm.dm_ma LIMIT 5";
                                                                                    $rsdm = mysqli_query($conn, $dm);

                                                                                    while ($rowdm = mysqli_fetch_array($rsdm)) {


                                                                                        echo '<a href="Xem_DM.php?id=' . $id . '&dm=' . $rowdm['dm_ma'] . ' ">
                                             <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="badge bg-label-primary ">' . $rowdm['dm_ten'] . '</span>
                                            ' . $rowdm['soluong'] . ' bài viết
                                           
                                          </li>
                                          </a>
                                         ';
                                                                                    }
                                                                                    ?>





                                                                                </ul>

                                                                            </div>
                                                                        </div>

                                                                    </div>


                                                                </div>



                                                                <?php include "hoatdong.php" ?>
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