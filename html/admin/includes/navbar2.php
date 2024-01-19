<?php
    
    $tai_khoan = "SELECT * FROM nguoi_dung WHERE nd_username='".$_SESSION['Admin']."'";
    $result_tai_khoan = mysqli_query($conn,$tai_khoan);
    $row_tai_khoan = mysqli_fetch_assoc($result_tai_khoan);

    $bv_da_xem = "SELECT count(*) as Tong_bv_xem 
                FROM bai_viet a 
                LEFT JOIN lich_su_xem l ON l.bv_ma = a.bv_ma
                where a.bv_ma in (select bv_ma from lich_su_xem where nd_username ='".$_SESSION['Admin']."') and l.bl_ma is null;";
    $result_bv_da_xem= mysqli_query($conn,$bv_da_xem);
    $row_bv_da_xem= mysqli_fetch_assoc($result_bv_da_xem);

    $bl_da_xem = "SELECT count(*) as Tong_bl_xem 
                    FROM binh_luan a 
                    LEFT JOIN lich_su_xem l ON l.bl_ma = a.bl_ma
                    where a.bl_ma in (select bl_ma from lich_su_xem where nd_username ='".$_SESSION['Admin']."') ;";
    $result_bl_da_xem= mysqli_query($conn,$bl_da_xem);
    $row_bl_da_xem= mysqli_fetch_assoc($result_bl_da_xem);

    $so_bv = "SELECT count(*) as Tong FROM bai_viet";
    $result_so_bv = mysqli_query($conn, $so_bv);
    $row_so_bv = mysqli_fetch_assoc($result_so_bv);

    $so_bl = "SELECT count(*) as Tong FROM binh_luan";
    $result_so_bl = mysqli_query($conn, $so_bl);
    $row_so_bl = mysqli_fetch_assoc($result_so_bl);

