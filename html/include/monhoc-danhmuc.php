
<li class="menu-header small text-uppercase">
      <span class="menu-header-text">Môn Học</span>
    </li>




    <?php

    $sql1 = "SELECT * FROM mon_hoc";
    $result1 = mysqli_query($conn, $sql1);
    while ($monhoc1 = mysqli_fetch_array($result1)) {
    ?>
      <li class="menu-item">
        <a href="index.php?id=<?php echo $monhoc1['mh_ma'] ?>" class="menu-link menu-toggle">
          <i class="menu-icon fa-solid fa-book"></i>
          <!-- <i class=" tf-icons bx bx-dock-top"></i> -->
          <div data-i18n="Account Settings"><?php echo $monhoc1['mh_ten'] ?></div>
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

        // Pass the top-level menu items to the recursive function
        dequy2($data, 0, 3);
        ?>
      </li>
    <?php
    }
    ?>

    <?php
    function dequy2($data, $parent = 0, $level = 0)
    {
      echo '<ul class="menu-sub">';
      foreach ($data as $k => $value) {
        if ($value['parent'] == $parent) {
          echo "<li class='menu-item'>";
          echo '<a href="index.php?danhmuc=' . $value['id'] . '" class="menu-link' . (subMenuExists2($data, $value['id']) ? ' menu-toggle' : '') . ' level-' . $level . '">';
          echo '<div data-i18n="Account Settings">' . $value['name'] . '</div>';
          echo '</a>';
          dequy2($data, $value['id'], $level + 1);
          echo '</li>';
        }
      }
      echo '</ul>';
    }

    function subMenuExists2($data, $id)
    {
      foreach ($data as $item) {
        if ($item['parent'] == $id) {
          return true; 
        }
      }
      return false; 
    }
    ?>

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
    function subMenuExists($data, $id)
    {
      foreach ($data as $item) {
        if ($item['parent'] == $id) {
          return true; // Sub-menu exists
        }
      }
      return false; // No sub-menu found
    }

    ?>
