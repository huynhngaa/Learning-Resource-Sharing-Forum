<?php
    session_start();
    include("./includes/connect.php");

    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}

    $user = $_GET['username'];

    if (isset($_POST['CapNhatPC'])) {
        $dm_ma_array = $_POST['dm_ma'];
    
        // Xóa tất cả các bản ghi cho người dùng cụ thể
        $xoa_ql = "DELETE FROM quan_ly WHERE nd_username = '$user'";
        mysqli_query($conn, $xoa_ql);
    
        // Chèn các bản ghi mới từ danh sách được chọn từ biểu mẫu
        foreach ($dm_ma_array as $dm_ma) {
            $cap_quanly = "INSERT INTO quan_ly (nd_username, dm_ma) VALUES ('$user', '$dm_ma')";
            mysqli_query($conn, $cap_quanly);
        }
    
        echo "<script>alert('Cập nhật phân công thành công!');</script>";
        header("Refresh: 0;url=PCQL_DanhMuc.php");
    }
    
   
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Cập nhật phân công quản lý danh mục</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <link rel="stylesheet" href="assets/vendor/libs/apex-charts/apex-charts.css" />

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <?php
                include_once("includes/menu.php");
            ?>

            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <?php
                    include_once("includes/navbar.php");
                ?>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">

                        <!-- Basic Breadcrumb -->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="index.php">Home</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="PCQL_DanhMuc.php">Quản lý phân công người quản lý danh mục</a>
                                </li>
                                <li class="breadcrumb-item active">Cập nhật phân công người quản lý danh mục</li>
                            </ol>
                        </nav>


                        <!-- Basic Layout -->
                        <div class="row">
                            <div class="col-xl">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Cập nhật phân công</h5>
                                        <!-- <small class="text-muted float-end">Merged input group</small> -->
                                    </div>
                                    <div class="card-body">
                                        <form method="POST" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label class="form-label" for="basic-icon-default-fullname">Chọn danh
                                                    mục phân công</label>
                                                    <?php
                                                        $ql2 = "SELECT a.*, nd_username, tg_phancong FROM danh_muc a, quan_ly b WHERE a.dm_ma = b.dm_ma AND b.nd_username ='$user' ";
                                                        $result_ql2 = mysqli_query($conn, $ql2);

                                                        $dm_phancong = array();

                                                        while ($row_ql2 = mysqli_fetch_array($result_ql2)) {
                                                            $dm_phancong[] = $row_ql2['dm_ma'];
                                                        }

                                                        $data = array();

                                                        $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
                                                                FROM danh_muc d
                                                                LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con ";
                                                        $result = mysqli_query($conn, $sql);

                                                        // Tạo mảng chứa danh mục cha
                                                        $danhmuc_goc = array();

                                                        while ($row = mysqli_fetch_assoc($result)) {
                                                            $data[] = array(
                                                                "id" => $row['dm_ma'],
                                                                "name" => $row['dm_ten'],
                                                                "parent" => $row['dm_cha']
                                                            );

                                                            // Lọc danh mục gốc
                                                            if ($row['dm_cha'] == 0) {
                                                                $danhmuc_goc[] = $row;
                                                            }
                                                        }

                                                        // Duyệt qua danh mục gốc và hiển thị
                                                        foreach ($danhmuc_goc as $goc) {
                                                            echo '<div class="tree">';
                                                            echo '<div><label><input ' . (in_array($goc['dm_ma'], $dm_phancong) ? 'checked' : '') . ' name="dm_ma[]" class="category form-check-input" type="checkbox" value="' . $goc['dm_ma'] . '">  ' . $goc['dm_ten'] . '</label>';
                                                            // Gọi hàm đệ quy để hiển thị danh mục con của danh mục gốc
                                                            dequy($data, $goc['dm_ma'],$dm_phancong);
                                                            echo '</div></div>';
                                                        }

                                                        function dequy($data, $parent, $dm_phancong) {
                                                            echo '<div class="tree">';
                                                            foreach ($data as $k => $value) {
                                                                if ($value['parent'] == $parent) {
                                                                    echo '<div><label><input ' . (in_array($value['id'], $dm_phancong) ? 'checked' : '') . ' name="dm_ma[]" class="category form-check-input" type="checkbox" value="' . $value['id'] . '"> ' . $value['name'] . '</label>';
                                                                    dequy($data, $value['id'], $dm_phancong);
                                                                    echo '</div>';
                                                                    unset($data[$k]);
                                                                }
                                                            }
                                                            echo '</div>';
                                                        }
                                                        ?>

                                            </div>

                                           <!-- <div class="row">
                                                <?php
                                                $danhmuc = "SELECT * FROM danh_muc";
                                                $result_danhmuc = mysqli_query($conn, $danhmuc);
                                                $total_rows = mysqli_num_rows($result_danhmuc);
                                                $half_rows = ceil($total_rows / 2);
                                                $count = 0;

                                                while ($row_danhmuc = mysqli_fetch_array($result_danhmuc)) {
                                                    if ($count % $half_rows == 0) {
                                                        echo '</div><div class="row">';
                                                    }
                                                    ?>
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="dm_ma[]"
                                                                value="<?php echo $row_danhmuc['dm_ma']; ?>">
                                                            <label class="form-check-label">
                                                                <?php echo $row_danhmuc['dm_ten']; ?>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $count++;
                                                }
                                                ?>
                                            </div> -->


                                            <button name="CapNhatPC" type="submit" class="btn btn-primary">Cập
                                                nhật</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Content wrapper -->
                </div>
                <!-- / Layout page -->
            </div>

            <!-- Overlay -->
            <div class="layout-overlay layout-menu-toggle"></div>
        </div>
        <!-- / Layout wrapper -->

        <!-- Core JS -->
        <!-- build:js assets/vendor/js/core.js -->
        <script src="assets/vendor/libs/jquery/jquery.js"></script>
        <script src="assets/vendor/libs/popper/popper.js"></script>
        <script src="assets/vendor/js/bootstrap.js"></script>
        <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

        <script src="assets/vendor/js/menu.js"></script>
        <!-- endbuild -->

        <!-- Vendors JS -->
        <script src="assets/vendor/libs/apex-charts/apexcharts.js"></script>

        <!-- Main JS -->
        <script src="assets/js/main.js"></script>

        <!-- Page JS -->
        <script src="assets/js/dashboards-analytics.js"></script>

        <!-- Place this tag in your head or just before your close body tag. -->
        <script async defer src="https://buttons.github.io/buttons.js"></script>

        <script>
        const categoryCheckboxes = document.querySelectorAll('.category');

        categoryCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const allChildCheckboxes = this.parentElement.querySelectorAll(
                    'input[type="checkbox"]');
                allChildCheckboxes.forEach(function(childCheckbox) {
                    childCheckbox.checked = checkbox.checked;
                });
                // Kiểm tra xem checkbox được chọn có là danh mục cha không
                const isParentCategory = checkbox.parentElement.nextElementSibling && checkbox
                    .parentElement
                    .nextElementSibling.tagName === 'DIV';
                if (isParentCategory) {
                    // Nếu là danh mục cha, thì chọn/deselect tất cả checkbox con
                    const childCheckboxes = checkbox.parentElement.nextElementSibling.querySelectorAll(
                        'input[type="checkbox"]');
                    childCheckboxes.forEach(function(childCheckbox) {
                        childCheckbox.checked = checkbox.checked;
                    });
                }
            });
        });
        </script>

        <script>
        var data = <?php echo json_encode($danhmuc_dachon); ?>;
        console.log(data);
        </script>
</body>

</html>