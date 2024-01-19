<?php include "include/conn.php";
include "include/head.php";
$id = $_GET['id'];

if ( isset($_POST['xoa'])) {
  $user = $id;
  $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];
    try {
        foreach ($selectedIds as $bv) {
      
                $delete_query = "DELETE FROM kiem_duyet WHERE bv_ma = '$bv'";
                mysqli_query($conn, $delete_query);

                $delete_query = "DELETE FROM tai_lieu WHERE bv_ma = '$bv'";
                mysqli_query($conn, $delete_query);

                $delete_query = "DELETE FROM lich_su_xem WHERE bv_ma = '$bv'";
                mysqli_query($conn, $delete_query);

                $delete_query = "DELETE FROM bai_viet WHERE bv_ma = '$bv'";
                mysqli_query($conn, $delete_query);
    }    
        echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";
    } catch (Exception $e) {
        // If an error occurs, rollback the transaction and display an error message
        mysqli_rollback($conn);
        echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>";
        echo "Error: " . $e->getMessage();
    }
} 

if (isset($_POST['phuchoi'])) {
    $user = $id;
    $success = false;
    $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];
    $successIds = [];
    $failedIds = [];
    try {
        foreach ($selectedIds as $bv) {
            $kt = "SELECT * FROM kiem_duyet WHERE bv_ma = '$bv' AND nd_username = '$user'";
            $result_kt = mysqli_query($conn, $kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if (mysqli_num_rows($result_kt) > 0) {
                $update_query = "UPDATE kiem_duyet SET tt_ma = '" . $row_kt['ghi_chu'] . "', nd_username='$user', ghi_chu='', thoigian = now() WHERE bv_ma = '$bv'";
                $update_result = mysqli_query($conn, $update_query);
                if ($update_result) {
                    $successIds[] = $bv;
                } else {
                    $failedIds[] = $bv;
                }
                $success = true;
            }
        }

        $successMessage = '';
        $failedMessage = '';

        if (!empty($successIds)) {
            $successMessage = 'Bài viết có ID: ' . implode(', ', $successIds) . ' đã được khôi phục thành công!\n';
        }

        if (!empty($failedIds)) {
            $failedMessage = 'Bài viết có ID: ' . implode(', ', $failedIds) . '  đã bị Admin xoá nên không thể khôi phục được!Hãy liên hệ với Admin để được hỗ trợ\n';
        }

        if ($success) {
            echo "<script>alert('$successMessage $failedMessage');</script>";
        } else {
            echo "<script>alert('Dữ liệu của bạn đã bị Admin xoá nên bạn không thể khôi phục!');</script>";
        }

    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo "<script>alert('Có lỗi xảy ra trong quá trình khôi phục dữ liệu.');</script>";
        echo "Error: " . $e->getMessage();
    }
}




$so_dong = 5;


$tungay = "2000-01-01";
$denngay = date('Y-m-d', strtotime('+1 day'));

if (isset($_GET['sodong'])) {
    $so_dong = intval($_GET['sodong']);
}


if (isset($_GET['tungay'])) {
    $tungay = $_GET['tungay'];
}

