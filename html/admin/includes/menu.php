<?php
    
    // Kiểm tra xem người dùng đã đăng nhập và có vai trò gì
    if(isset($_SESSION['vaitro'])){
        // Danh sách các trang quản lý của admin
        $adminPages = array("QL_DanhMuc.php", "QL_KhoiLop.php", "QL_MonHoc.php", "QL_NguoiDung.php", "QL_BinhLuan.php", "PCQL_DanhMuc.php");

        // Lấy đường dẫn hiện tại của người dùng
        $currentPage = basename($_SERVER['SCRIPT_FILENAME']);

        // Kiểm tra quyền truy cập
        if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
            header("Refresh: 0;url=error.php");  
            exit;
        }
        if($_SESSION['vaitro'] == 'Super Admin'){?>
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.php" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <img src="assets/img/logo/logo.png" alt style="width:30%" />
                        </span>
                        <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">SHARE</span> -->
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                        <i class="bx bx-chevron-left bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item ">
                        <a href="index.php" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Analytics">Bảng điều khiển</div>
                        </a>
                    </li>

                    <!-- Layouts -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-layout"></i>
                            <div data-i18n="Layouts">Quản lý người dùng</div>
                        </a>

                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="ThemNguoiDung.php" class="menu-link">
                                    <div data-i18n="Container">Thêm người dùng</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="QL_NguoiDung.php?vt=2" class="menu-link">
                                    <div data-i18n="Layouts">Quản lý Admin</div>
                                </a>

                            </li>
                            <li class="menu-item">
                                <a href="QL_NguoiDung.php?vt=3" class="menu-link">
                                    <div data-i18n="Without navbar">Quản lý Giáo viên</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="QL_NguoiDung.php?vt=4" class="menu-link">
                                    <div data-i18n="Container">Quản lý Học sinh</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    



                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-file"></i>
                            <div data-i18n="Account Settings">Quản lý bài viết</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="QL_BaiViet.php" class="menu-link">
                                    <div data-i18n="Account">Danh sách bài viết</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="KiemDuyet.php" class="menu-link">
                                    <div data-i18n="Notifications">Kiểm duyệt bài viết</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item">
                        <a href="QL_BinhLuan.php" class="menu-link ">
                            <i class="fa fa-comments menu-icon tf-icons"></i>
                            <div data-i18n="Account Settings">Quản lý bình luận</div>
                        </a>                   
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-box"></i>
                            <div data-i18n="Basic">Quản lý danh mục</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="QL_DanhMuc.php" class="menu-link">
                                    <div data-i18n="Basic">Danh sách danh mục</div>
                                </a>
                            </li>
                            <!-- <li class="menu-item">
                                <a href="DanhMuc_PhanCap.php" class="menu-link">
                                    <div data-i18n="Basic">Cập nhật người quản lý </div>
                                </a>
                            </li> -->
                            <li class="menu-item">
                                <a href="Them_DanhMuc.php" class="menu-link" target="_blank">
                                    <div data-i18n="Basic">Thêm danh mục</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item ">
                        <a href="PCQL_DanhMuc.php" class="menu-link">
                            <!-- <i class="menu-icon tf-icons bx bx-home-circle"></i> -->
                            <i class="fa fa-pencil-square menu-icon tf-icons"></i>
                            <div data-i18n="Analytics">Phân công người quản trị danh mục</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons fa fa-book"></i>
                            <div data-i18n="Misc">Quản lý môn học</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="QL_MonHoc.php" class="menu-link">
                                    <div data-i18n="Basic">Danh sách môn học</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="Them_MonHoc.php" class="menu-link" target="_blank">
                                    <div data-i18n="Basic">Thêm môn học</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-dock-top"></i>
                            <div data-i18n="Misc">Quản lý khối lớp</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="QL_KhoiLop.php" class="menu-link">
                                    <div data-i18n="Basic">Danh sách khối lớp</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="Them_KhoiLop.php" class="menu-link" target="_blank">
                                    <div data-i18n="Basic">Thêm khối lớp</div>
                                </a>
                            </li>
                        </ul>
                    </li>
            </aside>
        <!-- / Menu -->
        <?php }
                elseif($_SESSION['vaitro'] == 'Admin'){?>
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
            <div class="app-brand demo">
                <a href="index.php" class="app-brand-link">
                    <span class="app-brand-logo demo">
                        <img src="assets/img/logo/logo.png" alt style="width:30%" />
                    </span>
                    <!-- <span class="app-brand-text demo menu-text fw-bolder ms-2">SHARE</span> -->
                </a>

                <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm align-middle"></i>
                </a>
            </div>

            <div class="menu-inner-shadow"></div>

            <ul class="menu-inner py-1">
                <!-- Dashboard -->
                <li class="menu-item ">
                    <a href="index.php" class="menu-link">
                        <i class="menu-icon tf-icons bx bx-home-circle"></i>
                        <div data-i18n="Analytics">Bảng điều khiển</div>
                    </a>
                </li>

                <!-- Layouts -->
                <li class="menu-item">
                    <a style="pointer-events: none;cursor: not-allowed;color:#bbc6d9" href="javascript:void(0);"
                        class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-layout"></i>
                        <div data-i18n="Layouts">Quản lý người dùng</div>
                    </a>

                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="ThemNguoiDung.php" class="menu-link">
                                <div data-i18n="Container">Thêm người dùng</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="QL_NguoiDung.php?vt=2" class="menu-link">
                                <div data-i18n="Layouts">Quản lý Admin</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="QL_NguoiDung.php?vt=3" class="menu-link">
                                <div data-i18n="Without navbar">Quản lý Giáo viên</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="QL_NguoiDung.php?vt=4" class="menu-link">
                                <div data-i18n="Container">Quản lý Học sinh</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-file"></i>
                        <div data-i18n="Account Settings">Quản lý bài viết</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="QL_BaiViet.php" class="menu-link">
                                <div data-i18n="Account">Danh sách bài viết</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="KiemDuyet.php" class="menu-link">
                                <div data-i18n="Notifications">Kiểm duyệt bài viết</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item">
                    <a style="pointer-events: none;cursor: not-allowed;color:#bbc6d9" href="QL_BinhLuan.php" class="menu-link ">
                        <i class="fa fa-comments menu-icon tf-icons"></i>
                        <div data-i18n="Account Settings">Quản lý bình luận</div>
                    </a>                   
                </li>

                <li class="menu-item">
                    <a style="pointer-events: none;cursor: not-allowed;color:#bbc6d9" href="javascript:void(0);"
                        class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-box"></i>
                        <div data-i18n="Basic">Quản lý danh mục</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="QL_DanhMuc.php" class="menu-link">
                                <div data-i18n="Basic">Danh sách danh mục</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="Them_DanhMuc.php" class="menu-link" target="_blank">
                                <div data-i18n="Basic">Thêm danh mục</div>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-item ">
                        <a style="pointer-events: none;cursor: not-allowed;color:#bbc6d9" href="PCQL_DanhMuc.php" class="menu-link">
                            <!-- <i class="menu-icon tf-icons bx bx-home-circle"></i> -->
                            <i class="fa fa-pencil-square menu-icon tf-icons"></i>
                            <div data-i18n="Analytics">Phân công người quản trị danh mục</div>
                        </a>
                    </li>

                <li class="menu-item">
                    <a style="pointer-events: none;cursor: not-allowed;color:#bbc6d9" href="javascript:void(0);"
                        class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons fa fa-book"></i>
                        <div data-i18n="Misc">Quản lý môn học</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="QL_MonHoc.php" class="menu-link">
                                <div data-i18n="Basic">Danh sách môn học</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="Them_MonHoc.php" class="menu-link" target="_blank">
                                <div data-i18n="Basic">Thêm môn học</div>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="menu-item">
                    <a style="pointer-events: none;cursor: not-allowed;color:#bbc6d9" href="javascript:void(0);"
                        class="menu-link menu-toggle">
                        <i class="menu-icon tf-icons bx bx-dock-top"></i>
                        <div data-i18n="Misc">Quản lý khối lớp</div>
                    </a>
                    <ul class="menu-sub">
                        <li class="menu-item">
                            <a href="QL_KhoiLop.php" class="menu-link">
                                <div data-i18n="Basic">Danh sách khối lớp</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="Them_KhoiLop.php" class="menu-link" target="_blank">
                                <div data-i18n="Basic">Thêm khối lớp</div>
                            </a>
                        </li>
                    </ul>
                </li>
        </aside>
        <!-- / Menu -->
        <?php } 
                else {
                    echo "<script>alert('Bạn chưa đăng nhập tài khoản quản trị! Hãy đăng nhập để tiếp tục.');</script>"; 
                    header("Refresh: 0;url=login.php");  
                    exit;
                }
            }
            else {
                echo '<script>
                                Swal.fire({
                                    title: "Lỗi!",
                                    text: "Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục nhé",
                                    icon: "error",
                                    
                                });
                    </script>';
                header("Refresh: 3;url=login.php");  
                exit;
            }

        ?>