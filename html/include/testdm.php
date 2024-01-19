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
                        <input
                          type="text"
                          class="form-control"
                          placeholder="Search..."
                          aria-label="Search..."
                          aria-describedby="basic-addon-search31"
                        />
                      </div>

      <li class="menu-item"><a href="#"><i class="fa-solid fa-house"></i> Trang chủ</a></li>
    </ul>
  </div>
</div>


<aside style="z-index: 1;" id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme mt-2">
          
        

          <div class="menu-inner-shadow"></div>
         
          <ul class="menu-inner py-1">
          <li class="menu-item">
          <a href="#" class="menu-link" style="text-align: center; display: block; background-color: #5DADE2 " id="dangBaiLink">
    <span style="color:#fff"><i class="fa-solid fa-plus"></i> ĐĂNG BÀI</span>
</a>

    </li>
    <li class="menu-header small text-uppercase">
        <span class="menu-header-text">Môn Học</span>
    </li>


            <?php
               $sql = "SELECT * FROM mon_hoc";
               $result = mysqli_query($conn, $sql);
              while(  $monhoc = mysqli_fetch_array($result) ){ 
            ?>
            <li class="menu-item ">
              <a href="index.php?id=<?php echo $monhoc['mh_ma']?>" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-dock-top"></i>
                <div data-i18n="Account Settings"><?php echo $monhoc['mh_ten']?></div>
              </a>
              <ul class="menu-sub">
                <?php 
                $id = $monhoc['mh_ma'];
                   $sql2 = "SELECT * FROM mon_hoc a, danh_muc b where a.mh_ma = b.mh_ma and b.mh_ma =$id";
                   $result2 = mysqli_query($conn, $sql2);
                   while(  $danhmuc= mysqli_fetch_array($result2) ){ 
                ?>
                <li class="menu-item">
                  <a href="index.php?id=<?php echo $danhmuc['dm_ma']?>" class="menu-link">
                    <div data-i18n="Account"><?php echo $danhmuc['dm_ten']?></div>
                  </a>
                </li>
                <?php } ?>
              
               
              </ul>
            </li>
            <?php } ?>
          
          
            <!-- Components -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Danh mục</span></li>
            <!-- Cards -->
            <?php 
$s = "WITH RECURSIVE danh_muc_paths AS ( 
    SELECT b1.dm_ma AS dm_cha, b2.dm_ma AS dm_con, 1 AS lvl
    FROM danh_muc b1
    LEFT JOIN danhmuc_phancap r1 ON b1.dm_ma = r1.dm_cha
    LEFT JOIN danh_muc b2 ON r1.dm_con = b2.dm_ma
    UNION ALL
    SELECT b1.dm_ma AS dm_cha, b2.dm_ma AS dm_con, bp.lvl + 1 AS lvl
    FROM danh_muc b1
    LEFT JOIN danhmuc_phancap r1 ON b1.dm_ma = r1.dm_cha
    LEFT JOIN danh_muc b2 ON r1.dm_con = b2.dm_ma
    INNER JOIN danh_muc_paths bp ON b1.dm_ma = bp.dm_con
), ranked_danh_muc_paths AS (
    SELECT dm_cha, dm_con, lvl, ROW_NUMBER() OVER (PARTITION BY dm_cha ORDER BY lvl DESC) AS row_num
    FROM danh_muc_paths
)
SELECT dm_cha, dm_con, lvl, dm_ten
FROM ranked_danh_muc_paths rp
INNER JOIN danh_muc d ON rp.dm_cha = d.dm_ma
WHERE row_num = 1
AND lvl = 1";

$r = mysqli_query($conn, $s);

while ($danhmuc = mysqli_fetch_array($r)) { 
    $parentId = $danhmuc['dm_con']; 
?>
<li class="menu-item ">
    <a href="index.php?id=<?php echo $danhmuc['dm_cha']?>" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons bx bx-dock-top"></i>
        <div data-i18n="Account Settings">
            <?php echo ($parentId !== null) ? $parentId : ' '; ?> <?php echo $danhmuc['dm_ten']?>
        </div>
    </a>
    <ul class="menu-sub">
        <?php 
        if ($parentId !== null) {
            $sqldm = "WITH RECURSIVE danh_muc_paths AS ( 
                SELECT b1.dm_ma AS dm_cha, b2.dm_ma AS dm_con, 1 AS lvl
                FROM danh_muc b1
                LEFT JOIN danhmuc_phancap r1 ON b1.dm_ma = r1.dm_cha
                LEFT JOIN danh_muc b2 ON r1.dm_con = b2.dm_ma
                UNION ALL
                SELECT b1.dm_ma AS dm_cha, b2.dm_ma AS dm_con, bp.lvl + 1 AS lvl
                FROM danh_muc b1
                LEFT JOIN danhmuc_phancap r1 ON b1.dm_ma = r1.dm_cha
                LEFT JOIN danh_muc b2 ON r1.dm_con = b2.dm_ma
                INNER JOIN danh_muc_paths bp ON b1.dm_ma = bp.dm_con
            ), ranked_danh_muc_paths AS (
                SELECT dm_cha, dm_con, lvl, ROW_NUMBER() OVER (PARTITION BY dm_cha ORDER BY lvl DESC) AS row_num
                FROM danh_muc_paths
            )
            SELECT dm_cha, dm_con, lvl, dm_ten
            FROM ranked_danh_muc_paths rp, danh_muc d
            WHERE rp.dm_cha = d.dm_ma
            AND row_num = 1
            AND dm_cha = $parentId";
        
            $resultdm = mysqli_query($conn, $sqldm);
            while ($danhmuccon = mysqli_fetch_array($resultdm)) { 
        ?>
        <li class="menu-item">
            <a href="index.php?id=<?php echo $danhmuccon['dm_cha']?>" class="menu-link">
                <div data-i18n="Account"><?php echo $danhmuccon['dm_ten']?></div>
            </a>
        </li>
        <?php } 
        }
        ?>
    </ul>
</li>
<?php } ?>



            <!-- Forms & Tables -->
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Khối Lớp</span></li>
            <!-- Forms -->
            <?php 
                
                $s2= "SELECT * FROM khoi_lop";
                $r2 = mysqli_query($conn, $s2);
                while(  $khoilop= mysqli_fetch_array($r2) ){ 
             ?>
            <li class="menu-item">
              <a href="javascript:void(0);" class="menu-link">
                <i class="menu-icon tf-icons bx bx-detail"></i>
                <div data-i18n="Form Elements"><?php echo $khoilop['kl_ten']?></div>
              </a>
            
            </li>
         <?php } ?>
          
        
          </ul>
        </aside>
        <script>
// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
  // Người dùng đã đăng nhập, không cần mở modal, chỉ chuyển hướng
  document.getElementById('dangBaiLink').href = 'post.php';
} else {
  // Người dùng chưa đăng nhập, mở modal khi click vào liên kết
  document.getElementById('dangBaiLink').addEventListener('click', function (event) {
    event.preventDefault(); // Ngăn chặn mở trang mới khi click
    $('#modalCenter').modal('show'); // Mở modal đăng nhập
  });
}
</script>






