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
            background-color: rgba(192, 192, 192, 0.3);

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
                            <div class="col-2">
                                <h5 style="margin-top:0.6rem" class=""><b>Sắp xếp theo:</b> </h5>
                            </div>
                            <div class="mb-4 col-10">

                                <form action="index.php" method="get">
                                    <div style="width:30rem" class="list-group list-group-horizontal-md text-md-center">

                                        <a id="newestTab" style="border: 1px solid;"
                                            class="list-group-item list-group-item-action " data-bs-toggle="list"
                                            href="#horizontal-home"
                                            onclick="changeLocation('newest', 'newestTab')"><b>Mới
                                                nhất</b></a>
                                        <a id="ratingTab" style="border: 1px solid;"
                                            class="list-group-item list-group-item-action" data-bs-toggle="list"
                                            href="#horizontal-profile"
                                            onclick="changeLocation('rating', 'ratingTab')"><b>Đánh giá</b></a>
                                        <a id="commentsTab" style="border: 1px solid;"
                                            class="list-group-item list-group-item-action" data-bs-toggle="list"
                                            href="#horizontal-messages"
                                            onclick="changeLocation('comments', 'commentsTab')"><b>Bình luận</b></a>
                                        <a id="viewsTab" style="border: 1px solid;"
                                            class="list-group-item list-group-item-action" data-bs-toggle="list"
                                            href="#horizontal-settings"
                                            onclick="changeLocation('views', 'viewsTab')"><b>Lượt xem</b></a>



                                    </div>
                                </form>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function () {
                                        // Lấy giá trị của tham số "loc" từ URL
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var locValue = urlParams.get('loc');

                                        // Mặc định tab "News" được chọn nếu không có tham số "loc" hoặc giá trị "loc" không phù hợp
                                        var defaultTabId = 'newestTab';

                                        // Nếu có tham số "loc" và giá trị "loc" phù hợp, cập nhật tabId mặc định
                                        if (locValue && document.getElementById(locValue + 'Tab')) {
                                            defaultTabId = locValue + 'Tab';
                                        }

                                        // Thêm class "active" vào tab mặc định
                                        var defaultTab = document.getElementById(defaultTabId);
                                        if (defaultTab) {
                                            defaultTab.classList.add('active');
                                        }
                                    });

                                    function changeLocation(loc, tabId) {
                                        // Lấy URL hiện tại
                                        var currentUrl = window.location.href;

                                        // Kiểm tra xem URL hiện tại đã chứa tham số "loc" hay chưa
                                        if (currentUrl.indexOf('loc=') === -1) {
                                            // Nếu không chứa, thêm tham số "loc" vào URL
                                            var separator = currentUrl.indexOf('?') !== -1 ? '&' : '?';
                                            var newUrl = currentUrl + separator + 'loc=' + loc;
                                            // Chuyển hướng trình duyệt đến URL mới
                                            window.location.href = newUrl;
                                        } else {
                                            // Nếu đã chứa, thay đổi giá trị của tham số "loc"
                                            var updatedUrl = currentUrl.replace(/(loc=)[^&]+/, '$1' + loc);
                                            // Chuyển hướng trình duyệt đến URL mới
                                            window.location.href = updatedUrl;

                                            // Loại bỏ class "active" từ tất cả các thẻ
                                            var tabs = document.querySelectorAll('.list-group-item');
                                            tabs.forEach(function (tab) {
                                                tab.classList.remove('active');
                                            });

                                            // Thêm class "active" vào thẻ được chọn
                                            document.getElementById(tabId).classList.add('active');
                                        }
                                    }
                                </script>

                            </div>
                        </div>
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
                                        $sql = "SELECT 
                                        bv.*, 
                                        nd.*, 
                                        dm.*, 
                                        mh.mh_ten, 
                                        dg.dg_diem, 
                                        kl.*, 
                                        CURRENT_TIMESTAMP(), 
                                        COUNT(
                                        DISTINCT CASE WHEN bl.trangthai = 1 THEN bl.bl_ma END
                                        ) AS slbl, 
                                        SUM(DISTINCT bv.bv_luotxem) AS luotxem FROM 
                                        bai_viet bv
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
                                            // echo '<h5>Bài viết dành cho bạn</h5>';
                                        }
                                    }
                                    ?>
                                    <style>
                                        #loadMoreBtn:hover {
                                            color: #12486b
                                        }
                                    </style>


                                    <!-- <div id="searchResult">  </div> -->

                                    <?php

                                    if (isset($_GET['noidung']) && !empty($_GET['noidung'])) {
                                        echo '<h3 class= "mt-4">Kết quả tìm kiếm của từ khoá "' . $_GET['noidung'] . '" là: </h3>';
                                        include "search-tm.php";
                                    } 
                                    else {

                                        if (isset($_GET['khoilop']) && !empty($_GET['khoilop'])) {


                                            $khoilop = $_GET['khoilop'];
                                            $sql = "SELECT 
                                            bv.*, 
                                            nd.*, 
                                            dm.*, 
                                            mh.mh_ten, 
                                            dg.dg_diem, 
                                            kl.*, 
                                            CURRENT_TIMESTAMP(), 
                                            COUNT(
                                            DISTINCT CASE WHEN bl.trangthai = 1 THEN bl.bl_ma END
                                            ) AS slbl, 
                                            SUM(DISTINCT bv.bv_luotxem) AS luotxem FROM 
                                            bai_viet bv
                                                LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
                                                JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
                                                JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
                                                JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
                                                JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
                                                LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
                                                left join danh_gia dg on bv.bv_ma = dg.bv_ma
                                                WHERE kd.tt_ma = 1 and kl.kl_ma = $khoilop
                                                GROUP BY bv.bv_ma
                                                ";
                                            if (isset($_GET['loc']) && !empty($_GET['loc'])) {
                                                $loc = $_GET['loc'];
                                                if ($loc == 'newest') {
                                                    $sql .= ' ORDER BY bv_ngaydang DESC';
                                                } elseif ($loc == 'rating') {
                                                    $sql .= ' ORDER BY bv_diemtrungbinh DESC';
                                                } elseif ($loc == 'comments') {
                                                    $sql .= ' ORDER BY slbl DESC';
                                                } elseif ($loc == 'views') {
                                                    $sql .= ' ORDER BY bv_luotxem DESC';
                                                }
                                            } else {
                                                // Mặc định sắp xếp theo ngày đăng mới nhất
                                                $sql .= ' ORDER BY bv_ngaydang DESC';
                                            }
                                            ;
                                        } elseif (isset($_GET['monhoc']) && !empty($_GET['monhoc'])) {
                                            $monhoc = $_GET['monhoc'];
                                            $sql = "SELECT 
                                            bv.*, 
                                            nd.*, 
                                            dm.*, 
                                            mh.mh_ten, 
                                            dg.dg_diem, 
                                            kl.*, 
                                            CURRENT_TIMESTAMP(), 
                                            COUNT(
                                            DISTINCT CASE WHEN bl.trangthai = 1 THEN bl.bl_ma END
                                            ) AS slbl, 
                                            SUM(DISTINCT bv.bv_luotxem) AS luotxem FROM 
                                            bai_viet bv
                                            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
                                            JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
                                            JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
                                            JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
                                            JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
                                            LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
                                            left join danh_gia dg on bv.bv_ma = dg.bv_ma
                                            WHERE kd.tt_ma = 1 and mh.mh_ma = $monhoc
                                            GROUP BY bv.bv_ma
                                            ";
                                            if (isset($_GET['loc']) && !empty($_GET['loc'])) {
                                                $loc = $_GET['loc'];
                                                if ($loc == 'newest') {
                                                    $sql .= ' ORDER BY bv_ngaydang DESC';
                                                } elseif ($loc == 'rating') {
                                                    $sql .= ' ORDER BY bv_diemtrungbinh DESC';
                                                } elseif ($loc == 'comments') {
                                                    $sql .= ' ORDER BY slbl DESC';
                                                } elseif ($loc == 'views') {
                                                    $sql .= ' ORDER BY bv_luotxem DESC';
                                                }
                                            } else {
                                                // Mặc định sắp xếp theo ngày đăng mới nhất
                                                $sql .= ' ORDER BY bv_ngaydang DESC';
                                            }
                                        } elseif (isset($_GET['danhmuc']) && !empty($_GET['danhmuc'])) {
                                            $danhmuc = $_GET['danhmuc'];
                                            $sql = "SELECT 
                                            bv.*, 
                                            nd.*, 
                                            dm.*, 
                                            mh.mh_ten, 
                                            dg.dg_diem, 
                                            kl.*, 
                                            CURRENT_TIMESTAMP(), 
                                            COUNT(
                                            DISTINCT CASE WHEN bl.trangthai = 1 THEN bl.bl_ma END
                                            ) AS slbl, 
                                            SUM(DISTINCT bv.bv_luotxem) AS luotxem FROM 
                                            bai_viet bv
                                            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma
                                            JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
                                            JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
                                            JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
                                            JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
                                            LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
                                            left join danh_gia dg on bv.bv_ma = dg.bv_ma
                                            WHERE kd.tt_ma = 1 and dm.dm_ma = $danhmuc
                                            GROUP BY bv.bv_ma
                                            ";
                                            if (isset($_GET['loc']) && !empty($_GET['loc'])) {
                                                $loc = $_GET['loc'];
                                                if ($loc == 'newest') {
                                                    $sql .= ' ORDER BY bv_ngaydang DESC';
                                                } elseif ($loc == 'rating') {
                                                    $sql .= ' ORDER BY bv_diemtrungbinh DESC';
                                                } elseif ($loc == 'comments') {
                                                    $sql .= ' ORDER BY slbl DESC';
                                                } elseif ($loc == 'views') {
                                                    $sql .= ' ORDER BY bv_luotxem DESC';
                                                }
                                            } else {
                                                // Mặc định sắp xếp theo ngày đăng mới nhất
                                                $sql .= ' ORDER BY bv_ngaydang DESC';
                                            }
                                        } else {
                                            $sql = "SELECT 
                                            bv.*, 
                                            nd.*, 
                                            dm.*, 
                                            mh.mh_ten, 
                                            dg.dg_diem, 
                                            kl.*, 
                                            CURRENT_TIMESTAMP(), 
                                            COUNT(
                                            DISTINCT CASE WHEN bl.trangthai = 1 THEN bl.bl_ma END
                                            ) AS slbl, 
                                            SUM(DISTINCT bv.bv_luotxem) AS luotxem FROM 
                                            bai_viet bv 
                                            JOIN nguoi_dung nd ON bv.nd_username = nd.nd_username 
                                            JOIN danh_muc dm ON bv.dm_ma = dm.dm_ma 
                                            JOIN mon_hoc mh ON dm.mh_ma = mh.mh_ma 
                                            JOIN khoi_lop kl ON kl.kl_ma = mh.kl_ma 
                                            LEFT JOIN kiem_duyet kd ON kd.bv_ma = bv.bv_ma 
                                            LEFT JOIN binh_luan bl ON bv.bv_ma = bl.bv_ma 
                                            LEFT JOIN danh_gia dg ON bv.bv_ma = dg.bv_ma
                                            where kd.tt_ma = 1  GROUP BY 
                                            bv.bv_ma
                                            ";
                                            if (isset($_GET['loc']) && !empty($_GET['loc'])) {
                                                $loc = $_GET['loc'];
                                                if ($loc == 'newest') {
                                                    $sql .= ' ORDER BY bv_ngaydang DESC';
                                                } elseif ($loc == 'rating') {
                                                    $sql .= ' ORDER BY bv_diemtrungbinh DESC';
                                                } elseif ($loc == 'comments') {
                                                    $sql .= ' ORDER BY slbl DESC';
                                                } elseif ($loc == 'views') {
                                                    $sql .= ' ORDER BY bv_luotxem DESC';
                                                }
                                            } else {
                                                // Mặc định sắp xếp theo ngày đăng mới nhất
                                                $sql .= ' ORDER BY bv_ngaydang DESC';
                                            }
                                        }
                                        $result = mysqLi_query($conn, $sql);
                                        while ($row = mysqli_fetch_array($result)) {
                                            $dm = $row['dm_ma'];
                                            $currentTimestamp = strtotime($row['bv_ngaydang']);
                                            $current_time = strtotime($row['CURRENT_TIMESTAMP()']); // Get the current Unix timestamp
                                            $timeDifference = $current_time - $currentTimestamp;  // Calculate the time difference
                                    
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


                                                <div class="col-md-12 col-12 mb-3 " data-class="<?php echo $row['kl_ma']; ?>">
                                                    <a class="baiviet" target="_blank"
                                                        href="chitietbv.php?id=<?php echo $row['bv_ma'] ?>">
                                                        <div class="card baiviet">
                                                            <div class="card-body">
                                                                <div class="d-flex">

                                                                    <!-- <div class="flex-shrink-0">
                                    <img src="../assets/img/avatars/<?php echo $row['nd_hinh'] ?>" alt="google" class="me-3 rounded-circle" height="40" />
                                  </div> -->
                                                                    <div class="flex-grow-1 row">
                                                                        <div class="col-12 mb-sm-0 mb-2">
                                                                            <h4 style="color:#1774af" class="mb-0  "><b>
                                                                                    <?php echo $row['bv_tieude'] ?>
                                                                                </b></h4>
                                                                            <br>

                                                                            <small class="text-dark">Tác giả: <b>
                                                                                    <?php echo $row['nd_hoten'] ?>
                                                                                </b></small>
                                                                            <br>
                                                                            <small class="text-dark">Lớp:
                                                                                <?php echo $row['kl_ten'] ?>
                                                                            </small> <br>
                                                                            <small class="text-dark">Môn:
                                                                                <?php echo $row['mh_ten'] ?>
                                                                            </small> <br>
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

                                                                            <small class="text-dark"><em> Tag:
                                                                                    <?php echo $row['dm_ten'] ?>
                                                                                    <?php while ($tag = mysqli_fetch_array($resulttag)) {
                                                                                        echo ', ' . $tag['dm_ten'] . '';
                                                                                    } ?>
                                                                                </em></small>

                                                                            <br>
                                                                            <small class="text-dark"><i
                                                                                    class="fa-solid fa-calendar-days"></i>
                                                                                <?php echo $timeAgo ?>
                                                                            </small> &nbsp;
                                                                            <small class="text-dark"><i class="fa-solid fa-star"
                                                                                    style="color: #FDCC0D;"></i>
                                                                                <?php echo $row['bv_diemtrungbinh'] ?>
                                                                            </small>
                                                                            &nbsp;
                                                                            <small class="text-dark"> <i
                                                                                    class="fa-solid fa-eye"></i>
                                                                                <?php echo $row['bv_luotxem'] ?>
                                                                            </small> &nbsp;
                                                                            <small class="text-dark"> <i
                                                                                    class="fa-solid fa-comment"> </i>
                                                                                <?php echo $row['slbl'] ?>
                                                                            </small>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            <?php }
                                    } ?>


                                    </div>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- <script>
        $(document).ready(function () {
            $('.form-check-input').change(function () {
                var classValue = this.id.replace('defaultCheck', '');

                // Nếu checkbox "Tất cả bài viết" được chọn
                if (classValue === '') {
                    // Ẩn tất cả bài viết
                    $('[data-class]').hide();

                    // Lặp qua các checkbox được chọn (ngoại trừ checkbox "Tất cả bài viết")
                    $('.form-check-input:checked').not('#defaultCheck').each(function () {
                        var selectedClass = this.id.replace('defaultCheck', '');
                        // Hiển thị các bài viết tương ứng
                        $('[data-class="' + selectedClass + '"]').show();
                    });
                } else {
                    // Nếu checkbox khác được chọn
                    $('[data-class="' + classValue + '"]').toggle(this
                        .checked); // Hiển thị hoặc ẩn các bài viết tương ứng
                }

                // Kiểm tra nếu không có checkbox khác được chọn ngoài checkbox "Tất cả bài viết"
                if ($('.form-check-input:checked').not('#defaultCheck').length === 0) {
                    // Chọn lại checkbox "Tất cả bài viết" và hiển thị tất cả bài viết
                    $('#defaultCheck').prop('checked', true);
                    $('[data-class]').show();
                }
            });
        }); -->
    </script>
    <script>
 $(document).ready(function () {
    $('.form-check-input').change(function () {
        // Check if the current checkbox is the "Tất cả bài viết" checkbox
        if ($(this).attr('id') === 'defaultCheck') {
            // Uncheck all other checkboxes
            $('.form-check-input').not(this).prop('checked', false);
        } else {
            // If any checkbox other than "Tất cả bài viết" is checked, uncheck it
            $('#defaultCheck').prop('checked', false);
        }

        // Hide all posts
        $('[data-class]').hide();

        // Check if any checkbox is checked
        if ($('.form-check-input:checked').length > 0) {
            // Show posts for selected classes
            $('.form-check-input:checked').each(function () {
                var classValue = this.id.replace('defaultCheck', '');
                $('[data-class="' + classValue + '"]').show();
            });
        } else {
            // If all checkboxes are unchecked, show all posts
            $('[data-class]').show();
        }
    });

    // Handling "Tất cả bài viết" checkbox separately
    $('#defaultCheck').change(function () {
        // If "Tất cả bài viết" checkbox is checked, uncheck all other checkboxes
        if ($(this).is(':checked')) {
            $('.form-check-input').not(this).prop('checked', false);
        }

        // Show all posts when "Tất cả bài viết" checkbox is checked
        $('[data-class]').show();
    });
});


</script>

    <script>
        function showLoader() {
            $("#loaderContainer").css("display", "flex");
        }

        function hideLoader() {
            $("#loaderContainer").css("display", "none");
        }
    </script>

    <script>
        $(document).ready(function () {
            hideLoader(); // Hide loader when the search results are loaded
        });
    </script>

    <!-- <script>
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
</script> -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>