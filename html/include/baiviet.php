<?php

$result_bai_viet = mysqli_query($conn, $bai_viet);

if (isset($_SESSION['user'])) {
  $user = $_SESSION['user'];
  if ($user['nd_username'] == $id) {
    ?>

    <div class="row card-header d-flex flex-wrap py-3 px-3 justify-content-between">

      <div style="margin-bottom:1rem; margin-top:-1rem" class="col-lg-12  row">
        <div class="col-md-3 me-1 product_status">
          <label>Trạng thái </label>
          <select name="trangthai" class="form-select" id="trangthai">
            <option value="Tất cả">Tất cả</option>
            <?php
            $tt = "select * from trang_thai where tt_ma in (1,2,3) ";
            $result_tt = mysqli_query($conn, $tt);
            while ($row_tt = mysqli_fetch_array($result_tt)) {
              ?>
              <option value="<?php echo $row_tt['tt_ma']; ?>">
                <?php echo $row_tt['tt_ten']; ?>
              </option>
            <?php } ?>
          </select>
        </div>
        <div class="col-md-3  me-1 ">
          <div class="dataTables_filter">
            <label>Từ ngày</label>
            <input id="tungay" type="date" class="form-control" value="2000-01-01">

          </div>
        </div>
        <div class="col-md-3  me-1 ">
          <span>Đến ngày</span>
          <input id="denngay" type="date" class="form-control" value="<?php echo date('Y-m-d'); ?>">

        </div>
        <div class="col-md-2">

          <br>
          <button id="loc_ngay" class="btn btn-primary"><i class="fa fa-search"></i></button>
        </div>
      </div>



      <div style="padding-left:10px"
        class="dt-action-buttons text-xl-end text-lg-start text-md-end text-start d-flex align-items-center justify-content-md-end gap-3 gap-sm-2 flex-wrap flex-sm-nowrap pt-0">

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
          <button name="xoa_bv" type="submit" class="btn btn-sm btn-outline-warning" id="xoa_bv">


            <span>
              <i class="bx bx-trash"></i>
              <span class="dt-down-arrow d-none d-xl-inline-block">Chuyển vào thùng rác</span>

            </span>

          </button>
          <a href="baiviet_daxoa.php?id=<?php echo $id ?>" type="submit" class="btn btn-sm btn-outline-danger">


            <span>
              <i class="bx bx-trash"></i>
              <span class="dt-down-arrow d-none d-xl-inline-block">Thùng rác</span>

            </span>

          </a>
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
                if ($trangthai == "Tất cả") {
                  $sd = "SELECT count(*) as tong
                                                                    FROM bai_viet a
                                                                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                                                                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where (c.tt_ma IS NULL OR c.tt_ma != 4)
                                                                    And a.nd_username ='$id'";
                } elseif ($trangthai == "3") {
                  $sd = "SELECT count(*) as tong
                                                                    FROM bai_viet a
                                                                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                                                                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where (c.tt_ma IS NULL OR c.tt_ma != 4)
                                                                    And c.tt_ma IS NULL or c.tt_ma = 3
                                                                    And a.nd_username ='$id'
                                                                    And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
                } else {
                  $sd = "SELECT count(*) as tong
                                                                    FROM bai_viet a
                                                                    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
                                                                    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
                                                                    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
                                                                    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
                                                                    where (c.tt_ma IS NULL OR c.tt_ma != 4)
                                                                    And  c.tt_ma = '$trangthai'
                                                                    And a.nd_username ='$id'
                                                                    And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'";
                }

                $result_sd = mysqli_query($conn, $sd);
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
            if (mysqli_num_rows($result_bai_viet) > 0) {
              while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                $stt = $stt + 1;
                ?>
                <tr>
                  <td>
                    <input class="form-check-input check-item" name="check[]" type="checkbox"
                      value="<?php echo $row_bai_viet['bv_ma'] . '|' . $row_bai_viet['tt_ma']; ?>">
                  </td>
                  <td class="row-bai-viet">
                    <?php echo $stt ?>
                  </td>

                  <td>

                    <?php

                    if ($row_bai_viet['tt_ma'] == 1) {

                      echo "<span class='badge bg-label-success '>" . $row_bai_viet['tt_ten'] . "</span>";
                    } else if ($row_bai_viet['tt_ma'] == 2) {
                      echo "<span class='badge alert-warning '>" . $row_bai_viet['tt_ten'] . "</span>";
                    } else if ($row_bai_viet['tt_ma'] == 4) {

                      echo "<span class='badge bg-label-danger'>" . $row_bai_viet['tt_ten'] . "</span>";
                    } else {
                      echo "<span class='badge bg-label-primary '>Chờ duyệt</span>";
                    }
                    ?>

                  </td>
                  <td style="white-space: normal; padding:5px 0 5px 0">
                    <?php echo $row_bai_viet['bv_tieude'] ?>
                  </td>
                  <td style="white-space: normal">
                    <?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

                  </td>


                  <td>
                    <a id="dropdownHanhDong" data-bs-toggle="dropdown" aria-expanded="false"
                      style="display:math; padding:0.1rem 0.6rem" class="dropdown-item">
                      <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                    </a>

                    <div class="dt-button-collection dropdown-menu" style="top: 55.9375px; left: 419.797px;min-width:7rem"
                      aria-labelledby="dropdownHanhDong">
                      <div role="menu">
                        <a href="Xem_BaiViet.php?id=<?php echo $id ?>&bv=<?php echo $row_bai_viet['bv_ma'] ?>"
                          class="dt-button buttons-print dropdown-item" tabindex="0" type="button">
                          <span><i class=" fa fa-eye me-2"></i>Xem</span>
                        </a>
                        <a href="sua_baiviet.php?id=<?php echo $id ?>&bv=<?php echo $row_bai_viet['bv_ma'] ?>"
                          class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                          <span><i class="bx bx-edit-alt me-2"></i>Sửa</span>
                        </a>

                        <a href="#"
                          onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&id=<?php echo $row_bai_viet['nd_username'] ?>&tt=<?php echo $row_bai_viet['tt_ma'] ?>');"
                          class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                          <span><i class="bx bx-trash me-2"></i>Xoá</span>
                        </a>

                      </div>
                    </div>

                  </td>
                </tr>
              <?php }
            } else {
              echo '<tr><td>Không có dữ liệu</td></tr>';
            } ?>

          </tbody>

        </table>

      <?php }
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

            echo ' <small>  <span class="badge bg-label-primary">' . $article['dm_ten'] . '</span></small>';
            echo ' <small>  <span class="badge bg-label-primary">' . $article['mh_ten'] . '</span></small>';
            echo ' <small>  <span class="badge bg-label-primary">' . $article['kl_ten'] . '</span></small>';


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