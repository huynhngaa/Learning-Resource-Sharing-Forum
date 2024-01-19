<?php
if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
}
// session_start();
$err = [];
if (isset($_POST['dangnhap'])) {
  $tentaikhoan = $_POST['username'];
  $matkhau = $_POST['matkhau'];
  // $loaitaikhoan = "2";
  $sl = "SELECT * from nguoi_dung nd , vai_tro t  where nd.vt_ma = t.vt_ma and nd_username ='$tentaikhoan' ";
  $res = mysqli_query($conn, $sl);
  $data = mysqli_fetch_assoc($res);

  if (password_verify($matkhau, $data['nd_matkhau'])) {
    // Inside your PHP code after a successful login

    $_SESSION['user'] = $data;

    // Add a success message to indicate successful login
    $_SESSION['login_success'] = true; // Set this variable for successful login
    $current_page = $_SERVER['HTTP_REFERER'];
    // Redirect the user to the current page
    header("Location: $current_page");
    exit();
  } else {


    $_SESSION['login_failed'] = true;
    $current_page = $_SERVER['HTTP_REFERER'];

    header("Location: $current_page");
    exit();
    // $errors[] = 'Tài khoản hoặc mật khẩu không đúng!!!';
  }
}
?>
<style>
  /* CSS to set a higher z-index for the SweetAlert modal */
  .swal-modal {
    z-index: 10003;
    /* Set a value higher than the navigation bar's z-index */
  }
</style>
<nav style=" background-color: #12486B; height:57px" class="navbar navbar-expand-lg  navbar-dark mb-5 fixed-top">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><img width="150px" src="../assets/img/favicon/logo2.png" alt=""></a>
    <!-- <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button> -->
    <div class="layout-menu2-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#menuOffcanvas">
        <i class="bx bx-menu bx-sm"></i>

      </a>
    </div>

    <style>
      .dropdown-submenu {
        position: relative;
       
      }

      .dropdown-submenu .dropdown-menu {
        top: 10%;
        left: 100%;
        margin-top: -1px;
      }
      .dropdown-submenu:hover , .hover:hover{
        background-color: #2088cc;
      }
      .dropdown-submenu:hover>ul.dropdown-menu,
      .menu-item:hover>ul.dropdown-menu {
        display: block;
  
      }

      .dropdown-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .dropdown-item i {
        margin-left: 10px;
        /* Adjust the margin as needed */
      }
    </style>
    <style>
    /* Styles for the overlay */
    #overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.07); /* Adjust the alpha value for the desired level of transparency */
        z-index: 1000; /* Adjust the z-index to make sure it overlays other elements */
    }

    /* Add more styles as needed */
    
</style>

 <ul class="navbar-nav me-auto mb-2 mb-lg-0">
 <li class="nav-item dropdown">

 <a href="#" id="menu" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static">Khối lớp</a>

<div id="overlay"></div>
<ul style="background-color: #175d8b; box-shadow:none" class="dropdown-menu ">
 <?php

