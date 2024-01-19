<?php include "include/conn.php";
include "include/head.php";
if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
  $username = $user['nd_username'];
  $bv = $_GET['id'];

  $checkQuery = "SELECT * FROM lich_su_xem WHERE nd_username = '$username' AND bv_ma = '$bv'";
  $result = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($result) == 0) {

    $sqlxem = "INSERT INTO lich_su_xem (nd_username, bv_ma) VALUES ('$username', '$bv')";
    $xem = mysqli_query($conn, $sqlxem);
    $sqlslxem = "UPDATE bai_viet SET bv_luotxem = bv_luotxem + 1  WHERE bv_ma = $bv";
    $slxem = mysqli_query($conn, $sqlslxem);
  }
}

?>
<style>
  p {
    font-size: 1rem;
  }
</style>


<body>
  <?php include "include/navbar.php" ?>
  <!-- Layout wrapper -->
  <div class="position-fixed top-0 end-0" style="z-index: 11; margin-top:75px">
    <div id="loginToast" class="toast hide bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto">Vui lòng đăng nhập để thực hiện chức năng này!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
  <div class="position-fixed top-0 end-0" style="z-index: 11; margin-top:75px">
  <?php
  if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
  }
  ?>
<a href="ls-binhluan.php?id=<?php echo  $user  ['nd_username'] ?>">
    <div id="binhluan" class="toast hide bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto">Bình luận của bạn đã được ghi nhận và đang trong trạng thái chờ duyệt!</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
</a>
  </div>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <?php include "include/rightmenu.php" ?>
      <div class="layout-page mt-5">
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                    <div id="loginFailedToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="toast-header">
                        <strong class="me-auto">Login Failed</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                      <div class="toast-body">
                        Tài khoản hoặc mật khẩu không đúng!!!
                      </div>
                    </div>
                  </div>

                  <?php
                  $id = $_GET['id'];
                  $sql = "SELECT bv.*,nd.*,trangthai,  dm_ten, mh.mh_ma as mh_ma, mh_ten, kl_ten,CURRENT_TIMESTAMP(), COALESCE(slbl, 0) AS slbl
                  FROM bai_viet bv
                  LEFT JOIN (
                      SELECT bv_ma, COUNT(*) AS slbl
                      FROM binh_luan
                      WHERE trangthai = 1
                      GROUP BY bv_ma
                  ) bl ON bv.bv_ma = bl.bv_ma
                  LEFT JOIN binh_luan bl2 ON bv.bv_ma = bl2.bv_ma
                                    JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
                                    JOIN danh_muc dm on bv.dm_ma = dm.dm_ma
                                    JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
                                    JOIN khoi_lop kl on kl.kl_ma  = mh.kl_ma
                                    LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
                                    WHERE kd.tt_ma = 1  AND bv.bv_ma = $id             
                 
                  ";
                  $result = mysqLi_query($conn, $sql);
                  $baiviet = mysqli_fetch_assoc($result) ?>

                  <div class="col-md-12 col-12 mb-md-0 mb-4 mt-3 ">

                    <div class="card">

                      <div class="card-body">
                        <nav aria-label="breadcrumb">
                          <ol class="breadcrumb breadcrumb-style1">
                            <li class="breadcrumb-item">
                              <a href="index.php">Trang Chủ</a>
                            </li>
                            <li class="breadcrumb-item">
                              <a href="index.php?monhoc=<?php echo $baiviet['mh_ma'] ?>"><?php echo $baiviet['mh_ten'] ?></a>
                            </li>
                            <li class="breadcrumb-item active">
                            <a href="index.php?danhmuc=<?php echo $baiviet['dm_ma'] ?>"><?php echo $baiviet['dm_ten'] ?></a>
                            </li>
                          </ol>
                        </nav>
                        <div class="d-flex">

                          <div class="flex-shrink-0">
                            <img src="../assets/img/avatars/<?php echo $baiviet['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
                          </div>

                          <div class="flex-grow-1 row">
                            <div class="col-9 mb-sm-0 mb-2">
                              <h6 class="mb-0 text-dark"><a style="color:#0768ea;" href="profile.php?id=<?php echo $baiviet['nd_username'] ?>"> <?php echo $baiviet['nd_hoten'] ?></a></h6>
                              <small class="text-muted"><?php $ng =  $baiviet['bv_ngaydang'];
                                                        echo date('H:i:s d/m/Y', strtotime($ng)); ?></small>
                            </div>
                            <!-- <div class="col-3 text-end">
                              
                       
                              <button class="btn btn-danger btn-sm"> 
                              Báo cáo
                              </button>
                             

                            </div> -->
                          </div>

                        </div>
                        <br>
                        <h4 class="text-dark"><?php echo $baiviet['bv_tieude'] ?></h4>
                        <p style="font-size: 1.1rem;"> <?php echo $baiviet['bv_noidung'] ?></p>
                        <div>
                          <?php
                          $sql = "SELECT * FROM bai_viet a , tai_lieu b where a.bv_ma = b.bv_ma and a.bv_ma = '$id'";
                          $result = mysqli_query($conn, $sql);
                          while ($tailieu = mysqli_fetch_array($result)) {
                            $ten_tai_lieu = $tailieu['tl_tentaptin'];
                            $duoi_tai_lieu = pathinfo($ten_tai_lieu, PATHINFO_EXTENSION);

                            if (isset( $ten_tai_lieu)) { 
                            $icon = '<i class="fa-solid fa-file-pdf" style="color: #e40707;"></i>'; // Biến này sẽ lưu trữ biểu tượng dựa trên loại đuôi tệp
                            
                            // Hiển thị biểu tượng và tên tệp
                            echo '<div>' . $icon . ' <a class="text-muted" href="uploads/' . $ten_tai_lieu . '" target="_blank">' . $ten_tai_lieu . '</a></div>';
                          }}
                          ?>
                          <!-- Thêm các mục khác tại đây -->
                        </div>


                        <?php include "danhgia.php" ?>






                        <div class="demo-inline-spacing">
                          <span class="badge bg-label-info"><?php echo $baiviet['dm_ten'] ?></span>

                          <span><i class="fa-solid fa-eye"></i><?php echo $baiviet['bv_luotxem'] ?> </span>
                          <span><i class="fa-regular fa-comment"></i> <?php echo $baiviet['slbl'] ?></span>

                        </div>
                      </div>

                    </div>

                  </div>



                </div>

                <div class="card mt-3">
                  <h5 class="card-header">Bài Viết Liên Quan</h5>

                  <div class="card-body">
                    <?php
                    $dm_ma = $baiviet['dm_ma'];
                    $sql = "SELECT *  , CURRENT_TIMESTAMP() FROM bai_viet bv
                  JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
                  JOIN danh_muc dm on dm.dm_ma = bv.dm_ma
                  JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
                  JOIN khoi_lop  kl on kl.kl_ma= mh.kl_ma
                  LEFT JOIN kiem_duyet kd on kd.bv_ma = bv.bv_ma
                  WHERE kd.tt_ma = 1 and bv.bv_ma != $id and bv.dm_ma = $dm_ma
                  LIMIT 3
                  ";
                    $res = mysqLi_query($conn, $sql);
                    while ($baivietlq = mysqli_fetch_array($res)) { ?>
                      <div class="mb-3 col-12 mb-0">
                        <a href="chitietbv.php?id=<?php echo $baivietlq['bv_ma'] ?>">
                          <div class="alert alert-secondary">

                            <p style="font-size: 1rem;" class="mb-0"><?php echo $baivietlq['bv_tieude'] ?></p>
                          </div>
                        </a>
                      <?php } ?>
                      </div>

                  </div>
                </div>

                <!-- <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button> -->

                <!-- 
