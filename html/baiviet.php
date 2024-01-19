<?php include "include/conn.php";
include "include/head.php";
$id = $_GET['id'];

if (isset($_POST['xoa_bv'])) {
    $user = $id;

    $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];
    try {
        foreach ($selectedIds as $bv) {
            $parts = explode('|', $bv);
            $bv_ma = $parts[0]; // Giá trị của bv_ma
            $tt = $parts[1]; // Giá trị của ghi_chu

            if ($tt == null) {
                $tt = 3;
            }

            $kt = "select *from kiem_duyet where bv_ma = '$bv_ma'";
            $result_kt = mysqli_query($conn, $kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if ($row_kt && $bv_ma == $row_kt['bv_ma']) {
                $huy_bv = "UPDATE kiem_duyet SET tt_ma = '4', nd_username='$user', ghi_chu='$tt' , thoigian = now() WHERE bv_ma = '$bv_ma'";
                mysqli_query($conn, $huy_bv);
            } else {
                $xoa_bai_viet = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma, thoigian, ghi_chu) VALUE ('$bv_ma', '$user', '4', now(), '$tt')";
                mysqli_query($conn, $xoa_bai_viet);
            }


            // $xoa_bai_viet="DELETE FROM bai_viet where bv_ma= '$id'";
            // mysqli_query($conn,$xoa_bai_viet); 
        }

        // Nếu mọi thứ thành công, commit transaction
        mysqli_commit($conn);
        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";
        // header("Refresh: 0;url=QL_BaiViet.php");  
    } catch (Exception $e) {
        // Nếu có lỗi xảy ra, rollback transaction và hiển thị thông báo lỗi
        mysqli_rollback($conn);
        echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>";
        echo "Error: " . $e->getMessage();

    }
}



$so_dong = 5;
$trangthai = "Tất cả";

$tungay = "2000-01-01";
$denngay = date('Y-m-d');

if (isset($_GET['sodong'])) {
    $so_dong = intval($_GET['sodong']);
}


if (isset($_GET['tungay'])) {
    $tungay = $_GET['tungay'];
}

if (isset($_GET['denngay'])) {
    $denngay = $_GET['denngay'];
}

if (isset($_GET['trangthai'])) {
    $trangthai = $_GET['trangthai'];
}



if ($trangthai == "3") {
    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            -- left join danh_muc dm on dm.dm_ma = a.dm_ma
            -- left join mon_hoc mh on dm.mh_ma = dm.mh_ma
            -- left join khoi_lop kl on kl.kl_ma = mh.kl_ma
            where (c.tt_ma IS NULL OR c.tt_ma != 4)
            And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            and c.tt_ma IS NULL or c.tt_ma = 3
            And a.nd_username ='$id'
            LIMIT $so_dong";

} elseif ($trangthai == "1" || $trangthai == "2") {
    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
        -- left join danh_muc dm on dm.dm_ma = a.dm_ma
        -- left join mon_hoc mh on dm.mh_ma = dm.mh_ma
        -- left join khoi_lop kl on kl.kl_ma = mh.kl_ma
            where (c.tt_ma IS NULL OR c.tt_ma != 4)
            And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            and c.tt_ma = '$trangthai'
            And a.nd_username ='$id'
            LIMIT $so_dong";

} elseif ($trangthai == "Tất cả") {
    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
        -- left join danh_muc dm on dm.dm_ma = a.dm_ma
        -- left join mon_hoc mh on dm.mh_ma = dm.mh_ma
        -- left join khoi_lop kl on kl.kl_ma = mh.kl_ma
            where (c.tt_ma IS NULL OR c.tt_ma != 4)
            And a.nd_username ='$id'
            And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            LIMIT $so_dong";
} else {
    $bai_viet = "SELECT a.*, e.*,c.tt_ma, d.* FROM bai_viet a
        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
        -- left join danh_muc dm on dm.dm_ma = a.dm_ma
        -- left join mon_hoc mh on dm.mh_ma = dm.mh_ma
        -- left join khoi_lop kl on kl.kl_ma = mh.kl_ma 
            where (c.tt_ma IS NULL OR c.tt_ma != 4)
            And a.nd_username ='$id'
            And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            LIMIT $so_dong";
}