$sql1 = "SELECT * FROM khoi_lop"; // Assuming you have a table for school grades
$result1 = mysqli_query($conn, $sql1);
while ($khoi = mysqli_fetch_array($result1)) {
?>
    <li  class="dropdown-submenu">
        <a href="index.php?khoilop=<?php echo $khoi['kl_ma'] ?>" class="dropdown-item text-white"> <?php echo $khoi['kl_ten']; ?> <i class="fas fa-chevron-right"></i></a>
        <ul  style="background-color: #175d8b; box-shadow:none" class="dropdown-menu">
            <?php
            $sql2 = "SELECT * FROM mon_hoc WHERE kl_ma = " . $khoi['kl_ma'];
            $result2 = mysqli_query($conn, $sql2);

            while ($monhoc1 = mysqli_fetch_array($result2)) {
            ?>
                <li class="dropdown-submenu">
                    <a href="index.php?monhoc=<?php echo $monhoc1['mh_ma'] ?>" class="dropdown-item text-white">
                       <?php echo $monhoc1['mh_ten']; ?>
                        <i class="fas fa-chevron-right"></i>
                    </a>

                    <?php
                    $data = array();
                    $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
                            FROM danh_muc d
                            LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con
                            WHERE d.mh_ma = " . $monhoc1['mh_ma'];
                    $result = mysqli_query($conn, $sql);

                    while ($monhoc = mysqli_fetch_array($result)) {
                        $data[] = array(
                            "id" => $monhoc['dm_ma'],
                            "name" => $monhoc['dm_ten'],
                            "parent" => $monhoc['dm_cha']
                        );
                    }

                    if (!empty($data)) {
                        dequy3($data, 0, 3);
                    }
                    ?>
                </li>
            <?php
            }
            ?>
        </ul>
    </li>
<?php
}
?>
</ul>
 </li>
    </ul>

  

          <?php


          function dequy3($data, $parent = 0, $level = 0)
          {
            echo '<ul  style="background-color: #175d8b; box-shadow:none" class="dropdown-menu">';
            foreach ($data as $k => $value) {
              if ($value['parent'] == $parent) {
                echo "<li class='menu-item hover'>";
                echo '<a href="index.php?danhmuc=' . $value['id'] . '" class="dropdown-item text-white">';

                echo $value['name'];

                // Check if the current item has children
                $hasChildren = hasChildren($data, $value['id']);

                // If there are children, display the right arrow icon
                if ($hasChildren) {
                  echo ' <i class="fas fa-chevron-right"></i>';
                }

                echo '</a>';

                // Recursively call the function for the children
                if ($hasChildren) {
                  dequy3($data, $value['id'], $level + 1);
                }

                echo '</li>';
              }
            }
            echo '</ul>';
          }

          function hasChildren($data, $parentId)
          {
            foreach ($data as $value) {
              if ($value['parent'] == $parentId) {
                return true;
              }
            }
            return false;
          }

          ?>
      



    <style>
      /* Add your default input styles here */
      ::selection {
        background: #ffcc89;
        color: #222;
      }

      ::-moz-selection {
        background: #ffcc89;
        color: #222;
      }
    </style>
    <form class="d-flex mx-auto w-75" action="index.php" method="get">
      <div class="input-group input-group-merge">
        <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
        <input required class="form-control me-2" id="searchInput" name="noidung" type="search" placeholder="Tìm kiếm..." aria-label="Search" />
      </div>


    </form>

    <script>
      // Get the input element
      var searchInput = document.getElementById('searchInput');

      // Add an event listener for the 'input' event
      searchInput.addEventListener('input', function() {
        // Check if the input value is not empty
        if (searchInput.value.trim() !== '') {
          // Add a class to change the background color
          searchInput.classList.add('not-empty');
        } else {
          // Remove the class if the input is empty
          searchInput.classList.remove('not-empty');
        }
      });
    </script>
    <style>
      /* Đảm bảo rằng khung gợi ý ban đầu là ẩn đi */
      .search-suggestions {
        margin-top: 100px;
        margin-left: 250px;
        display: none;
        position: absolute;
        width: 50%;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #ccc;
        background-color: #fff;
        z-index: 1;
        color: red;
      }

      /* Hiển thị khung gợi ý khi có kết quả tìm kiếm */
      .search-suggestions.show {
        display: block;
      }
    </style>
    <div id="searchSuggestions" class="search-suggestions">
      <!-- Gợi ý kết quả tìm kiếm sẽ được hiển thị ở đây -->
    </div>


    <?php
    if (isset($_SESSION['user'])) {
      $user = $_SESSION['user']; ?>
      <?php
      include "include/thongbao.php"
      ?>
    <?php } ?>
    <?php
    if (isset($_SESSION['user'])) {
      $user = $_SESSION['user'];
      $username = $user['nd_username'];
      $nguoi_dung = "SELECT * FROM nguoi_dung a, vai_tro b WHERE a.vt_ma=b.vt_ma and nd_username='$username'";
      $result_nguoi_dung = mysqli_query($conn, $nguoi_dung);
      $row_nguoi_dung = mysqli_fetch_assoc($result_nguoi_dung);
    ?>


      <ul class="navbar-nav mb-2 mb-lg-0">


        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
              <img src="../assets/img/avatars/<?php echo $row_nguoi_dung['nd_hinh'] ?>" alt class="w-px-40 h-auto rounded-circle" />
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="profile.php?id=<?php echo $row_nguoi_dung['nd_username'] ?>">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img src="../assets/img/avatars/<?php echo $row_nguoi_dung['nd_hinh'] ?>" alt class="w-px-40 h-auto rounded-circle" />
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-semibold d-block"><?php echo $row_nguoi_dung['nd_hoten'] ?></span>
                    <small class="text-muted"><?php echo $row_nguoi_dung['vt_ten'] ?></small>
                  </div>
                </div>
              </a>
            </li>
            <li>
              <div class="dropdown-divider"></div>
            </li>


            <li>
              <a onclick="logout()" href="#" class="dropdown-item">
                <i class="bx bx-power-off me-2"></i>
                <span>Đăng Xuất</span>
              </a>
            </li>
          </ul>
        <?php } else { ?>
          <ul class="navbar-nav mb-2 mb-lg-0">
            <li> <a data-bs-toggle="modal" data-bs-target="#modalCenter" class="nav-link align-middle" href="">Đăng Nhập</a></li>
          </ul>
        <?php } ?>
        </li>
      </ul>
  </div>
  </div>

  


</nav>
<div class="position-fixed top-0 end-0" style="z-index: 11; margin-top:75px">
    <div id="loginSuccessToast" class="toast hide bg-success " role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <span><i class="fa-solid fa-circle-check fa-lg"> </i> &nbsp; </span>
        <strong class="me-auto">Đăng nhập thành công</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>

    </div>
  </div>




  <!-- HTML structure for failed toast message -->
  <div class="position-fixed top-0 end-0 " style="z-index: 11; margin-top:70px">
    <div id="loginFailedToast" class="toast hide bg-danger" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <span><i class="fa-solid fa-circle-exclamation fa-lg"></i> &nbsp;</span>
        <strong class="me-auto">Đăng nhập thất bại</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>

    </div>
  </div>
  <!-- HTML structure for logout success toast message -->
  <div class="position-fixed top-0 end-0" style="z-index: 11; margin-top: 80px">
    <div id="logoutSuccessToast" class="toast hide bg-success" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
        <strong class="me-auto">Đăng xuất thành công</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>
