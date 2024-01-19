<?php
    session_start();
    include("./includes/connect.php");
    if(!isset($_SESSION['Admin'])){
        echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>"; 
        header("Refresh: 0;url=login.php");  
    }else{}
    $this_username = $_GET['this_username'];
    $vt = $_GET['vt'];

    $vai_tro = "SELECT * FROM vai_tro WHERE vt_ma='$vt'";
    $result_vai_tro = mysqli_query($conn,$vai_tro);
    $row_vai_tro = mysqli_fetch_assoc($result_vai_tro);
    
    $nguoi_dung = "SELECT * FROM nguoi_dung a, vai_tro b WHERE a.vt_ma=b.vt_ma and nd_username='$this_username'";
    $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
	$row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);

    if (isset($_POST['CapNhat'])) {
        $ten = $_POST['name'];
        
        $sdt = $_POST['phone'];
        $gioitinh = $_POST['gioitinh'];
        $vaitro = $_POST['vaitro'];
        $email = $_POST['email'];
        $diachi = $_POST['address'];
        $matkhau = $_POST['pass'];
        $gioitinh = $_POST['gioitinh'];
        $ngaysinh = $_POST['ngaysinh'];

      
    $img = $row_nguoi_dung['nd_hinh']; // Mặc định sử dụng ảnh cũ

    if (!empty($_FILES['img']['name'])) {
        // Nếu có tệp ảnh mới được tải lên
        $img = $_FILES['img']['name'];
        $img_tmp_name = $_FILES['img']['tmp_name'];
        move_uploaded_file($img_tmp_name, './assets/img/avatars/' . $img); 
    }

    
        $cap_nhat_nguoi_dung = "UPDATE nguoi_dung 
                                SET nd_hoten = '$ten', 
                                    nd_gioitinh = '$gioitinh', 
                                    nd_ngaysinh = '$ngaysinh',
                                    nd_sdt = '$sdt', 
                                    nd_email = '$email', 
                                    nd_diachi = '$diachi', 
                                    nd_matkhau = '$matkhau', 
                                    vt_ma = '$vaitro',
                                    nd_hinh = '$img'
                                WHERE nd_username = '$this_username'";
        mysqli_query($conn,$cap_nhat_nguoi_dung); 
         
		echo "<script>alert('Cập nhật tài khoản thành công!');</script>"; 
        header("Refresh: 0;url=Xem_NguoiDung.php?this_username=" . $row_nguoi_dung['nd_username'] . "&vt=" . $row_nguoi_dung['vt_ma']); 
    }
