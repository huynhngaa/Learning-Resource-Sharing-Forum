<?php include "include/conn.php";
include "include/head.php";
if (!isset($_SESSION['user'])) {

     header("Location: 404.php");
}
$this_bv_ma = $_GET['dm'];
$id = $_GET['id'];
$bai_viet = "SELECT *  , CURRENT_TIMESTAMP() FROM bai_viet bv
JOIN nguoi_dung nd on bv.nd_username = nd.nd_username
JOIN danh_muc dm on dm.dm_ma = bv.dm_ma
JOIN mon_hoc mh on dm.mh_ma = mh.mh_ma
JOIN khoi_lop  kl on kl.kl_ma= mh.kl_ma
 WHERE bv.dm_ma='$this_bv_ma' and nd.nd_username = '$id'";

$result_bai_viet = mysqli_query($conn, $bai_viet);

?>

<body>
     <?php include "include/navbar.php" ?>
     <!-- Layout wrapper -->
     <div class="layout-wrapper layout-content-navbar">
          <div class="layout-container">
               <?php include "include/rightmenu.php" ?>
               <div style="" class="layout-page">
                    <div class="content-wrapper">
                         <div class="container-xxl flex-grow-1 container-p-y">
                              <div class="row">
                                   <div class="col-md-12">
                                        <div class="row">

                                             <div class="col-xl-12">
                                                  <div class="position-fixed top-0 end-0"
                                                       style="z-index: 11; margin-top:75px">
                                                       <div id="capnhat-thanhcong" class="toast hide bg-success "
                                                            role="alert" aria-live="assertive" aria-atomic="true">
                                                            <div class="toast-header">
                                                                 <strong class="me-auto">Cập nhật thành công</strong>
                                                                 <button type="button" class="btn-close"
                                                                      data-bs-dismiss="toast"
                                                                      aria-label="Close"></button>
                                                            </div>

                                                       </div>
                                                  </div>

                                                  <div class="position-fixed top-0 end-0"
                                                       style="z-index: 11; margin-top:75px">
                                                       <div id="capnhat-thatbai" class="toast hide bg-danger"
                                                            role="alert" aria-live="assertive" aria-atomic="true">
                                                            <div class="toast-header">
                                                                 <strong class="me-auto">Cập nhật thất bại</strong>
                                                                 <button type="button" class="btn-close"
                                                                      data-bs-dismiss="toast"
                                                                      aria-label="Close"></button>
                                                            </div>

                                                       </div>
                                                  </div>
                                                  <div class="nav-align-top mb-4 ">

                                                       <div class="tab-content">
                                                            <nav aria-label="breadcrumb">
                                                                 <ol class="breadcrumb breadcrumb-style1">
                                                                      <li class="breadcrumb-item">
                                                                           <a href="profile.php?id=<?php echo $id ?>">
                                                                                <?php echo 'Tài khoản' ?>
                                                                           </a>
                                                                      </li>
                                                                      <li class="breadcrumb-item">
                                                                           <a
                                                                                href="nhatkyhoatdong.php?id=<?php echo $id ?>">
                                                                                <?php echo 'Nhật ký hoạt động' ?>
                                                                           </a>
                                                                      </li>
                                                                      <li class="breadcrumb-item active">
                                                                           <?php echo 'Lịch sử bình luận' ?>
                                                                      </li>
                                                                 </ol>
                                                            </nav>
                                                            <div class="tab-pane fade show active"
                                                                 id="navs-justified-home" role="tabpanel">
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
                                                                                                    height="110"
                                                                                                    width="110"
                                                                                                    alt="User avatar">
                                                                                               <div
                                                                                                    class="user-info text-center">
                                                                                                    <h4 class="mb-2">
                                                                                                         <?php echo $nguoidung['nd_hoten'] ?>
                                                                                                    </h4>
                                                                                                    <span
                                                                                                         class="badge bg-label-secondary">
                                                                                                         <?php echo $nguoidung['vt_ten'] ?>
                                                                                                    </span>
                                                                                               </div>
                                                                                          </div>
                                                                                     </div>

                                                                                     <h5
                                                                                          class="pb-2 border-bottom mb-4">
                                                                                          Details</h5>
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
                                                                                                    <span
                                                                                                         class="fw-medium me-2">Tax
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

                                                                                <div class="content-wrapper">

                                                                                     <div
                                                                                          class="row card-header d-flex flex-wrap py-3 px-3 justify-content-between">
                                                                                          <div style="margin-bottom:1rem; margin-top:-1rem"
                                                                                               class="col-lg-12  row">

                                                                                               <div
                                                                                                    class="col-md-3  me-1 ">
                                                                                                    <div
                                                                                                         class="dataTables_filter">
                                                                                                         <label>Từ
                                                                                                              ngày</label>
                                                                                                         <input
                                                                                                              id="tungay"
                                                                                                              type="date"
                                                                                                              class="form-control"
                                                                                                              value="2022-01-01">

                                                                                                    </div>
                                                                                               </div>
                                                                                               <div
                                                                                                    class="col-md-3  me-1 ">
                                                                                                    <span>Đến
                                                                                                         ngày</span>
                                                                                                    <input id="denngay"
                                                                                                         type="date"
                                                                                                         class="form-control"
                                                                                                         value="<?php echo date('Y-m-d'); ?>">

                                                                                               </div>
                                                                                               <div class="col-md-2">

                                                                                                    <br>
                                                                                                    <button
                                                                                                         id="loc_ngay"
                                                                                                         class="btn btn-primary"><i
                                                                                                              class="fa fa-search"></i></button>
                                                                                               </div>
                                                                                          </div>
                                                                                     </div>
                                                                                     <!-- <div class="card mb-3">
                                                                                     <div class="card-body d-flex justify-content-between">
                                                                                          <div class="form-check ">
                                                                                               <input class="form-check-input" id="checkall" type="checkbox">
                                                                                               <label class="form-check-label">Tất cả</label>
                                                                                          </div>
                                                                                          <button class="btn btn-sm btn-outline-danger"><i class="fa-regular fa-trash-can"></i></button>
                                                                                     </div>
                                                                                </div> -->

                                                                                     <?php
                                                                                     $username = $_GET['id'];
                                                                                     $nguoi_dung = "SELECT DISTINCT trangthai, tt_ten,  a.nd_username as chunhan , b.*, c.nd_hoten ,r.bl_cha, CURRENT_TIMESTAMP()
                                                                                FROM binh_luan b
                                                                                LEFT JOIN  bai_viet a ON a.bv_ma = b.bv_ma
                                                                                JOIN nguoi_dung c ON a.nd_username = c.nd_username
                                                                                LEFT JOIN rep_bl r ON b.bl_ma = r.bl_con
                                                                               
                                                                                left join trang_thai tt on b.trangthai = tt.tt_ma
                                                                                WHERE b.nd_username = '$id' 
                                                                              
                                                                                Order by bl_thoigian desc";
                                                                                     $result_nguoi_dung = mysqli_query($conn, $nguoi_dung);

                                                                                     $grouped_articles = array(); // Initialize an array to group articles by day
                                                                                     
                                                                                     while ($row = mysqli_fetch_assoc($result_nguoi_dung)) {

                                                                                          $ls_thoigian = $row['bl_thoigian']; // Assuming ls_thoigian contains a date/time
                                                                                     
                                                                                          // Extract the date part from ls_thoigian
                                                                                          $date = date("Y-m-d", strtotime($ls_thoigian));

                                                                                          if (!isset($grouped_articles[$date])) {
                                                                                               $grouped_articles[$date] = array();
                                                                                          }

                                                                                          $grouped_articles[$date][] = $row;
                                                                                     }
                                                                                     if (mysqli_num_rows($result_nguoi_dung) > 0) {
                                                                                          foreach ($grouped_articles as $date => $articles) {

                                                                                               echo '<div class="card mb-3">';
                                                                                               echo '<div class="card-body">';
                                                                                               echo '<div class="col-md-12 col-12">';

                                                                                               echo '<h5>' . date("d \\T\\h\\á\\n\\g m, Y", strtotime($date)) . '</h5>';

                                                                                               foreach ($articles as $key => $article) {
                                                                                                    $trangthai = $article['trangthai'];
                                                                                                    $class = ($trangthai == 1) ? 'badge bg-success' : (
                                                                                                         ($trangthai == 2) ? 'badge bg-secondary' : (
                                                                                                              ($trangthai == 3) ? 'badge bg-warning' : (
                                                                                                                   ($trangthai == 4) ? 'badge bg-danger' : ''
                                                                                                              )
                                                                                                         )
                                                                                                    );


                                                                                                    echo '<div class="d-flex">';
                                                                                                    echo '<div class="flex-grow-1 row">';
                                                                                                    echo '<div class="col-lg-2  mb-sm-0">';
                                                                                                    echo '<p class="' . $class . '"> ' . $article['tt_ten'] . ' </p>';
                                                                                                    // echo '<input class="form-check-input check-item" name="check[]" type="checkbox" value="' . $article['bl_ma'] . ' ">';
                                                                                                    echo '</div>';

                                                                                                    echo '<div class="col-lg-8 mb-sm-0">';
                                                                                                    if ($article['bl_cha'] == null) {
                                                                                                         echo '<h6 class="mb-0 text-dark">Bạn đã bình luận ';
                                                                                                         echo '<a href="chitietbv.php?id=' . $article['bv_ma'] . '">';
                                                                                                         echo 'bài viết';
                                                                                                         echo '</a>';
                                                                                                         echo ' của ';
                                                                                                         echo '<a href="profile.php?id=' . $article['chunhan'] . '">';
                                                                                                         echo $article['nd_hoten'];
                                                                                                         echo '</a>';
                                                                                                         echo '</h6>';

                                                                                                         echo $article['bl_noidung'];
                                                                                                         echo '</div>';
                                                                                                         echo '<div class="col-2 text-end">';
                                                                                                         echo '<small>' . date("H:i:s", strtotime($article['bl_thoigian'])) . '</small>';
                                                                                                         echo '</div>';
                                                                                                         echo '</div>';
                                                                                                    } else {


                                                                                                         echo '<h6 class="mb-0 text-dark">Bạn đã phản hồi  ';
                                                                                                         echo '<a href="chitietbv.php?id=' . $article['bv_ma'] . '">';
                                                                                                         echo 'bình luận';
                                                                                                         echo '</a>';
                                                                                                         echo ' về bài viết của ';
                                                                                                         echo '<a href="profile.php?id=' . $article['chunhan'] . '">';
                                                                                                         echo $article['nd_hoten'];
                                                                                                         echo '</a>';
                                                                                                         echo '</h6>';

                                                                                                         echo $article['bl_noidung'];
                                                                                                         echo '</div>';
                                                                                                         echo '<div class="col-2 text-end">';
                                                                                                         echo '<small>' . date("H:i:s", strtotime($article['bl_thoigian'])) . '</small>';
                                                                                                         echo '</div>';
                                                                                                         echo '</div>';
                                                                                                    }
                                                                                                    // Add ellipsis icon
                                                                                                    echo '<div class="col-1 mb-sm-0">';
                                                                                                    echo '<a id="dropdownHanhDong" data-bs-toggle="dropdown" aria-expanded="false" style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="Xem_BinhLuan.php?this_bl_ma=' . $article['bl_ma'] . '&tg=' . $article['nd_username'] . '">';
                                                                                                    echo '    <i class="fa fa-ellipsis-v" aria-hidden="true"></i>';
                                                                                                    echo '</a>';

                                                                                                    echo '<div class="dt-button-collection dropdown-menu" style="top: 55.9375px; left: 419.797px;min-width:7rem" aria-labelledby="dropdownHanhDong">';
                                                                                                    echo '    <div role="menu">';

                                                                                                    // Xem
                                                                                                    if ($trangthai == 1) {
                                                                                                         echo '        <a href="chitietbv.php?id=' . $article['bv_ma'] . '&bl_ma=' . $article['bl_ma'] . '" class="dt-button buttons-print dropdown-item" tabindex="0" type="button">';
                                                                                                         echo '            <span><i class="fa fa-eye me-2"></i>Xem</span>';
                                                                                                         echo '        </a>';
                                                                                                    }
                                                                                                    // Phê duyệt
                                                                                                    echo '        <a class="dt-button buttons-csv buttons-html5 dropdown-item" type="button" >';
                                                                                                    echo '            <span data-bs-toggle="modal" data-bs-target="#modalbinhluan'.$article['bl_ma'].'"><i class="bx bx-edit-alt me-2"></i>Sửa</span>';
                                                                                                    echo '        </a>';

                                                                                                    // Xoá
                                                                                                    echo '        <a href="#" onclick="Xoa_Binhluan(\'' . $article['bl_ma'] . '&tg=' . $article['nd_username'] . '\');" class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">';
                                                                                                    echo '            <span><i class="bx bx-trash me-2"></i>Xoá</span>';
                                                                                                    echo '        </a>';

                                                                                                    echo '    </div>';
                                                                                                    echo '</div>';
                                                                                                    echo '</div>';

                                                                                                    echo '</div>';
                                                                                                    ?>
                                                                                                   <form action="ls-bl-sua.php" method ="post">
                                                                                                    <div class="modal fade"
                                                                                                         id="modalbinhluan<?php echo $article['bl_ma']; ?>"
                                                                                                         tabindex="-1"
                                                                                                         style="display: none;"
                                                                                                         aria-hidden="true">
                                                                                                         <div class="modal-dialog modal-dialog-centered"
                                                                                                              role="document">
                                                                                                              <div
                                                                                                                   class="modal-content">
                                                                                                                   <div
                                                                                                                        class="modal-header">
                                                                                                                        <h5 class="modal-title"
                                                                                                                             id="modalCenterTitle">
                                                                                                                            Sửa bình luận</h5>
                                                                                                                        <button
                                                                                                                             type="button"
                                                                                                                             class="btn-close"
                                                                                                                             data-bs-dismiss="modal"
                                                                                                                             aria-label="Close"></button>
                                                                                                                   </div>
                                                                                                                   <div
                                                                                                                        class="modal-body">
                                                                                                                        <div >
                                                                                                                            
                                                                                                                                  <label   for="nameWithTitle"  class="form-label">Nội dung</label>
                                                                                                                                  <input type="hidden" name="ma" value ="<?php  echo $article['bl_ma'] ?>">
                                                                                                                                  <input type="text" name="noidung" value="<?php echo  $article['bl_noidung']  ?>" id="nameWithTitle"    class="form-control"    placeholder="Enter Name">
                                                                                                                            
                                                                                                                        </div>      
                                                                                                                   </div>
                                                                                                                   <div
                                                                                                                        class="modal-footer">
                                                                                                                        <button
                                                                                                                             type="button"
                                                                                                                             class="btn btn-outline-secondary"
                                                                                                                             data-bs-dismiss="modal">
                                                                                                                             Close
                                                                                                                        </button>
                                                                                                                        
                                                                                                                        <button name ="suabinhluan"
                                                                                                                             type="submit"
                                                                                                                             class="btn btn-primary">Cập nhật</button>
                                                                                                                   </div>
                                                                                                              </div>
                                                                                                         </div>
                                                                                                    </div>
                                                                                                    </form>
                                                                                                    <?php
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
                                                                                          echo 'Không có lịch sử xem';
                                                                                     }


                                                                                     ?>




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
                                        <a href="https://themeselection.com/" target="_blank"
                                             class="footer-link me-4">More Themes</a>

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

<script>
          document.addEventListener("DOMContentLoaded", function () {
               <?php if (isset($_SESSION['sua-tc']) && $_SESSION['sua-tc']) { ?>
                    // Successful profile update
                    const successToast = document.getElementById("capnhat-thanhcong");
                    var successToastInstance = new bootstrap.Toast(successToast);
                    successToastInstance.show();
                    <?php
                    unset($_SESSION['sua-tc']);
                    ?>
               <?php } else if (isset($_SESSION['sua-tb']) && !$_SESSION['sua-tb']) { ?>
                         // Handle when capnhat-hoso is false (e.g., show an error message)
                         const errorToast = document.getElementById("capnhat-thatbai");
                         var errorToastInstance = new bootstrap.Toast(errorToast);
                         errorToastInstance.show();
                    <?php unset($_SESSION['sua-tb']);
                    } ?>
          });
     </script>
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