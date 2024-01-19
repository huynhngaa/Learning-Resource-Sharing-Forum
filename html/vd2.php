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


    <form>
    <label for="mon_hoc">Chọn Môn Học:</label>
    <select id="mon_hoc" name="mon_hoc">
        <option value="">Chọn Môn Học</option>
        <?php
        $sql1 = "SELECT * FROM mon_hoc";
        $result1 = mysqli_query($conn, $sql1);

        while ($monhoc1 = mysqli_fetch_array($result1)) {
            echo '<option value="' . $monhoc1['mh_ma'] . '">' . $monhoc1['mh_ten'] . '</option>';
        }
        ?>
    </select>

    <label for="danh_muc">Chọn Danh Mục:</label>
    <select id="danh_muc" name="danh_muc" >
        <option value="">Chọn Danh Mục</option>
    </select>
</form>

<script>
document.getElementById('mon_hoc').addEventListener('change', function() {
    var selectedMonHoc = this.value;
    var danhMucSelect = document.getElementById('danh_muc');
    danhMucSelect.innerHTML = '<option value="">Chọn Danh Mục</option>';

    if (selectedMonHoc) {
        // Fetch danh_muc options based on selected subject using AJAX
        // You would need to implement this part
        // The response should contain nested danh_muc options based on the selectedMonHoc

        // Example AJAX code (you will need to adapt this to your needs):
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                var options = JSON.parse(xhr.responseText);
                generateCategoryOptions(options, danhMucSelect);
                danhMucSelect.disabled = false;
            }
        };
        xhr.open('GET', 'include/get_danhmuc.php?mh_ma=' + selectedMonHoc, true);
        xhr.send();
    } else {
        danhMucSelect.disabled = true;
    }
});

function generateCategoryOptions(categories, selectElement, level = 0) {
    categories.forEach(function(category) {
        var optionElement = document.createElement('option');
        optionElement.value = category.id;
        optionElement.text = '  '.repeat(level) + category.name;
        selectElement.appendChild(optionElement);
        if (category.children) {
            generateCategoryOptions(category.children, selectElement, level + 1);
        }
    });
}
</script>




    <style>
    
 


    </style>
    <!-- Components -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Danh mục</span></li>
    <!-- Cards -->



    <?php

    $data = array();

    $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
FROM danh_muc d
LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con;
";
    $result = mysqli_query($conn, $sql);

    while ($monhoc = mysqli_fetch_array($result)) {
      $data[] = array(
        "id" => $monhoc['dm_ma'],
        "name" => $monhoc['dm_ten'],
        "parent" => $monhoc['dm_cha']
      );
    }


    function dequy($data, $parent = 0, $level = 0)
    {
        foreach ($data as $k => $value) {
            if ($value['parent'] == $parent) {
                echo "<li class='menu-item'>";
                echo '<a href="index.php?id=' . $value['id'] . '" class="menu-link' . (subMenuExists($data, $value['id']) ? ' menu-toggle' : '') . ' level-' . $level . '">';
                
                // Kiểm tra xem parent có bằng 0 không để hiển thị icon
                if ($value['parent'] == 0) {
                    echo "<i class='menu-icon fa-solid fa-folder'></i>";
                }
                
                echo '<div data-i18n="Account Settings">' . $value['name'] . '</div>';
                echo '</a>';
                echo '<ul class="menu-sub">';
                $id = $value['id'];
                unset($data[$k]);
                dequy($data, $id, $level + 1);
                echo '</ul>';
                echo '</li>';
            }
        }
    }
    
    
    




    dequy($data);
    function subMenuExists($data, $id) {
      foreach ($data as $item) {
          if ($item['parent'] == $id) {
              return true; // Sub-menu exists
          }
      }
      return false; // No sub-menu found
  }
  
    ?>



    <!-- Forms & Tables -->
    <li class="menu-header small text-uppercase"><span class="menu-header-text">Khối Lớp</span></li>
    <!-- Forms -->
    <?php

    $s2 = "SELECT * FROM khoi_lop";
    $r2 = mysqli_query($conn, $s2);
    while ($khoilop = mysqli_fetch_array($r2)) {
    ?>
      <li class="menu-item">
        <a href="javascript:void(0);" class="menu-link">
          <i class="menu-icon tf-icons bx bx-detail"></i>
          <div data-i18n="Form Elements"><?php echo $khoilop['kl_ten'] ?></div>
        </a>

      </li>
    <?php } ?>


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