?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Quản lý Tài khoản</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

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
    <!-- Thông báo -->

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="sweetalert2.all.min.js"></script>
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


                        <h4 class="py-3 mb-4">
                            <span class="text-muted fw-light">Quản lý người dùng / Chi tiết
                                <?php echo $row_vai_tro['vt_ten']?> /</span> Tổng quan
                        </h4>

                      


                        <div class="row">
                            <!-- Customer-detail Sidebar -->
                            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                                <!-- Customer-detail Card -->
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <div class="customer-avatar-section">
                                            <div class="d-flex align-items-center flex-column">
                                                <img class="img-fluid rounded my-3"
                                                    src="./assets/img/avatars/<?php echo $row_nguoi_dung['nd_hinh']?>"
                                                    height="110" width="110" alt="User avatar">
                                                <div class="customer-info text-center">
                                                    <h4 class="mb-1"><?php echo $row_nguoi_dung['nd_hoten']?></h4>
                                                    <small>Username: <?php echo $row_nguoi_dung['nd_username']?></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-around flex-wrap mt-4 py-3">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar">
                                                    <div class="avatar-initial rounded bg-label-primary"><i
                                                            class="bx bx-cart-alt bx-sm"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">184</h5>
                                                    <span>Số bài viết</span>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar">
                                                    <div class="avatar-initial rounded bg-label-primary"><i
                                                            class="bx bx-dollar bx-sm"></i>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h5 class="mb-0">$12,378</h5>
                                                    <span>Spent</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="info-container">
                                            <small
                                                class="d-block pt-4 border-top fw-normal text-uppercase text-muted my-3">CHI
                                                TIẾT</small>
                                            <ul class="list-unstyled">
                                                <li class="mb-3">
                                                    <span class="fw-medium me-2">Username:</span>
                                                    <span><?php echo $row_nguoi_dung['nd_username']?></span>
                                                </li>
                                                <li class="mb-3">
                                                    <span class="fw-medium me-2">Email:</span>
                                                    <span><?php echo $row_nguoi_dung['nd_email']?></span>
                                                </li>
                                                <li class="mb-3">
                                                    <span class="fw-medium me-2">Trạng thái:</span>
                                                    <span class="badge bg-label-success">Active</span>
                                                </li>
                                                <li class="mb-3">
                                                    <span class="fw-medium me-2">Liên hệ:</span>
                                                    <span><?php echo $row_nguoi_dung['nd_sdt']?></span>
                                                </li>

                                                <li class="mb-3">
                                                    <span class="fw-medium me-2">Địa chỉ:</span>
                                                    <span><?php echo $row_nguoi_dung['nd_diachi']?></span>
                                                </li>
                                            </ul>
                                            <div class="d-flex justify-content-center">
                                                <a href="javascript:;" class="btn btn-primary me-3"
                                                    data-bs-target="#editUser" data-bs-toggle="modal">Chỉnh sửa</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Customer-detail Card -->

                            </div>
                            <!--/ Customer Sidebar -->

                            <!-- Customer Content -->
                            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                                <!-- Customer Pills -->
                                <ul class="nav nav-pills flex-column flex-md-row mb-4">
                                    <li class="nav-item"><a class="nav-link active" href="javascript:void(0);"><i
                                                class="bx bx-user me-1"></i>Tổng quan</a></li>
                                    <li class="nav-item"><a class="nav-link"
                                            href="app-ecommerce-customer-details-security.html"><i
                                                class="bx bx-lock-alt me-1"></i>Mật khẩu</a></li>
                                    <li class="nav-item"><a class="nav-link"
                                            href="app-ecommerce-customer-details-billing.html"><i
                                                class="bx bx-detail me-1"></i>Bài viết</a></li>
                                </ul>
                                <!--/ Customer Pills -->

                                <!-- Invoice table -->
                                <div class="card mb-4">
                                    <div class="table-responsive mb-3">
                                        <div id="DataTables_Table_0_wrapper"
                                            class="dataTables_wrapper dt-bootstrap5 no-footer">
                                            <div class="card-header d-flex flex-wrap py-3 py-sm-2">
                                                <div class="head-label text-center me-4 ms-1">
                                                    <h5 class="card-title mb-0 text-nowrap">Danh sách cácv bài viết</h5>
                                                </div>
                                                <div id="DataTables_Table_0_filter" class="dataTables_filter">
                                                    <label><input type="search" class="form-control"
                                                            placeholder="Search order"
                                                            aria-controls="DataTables_Table_0"></label></div>
                                            </div>
                                            <table
                                                class="table datatables-customer-order border-top dataTable no-footer dtr-column collapsed"
                                                id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info"
                                                style="width: 798px;">
                                                <thead>
                                                    <tr>
                                                        <th class="control sorting_disabled" rowspan="1" colspan="1"
                                                            style="width: 0px;" aria-label=""></th>
                                                        <th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all"
                                                            rowspan="1" colspan="1" style="width: 18px;" data-col="1"
                                                            aria-label=""><input type="checkbox"
                                                                class="form-check-input"></th>
                                                        <th class="sorting sorting_desc" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            style="width: 71px;"
                                                            aria-label="Order: activate to sort column ascending"
                                                            aria-sort="descending">Order</th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            style="width: 121px;"
                                                            aria-label="Date: activate to sort column ascending">Date
                                                        </th>
                                                        <th class="sorting dtr-hidden" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            style="width: 159px; display: none;"
                                                            aria-label="Status: activate to sort column ascending">
                                                            Status</th>
                                                        <th class="sorting dtr-hidden" tabindex="0"
                                                            aria-controls="DataTables_Table_0" rowspan="1" colspan="1"
                                                            style="width: 77px; display: none;"
                                                            aria-label="Spent: activate to sort column ascending">Spent
                                                        </th>
                                                        <th class="text-md-center sorting_disabled dtr-hidden"
                                                            rowspan="1" colspan="1" style="width: 88px; display: none;"
                                                            aria-label="Actions">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr class="odd">
                                                        <td class="control" tabindex="0" style=""></td>
                                                        <td class="  dt-checkboxes-cell"><input type="checkbox"
                                                                class="dt-checkboxes form-check-input"></td>
                                                        <td class="sorting_1"><a href="app-ecommerce-order-details.html"
                                                                class="fw-medium"><span>#9957</span></a></td>
                                                        <td class="" style=""><span class="text-nowrap">Nov 29,
                                                                2022</span> </td>
                                                        <td class="dtr-hidden" style="display: none;"><span
                                                                class="badge bg-label-primary" text-capitalized="">Out
                                                                for delivery</span></td>
                                                        <td class="dtr-hidden" style="display: none;">
                                                            <span>$59.28</span></td>
                                                        <td class="dtr-hidden" style="display: none;">
                                                            <div class="text-xxl-center"><button
                                                                    class="btn btn-sm btn-icon dropdown-toggle hide-arrow"
                                                                    data-bs-toggle="dropdown"><i
                                                                        class="bx bx-dots-vertical-rounded"></i></button>
                                                                <div class="dropdown-menu dropdown-menu-end m-0"><a
                                                                        href="javascript:;"
                                                                        class="dropdown-item">View</a><a
                                                                        href="javascript:;"
                                                                        class="dropdown-item  delete-record">Delete</a>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="row mx-4">
                                                <div
                                                    class="col-md-12 col-xl-6 text-center text-xl-start pb-2 pb-lg-0 pe-0">
                                                    <div class="dataTables_info" id="DataTables_Table_0_info"
                                                        role="status" aria-live="polite">Showing 1 to 6 of 100 entries
                                                    </div>
                                                </div>
                                                <div
                                                    class="col-md-12 col-xl-6 d-flex justify-content-center justify-content-xl-end">
                                                    <div class="dataTables_paginate paging_simple_numbers"
                                                        id="DataTables_Table_0_paginate">
                                                        <ul class="pagination">
                                                            <li class="paginate_button page-item previous disabled"
                                                                id="DataTables_Table_0_previous"><a
                                                                    aria-controls="DataTables_Table_0"
                                                                    aria-disabled="true" role="link"
                                                                    data-dt-idx="previous" tabindex="0"
                                                                    class="page-link">Previous</a></li>
                                                            <li class="paginate_button page-item active"><a href="#"
                                                                    aria-controls="DataTables_Table_0" role="link"
                                                                    aria-current="page" data-dt-idx="0" tabindex="0"
                                                                    class="page-link">1</a></li>
                                                            <li class="paginate_button page-item "><a href="#"
                                                                    aria-controls="DataTables_Table_0" role="link"
                                                                    data-dt-idx="1" tabindex="0" class="page-link">2</a>
                                                            </li>
                                                            <li class="paginate_button page-item "><a href="#"
                                                                    aria-controls="DataTables_Table_0" role="link"
                                                                    data-dt-idx="2" tabindex="0" class="page-link">3</a>
                                                            </li>
                                                            <li class="paginate_button page-item "><a href="#"
                                                                    aria-controls="DataTables_Table_0" role="link"
                                                                    data-dt-idx="3" tabindex="0" class="page-link">4</a>
                                                            </li>
                                                            <li class="paginate_button page-item "><a href="#"
                                                                    aria-controls="DataTables_Table_0" role="link"
                                                                    data-dt-idx="4" tabindex="0" class="page-link">5</a>
                                                            </li>
                                                            <li class="paginate_button page-item disabled"
                                                                id="DataTables_Table_0_ellipsis"><a
                                                                    aria-controls="DataTables_Table_0"
                                                                    aria-disabled="true" role="link"
                                                                    data-dt-idx="ellipsis" tabindex="0"
                                                                    class="page-link">…</a></li>
                                                            <li class="paginate_button page-item "><a href="#"
                                                                    aria-controls="DataTables_Table_0" role="link"
                                                                    data-dt-idx="16" tabindex="0"
                                                                    class="page-link">17</a></li>
                                                            <li class="paginate_button page-item next"
                                                                id="DataTables_Table_0_next"><a href="#"
                                                                    aria-controls="DataTables_Table_0" role="link"
                                                                    data-dt-idx="next" tabindex="0"
                                                                    class="page-link">Next</a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /Invoice table -->
                            </div>
                            <!--/ Customer Content -->
                        </div>

                        <!-- Modal -->
                        <!-- Edit User Modal -->
                        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                                <div class="modal-content p-3 p-md-5">
                                    <div class="modal-body">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <div class="text-center mb-4">
                                            <h3>Edit User Information</h3>
                                            <p>Updating user details will receive a privacy audit.</p>
                                        </div>
                                        <form id="editUserForm"
                                            class="row g-3 fv-plugins-bootstrap5 fv-plugins-framework"
                                            onsubmit="return false" novalidate="novalidate">
                                            <div class="col-12 col-md-6 fv-plugins-icon-container">
                                                <label class="form-label" for="modalEditUserFirstName">First
                                                    Name</label>
                                                <input type="text" id="modalEditUserFirstName"
                                                    name="modalEditUserFirstName" class="form-control"
                                                    placeholder="John">
                                                <div
                                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6 fv-plugins-icon-container">
                                                <label class="form-label" for="modalEditUserLastName">Last Name</label>
                                                <input type="text" id="modalEditUserLastName"
                                                    name="modalEditUserLastName" class="form-control" placeholder="Doe">
                                                <div
                                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                </div>
                                            </div>
                                            <div class="col-12 fv-plugins-icon-container">
                                                <label class="form-label" for="modalEditUserName">Username</label>
                                                <input type="text" id="modalEditUserName" name="modalEditUserName"
                                                    class="form-control" placeholder="john.doe.007">
                                                <div
                                                    class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="modalEditUserEmail">Email</label>
                                                <input type="text" id="modalEditUserEmail" name="modalEditUserEmail"
                                                    class="form-control" placeholder="example@domain.com">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="modalEditUserStatus">Status</label>
                                                <select id="modalEditUserStatus" name="modalEditUserStatus"
                                                    class="form-select" aria-label="Default select example">
                                                    <option selected="">Status</option>
                                                    <option value="1">Active</option>
                                                    <option value="2">Inactive</option>
                                                    <option value="3">Suspended</option>
                                                </select>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="modalEditTaxID">Tax ID</label>
                                                <input type="text" id="modalEditTaxID" name="modalEditTaxID"
                                                    class="form-control modal-edit-tax-id" placeholder="123 456 7890">
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="modalEditUserPhone">Phone Number</label>
                                                <div class="input-group input-group-merge">
                                                    <span class="input-group-text">+1</span>
                                                    <input type="text" id="modalEditUserPhone" name="modalEditUserPhone"
                                                        class="form-control phone-number-mask"
                                                        placeholder="202 555 0111">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="modalEditUserLanguage">Language</label>
                                                <div class="position-relative"><select id="modalEditUserLanguage"
                                                        name="modalEditUserLanguage"
                                                        class="select2 form-select select2-hidden-accessible"
                                                        multiple="" data-select2-id="modalEditUserLanguage"
                                                        tabindex="-1" aria-hidden="true">
                                                        <option value="">Select</option>
                                                        <option value="english" selected="" data-select2-id="2">English
                                                        </option>
                                                        <option value="spanish">Spanish</option>
                                                        <option value="french">French</option>
                                                        <option value="german">German</option>
                                                        <option value="dutch">Dutch</option>
                                                        <option value="hebrew">Hebrew</option>
                                                        <option value="sanskrit">Sanskrit</option>
                                                        <option value="hindi">Hindi</option>
                                                    </select><span
                                                        class="select2 select2-container select2-container--default"
                                                        dir="ltr" data-select2-id="1" style="width: auto;"><span
                                                            class="selection"><span
                                                                class="select2-selection select2-selection--multiple"
                                                                role="combobox" aria-haspopup="true"
                                                                aria-expanded="false" tabindex="-1"
                                                                aria-disabled="false">
                                                                <ul class="select2-selection__rendered">
                                                                    <li class="select2-selection__choice"
                                                                        title="English" data-select2-id="3"><span
                                                                            class="select2-selection__choice__remove"
                                                                            role="presentation">×</span>English</li>
                                                                    <li class="select2-search select2-search--inline">
                                                                        <input class="select2-search__field"
                                                                            type="search" tabindex="0"
                                                                            autocomplete="off" autocorrect="off"
                                                                            autocapitalize="none" spellcheck="false"
                                                                            role="searchbox" aria-autocomplete="list"
                                                                            placeholder="" style="width: 0.75em;"></li>
                                                                </ul>
                                                            </span></span><span class="dropdown-wrapper"
                                                            aria-hidden="true"></span></span></div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label" for="modalEditUserCountry">Country</label>
                                                <div class="position-relative"><select id="modalEditUserCountry"
                                                        name="modalEditUserCountry"
                                                        class="select2 form-select select2-hidden-accessible"
                                                        data-allow-clear="true" data-select2-id="modalEditUserCountry"
                                                        tabindex="-1" aria-hidden="true">
                                                        <option value="" data-select2-id="5">Select</option>
                                                        <option value="Australia">Australia</option>
                                                        <option value="Bangladesh">Bangladesh</option>
                                                        <option value="Belarus">Belarus</option>
                                                        <option value="Brazil">Brazil</option>
                                                        <option value="Canada">Canada</option>
                                                        <option value="China">China</option>
                                                        <option value="France">France</option>
                                                        <option value="Germany">Germany</option>
                                                        <option value="India">India</option>
                                                        <option value="Indonesia">Indonesia</option>
                                                        <option value="Israel">Israel</option>
                                                        <option value="Italy">Italy</option>
                                                        <option value="Japan">Japan</option>
                                                        <option value="Korea">Korea, Republic of</option>
                                                        <option value="Mexico">Mexico</option>
                                                        <option value="Philippines">Philippines</option>
                                                        <option value="Russia">Russian Federation</option>
                                                        <option value="South Africa">South Africa</option>
                                                        <option value="Thailand">Thailand</option>
                                                        <option value="Turkey">Turkey</option>
                                                        <option value="Ukraine">Ukraine</option>
                                                        <option value="United Arab Emirates">United Arab Emirates
                                                        </option>
                                                        <option value="United Kingdom">United Kingdom</option>
                                                        <option value="United States">United States</option>
                                                    </select><span
                                                        class="select2 select2-container select2-container--default"
                                                        dir="ltr" data-select2-id="4" style="width: auto;"><span
                                                            class="selection"><span
                                                                class="select2-selection select2-selection--single"
                                                                role="combobox" aria-haspopup="true"
                                                                aria-expanded="false" tabindex="0" aria-disabled="false"
                                                                aria-labelledby="select2-modalEditUserCountry-container"><span
                                                                    class="select2-selection__rendered"
                                                                    id="select2-modalEditUserCountry-container"
                                                                    role="textbox" aria-readonly="true"><span
                                                                        class="select2-selection__placeholder">Select
                                                                        value</span></span><span
                                                                    class="select2-selection__arrow"
                                                                    role="presentation"><b
                                                                        role="presentation"></b></span></span></span><span
                                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <label class="switch">
                                                    <input type="checkbox" class="switch-input">
                                                    <span class="switch-toggle-slider">
                                                        <span class="switch-on"></span>
                                                        <span class="switch-off"></span>
                                                    </span>
                                                    <span class="switch-label">Use as a billing address?</span>
                                                </label>
                                            </div>
                                            <div class="col-12 text-center">
                                                <button type="submit"
                                                    class="btn btn-primary me-sm-3 me-1">Submit</button>
                                                <button type="reset" class="btn btn-label-secondary"
                                                    data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                                            </div>
                                            <input type="hidden">
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Edit User Modal -->

                        <!-- Add New Credit Card Modal -->
                        <div class="modal fade" id="upgradePlanModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-simple modal-upgrade-plan">
                                <div class="modal-content p-3 p-md-5">
                                    <div class="modal-body">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                        <div class="text-center mb-4">
                                            <h3>Upgrade Plan</h3>
                                            <p>Choose the best plan for user.</p>
                                        </div>
                                        <form id="upgradePlanForm" class="row g-3" onsubmit="return false">
                                            <div class="col-sm-9">
                                                <label class="form-label" for="choosePlan">Choose Plan</label>
                                                <select id="choosePlan" name="choosePlan" class="form-select"
                                                    aria-label="Choose Plan">
                                                    <option selected="">Choose Plan</option>
                                                    <option value="standard">Standard - $99/month</option>
                                                    <option value="exclusive">Exclusive - $249/month</option>
                                                    <option value="Enterprise">Enterprise - $499/month</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary">Upgrade</button>
                                            </div>
                                        </form>
                                    </div>
                                    <hr class="mx-md-n5 mx-n3">
                                    <div class="modal-body">
                                        <h6 class="mb-0">User current plan is standard plan</h6>
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <div class="d-flex justify-content-center me-2 mt-3">
                                                <sup
                                                    class="h5 pricing-currency pt-1 mt-3 mb-0 me-1 text-primary">$</sup>
                                                <h1 class="display-3 mb-0 text-primary">99</h1>
                                                <sub class="h5 pricing-duration mt-auto mb-2">/month</sub>
                                            </div>
                                            <button class="btn btn-label-danger cancel-subscription mt-3">Cancel
                                                Subscription</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/ Add New Credit Card Modal -->

                        <!-- /Modal -->
                    </div>
                </div>
                <!-- / Content -->

                <!-- Footer -->

                <?php
                        include("includes/footer.php"); 
                    ?>

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
    <script src="./assets/js/pages-account-settings-account.js"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>