$result_bai_viet = mysqli_query($conn, $bai_viet);
unset($_SESSION['sl_dong']);
$sl_dong_hientai = mysqli_num_rows($result_bai_viet);
$_SESSION['sl_dong'] = $sl_dong_hientai;

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
                                            <div id="capnhat-thanhcong" class="toast hide bg-success " role="alert"
                                                aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                    <strong class="me-auto">Cập nhật thành công</strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="toast"
                                                        aria-label="Close"></button>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="position-fixed top-0 end-0" style="z-index: 11; margin-top:75px">
                                            <div id="capnhat-thatbai" class="toast hide bg-danger" role="alert"
                                                aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                    <strong class="me-auto">Cập nhật thất bại</strong>
                                                    <button type="button" class="btn-close" data-bs-dismiss="toast"
                                                        aria-label="Close"></button>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="nav-align-top mb-4 mt-3">

                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="navs-justified-home"
                                                    role="tabpanel">
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
                                                                        <div
                                                                            class=" d-flex align-items-center flex-column">
                                                                            <img class="img-fluid rounded my-4"
                                                                                src="../assets/img/avatars/<?php echo $nguoidung['nd_hinh'] ?>"
                                                                                height="110" width="110"
                                                                                alt="User avatar">
                                                                            <div class="user-info text-center">
                                                                                <h4 class="mb-2">
                                                                                    <?php echo $nguoidung['nd_hoten'] ?>
                                                                                </h4>
                                                                                <span class="badge bg-label-secondary">
                                                                                    <?php echo $nguoidung['vt_ten'] ?>
                                                                                </span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <h5 class="pb-2 border-bottom mb-4">Details</h5>
                                                                    <div class="info-container">
                                                                        <ul class="list-unstyled">
                                                                            <li class="mb-3">
                                                                                <span
                                                                                    class="fw-medium me-2">Username:</span>

                                                                                <span>
                                                                                    <?php echo $nguoidung['nd_username'] ?>
                                                                                </span>
                                                                            </li>
                                                                            <li class="mb-3">
                                                                                <span
                                                                                    class="fw-medium me-2">Email:</span>
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
                                                                                <span
                                                                                    class="fw-medium me-2">Status:</span>
                                                                                <span
                                                                                    class="badge bg-label-success">Active</span>
                                                                            </li>
                                                                            <li class="mb-3">
                                                                                <span
                                                                                    class="fw-medium me-2">Role:</span>
                                                                                <span>
                                                                                    <?php echo $nguoidung['vt_ten'] ?>
                                                                                </span>
                                                                            </li>
                                                                            <li class="mb-3">
                                                                                <span class="fw-medium me-2">Tax
                                                                                    id:</span>
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
                                                                                <span
                                                                                    class="fw-medium me-2">Country:</span>
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
                                                            <ul class="nav nav-pills flex-column flex-md-row mb-3">
                                                                <li class="nav-item ">
                                                                    <a class="nav-link "
                                                                        href="profile.php?id=<?php echo $id ?>"><i
                                                                            class="bx bx-user me-1"></i> Tổng Quan</a>
                                                                </li>
                                                                <li class="nav-item ">
                                                                    <a class="nav-link"
                                                                        href="hoso.php?id=<?php echo $id ?>"><i
                                                                            class="bx bx-bell me-1"></i> Hồ Sơ</a>
                                                                </li>
                                                                <li class="nav-item ">
                                                                    <a class="nav-link active"
                                                                        href="baiviet.php?id=<?php echo $id ?>"><i
                                                                            class="bx bx-bell me-1"></i> Bài Viết</a>
                                                                </li>
                                                                <?php
                                                                $id = $_GET['id'];
                                                                if (isset($_SESSION['user'])) {
                                                                    $user = $_SESSION['user'];
                                                                    if ($user['nd_username'] == $id) { ?>
                                                                        <li class="nav-item">
                                                                            <a class="nav-link"
                                                                                href="nhatkyhoatdong.php?id=<?php echo $id ?>"><i
                                                                                    class="bx bx-link-alt me-1"></i> Nhật Ký
                                                                                Hoạt Động </a>
                                                                        </li>
                                                                    <?php }
                                                                } ?>
                                                            </ul>
                                                            <hr>


                                                            <div class="tab-pane fade active show">
                                                                <?php include "include/baiviet.php" ?>





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
        document.addEventListener("DOMContentLoaded", function () {
            var emailInput = document.getElementById("basic-icon-default-email");
            var emailErrorMessage = document.getElementById("email-error-message");

            emailInput.addEventListener("input", function () {
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
        document.addEventListener("DOMContentLoaded", function () {
            var phoneNumberInput = document.getElementById("basic-icon-default-phone");
            var errorMessage = document.getElementById("error-message");

            phoneNumberInput.addEventListener("input", function () {
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
        document.addEventListener("DOMContentLoaded", function () {
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function () {
            $('.small-button').click(function () {
                // Remove the 'active' class from all buttons
                $('.small-button').removeClass('active');

                // Add the 'active' class to the clicked button
                $(this).addClass('active');
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Xử lý khi số dòng hoặc trạng thái thay đổi
            $('#so_dong,#trangthai').change(function () {
                updateDataContainer();
            });

            // Xử lý khi nhấn nút sắp xếp
            // $('#asc, #desc').click(function() {
            //     var sortOrder = $(this).attr('id'); // Lấy 'asc' hoặc 'desc' từ id của nút
            //     updateDataContainer(sortOrder);
            //     $(this).addClass('active');
            //     $('#desc, #asc').not(this).removeClass('active');
            // });

            // Xử lý khi nhấn nút lọc ngày
            $('#loc_ngay').click(function () {
                var chon_gtri = $('#so_dong').val();
                var tungay = $('#tungay').val();
                var denngay = $('#denngay').val();
                var trangthai = $('#trangthai').val();
                var user = '<?php echo $id ?>';
                $.ajax({
                    url: 'get_baiviet.php',
                    method: 'GET',
                    data: {
                        tungay: tungay,
                        sodong: chon_gtri,
                        denngay: denngay,
                        trangthai: trangthai,
                        id: user,
                        loc_ngay: true // Thêm tham số loc_ngay
                    },
                    // success: function(data) {
                    //     $('#data-container').html(data);
                    // }
                    success: function (data) {
                        $('#data-container').html(data);
                        updateDisplayInfo();
                    }
                });
            });

            // Hàm cập nhật dữ liệu với tùy chọn sắp xếp
            function updateDataContainer(sortOrder) {
                var chon_gtri = $('#so_dong').val();
                var chon_trangthai = $('#trangthai').val();
                var tungay = $('#tungay').val();
                var denngay = $('#denngay').val();
                var user = '<?php echo $id ?>';
                $.ajax({
                    url: 'get_baiviet.php',
                    data: {
                        tungay: tungay,
                        denngay: denngay,
                        sodong: chon_gtri,
                        trangthai: chon_trangthai,
                        id: user
                        // sort: sortOrder
                    },
                    // success: function(data) {
                    //     $('#data-container').html(data);
                    // }
                    success: function (data) {
                        $('#data-container').html(data);
                        updateDisplayInfo();
                    }
                });
            }
            // Hàm cập nhật thông tin hiển thị
            function updateDisplayInfo() {
                var soDongHienTai = $('#data-container .row-bai-viet').length;
                var Tong = $('#tong_sd').val(); // Lấy giá trị từ biến ẩn
                $('#so_dong_hien_tai').text(soDongHienTai);
                $('#tong-so-dong').text(Tong);
            }
        });
    </script>
    <!-- JavaScript để điều khiển hành vi -->
    <script>
        // Lắng nghe sự kiện khi checkbox chọn tất cả được thay đổi trạng thái
        document.getElementById('checkall').addEventListener('change', function () {
            // Lấy tất cả các checkbox cần chọn
            var checkboxes = document.querySelectorAll('.check-item');

            // Duyệt qua từng checkbox và thiết lập trạng thái chọn/bỏ chọn
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = document.getElementById('checkall').checked;
            });
        });
    </script>

    <script>
        function Xoa_Baiviet(bv_ma) {
            Swal.fire({
                title: 'Xác nhận xóa',
                text: 'Bạn có chắc muốn xóa bài viết này?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Xóa',
                cancelButtonText: 'Hủy',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                    window.location.href = 'xoa_baiviet.php?this_bv_ma=' + bv_ma;
                }
            });
        }
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