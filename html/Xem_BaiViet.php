<?php include "include/conn.php";
include "include/head.php";
if (!isset($_SESSION['user'])) {

    header("Location: 404.php");
}
$this_bv_ma = $_GET['bv'];

$bai_viet = "SELECT a.*, b.*, c.*, d.*, e.*, f.*, t.*,k.thoigian
            FROM bai_viet a
            LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
            LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
            LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
            LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
            LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
            LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
            LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
            WHERE a.bv_ma='$this_bv_ma'";

$result_bai_viet = mysqli_query($conn, $bai_viet);
$row_bai_viet = mysqli_fetch_assoc($result_bai_viet);
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
                                                                <div class="content-wrapper">
                                                                    <!-- Content -->

                                                                    <div
                                                                        class="container-xxl flex-grow-1 container-p-y">

                                                                        <!-- Basic Breadcrumb -->
                                                                        <nav aria-label="breadcrumb">
                                                                            <ol class="breadcrumb">
                                                                                <!-- <li class="breadcrumb-item">
                                    <a href="index.php">Home</a>
                                </li> -->
                                                                                <li class="breadcrumb-item">
                                                                                    <a
                                                                                        href="baiviet.php?id=<?php echo $id ?>">Quản
                                                                                        lý bài viết</a>
                                                                                </li>
                                                                                <li class="breadcrumb-item active">Chi
                                                                                    tiết bài viết</li>
                                                                            </ol>
                                                                        </nav>

                                                                        <!-- Basic Layout -->
                                                                        <div class="card ">
                                                                            <div class="card-body  g-3">

                                                                                <h5 class="mb-2">
                                                                                    <span class="badge bg-primary">Mã
                                                                                        bài viết #
                                                                                        <?php echo $this_bv_ma; ?>
                                                                                    </span>
                                                                                    <!-- <span class="badge bg-primary">
                                        <?php echo $row_bai_viet['tt_ten']; ?>
                                    </span> -->
                                                                                    <?php
                                                                                    if ($row_bai_viet['tt_ma'] == 1) {

                                                                                        echo "<span class='badge bg-label-success me-1'>" . $row_bai_viet['tt_ten'] . '</span>';

                                                                                    } else if ($row_bai_viet['tt_ma'] == 2) {
                                                                                        echo "<span class='badge bg-label-danger me-1'>" . $row_bai_viet['tt_ten'] . '</span>';

                                                                                    } else if ($row_bai_viet['tt_ma'] == 4) {

                                                                                        echo "<span class='badge bg-label-dismissible me-1'>" . $row_bai_viet['tt_ten'] . '</span>';
                                                                                    } else {
                                                                                        echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
                                                                                    }
                                                                                    ?>
                                                                                </h5>

                                                                                <h4 class="mb-2 text-center">
                                                                                    <?php echo $row_bai_viet['bv_tieude']; ?>
                                                                                </h4>
                                                                                <!-- <p class="mb-0 pt-1">Learn web design in 1 hour with 25+ simple-to-use rules and
                                        guidelines — tons
                                        of amazing web design resources included!</p> -->
                                                                                <hr class="my-4">
                                                                                <h5>Tóm tắt</h5>
                                                                                <div class="d-flex flex-wrap">
                                                                                    <div class="me-5">
                                                                                        <p class="text-nowrap"><i
                                                                                                class="bx bx-user bx-sm me-2"></i>Tác
                                                                                            giả:
                                                                                            <?php echo $row_bai_viet['nd_hoten']; ?>
                                                                                        </p>
                                                                                        <p class="text-nowrap"><i
                                                                                                class="fa fa-eye me-2"></i>Lượt
                                                                                            xem:
                                                                                            <?php echo $row_bai_viet['bv_luotxem']; ?>
                                                                                        </p>

                                                                                    </div>
                                                                                    <div class="me-5">
                                                                                        <p class="text-nowrap"><i
                                                                                                class="fa fa-star me-2"></i>Điểm
                                                                                            đánh
                                                                                            giá:
                                                                                            <?php echo $row_bai_viet['bv_diemtrungbinh']; ?>
                                                                                        </p>
                                                                                        <p style="white-space: normal"
                                                                                            class="text-nowrap "><i
                                                                                                class="fa fa-calendar me-2"></i>Ngày
                                                                                            xuất
                                                                                            bản:
                                                                                            <?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>
                                                                                        </p>

                                                                                    </div>
                                                                                    <div class="me-5">

                                                                                        <p class="text-nowrap"><i
                                                                                                class="fa fa-graduation-cap me-2"></i>Khối
                                                                                            lớp:
                                                                                            <?php echo $row_bai_viet['kl_ten']; ?>
                                                                                        </p>
                                                                                        <p class="text-nowrap "><i
                                                                                                class="fa fa-bookmark me-2"></i>Môn
                                                                                            học:
                                                                                            <?php echo $row_bai_viet['mh_ten']; ?>
                                                                                        </p>
                                                                                    </div>
                                                                                    <div class="me-5">
                                                                                        <p class="text-nowrap"><i
                                                                                                class="fa fa-folder me-2"></i>Danh
                                                                                            mục:
                                                                                            <?php echo $row_bai_viet['dm_ten']; ?>
                                                                                        </p>
                                                                                        <?php
                                                                                        if (!empty($row_bai_viet['tt_ma'] && $row_bai_viet['tt_ma'] !== '3')) {
                                                                                            echo '<p style="white-space: normal" class="text-nowrap"><i class="fa fa-calendar me-2"></i>Ngày xét duyệt: ' . date_format(date_create($row_bai_viet['thoigian']), "d-m-Y (H:i:s)") . '</p>';
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                </div>
                                                                                <hr class="mb-4 mt-2">
                                                                                <h5>Nội dung</h5>
                                                                                <p class="mb-4">
                                                                                    <?php echo $row_bai_viet['bv_noidung']; ?>
                                                                                </p>

                                                                                <h5>Tài liệu đính kèm</h5>
                                                                                <p class="mb-4">
                                                                                    <?php
                                                                                    if ($row_bai_viet['tl_tentaptin'] == '') {
                                                                                        echo "<i>(Không có tài liệu đính kèm)</i>";
                                                                                    } else {
                                                                                        $tailieu = "SELECT * from tai_lieu
                                                        WHERE bv_ma='$this_bv_ma'";

                                                                                        $result_tailieu = mysqli_query($conn, $tailieu);
                                                                                        while ($row_tai_lieu = mysqli_fetch_array($result_tailieu)) {
                                                                                            // echo "".$row_tai_lieu['tl_tentaptin']."<br>";
                                                                                            $ten_tai_lieu = $row_tai_lieu['tl_tentaptin'];
                                                                                            $duoi_tai_lieu = pathinfo($ten_tai_lieu, PATHINFO_EXTENSION);

                                                                                            $icon = ''; // Biến này sẽ lưu trữ biểu tượng dựa trên loại đuôi tệp
                                                                                    
                                                                                            // Kiểm tra loại đuôi tệp và gán biểu tượng tương ứng
                                                                                            if (strtolower($duoi_tai_lieu) === 'pdf') {
                                                                                                $icon = '<i class="fa-solid fa-file-pdf" style="color: #e40707;"></i>';
                                                                                            } elseif (in_array(strtolower($duoi_tai_lieu), ['doc', 'docx'])) {
                                                                                                $icon = '<i class="fa-solid fa-file-word" style="color: #0078d4;"></i>';
                                                                                            } elseif (in_array(strtolower($duoi_tai_lieu), ['zip'])) {
                                                                                                $icon = '<i class="fa-solid fa-file-archive" style="color: #FFA500;"></i>'; // Icon cho ZIP
                                                                                            } elseif (in_array(strtolower($duoi_tai_lieu), ['rar'])) {
                                                                                                $icon = '<i class="fa-solid fa-file-archive" style="color: #8B0000;"></i>'; // Icon cho RAR
                                                                                            }

                                                                                            // Hiển thị biểu tượng và tên tệp
                                                                                            echo '<div>' . $icon . ' <a class="text-muted" href="uploads/' . $ten_tai_lieu . '" target="_blank">' . $ten_tai_lieu . '</a></div>';
                                                                                        }
                                                                                    }
                                                                                    ?>
                                                                                </p>
                                                                                <hr class="my-4">

                                                                                <div class="demo-inline-spacing">
                                                                                    <span
                                                                                        class="badge bg-label-primary">
                                                                                        <?php echo $row_bai_viet['dm_ten']; ?>
                                                                                    </span>
                                                                                    <span
                                                                                        class="badge bg-label-secondary">
                                                                                        <?php echo $row_bai_viet['mh_ten']; ?>
                                                                                    </span>
                                                                                    <span class="badge bg-label-dark">
                                                                                        <?php echo $row_bai_viet['kl_ten']; ?>
                                                                                    </span>
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
                                </div>
                            </div>
                        </div>
                        <footer class="content-footer footer bg-footer-theme">
                            <div
                                class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by
                                    <a href="https://themeselection.com" target="_blank"
                                        class="footer-link fw-bolder">ThemeSelection</a>
                                </div>
                                <div>
                                    <a href="https://themeselection.com/license/" class="footer-link me-4"
                                        target="_blank">License</a>
                                    <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">More
                                        Themes</a>

                                    <a href="https://themeselection.com/demo/sneat-bootstrap-html-admin-template/documentation/"
                                        target="_blank" class="footer-link me-4">Documentation</a>

                                    <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues"
                                        target="_blank" class="footer-link me-4">Support</a>
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