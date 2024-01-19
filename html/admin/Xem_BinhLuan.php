<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}
$this_bl_ma = $_GET['bl_ma'];

$binh_luan = "SELECT *
                    FROM binh_luan l
                    LEFT JOIN bai_viet a ON a.bv_ma = l.bv_ma
                    LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                    LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                    LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                    LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                    LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                    
                    where bl_ma = '$this_bl_ma'";

$result_binh_luan = mysqli_query($conn, $binh_luan);
$row_binh_luan = mysqli_fetch_assoc($result_binh_luan);
// if (isset($_POST['CapNhatMonHoc'])) {
//     $mh_ten = $_POST['mh_ten'];
//     $kl_ma = $_POST['kl_ma'];
//     $cap_nhat_mon_hoc = "UPDATE mon_hoc SET mh_ten = '$mh_ten', kl_ma = '$kl_ma' WHERE mh_ma='$this_mh_ma'";
//     mysqli_query($conn,$cap_nhat_mon_hoc);           
//     echo "<script>alert('Cập nhật môn học mới thành công!');</script>"; 
//     header("Refresh: 0;url=QL_MonHoc.php"); 	
// }

?>
<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Chi tiết bình luận</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

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
                                    <a href="QL_BinhLuan.php">Quản lý bình luận</a>
                                </li>
                                <li class="breadcrumb-item active">Chi tiết bình luận</li>
                            </ol>
                        </nav>

                        <!-- Basic Layout -->
                        <div class="card ">
                            <div class="card-body  g-3">

                                <h5 class="mb-2"><span class="badge bg-primary">Mã bài viết
                                        #<?php echo  $row_binh_luan['bv_ma']; ?></span></h5>

                                <h4 class="mb-2 text-center"><?php echo  $row_binh_luan['bv_tieude']; ?></h4>
                                <!-- <p class="mb-0 pt-1">Learn web design in 1 hour with 25+ simple-to-use rules and
                                        guidelines — tons
                                        of amazing web design resources included!</p> -->
                                <hr class="my-4">
                                <h5>Tóm tắt</h5>
                                <div class="d-flex flex-wrap">
                                    <div class="me-5">
                                        <p class="text-nowrap"><i class="bx bx-user bx-sm me-2"></i>Tác giả:
                                            <?php echo  $row_binh_luan['nd_hoten']; ?>
                                        </p>
                                        <p class="text-nowrap"><i class="fa fa-eye me-2"></i>Lượt xem:
                                            <?php echo  $row_binh_luan['bv_luotxem']; ?></p>

                                    </div>
                                    <div class="me-5">
                                        <p class="text-nowrap"><i class="fa fa-star me-2"></i>Điểm đánh
                                            giá: <?php echo  $row_binh_luan['bv_diemtrungbinh']; ?></p>
                                        <p class="text-nowrap "><i class="fa fa-calendar me-2"></i>Ngày xuất
                                            bản:
                                            <?php echo date_format(date_create($row_binh_luan['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>
                                        </p>
                                    </div>
                                    <div class="me-5">

                                        <p class="text-nowrap"><i class="fa fa-graduation-cap me-2"></i>Khối lớp:
                                            <?php echo  $row_binh_luan['kl_ten']; ?></p>
                                        <p class="text-nowrap "><i class="fa fa-bookmark me-2"></i>Môn học
                                            <?php echo  $row_binh_luan['mh_ten']; ?>
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-nowrap"><i class="fa fa-folder me-2"></i>Danh mục:
                                            <?php echo  $row_binh_luan['dm_ten']; ?></p>
                                    </div>
                                </div>
                                <hr class="mb-4 mt-2">
                                <h5>Nội dung</h5>
                                <p class="mb-4"> <?php echo $row_binh_luan['bv_noidung']; ?></p>

                                <h5>Tài liệu đính kèm</h5>
                                <p class="mb-4">
                                    <?php
                                    if ($row_binh_luan['tl_tentaptin'] == null) {
                                        echo "<i>(Không có tài liệu đính kèm)</i>";
                                    } else {
                                        $tailieu = "SELECT * from tai_lieu
                                                        WHERE bv_ma='" . $row_binh_luan['bv_ma'] . "'";

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
                                <h5>Bình luận</h5>
                                <?php
                                $id = $row_binh_luan['bv_ma'];
                $sql = "SELECT DISTINCT b.*, c.*,d.* , CURRENT_TIMESTAMP()
                  FROM bai_viet a
                  LEFT JOIN binh_luan b ON a.bv_ma = b.bv_ma
                  JOIN nguoi_dung c ON b.nd_username = c.nd_username
                  JOIN vai_tro d ON c.vt_ma = d.vt_ma
                  LEFT JOIN rep_bl r ON b.bl_ma = r.bl_cha
                  WHERE a.bv_ma = '$id' AND b.trangthai = 1
                  -- AND b.bl_ma NOT IN (SELECT bl_con FROM rep_bl WHERE bl_con IS NOT NULL)
                  ";
                $result = mysqli_query($conn, $sql);

                while ($binhluan = mysqli_fetch_array($result)) {
                  $parentCommentId = $binhluan['bl_ma'];
                  $currentTimestamp = strtotime($binhluan['bl_thoigian']);
                  $current_time = strtotime($binhluan['CURRENT_TIMESTAMP()']); // Get the current Unix timestamp
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

                  // ID of the parent comment (if applicable)

                ?>
                
                <style>
    .selected-comment {
        background-color:#DEE0E1  ;
    }

   
</style>

                <?php
if (isset($_GET['bl_ma'])) { 
$selectedCommentId = $_GET['bl_ma'];

// Assuming $binhluan['bl_ma'] is the comment ID from the current iteration in the loop
$currentCommentId = $binhluan['bl_ma'];

// Define a CSS class based on whether the current comment is selected or not
$commentClass = ($selectedCommentId == $currentCommentId) ? 'selected-comment' : '';

?>
                  <div class="d-flex mb-4 ">
                    <div class="flex-shrink-0 pt-1">
                      <img src="assets/img/avatars/<?php echo $binhluan['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
                    </div>
                    <div class="flex-grow-1 ">

                      <div class="col-9 mb-sm-0 rounded p-1 <?php echo $commentClass; ?>"id="comment-<?php echo $currentCommentId; ?>" >
                        <h6 class="mb-0 text-dark"><a style="color:#0768ea;" href="profile.php?id=<?php echo $binhluan['nd_username'] ?>">
                            <?php echo $binhluan['nd_hoten'] ?>
                            <?php if ($binhluan['vt_ma'] == 1) { ?>
                              <i class="fa-solid fa-crown" style="color:#ffbf00"></i>
                            <?php } ?>
                            <?php if ($binhluan['vt_ma'] == 2) { ?>
                              <i class="fa-solid fa-shield" style="color: #fbc900;"></i>
                            <?php } ?>

                          </a>
                          <?php
                          $style = '';
                          if ($binhluan['vt_ma'] == 1)
                            $style = 'style="color: #ED4245; background-color: #fde3e3;"';
                          if ($binhluan['vt_ma'] == 2)
                            $style = 'style="color: #ef8843; background-color: #fdede3"';
                          if ($binhluan['vt_ma'] == 3)
                            $style = 'style="color: #ffb700; background-color: #fff4d9"';
                          if ($binhluan['vt_ma'] == 4)
                            $style = 'style="color: #9b98a9; background-color: #f0f0f2;"';
                          ?>
                          <span <?php echo $style; ?> class="badge ms-1"><?php echo $binhluan['vt_ten']; ?></span>

                          <span style="color:#0E21A0" class="ms-1 "><small> <?php if ($binhluan['nd_username'] == $row_binh_luan['nd_username']) echo 'Tác giả' ?></small></span>

                        </h6>
                        <span class="text-dark"><?php echo $binhluan['bl_noidung'] ?></span>
                      </div>
                      <div class="mt-1">
                        <small><span> <i class="fa-solid fa-thumbs-up"></i> Thích</span></small> &ensp;
                        <!-- <span class="reply-button"><small><i class="fa-solid fa-reply"></i> Trả lời</small></span>&ensp; -->
                        <span style="cursor: pointer;" class="reply-button1" data-comment-id="<?php echo $binhluan['bl_ma']; ?>">
                          <small><i class="fa-solid fa-reply"></i> Trả lời</small>
                        </span>
                        &ensp;
                        <span><small class="text-muted ms-1"><?php echo $timeAgo ?></small></span>
                        <!-- <span class="reply-button"><i class="fa-solid fa-reply"></i> Trả lời</span> -->


                        <!-- The reply form for each comment, initially hidden -->

                        <div class="reply-form reply-form1-<?php echo $binhluan['bl_ma']; ?>" style="display: none;">
                          <form action="include/repbl.php" method="post">
                            <div class="form-group">


                              <label style="color:#000" for="exampleFormControlTextarea1"> <span style="font-size: 13px;font-weight:lighter;color:#000">Trả lời </span><?php
                                                                                                                                                                        echo $binhluan['nd_hoten'] ?></label>
                              <textarea name="noidung" required id="basic-default-message" class="form-control" placeholder="Viết bình luận"></textarea>
                              <?php
                              if (isset($_SESSION['user'])) {
                                $user = $_SESSION['user'];
                              ?>
                                <input name="bv-ma" type="hidden" value="<?php echo $id ?>">
                                <input name="bl-ma" type="hidden" value="<?php echo $binhluan['bl_ma']; ?>">
                                <input type="hidden" name="username" value="<?php echo $user['nd_username'] ?>">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                  <button name="repbl" type="submit" class="btn btn-primary btn-sm">Bình Luận</button>
                                </div>
                              <?php
                              } else {
                                // If the user session is not set, display a toast message
                              ?>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                  <button id="cmt" class="btn btn-primary btn-sm" onclick="showLoginToast()" name="repbl" type="button" class="btn btn-primary btn-sm">Bình Luận</button>
                                </div>

                              <?php
                              }
                              ?>

                            </div>
                          </form>
                          <!-- Nội dung của form trả lời -->
                        </div>

                        <!-- <span><small class="text-muted ms-1"><?php echo $timeAgo ?></small></span> -->

                      </div>


                    </div>

                  </div>
                  <?php } else { ?>
                    <div class="d-flex mb-4">
                    <div class="flex-shrink-0">
                      <img src="assets/img/avatars/<?php echo $binhluan['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
                    </div>
                    <div class="flex-grow-1">

                      <div class="col-9 mb-sm-0">
                        <h6 class="mb-0 text-dark"><a style="color:#0768ea;" href="profile.php?id=<?php echo $binhluan['nd_username'] ?>">
                            <?php echo $binhluan['nd_hoten'] ?>
                            <?php if ($binhluan['vt_ma'] == 1) { ?>
                              <i class="fa-solid fa-crown" style="color:#ffbf00"></i>
                            <?php } ?>
                            <?php if ($binhluan['vt_ma'] == 2) { ?>
                              <i class="fa-solid fa-shield" style="color: #fbc900;"></i>
                            <?php } ?>

                          </a>
                          <?php
                          $style = '';
                          if ($binhluan['vt_ma'] == 1)
                            $style = 'style="color: #ED4245; background-color: #fde3e3;"';
                          if ($binhluan['vt_ma'] == 2)
                            $style = 'style="color: #ef8843; background-color: #fdede3"';
                          if ($binhluan['vt_ma'] == 3)
                            $style = 'style="color: #ffb700; background-color: #fff4d9"';
                          if ($binhluan['vt_ma'] == 4)
                            $style = 'style="color: #9b98a9; background-color: #f0f0f2;"';
                          ?>
                          <span <?php echo $style; ?> class="badge ms-1"><?php echo $binhluan['vt_ten']; ?></span>
                          <span style="color:red">  <?php if ($repbinhluan['trangthai'] == 3) echo 'Chờ duyệt';
                            if ($repbinhluan['trangthai'] == 2) echo 'Đã bị hủy';
                            if ($repbinhluan['trangthai'] == 4) echo 'Đã bị xóa';
                             ?></span>
                          <span style="color:#0E21A0" class="ms-1 "><small> <?php if ($binhluan['nd_username'] == $row_binh_luan['nd_username']) echo 'Tác giả' ?></small></span>



                        </h6>
                        <span class="text-dark"><?php echo $binhluan['bl_noidung'] ?></span>
                      </div>
                      <div class="mt-1">
                        <small><span> <i class="fa-solid fa-thumbs-up"></i> Thích</span></small> &ensp;
                        <!-- <span class="reply-button"><small><i class="fa-solid fa-reply"></i> Trả lời</small></span>&ensp; -->
                        <span style="cursor: pointer;" class="reply-button1" data-comment-id="<?php echo $binhluan['bl_ma']; ?>">
                          <small><i class="fa-solid fa-reply"></i> Trả lời</small>
                        </span>
                        &ensp;
                        <span><small class="text-muted ms-1"><?php echo $timeAgo ?></small></span>
                        <!-- <span class="reply-button"><i class="fa-solid fa-reply"></i> Trả lời</span> -->


                        <!-- The reply form for each comment, initially hidden -->

                        <div class="reply-form reply-form1-<?php echo $binhluan['bl_ma']; ?>" style="display: none;">
                          <form action="include/repbl.php" method="post">
                            <div class="form-group">


                              <label style="color:#000" for="exampleFormControlTextarea1"> <span style="font-size: 13px;font-weight:lighter;color:#000">Trả lời </span><?php
                                                                                                                                                                        echo $binhluan['nd_hoten'] ?></label>
                              <textarea name="noidung" required id="basic-default-message" class="form-control" placeholder="Viết bình luận"></textarea>
                              <?php
                              if (isset($_SESSION['user'])) {
                                $user = $_SESSION['user'];
                              ?>
                                <input name="bv-ma" type="hidden" value="<?php echo $id ?>">
                                <input name="bl-ma" type="hidden" value="<?php echo $binhluan['bl_ma']; ?>">
                                <input type="hidden" name="username" value="<?php echo $user['nd_username'] ?>">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                  <button name="repbl" type="submit" class="btn btn-primary btn-sm">Bình Luận</button>
                                </div>
                              <?php
                              } else {
                                // If the user session is not set, display a toast message
                              ?>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                  <button id="cmt" class="btn btn-primary btn-sm" onclick="showLoginToast()" name="repbl" type="button" class="btn btn-primary btn-sm">Bình Luận</button>
                                </div>

                              <?php
                              }
                              ?>

                            </div>
                          </form>
                          <!-- Nội dung của form trả lời -->
                        </div>

                        <!-- <span><small class="text-muted ms-1"><?php echo $timeAgo ?></small></span> -->

                      </div>


                    </div>

                  </div>
              <?php     }
                  
                  ?>

                  <?php
                  // Display replies associated with the parent comment
                  $replySql = "WITH RECURSIVE binh_luan_paths AS
                    (
                      SELECT b1.bl_ma AS bl_cha, b2.bl_ma AS bl_con, 1 AS lvl
                      FROM binh_luan b1
                      LEFT JOIN rep_bl r1 ON b1.bl_ma = r1.bl_cha
                      LEFT JOIN binh_luan b2 ON r1.bl_con = b2.bl_ma
                      WHERE b1.bl_ma =  $parentCommentId
                    
                      UNION ALL
                    
                      SELECT b1.bl_ma AS bl_cha, b2.bl_ma AS bl_con, bp.lvl + 1 AS lvl
                      FROM binh_luan b1
                      LEFT JOIN rep_bl r1 ON b1.bl_ma = r1.bl_cha
                      LEFT JOIN binh_luan b2 ON r1.bl_con = b2.bl_ma
                      INNER JOIN binh_luan_paths bp ON b1.bl_ma = bp.bl_con
                    )
                    SELECT  * , CURRENT_TIMESTAMP()
                      FROM binh_luan_paths bp
                      INNER JOIN binh_luan bl ON bp.bl_con = bl.bl_ma
                      INNER JOIN nguoi_dung ngd ON bl.nd_username = ngd.nd_username
                      INNER JOIN vai_tro vt ON ngd.vt_ma = vt.vt_ma
                      where bl_con is not null
                      ORDER BY bl_con;
                     ";
                  $replyResult = mysqli_query($conn, $replySql);
                  while ($repbinhluan = mysqli_fetch_array($replyResult)) {
                    $bl_cha = $repbinhluan['bl_cha'];
                    $currentTimestamp = strtotime($repbinhluan['bl_thoigian']);
                    $current_time = strtotime($repbinhluan['CURRENT_TIMESTAMP()']); 
                    $timeDifference =  $current_time - $currentTimestamp;  

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
                   <?php
                   if (isset($_GET['bl_ma'])){
// Assuming $selectedCommentId is the comment ID extracted from the URL parameter 'bl_ma'
$selectedCommentId = $_GET['bl_ma'];

// Assuming $binhluan['bl_ma'] is the comment ID from the current iteration in the loop
$currentCommentId = $repbinhluan ['bl_ma'];

// Define a CSS class based on whether the current comment is selected or not
$commentClass = ($selectedCommentId == $currentCommentId) ? 'selected-comment' : '';
?>
                    <div class="d-flex ms-5 ">
                      <div id="commentList"></div>

                      <div class="flex-shrink-0 pt-2">
                        <img src="assets/img/avatars/<?php echo $repbinhluan['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
                      </div>
                      <div class="flex-grow-1 row ">
                        <div class="col-12  p-1 rounded <?php echo $commentClass; ?>"id="comment-<?php echo $currentCommentId; ?>" >
                          <h6 class="mb-0 text-dark"><a style="color:#0768ea;" href="profile.php?id=<?php echo $repbinhluan['nd_username'] ?>">
                              <?php echo $repbinhluan['nd_hoten'] ?>
                              <?php if ($repbinhluan['vt_ma'] == 1) { ?>
                                <i class="fa-solid fa-crown" style="color:#ffbf00"></i>
                              <?php } ?>
                            </a>
                            <?php
                            $style = '';
                            if ($repbinhluan['vt_ma'] == 1)
                              $style = 'style="color: #ED4245; background-color: #fde3e3;"';
                            if ($repbinhluan['vt_ma'] == 2)
                              $style = 'style="color: #ef8843; background-color: #fdede3"';
                            if ($repbinhluan['vt_ma'] == 3)
                              $style = 'style="color: #ffb700; background-color: #fff4d9"';
                            if ($repbinhluan['vt_ma'] == 4)
                              $style = 'style="color: #9b98a9; background-color: #f0f0f2;"';
                            ?>
                            <span <?php echo $style  ?> class="badge  ms-1"> <?php echo $repbinhluan['vt_ten'] ?></span>
                            <span style="color:red">  <?php if ($repbinhluan['trangthai'] == 3) echo 'Chờ duyệt';
                            if ($repbinhluan['trangthai'] == 2) echo 'Đã bị hủy';
                            if ($repbinhluan['trangthai'] == 4) echo 'Đã bị xóa';
                             ?></span>
                            <span style="color:#0E21A0" class="ms-1 "><small>
                                 <?php if ($repbinhluan['nd_username'] == $row_binh_luan['nd_username']) echo 'Tác giả' ?></small></span>
                          </h6>
                          <h6 class="mb-0 text-dark mt-1">
                            <?php
                            // Display replies associated with the parent comment
                            $hoten = "WITH RECURSIVE binh_luan_paths AS
                    (
                      SELECT b1.bl_ma AS bl_cha, b2.bl_ma AS bl_con, 1 AS lvl
                      FROM binh_luan b1
                      LEFT JOIN rep_bl r1 ON b1.bl_ma = r1.bl_cha
                      LEFT JOIN binh_luan b2 ON r1.bl_con = b2.bl_ma
                      WHERE b1.bl_ma =  $parentCommentId
                    
                      UNION ALL
                    
                      SELECT b1.bl_ma AS bl_cha, b2.bl_ma AS bl_con, bp.lvl + 1 AS lvl
                      FROM binh_luan b1
                      LEFT JOIN rep_bl r1 ON b1.bl_ma = r1.bl_cha
                      LEFT JOIN binh_luan b2 ON r1.bl_con = b2.bl_ma
                      INNER JOIN binh_luan_paths bp ON b1.bl_ma = bp.bl_con
                    )
                    SELECT  *
                      FROM binh_luan_paths bp
                      INNER JOIN binh_luan bl ON bp.bl_cha = bl.bl_ma
                      INNER JOIN nguoi_dung ngd ON bl.nd_username = ngd.nd_username
                      INNER JOIN vai_tro vt ON ngd.vt_ma = vt.vt_ma
                      where bl_con is not null and bl_cha =   $bl_cha 
                      ORDER BY bp.lvl;
                     ";
                            $hotenResult = mysqli_query($conn, $hoten);
                            $layhoten = mysqli_fetch_array($hotenResult); ?>
                            <span class="text-dark"><a style="color:#0768ea;" href="profile.php?id=<?php echo $layhoten['nd_username'] ?>"> <?php echo  $layhoten['nd_hoten'] ?></a> <?php echo $repbinhluan['bl_noidung'] ?></span>
                          </h6>
                        </div>
                        <div class="mt-1 mb-3">
                          <small><span> <i class="fa-solid fa-thumbs-up"></i> Thích</span></small> &ensp;
                          <span style="cursor: pointer;" class="reply-button" data-comment-id="<?php echo $repbinhluan['bl_con']; ?>">
                            <small><i class="fa-solid fa-reply"></i> Trả lời</small>
                          </span>
                          &ensp;
                          <!-- <span class="reply-button"><i class="fa-solid fa-reply"></i> Trả lời</span> -->
                          <span><small class="text-muted ms-1"><?php echo $timeAgo ?></small></span>


                          <div class="reply-form reply-form-<?php echo $repbinhluan['bl_con']; ?>" style="display: none;">
                            <form action="include/repbl.php" method="post">
                              <div class="form-group">


                                <label style="color:#000" for="exampleFormControlTextarea1"> <span style="font-size: 13px;font-weight:lighter;color:#000">Trả lời </span><?php echo $repbinhluan['nd_hoten'] ?></label>
                                <textarea name="noidung" required id="basic-default-message" class="form-control" placeholder="Viết bình luận"></textarea>
                                <?php
                                if (isset($_SESSION['user'])) {
                                  $user = $_SESSION['user'];
                                ?>
                                  <input name="bv-ma" type="hidden" value="<?php echo $id ?>">
                                  <input name="bl-ma" type="hidden" value="<?php echo $repbinhluan['bl_con']; ?>">
                                  <input type="hidden" name="username" value="<?php echo $user['nd_username'] ?>">


                                  <input type="hidden" name="username" value="<?php echo $user['nd_username'] ?>">
                                  <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                    <button name="repbl" type="submit" class="btn btn-primary btn-sm">Bình Luận</button>
                                  </div>
                                <?php
                                } else {
                                  // If the user session is not set, display a toast message
                                ?>
                                  <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                    <button id="cmt" class="btn btn-primary btn-sm" onclick="showLoginToast()" name="repbl" type="button" class="btn btn-primary btn-sm">Bình Luận</button>
                                  </div>

                                <?php
                                }
                                ?>

                              </div>
                            </form>
                            <!-- Nội dung của form trả lời -->
                          </div>

                          <!-- JavaScript để mở và đóng form khi nhấn nút "Trả lời" -->







                        </div>

                      </div>
                    </div>
                  <?php } else { ?> 
                    <div class="d-flex ms-5">
                      <div id="commentList"></div>

                      <div class="flex-shrink-0">
                        <img src="../assets/img/avatars/<?php echo $repbinhluan['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
                      </div>
                      <div class="flex-grow-1 row">
                        <div class="col-9 mb-sm-0">
                          <h6 class="mb-0 text-dark"><a style="color:#0768ea;" href="profile.php?id=<?php echo $repbinhluan['nd_username'] ?>">
                              <?php echo $repbinhluan['nd_hoten'] ?>
                              <?php if ($repbinhluan['vt_ma'] == 1) { ?>
                                <i class="fa-solid fa-crown" style="color:#ffbf00"></i>
                              <?php } ?>
                            </a>
                            <?php
                            $style = '';
                            if ($repbinhluan['vt_ma'] == 1)
                              $style = 'style="color: #ED4245; background-color: #fde3e3;"';
                            if ($repbinhluan['vt_ma'] == 2)
                              $style = 'style="color: #ef8843; background-color: #fdede3"';
                            if ($repbinhluan['vt_ma'] == 3)
                              $style = 'style="color: #ffb700; background-color: #fff4d9"';
                            if ($repbinhluan['vt_ma'] == 4)
                              $style = 'style="color: #9b98a9; background-color: #f0f0f2;"';
                            ?>
                            <span <?php echo $style  ?> class="badge  ms-1"> <?php echo $repbinhluan['vt_ten'] ?></span>
                            <span style="color:#0E21A0" class="ms-1 "><small> <?php if ($repbinhluan['nd_username'] == $row_binh_luan['nd_username']) echo 'Tác giả' ?></small></span>
                          </h6>
                          <h6 class="mb-0 text-dark mt-1">
                            <?php
                            // Display replies associated with the parent comment
                            $hoten = "WITH RECURSIVE binh_luan_paths AS
                    (
                      SELECT b1.bl_ma AS bl_cha, b2.bl_ma AS bl_con, 1 AS lvl
                      FROM binh_luan b1
                      LEFT JOIN rep_bl r1 ON b1.bl_ma = r1.bl_cha
                      LEFT JOIN binh_luan b2 ON r1.bl_con = b2.bl_ma
                      WHERE b1.bl_ma =  $parentCommentId
                    
                      UNION ALL
                    
                      SELECT b1.bl_ma AS bl_cha, b2.bl_ma AS bl_con, bp.lvl + 1 AS lvl
                      FROM binh_luan b1
                      LEFT JOIN rep_bl r1 ON b1.bl_ma = r1.bl_cha
                      LEFT JOIN binh_luan b2 ON r1.bl_con = b2.bl_ma
                      INNER JOIN binh_luan_paths bp ON b1.bl_ma = bp.bl_con
                    )
                    SELECT  *
                      FROM binh_luan_paths bp
                      INNER JOIN binh_luan bl ON bp.bl_cha = bl.bl_ma
                      INNER JOIN nguoi_dung ngd ON bl.nd_username = ngd.nd_username
                      INNER JOIN vai_tro vt ON ngd.vt_ma = vt.vt_ma
                      where bl_con is not null and bl_cha =   $bl_cha and trangthai =1
                      ORDER BY bp.lvl;
                     ";
                            $hotenResult = mysqli_query($conn, $hoten);
                            $layhoten = mysqli_fetch_array($hotenResult); ?>
                            <span class="text-dark"><a style="color:#0768ea;" href="profile.php?id=<?php echo $layhoten['nd_username'] ?>"> <?php echo  $layhoten['nd_hoten'] ?></a> <?php echo $repbinhluan['bl_noidung'] ?></span>
                          </h6>
                        </div>
                        <div class="mt-1 mb-3">
                          <small><span> <i class="fa-solid fa-thumbs-up"></i> Thích</span></small> &ensp;
                          <span style="cursor: pointer;" class="reply-button" data-comment-id="<?php echo $repbinhluan['bl_con']; ?>">
                            <small><i class="fa-solid fa-reply"></i> Trả lời</small>
                          </span>
                          &ensp;
                          <!-- <span class="reply-button"><i class="fa-solid fa-reply"></i> Trả lời</span> -->
                          <span><small class="text-muted ms-1"><?php echo $timeAgo ?></small></span>


                          <div class="reply-form reply-form-<?php echo $repbinhluan['bl_con']; ?>" style="display: none;">
                            <form action="include/repbl.php" method="post">
                              <div class="form-group">


                                <label style="color:#000" for="exampleFormControlTextarea1"> <span style="font-size: 13px;font-weight:lighter;color:#000">Trả lời </span><?php echo $repbinhluan['nd_hoten'] ?></label>
                                <textarea name="noidung" required id="basic-default-message" class="form-control" placeholder="Viết bình luận"></textarea>
                                <?php
                                if (isset($_SESSION['user'])) {
                                  $user = $_SESSION['user'];
                                ?>
                                  <input name="bv-ma" type="hidden" value="<?php echo $id ?>">
                                  <input name="bl-ma" type="hidden" value="<?php echo $repbinhluan['bl_con']; ?>">
                                  <input type="hidden" name="username" value="<?php echo $user['nd_username'] ?>">


                                  <input type="hidden" name="username" value="<?php echo $user['nd_username'] ?>">
                                  <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                    <button name="repbl" type="submit" class="btn btn-primary btn-sm">Bình Luận</button>
                                  </div>
                                <?php
                                } else {
                                  // If the user session is not set, display a toast message
                                ?>
                                  <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1">
                                    <button id="cmt" class="btn btn-primary btn-sm" onclick="showLoginToast()" name="repbl" type="button" class="btn btn-primary btn-sm">Bình Luận</button>
                                  </div>

                                <?php
                                }
                                ?>

                              </div>
                            </form>
                            <!-- Nội dung của form trả lời -->
                          </div>

                          <!-- JavaScript để mở và đóng form khi nhấn nút "Trả lời" -->







                        </div>

                      </div>
                    </div>
                 <?php  }

                 }
                  ?>
                <?php } ?>
                            
                                 

                                  
                                        
                                 
                             

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

    <!-- Thanh công cụ soanj thảo -->
    <script src="./ckeditor/ckeditor/ckeditor.js"></script>
    <script src="./ckfinder/ckfinder/ckfinder.js"></script>

    <script>
        CKEDITOR.replace('Noidung_Baiviet', {
            filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
            filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'

        })
    </script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
$(document).ready(function() {
  // Hàm này được thực thi khi tài liệu (trang web) đã tải hoàn toàn

  // Lấy ID bình luận từ tham số URL
  var commentId = getParameterByName('bl_ma'); 
  var bl_ma_value = getParameterByName('bl_ma');
console.log(bl_ma_value);
  // Nếu ID bình luận được đặt, cuộn đến nó và giữa màn hình
  if(commentId) {
    scrollToComment(commentId);
  }

  function getParameterByName(name) {
    // Hàm này trích xuất giá trị của tham số URL bằng cách sử dụng biểu thức chính quy
    var url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  function scrollToComment(commentId) {
    // Tìm phần tử bình luận
    var commentElement = $("#comment-" + commentId);

    // Nếu phần tử tồn tại, cuộn đến giữa màn hình
    if (commentElement.length > 0) {
      var windowHeight = $(window).height();
      var commentTop = commentElement.offset().top;
      var scrollTo = commentTop - (windowHeight / 2);

      // Cuộn đến vị trí được tính toán
      $('html, body').animate({
        scrollTop: scrollTo
      }, 100);
    }
  }
});

</script>

 
</body>

</html>