<div class="col-lg-4 col-md-6">

  <style>
    /* CSS để thay đổi kích thước cửa sổ modal */
    #modalCenter .modal-dialog {
      max-width: 25%;
      /* Đặt chiều rộng tối đa là 100px */
      width: 100%;
      /* Đặt chiều rộng tối đa là 100% để cửa sổ modal không bị tràn ra ngoài */
    }
  </style>
  <!-- Modal -->

  <div class="modal fade" id="modalCenter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <!-- <h4 class="modal-title" id="modalCenterTitle">Đăng Nhập</h4> -->
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="icon d-flex align-items-center justify-content-center">
            <img src="https://ps.w.org/user-avatar-reloaded/assets/icon-256x256.png?rev\u003d2540745" width="70px" alt="">
          </div>
          <br>

          <h3 class="text-center mb-4">Đăng Nhập</h3>
          <?php
          if (isset($error)) {
            foreach ($error as $error) {
              echo '<span class="error-msg">' . $error . '</span>';
            }
          };

          ?>
          <form action="index.php" method="post">
            <div class="row">
              <div class="col mb-3">
                <label for="nameWithTitle" class="form-label"><b>Tên Đăng Nhập</b></label>
                <input name="username" type="text" class="form-control" placeholder="Nhập tên đăng nhập" />
              </div>

            </div>

            <div class="row">

              <div class="col mb-3">
                <label for="nameWithTitle" class="form-label"><b>Mật Khẩu</b></label>
                <input name="matkhau" type="password" class="form-control" placeholder="Nhập mật khẩu" />
              </div>

            </div>
            <div class="modal-footer d-flex justify-content-center">

              <button type="submit" id="liveToastBtn" name="dangnhap" class="btn btn-primary">Đăng nhập</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  var toast = new bootstrap.Toast(document.getElementById('loginSuccessToast'));
  toast.show();
  setTimeout(function() {
    toast.hide();
  }, 1500);
</script>
<script>
  var toast = new bootstrap.Toast(document.getElementById('loginFailedToast'));
  toast.show();
  setTimeout(function() {
    toast.hide();
  }, 1500);
</script>
<script>
  <?php if (isset($_SESSION['login_success']) && $_SESSION['login_success']) { ?>
    // Successful login
    document.addEventListener("DOMContentLoaded", function() {
      const successToast = document.getElementById("loginSuccessToast");
      var successToastInstance = new bootstrap.Toast(successToast);
      successToastInstance.show();
    });
  <?php
    // Reset the login_success session variable
    unset($_SESSION['login_success']);
  }
  ?>

  <?php if (isset($_SESSION['login_failed']) && $_SESSION['login_failed']) { ?>
    // Failed login
    document.addEventListener("DOMContentLoaded", function() {
      console.log("Failed login detected"); // Add this line for debugging
      const failedToast = document.getElementById("loginFailedToast");
      var failedToastInstance = new bootstrap.Toast(failedToast);
      failedToastInstance.show();
    });
  <?php
    // Reset the login_failed session variable
    unset($_SESSION['login_failed']);
  }
  ?>
</script>


<script type="text/javascript">
  function logout() {
    Swal.fire({
      //title: 'Bạn chưa đăng nhập',
      text: "Bạn có muốn đăng xuất?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      cancelButtonText: 'Huỷ ',
      confirmButtonText: 'Đăng Xuất'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location = "include/logout.php";
      }
    })
  }
</script>
<script>
  // Get the toggle button and the notifications container
  const notificationsToggle = document.getElementById("notifications-toggle");
  const notificationsContainer = document.getElementById("notifications-container");

  // Add a click event listener to the toggle button
  notificationsToggle.addEventListener("click", function() {
    // Toggle the 'show' class to make the container visible
    notificationsContainer.classList.toggle("show");
  });

  // You can also close the notifications when clicking outside of the container
  document.addEventListener("click", function(event) {
    if (event.target !== notificationsToggle && !notificationsContainer.contains(event.target)) {
      notificationsContainer.classList.remove("show");
    }
  });
  // Get all "x" buttons in your notifications
</script>


<script>
  // Get all "x" buttons in your notifications
  const notificationDeleteButtons = document.querySelectorAll(".dropdown-notifications-archive");

  // Add a click event listener to each "x" button
  notificationDeleteButtons.forEach(function(button) {
    button.addEventListener("click", function(event) {
      // Find the parent notification item (the <li>) and remove it
      const notificationItem = button.closest(".dropdown-notifications-item");
      if (notificationItem) {
        notificationItem.remove();
      }
      event.preventDefault(); // Prevent any link behavior
    });
  });
</script>