?>
<style>.badge.badge-notifications:not(.badge-dot) {
   
    font-size: .582rem;
    line-height: .75rem;
}</style>
<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <!-- Search -->
        <div class="navbar-nav align-items-center">
            <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Tìm kiếm..."
                    aria-label="Tìm kiếm..." />
            </div>
        </div>
        <!-- /Search -->

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Notification. -->
            <li class="nav-item navbar-dropdown dropdown-notification dropdown me-4">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                <i class="bx bx-bell bx-sm"></i>
                    <?php 
                        $binhluan_chuaxem = $row_so_bl['Tong'] - $row_bl_da_xem['Tong_bl_xem'] ;
                        $baiviet_chuaxem = $row_so_bv['Tong'] - $row_bv_da_xem['Tong_bv_xem'] ;
                        $thongbao_chuaxem = $binhluan_chuaxem + $baiviet_chuaxem;
                    
                        if($thongbao_chuaxem > 0){
                            echo " <span style='    position: absolute;
                            top: auto;
                            display: inline-block;
                            margin: 0;
                            transform: translate(-50%, -30%);' class='badge bg-danger rounded-pill badge-notifications'>$thongbao_chuaxem </span>";
                        }else{

                        }
                        
                    ?>

                </a>
                <style>
                /* Định dạng khung cuộn thông báo */
                .notification-scroll {
                    max-height: 400px;
                    /* Điều chỉnh độ cao tối đa của khung cuộn */
                    overflow-y: auto;
                    /* Tạo thanh cuộn dọc khi nội dung vượt quá kích thước khung */
                    overflow-x: hidden;
                    /* Ẩn thanh cuộn ngang */
                    /* border: 1px solid #ccc; */
                    /* Viền bao quanh thanh cuộn */
                    /* background-color: #f5f5f5; */
                    /* Màu nền của thanh cuộn */
                    border-radius: 8px;
                    /* Góc bo tròn */
                    /* box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); */
                    /* Bóng đổ */
                }

                /* Định dạng thanh cuộn */
                .notification-scroll::-webkit-scrollbar {
                    width: 5px;
                    /* Chiều rộng của thanh cuộn */
                    background-color:white;
                    
                }

                /* Định dạng nút cuộn (phần có thể kéo) */
                .notification-scroll::-webkit-scrollbar-thumb {
                    background: gray;
                    /* Màu nền của nút cuộn */
                    border-radius: 8px;
                    /* Góc bo tròn cho nút cuộn */
                }

                /* Định dạng nút cuộn khi di chuột qua */
                .notification-scroll::-webkit-scrollbar-thumb:hover {
                    background: #555;
                    /* Màu nền khi di chuột qua */
                }

                .text-container {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    /* Giới hạn số dòng hiển thị */
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                    text-overflow: ellipsis;
                    /* Hiển thị dấu ba chấm khi nội dung bị cắt ngắn */
                    white-space: pre-wrap;
                }
               

                </style>

                <ul style="width:350px; height: 400px" class="dropdown-menu dropdown-menu-end notification-scroll" data-bs-popper="static">
                    <form method="POST">
                        <li >
                            <div class="dropdown-header d-flex align-items-center py-3">
                            <h5 class="text-body mb-0 me-auto">Thông báo</h5>
                            <button name = "doc_tatca" style = "border: none; background-color: white" href="javascript:void(0)" class="dropdown-notifications-all text-body" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Đánh dấu đã đọc" data-bs-original-title="Đánh dấu đã đọc"><i class="bx fs-4 bx-envelope-open"></i></button>
                            </div>
                        </li>
                    </form>
                    <?php 
                        // Thực hiện truy vấn SQL để kiểm tra xem bài viết đã được xem hay chưa        
                        foreach ($thongbao as $tb) {?>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <form method="POST">
                        <li>
                            <?php
                            if($tb['da_xem'] == "" ){
                                if($tb['nd_bl'] !== "" ){?> 
                                    
                                    <button name="chitiet_bl" class="dropdown-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar ">
                                                    <img src="assets/img/avatars/<?php echo $tb['hinh']; ?>"
                                                        alt class="w-px-40 h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                        
                                                <h6 class="mb-1 text-container"> <?php echo $tb['hoten']; ?></h6>
                                                <p  class=" mb-0 text-container"> Đã bình luận bài viết "<i><b><?php echo $tb['tieude']; ?></b></i>": <?php echo $tb['nd_bl']; ?></p>
                                                <small  class="text-muted">
                                                    <?php  
                                                                    // Lấy thời gian của bài viết và thời gian hiện tại
                                                                    $commentDate = strtotime($tb['ngaydang']);
                                                                    $currentTime = time();
                                                                    $timeDiff = $currentTime - $commentDate;
                                                                    
                                                                    // Tính thời gian dưới dạng phút, giờ và ngày
                                                                    $minutes = floor($timeDiff / 60);
                                                                    $hours = floor($minutes / 60);
                                                                    $days = floor($hours / 24);
                                                                    
                                                                    // Hiển thị thông điệp dựa trên thời gian
                                                                    if ($minutes < 1) {
                                                                        echo "Vừa xong";
                                                                    } elseif ($minutes < 60) {
                                                                        echo $minutes . " phút trước";
                                                                    } elseif ($hours == 1) {
                                                                        echo "1 giờ trước";
                                                                    } elseif ($hours < 24) {
                                                                        echo $hours . " giờ trước";
                                                                    } elseif ($days == 1) {
                                                                        echo "1 ngày trước";
                                                                    } else {
                                                                        if ($days < 7) {
                                                                            echo $days . " ngày trước";
                                                                        } else {
                                                                            echo date('d/m/Y', $commentDate); // Hiển thị ngày tháng năm gốc nếu đã qua 7 ngày.
                                                                        }
                                                                    }                                              
                                                                ?>
                                                </small>
                                            </div>
                                                            
                                            <span ><i style="font-size: 10px; color:#696cff" class="fa fa-circle" aria-hidden="true" ></i></span> 
                                                            
                                        </div>
                                    </button>

                                <?php  } else { ?>

                                    <button name="chitiet_bv" class="dropdown-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar ">
                                                    <img src="assets/img/avatars/<?php echo $tb['hinh']; ?>"
                                                        alt class="w-px-40 h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                        
                                                <h6 class="mb-1 "> <?php echo $tb['hoten']; ?></h6>
                                                <p  class=" mb-0 text-container"> Đã đăng bài viết "<i><b><?php echo $tb['tieude']; ?></b></i>"</p>
                                                <small  class="text-muted">
                                                    <?php  
                                                                    // Lấy thời gian của bài viết và thời gian hiện tại
                                                                    $commentDate = strtotime($tb['ngaydang']);
                                                                    $currentTime = time();
                                                                    $timeDiff = $currentTime - $commentDate;
                                                                    
                                                                    // Tính thời gian dưới dạng phút, giờ và ngày
                                                                    $minutes = floor($timeDiff / 60);
                                                                    $hours = floor($minutes / 60);
                                                                    $days = floor($hours / 24);
                                                                    
                                                                    // Hiển thị thông điệp dựa trên thời gian
                                                                    if ($minutes < 1) {
                                                                        echo "Vừa xong";
                                                                    } elseif ($minutes < 60) {
                                                                        echo $minutes . " phút trước";
                                                                    } elseif ($hours == 1) {
                                                                        echo "1 giờ trước";
                                                                    } elseif ($hours < 24) {
                                                                        echo $hours . " giờ trước";
                                                                    } elseif ($days == 1) {
                                                                        echo "1 ngày trước";
                                                                    } else {
                                                                        if ($days < 7) {
                                                                            echo $days . " ngày trước";
                                                                        } else {
                                                                            echo date('d/m/Y', $commentDate); // Hiển thị ngày tháng năm gốc nếu đã qua 7 ngày.
                                                                        }
                                                                    }                                              
                                                                ?>
                                                </small>
                                            </div>
                                                            
                                            <span ><i style="font-size: 10px; color:#696cff" class="fa fa-circle" aria-hidden="true" ></i></span> 
                                                            
                                        </div>
                                    </button>

                                <?php  }  ?>

                            <?php } else{
                                if($tb['nd_bl'] !== ""){?> 

                                    <button style="color: #aaaaaa " name="chitiet_bl" class="dropdown-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar ">
                                                    <img src="assets/img/avatars/<?php echo $tb['hinh']; ?>"
                                                        alt class="w-px-40 h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                        
                                                <h6 style="color: #aaaaaa " class="mb-1 text-container"> <?php echo $tb['hoten']; ?></h6>
                                                <p  class=" mb-0 text-container"> Đã bình luận bài viết "<i><b><?php echo $tb['tieude']; ?></b></i>": <?php echo $tb['nd_bl']; ?></p>
                                                <small  class="text-muted">
                                                    <?php  
                                                                    // Lấy thời gian của bài viết và thời gian hiện tại
                                                                    $commentDate = strtotime($tb['ngaydang']);
                                                                    $currentTime = time();
                                                                    $timeDiff = $currentTime - $commentDate;
                                                                    
                                                                    // Tính thời gian dưới dạng phút, giờ và ngày
                                                                    $minutes = floor($timeDiff / 60);
                                                                    $hours = floor($minutes / 60);
                                                                    $days = floor($hours / 24);
                                                                    
                                                                    // Hiển thị thông điệp dựa trên thời gian
                                                                    if ($minutes < 1) {
                                                                        echo "Vừa xong";
                                                                    } elseif ($minutes < 60) {
                                                                        echo $minutes . " phút trước";
                                                                    } elseif ($hours == 1) {
                                                                        echo "1 giờ trước";
                                                                    } elseif ($hours < 24) {
                                                                        echo $hours . " giờ trước";
                                                                    } elseif ($days == 1) {
                                                                        echo "1 ngày trước";
                                                                    } else {
                                                                        if ($days < 7) {
                                                                            echo $days . " ngày trước";
                                                                        } else {
                                                                            echo date('d/m/Y', $commentDate); // Hiển thị ngày tháng năm gốc nếu đã qua 7 ngày.
                                                                        }
                                                                    }                                              
                                                                ?>
                                                </small>
                                            </div>
                                                            
                                        
                                                            
                                        </div>
                                    </button>

                                <?php  } else { ?>

                                    <button style="color: #aaaaaa " name="chitiet_bv" class="dropdown-item">
                                        <div class="d-flex">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar ">
                                                    <img src="assets/img/avatars/<?php echo $tb['hinh']; ?>"
                                                        alt class="w-px-40 h-auto rounded-circle" />
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                        
                                                <h6 style="color: #aaaaaa " class="mb-1 "> <?php echo $tb['hoten']; ?></h6>
                                                <p  class=" mb-0 text-container"> Đã đăng bài viết "<i><b><?php echo $tb['tieude']; ?></b></i>"</p>
                                                <small  class="text-muted">
                                                    <?php  
                                                                    // Lấy thời gian của bài viết và thời gian hiện tại
                                                                    $commentDate = strtotime($tb['ngaydang']);
                                                                    $currentTime = time();
                                                                    $timeDiff = $currentTime - $commentDate;
                                                                    
                                                                    // Tính thời gian dưới dạng phút, giờ và ngày
                                                                    $minutes = floor($timeDiff / 60);
                                                                    $hours = floor($minutes / 60);
                                                                    $days = floor($hours / 24);
                                                                    
                                                                    // Hiển thị thông điệp dựa trên thời gian
                                                                    if ($minutes < 1) {
                                                                        echo "Vừa xong";
                                                                    } elseif ($minutes < 60) {
                                                                        echo $minutes . " phút trước";
                                                                    } elseif ($hours == 1) {
                                                                        echo "1 giờ trước";
                                                                    } elseif ($hours < 24) {
                                                                        echo $hours . " giờ trước";
                                                                    } elseif ($days == 1) {
                                                                        echo "1 ngày trước";
                                                                    } else {
                                                                        if ($days < 7) {
                                                                            echo $days . " ngày trước";
                                                                        } else {
                                                                            echo date('d/m/Y', $commentDate); // Hiển thị ngày tháng năm gốc nếu đã qua 7 ngày.
                                                                        }
                                                                    }                                              
                                                                ?>
                                                </small>
                                            </div>
                                                            
                                                        
                                        </div>
                                    </button>

                                <?php  }  ?>
                            <?php } ?>
                                
                                <input name="xem_bv_ma" type="hidden" value="<?php echo $tb['ma_bv']; ?>">
                                <input name="xem_bl_ma" type="hidden" value="<?php echo $tb['ma_bl']; ?>">
                                <input name="bv_user" type="hidden" value="<?php echo $tb['username']; ?>">

                        </li>    
                    </form>                  
                    <?php } ?>
                </ul>
            </li>
            
            <!-- / Notification -->

            <!-- User -->
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                        <img src="assets/img/avatars/<?php echo $row_tai_khoan['nd_hinh']; ?>" alt
                            class="w-px-40 h-auto rounded-circle" />
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="TaiKhoan.php">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="assets/img/avatars/<?php echo $row_tai_khoan['nd_hinh']; ?>" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">
                                        <?php echo $row_tai_khoan['nd_hoten'];?>
                                    </span>
                                    <small class="text-muted">
                                        <?php echo $_SESSION['vaitro']?>
                                    </small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="TaiKhoan.php">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">Tài khoản</span>
                        </a>
                    </li>
                    <!-- <li>
                        <a class="dropdown-item" href="#">
                            <i class="bx bx-cog me-2"></i>
                            <span class="align-middle">Settings</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <span class="d-flex align-items-center align-middle">
                                <i class="flex-shrink-0 bx bx-credit-card me-2"></i>
                                <span class="flex-grow-1 align-middle">Billing</span>
                                <span
                                    class="flex-shrink-0 badge badge-center rounded-pill bg-danger w-px-20 h-px-20">4</span>
                            </span>
                        </a>
                    </li> -->
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="DangXuat.php">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Đăng xuất</span>
                        </a>
                    </li>
                </ul>
            </li>
            <!--/ User -->
        </ul>
    </div>
</nav>