<div class="position-fixed top-0" style="z-index: 11; margin-top:75px; margin-right:0px">
    <div id="binhluan" class="toast hide bg-success " role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Vui lòng chờ duyệt!</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      
    </div>
</div>
                   -->
                   

                <div class="card mt-2 p-4">
                  <p>Bình Luận (<?php echo $baiviet['slbl'] ?>)</p>
                  <form action="include/binhluan.php" method="post">
                    <textarea required name="noidung" id="basic-default-message" class="form-control" placeholder="Viết bình luận"></textarea>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-1 mb-1 ">

                      <?php
                      if (isset($_SESSION['user'])) {
                        $user = $_SESSION['user'];
                      ?>
                        <input name="bv-ma" type="hidden" value="<?php echo $id ?>">
                        <input type="hidden" name="username" value="<?php echo $user['nd_username'] ?>">
                        <button name="binhluan" type="submit" class="btn btn-primary btn-sm">Bình Luận</button>
                      <?php
                      } else {
                        // If the user session is not set, display a toast message
                      ?>
                        <button id="cmt" name="binhluan" type="button" class="btn btn-primary btn-sm" onclick="showLoginToast()">Bình Luận</button>
                        <!-- <button id="cmt" name="binhluan" type="submit" class="btn btn-primary btn-sm">Bình Luận</button> -->
                        <!-- <span class="toast" onclick="showLoginToast()">Vui lòng đăng nhập để bình luận</span> -->
                      <?php
                      }
                      ?>


                  </form>
                </div>
                <?php
                $sql = "SELECT DISTINCT b.*, c.*,d.* , CURRENT_TIMESTAMP()
                  FROM bai_viet a
                  LEFT JOIN binh_luan b ON a.bv_ma = b.bv_ma
                  JOIN nguoi_dung c ON b.nd_username = c.nd_username
                  JOIN vai_tro d ON c.vt_ma = d.vt_ma
                  LEFT JOIN rep_bl r ON b.bl_ma = r.bl_cha
                  WHERE a.bv_ma = '$id' AND b.trangthai = 1
                  AND b.bl_ma NOT IN (SELECT bl_con FROM rep_bl WHERE bl_con IS NOT NULL);
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
        background-color:#D5D8DC ;
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
                      <img src="../assets/img/avatars/<?php echo $binhluan['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
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

                          <span style="color:#0E21A0" class="ms-1 "><small> <?php if ($binhluan['nd_username'] == $baiviet['nd_username']) echo 'Tác giả' ?></small></span>

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
                      <img src="../assets/img/avatars/<?php echo $binhluan['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
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

                          <span style="color:#0E21A0" class="ms-1 "><small> <?php if ($binhluan['nd_username'] == $baiviet['nd_username']) echo 'Tác giả' ?></small></span>



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
                      where bl_con is not null and trangthai =1
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
                        <img src="../assets/img/avatars/<?php echo $repbinhluan['nd_hinh'] ?>" alt="google" class="me-3" height="30" />
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
                            <span style="color:#0E21A0" class="ms-1 "><small> <?php if ($repbinhluan['nd_username'] == $baiviet['nd_username']) echo 'Tác giả' ?></small></span>
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
                            <span style="color:#0E21A0" class="ms-1 "><small> <?php if ($repbinhluan['nd_username'] == $baiviet['nd_username']) echo 'Tác giả' ?></small></span>
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

  <!-- Thêm mã JavaScript sau vào trang của bạn -->

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
$(document).ready(function() {
  // Hàm này được thực thi khi tài liệu (trang web) đã tải hoàn toàn

  // Lấy ID bình luận từ tham số URL
  var commentId = getParameterByName('bl_ma'); 

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


  <script>
    <?php if (isset($_SESSION['binhluan']) && $_SESSION['binhluan']) { ?>
      // Successful login
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("binhluan");
        var successToastInstance = new bootstrap.Toast(successToast);
        successToastInstance.show();
      });
    <?php
      // Reset the login_success session variable
      unset($_SESSION['binhluan']);
    }
    ?>
  </script>

  <script>
    <?php if (isset($_SESSION['repbl']) && $_SESSION['repbl']) { ?>
      // Successful login
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("binhluan");
        var successToastInstance = new bootstrap.Toast(successToast);
        successToastInstance.show();
      });
    <?php
      // Reset the login_success session variable
      unset($_SESSION['repbl']);
    }
    ?>
  </script>
  <script>
    function showLoginToast() {
      const loginToast = document.getElementById("loginToast");
      var loginToastInstance = new bootstrap.Toast(loginToast);
      loginToastInstance.show();
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

  <!-- Main JS -->
  <script src="../assets/js/main.js"></script>

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
  // Get the value of bl_ma from the URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const commentIdToScroll = urlParams.get('bl_ma');

  // Check if the commentIdToScroll is not null or undefined
  if (commentIdToScroll) {
    // Construct the CSS selector for the comment element
    const commentSelector = `#comment-${commentIdToScroll}`;

    // Get the comment element
    const commentElement = document.querySelector(commentSelector);

    // Check if the comment element is found
    if (commentElement) {
      // Scroll to the comment element
      commentElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
});

  </script>
  <script>
    // Wait for the document to be ready
    $(document).ready(function() {
      // Add a click event listener to the button
      $('#liveToastBtn').click(function() {
        // Show the toast
        $('#liveToast').toast('show');
      });
    });
  </script>
  <!-- Add this script at the end of your HTML body -->
  <script>
    $(document).ready(function() {
      // Select all reply buttons
      $('.reply-button').click(function() {
        // Find the corresponding reply form using data-comment-id attribute
        var commentId = $(this).data('comment-id');
        var replyForm = $('.reply-form-' + commentId);

        // Toggle the display of the clicked reply form
        replyForm.slideToggle();

        // Hide all other reply forms
        $('.reply-form').not(replyForm).slideUp();
      });
    });
  </script>


  <script>
    $(document).ready(function() {
      // Select all reply buttons
      $('.reply-button1').click(function() {
        // Find the corresponding reply form using data-comment-id attribute
        var commentId = $(this).data('comment-id');
        var replyForm = $('.reply-form1-' + commentId);

        // Toggle the display of the clicked reply form
        replyForm.slideToggle();
      });
    });
  </script>



</body>

</html>