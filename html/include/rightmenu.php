<?php
if (isset($_SESSION['login_success']) && $_SESSION['login_success']) {
  // Người dùng đã đăng nhập, chuyển hướng đến trang đăng bài hoặc trang chính
  header("Location: trang-dang-bai.php");
  exit;
}
?>


<div class="offcanvas offcanvas-start" style="width:20rem" tabindex="-1" id="menuOffcanvas">
  <div class="offcanvas-header">
    <a style="    padding: 0" class="dropdown-item" href="#">
      <div class="d-flex">
        <div class="flex-shrink-0 me-3">
          <div class="avatar-online">
            <img src="../assets/img/avatars/<?php echo $user['nd_hinh'] ?>" alt class="w-px-50 h-auto rounded-circle" />
          </div>
        </div>
        <div class="flex-grow-1">
          <span class="fw-semibold d-block"><?php echo $user['nd_hoten'] ?></span>
          <small class="text-muted">Admin</small>
        </div>
      </div>
    </a>
    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled ">
      <div class="input-group input-group-merge">
        <span class="input-group-text" id="basic-addon-search31"><i class="bx bx-search"></i></span>
        <input type="text" class="form-control" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon-search31" />
      </div>

      <li class="menu-item"><a href="#"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
    </ul>
  </div>
</div>


<aside style="z-index: 1;" id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme mt-2">



  <div class="menu-inner-shadow"></div>

  <ul class="menu-inner py-1">
    <li class="menu-item">
      <a href="#" class="menu-link" style="text-align: center; display: block; background-color: #1774af " id="dangBaiLink">
        <span style="color:#fff"><i class="fa-solid fa-plus"></i> ĐĂNG BÀI</span>
      </a>

    </li>

    <li class="menu-header small text-uppercase"><span class="menu-header-text">Khối Lớp</span></li>
    <!-- Forms -->
    <li class="menu-item ms-4 ">
    <div class="form-check mt-3 ">
        <input checked class="form-check-input" type="checkbox" value="" id="defaultCheck" >
       <a href="index.php"> <label class="form-check-label" >Tất cả bài viết </label></a>
         
           </div>
           </li>
    <?php

    $s2 = "SELECT * FROM khoi_lop";
    $r2 = mysqli_query($conn, $s2);
    while ($khoilop = mysqli_fetch_array($r2)) {
    ?>
      <li class="menu-item ms-4 me-1">
        
       

        <div class="form-check mt-3 ">
      
           <input class="form-check-input" type="checkbox" value="" id="defaultCheck<?php echo $khoilop['kl_ma'] ?>" >
           <a href="index.php?khoilop=<?php echo $khoilop['kl_ma'] ?>"> <label  ><?php echo $khoilop['kl_ten'] ?> </label></a>
           <?php
                $sl_kl = "SELECT l.kl_ma, count(*) as tong FROM khoi_lop l
                          Left join mon_hoc m on l.kl_ma = m.kl_ma 
                          Left join danh_muc d on d.mh_ma = m.mh_ma 
                          right join bai_viet b on b.dm_ma = d.dm_ma
                          LEFT join kiem_duyet k On b.bv_ma = k.bv_ma
                          WHERE l.kl_ma='".$khoilop['kl_ma']."' and k.tt_ma = '1'";

                $result_sl_kl = mysqli_query($conn,$sl_kl);
                $row_sl_kl = mysqli_fetch_assoc($result_sl_kl);
                echo "(".$row_sl_kl['tong'].")";
           ?>
           </div>
       
        

      </li>
    <?php } ?>




    <?php
    // include "monhoc-danhmuc.php"
     ?> 


    <!-- Forms & Tables -->
 


  </ul>

  <?php


  ?>
</aside>

<script>
  // Kiểm tra xem người dùng đã đăng nhập hay chưa
  if (<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
    // Người dùng đã đăng nhập, không cần mở modal, chỉ chuyển hướng
    document.getElementById('dangBaiLink').href = 'post.php';
  } else {
    // Người dùng chưa đăng nhập, mở modal khi click vào liên kết
    document.getElementById('dangBaiLink').addEventListener('click', function(event) {
      event.preventDefault(); // Ngăn chặn mở trang mới khi click
      $('#modalCenter').modal('show'); // Mở modal đăng nhập
    });
  }
</script>


<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

<script>
  // Sử dụng jQuery để xử lý sự kiện khi người dùng bấm vào menu-link
  $('.menu-link.menu-toggle').click(function(e) {
    e.preventDefault(); // Ngăn chặn mặc định hành vi chuyển trang khi bấm vào liên kết

    // Tìm danh mục con tương ứng trong phần tử cha
    var subMenu = $(this).next('.sub-menu');

    if (subMenu.length > 0) {
      // Nếu có danh mục con, hiển thị hoặc ẩn danh mục con tương ứng
      subMenu.toggle();
    }
  });
</script>