if (isset($_GET['denngay'])) {
    $denngay = $_GET['denngay'];
}

        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.thoigian, d.* FROM bai_viet a
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            where  c.tt_ma = 4
            And a.nd_username ='$id'
            And DATE(thoigian) BETWEEN '$tungay' AND '$denngay'
            LIMIT $so_dong";
   

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
                                                                                <span
                                                                                    class="badge bg-label-secondary"><?php echo $nguoidung['vt_ten']  ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <h5 class="pb-2 border-bottom mb-4">Details</h5>
                                                                    <div class="info-container">
                                                                        <ul class="list-unstyled">
                                                                            <li class="mb-3">
                                                                                <span
                                                                                    class="fw-medium me-2">Username:</span>

                                                                                <span><?php echo $nguoidung['nd_username'] ?></span>
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
                                                                                <span><?php echo $nguoidung['vt_ten'] ?></span>
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
                                                                </li> <?php }
                                      } ?>
                                                            </ul>
                                                            <hr>


                                                            <div class="tab-pane fade active show">
                                                            <?php

                                $result_bai_viet = mysqli_query($conn, $bai_viet);

                                  if (isset($_SESSION['user'])) {
                                    $user = $_SESSION['user'];
                                    if ($user['nd_username'] == $id) {
                                  ?>

                                      <div class="row card-header d-flex flex-wrap py-3 px-3 justify-content-between">

                                        <div style="margin-bottom:1rem; margin-top:-1rem" class="col-lg-12  row">
                                         
                                          <div class="col-md-3  me-1 ">
                                            <div class="dataTables_filter">
                                              <label>Từ ngày</label>
                                              <input id="tungay" type="date" class="form-control" value="2000-01-01">

                                            </div>
                                          </div>
                                          <div class="col-md-3  me-1 ">
                                            <span>Đến ngày</span>
                                            <input id="denngay" type="date" class="form-control" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">

                                          </div>
                                          <div class="col-md-2">

                                            <br>
                                            <button id="loc_ngay" class="btn btn-primary"><i class="fa fa-search"></i></button>
                                          </div>
                                        </div>



                                        <div style="padding-left:10px" class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end gap-3 gap-sm-2 flex-wrap flex-sm-nowrap pt-0">

                                          <form method="POST">
                                            <div class=" dataTables_length mt-0 mt-md-0  me-2">
                                              <label>
                                                <select name="so_dong" class="form-select" id="so_dong">
                                                  <?php
                                                  $sd = "SELECT count(*) as tong FROM bai_viet where nd_username = '$id'";
                                                  $result_sd = mysqli_query($conn, $sd);
                                                  $row_sd = mysqli_fetch_assoc($result_sd);

                                                  $tong = $row_sd['tong'];

                                                  // In ra các số tròn chục nhỏ hơn tổng
                                                  echo "Các số tròn chục nhỏ hơn tổng là: ";
                                                  for ($i = 5; $i <= $tong + 5; $i += 5) {
                                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                                  }

                                                  ?>
                                                </select>
                                              </label>
                                            </div>
                                          </form>

                                          <style>
                                            @media (max-width: 300px) {
                                              #Export {
                                                /* Đặt kích thước của button thành 10px khi màn hình nhỏ hơn 400px */
                                                width: 20px;
                                                height: 40px;
                                              }
                                            }
                                          </style>




                                        </div>
                                      </div>
                                      <hr>
                            <form method="POST" enctype="multipart/form-data">
                                <div style="margin-left:0.1rem;" class="col-lg-12  row ">

                                    <div class="col-md-2 col-lg-8 col-sm-6 col-6 ">
                                        <button name="phuchoi" type="submit" class="btn btn-sm btn-outline-success" id="phuchoi">
                                            
                                           
                                            <span>
                                                <i class="fa fa-undo"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Phục hồi</span>

                                            </span>
                                               
                                        </button>
                                        <button name="xoa" type="submit" class="btn btn-sm btn-outline-danger" id="xoa">
                                            
                                           
                                            <span>
                                                <i class="fa fa-trash"></i>
                                                <span class="dt-down-arrow d-none d-xl-inline-block">Xoá</span>

                                            </span>
                                               
                                        </button>
                                    </div>



                                    <!-- Giao diện -->
                                    <div class="col-md-2 col-lg-4 col-sm-3 row mt-4">
                                        <div style="display: flex; justify-content: right;" class="col-lg-12">
                                            <p>Đang hiển thị:
                                                <span id="so_dong_hien_tai">
                                                    <?php echo $_SESSION['sl_dong']; ?>
                                                </span>/
                                                <span id="tong-so-dong">
                                                    <?php
                                                      
                                                            $sd="SELECT count(*) as tong
                                                                    FROM bai_viet a
                                                                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                                                                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where c.tt_ma = 4
                                                                    And a.nd_username ='$id'
                                                                    And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
                                                       
                                                       
                                                        $result_sd = mysqli_query($conn,$sd);
                                                        $row_sd = mysqli_fetch_assoc($result_sd);

                                                        $_SESSION['tong_sd'] = array();
                                                        $_SESSION['tong_sd'] = $row_sd['tong'];

                                                        echo $_SESSION['tong_sd']; 
                                                    ?>
                                                </span>(kết quả)
                                            </p>
                                        </div>
                                    </div>


                                </div>

                                  <?php }
                                  } ?>
                                  <div class="table-responsive text-nowrap border-top">
                                    <?php $id = $_GET['id'];
                                    if (isset($_SESSION['user'])) {
                                      $user = $_SESSION['user'];
                                      if ($user['nd_username'] == $id) {
                                    ?>

                                        <table class="table table-hover">
                                          <thead>
                                            <tr>
                                            <th>
                                                    <input class="form-check-input" id="checkall" type="checkbox">
                                                </th>
                                              <th>STT</th>
                                              <!-- <th>Mã</th> -->
                                              <th>Trạng thái</th>
                                              <th>Thời gian xoá</th>
                                              <th>Tiêu đề</th>
                                              <th>Ngày đăng</th>

                                              <style>
                                                .sort-header {
                                                  position: relative;
                                                }

                                                .small-button {
                                                  background-color: none;
                                                  background: none;
                                                  border: none;
                                                  font-size: 11px;
                                                  /* Reduce the font size */
                                                  padding: 5px 10px;
                                                  border-radius: 0.3rem;
                                                  cursor: pointer;
                                                  margin-left: 5rem;
                                                  color: #CCCCCC;
                                                }

                                                .sort-header button#asc {
                                                  position: absolute;
                                                  top: 0;
                                                  left: 0;
                                                }

                                                .sort-header button#desc {
                                                  position: absolute;
                                                  bottom: 0;
                                                  left: 0;
                                                }

                                                .active {
                                                  color: #666666;
                                                  /* Change the color to the desired active color */
                                                }
                                              </style>

                                              <th>Hành động</th>
                                            </tr>
                                          </thead>
                                          <tbody class="table-border-bottom-0" id="data-container">
                                            <?php
                                            $stt = 0;
                                            while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                                              $stt = $stt + 1;
                                            ?>
                                              <tr>
                                              <td>
                                                    <input class="form-check-input check-item" name="check[]"
                                                        type="checkbox" value="<?php echo $row_bai_viet['bv_ma'] ?>">
                                                </td>
                                                <td class="row-bai-viet"> <?php echo $stt ?> </td>

                                                <td style="white-space: normal">

                                                  <?php

                                                 
                                                  if ($row_bai_viet['tt_ma'] == 1) {

                                                    echo "<span class='badge bg-label-success '>" . $row_bai_viet['tt_ten'] . "</span>";
                                                  }elseif ($row_bai_viet['tt_ma'] == 2) {
                                                    echo "<span class='badge alert-warning '>" . $row_bai_viet['tt_ten'] . "</span>";
                                                  }elseif ($row_bai_viet['tt_ma'] == 4 ) {

                                                    echo "<span class='badge bg-label-danger'>" . $row_bai_viet['tt_ten'] . "</span>";
                                                    $kd="select * from kiem_duyet where bv_ma = '".$row_bai_viet['bv_ma']."' ";
                                                    $result_kd = mysqli_query($conn,$kd);
                                                    while ($row_kd = mysqli_fetch_array($result_kd)) {
                                                      if ($row_kd['tt_ma'] == 4 && $row_kd['nd_username'] !== $id ) {
  
                                                        echo "<span class='badge bg-label-warning'>Bởi Admin</span>";
                                                      }else{
  
                                                      }
                                                    }
                                                  }
                                                  else {
                                                    echo "<span class='badge bg-label-primary '>Chờ duyệt</span>";
                                                  }
                                                  ?>

                                                </td>
                                                <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['thoigian']), "d-m-Y (H:i:s)"); ?>
                                                <td style="white-space: normal; padding:5px 0 5px 0"> <?php echo $row_bai_viet['bv_tieude'] ?> </td>
                                                <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

                                                </td>


                                                <td>
                                                <a id="dropdownHanhDong" data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        style="display:math; padding:0.1rem 0.6rem"
                                                        class="dropdown-item"
                                                        >
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>

                                                    <div class="dt-button-collection dropdown-menu"
                                                        style="top: 55.9375px; left: 419.797px;min-width:7rem"
                                                        aria-labelledby="dropdownHanhDong">
                                                        <div role="menu">
                                                            <a href="Xem_BaiViet.php?id=<?php echo $id ?>&bv=<?php echo $row_bai_viet['bv_ma'] ?>"
                                                                class="dt-button buttons-print dropdown-item"
                                                                tabindex="0" type="button">
                                                                <span><i class=" fa fa-eye me-2"></i>Xem</span>
                                                            </a>
                                                           

                                                            <a href="#" onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&id=<?php echo $row_bai_viet['nd_username'] ?>');"
                                                                class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                type="button">
                                                                <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                                            </a>

                                                        </div>
                                                    </div>

                                                  <!-- <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="Xem_BaiViet.php?id=<?php echo $id ?>&bv=<?php echo $row_bai_viet['bv_ma'] ?>">

                                                    <i class="fa fa-eye"></i>
                                                  </a>

                                                  <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="#" onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>');">
                                                    <i class="bx bx-trash me-1"></i>
                                                  </a> -->

                                                </td>
                                              </tr>
                                            <?php } ?>

                                          </tbody>
                                        </table>
                                        
                                      <?php  }
                                    }
                                    if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['nd_username'] !== $id)) { ?>



                                    <?php

                                      while ($row = mysqli_fetch_array($result_bai_viet)) {
                                        $ls_thoigian = $row['bv_ngaydang']; // Assuming ls_thoigian contains a date/time

                                        // Extract the date part from ls_thoigian
                                        $date = date("Y-m-d", strtotime($ls_thoigian));

                                        if (!isset($grouped_articles[$date])) {
                                          $grouped_articles[$date] = array();
                                        }

                                        $grouped_articles[$date][] = $row;
                                      }
                                      if (mysqli_num_rows($result_bai_viet) > 0) {
                                        foreach ($grouped_articles as $date => $articles) {
                                          echo '<div class="card mb-3">';
                                          echo '<div class="card-body">';
                                          echo '<div class="col-md-12 col-12">';
                                          echo '<h5>' . date("d \\T\\h\\á\\n\\g m, Y", strtotime($date)) . '</h5>';

                                          foreach ($articles as $key => $article) {
                                            echo '<a href="chitietbv.php?id=' . $article['bv_ma'] . '">';
                                            echo '<div class="d-flex">';

                                            echo '<div class="flex-shrink-0">';

                                            echo '<img src="../assets/img/avatars/' . $article['nd_hinh'] . '" alt="google" class="me-3 rounded-circle" height="40" />';
                                            echo '</div>';
                                            echo '<div class="flex-grow-1 row">';
                                            echo '<div class="col-lg-10 mb-sm-0">';

                                            echo '<h6 class="mb-0 text-dark">' . $article['bv_tieude'] . '</h6>';

                                            echo ' <small>  <span class="badge bg-label-primary">' .  $article['dm_ten'] . '</span></small>';
                                            echo ' <small>  <span class="badge bg-label-primary">' .  $article['mh_ten'] . '</span></small>';

                                            echo '</div>';
                                            echo '<div class="col-2 text-end">';
                                            echo '<small>' . date("H:i:s", strtotime($article['bv_ngaydang'])) . '</small>';
                                            echo '</div>';
                                            echo '</div>';

                                            // Add ellipsis icon


                                            echo '</div>';
                                            echo '</a>';

                                            // Add <hr> except for the last article
                                            if ($key < count($articles) - 1) {
                                              echo '<hr>';
                                            }
                                          }




                                          echo '</div>';
                                          echo '</div>';
                                          echo '</div>';
                                        }
                                      } else {
                                        echo 'Không có lịch bài viết';
                                      }
                                    }
                                    ?>

                                  </div>
                            </form>






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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
    $(document).ready(function() {
        $('.small-button').click(function() {
            // Remove the 'active' class from all buttons
            $('.small-button').removeClass('active');

            // Add the 'active' class to the clicked button
            $(this).addClass('active');
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // Xử lý khi số dòng hoặc trạng thái thay đổi
        $('#so_dong').change(function() {
            updateDataContainer();
        });

        // Xử lý khi nhấn nút lọc ngày
        $('#loc_ngay').click(function() {
            var chon_gtri = $('#so_dong').val();
            var tungay = $('#tungay').val();
            var denngay = $('#denngay').val();
            var user = '<?php echo $id ?>';
            $.ajax({
                url: 'get_bv_daxoa.php',
                method: 'GET',
                data: {
                    tungay: tungay,
                    sodong: chon_gtri,
                    denngay: denngay,
                    id: user,
                    loc_ngay: true // Thêm tham số loc_ngay
                },
               
                success: function(data) {
                    $('#data-container').html(data);
                    updateDisplayInfo();
                }
            });
        });

        // Hàm cập nhật dữ liệu với tùy chọn sắp xếp
        function updateDataContainer(sortOrder) {
            var chon_gtri = $('#so_dong').val();
            var tungay = $('#tungay').val();
            var denngay = $('#denngay').val();
            var user = '<?php echo $id ?>';
            $.ajax({
                url: 'get_bv_daxoa.php',
                data: {
                    tungay: tungay,
                    denngay: denngay,
                    sodong: chon_gtri,
                    trangthai: chon_trangthai,
                    id: user
                    
                },
              
                success: function(data) {
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
    document.getElementById('checkall').addEventListener('change', function() {
        // Lấy tất cả các checkbox cần chọn
        var checkboxes = document.querySelectorAll('.check-item');

        // Duyệt qua từng checkbox và thiết lập trạng thái chọn/bỏ chọn
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = document.getElementById('checkall').checked;
        });
    });
    </script>

   <script>
     function Xoa_Baiviet(bv_ma) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa vĩnh viễn bài viết này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'xoa_baiviet_luon.php?this_bv_ma=' + bv_ma;
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