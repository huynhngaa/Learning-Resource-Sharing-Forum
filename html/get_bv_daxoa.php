<?php

include( 'include/conn.php' );
$id = $_GET['id'];
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


$bai_viet = "SELECT a.*, e.*,c.tt_ma,c.thoigian, d.tt_ten FROM bai_viet a
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




?>

<input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
<?php
                                            $stt = 0;
                                            while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
                                              $stt = $stt + 1;
                                            ?>
<tr>
    <td>
        <input class="form-check-input check-item" name="check[]" type="checkbox"
            value="<?php echo $row_bai_viet['bv_ma'] ?>">
    </td>
    <td class="row-bai-viet"> <?php echo $stt ?> </td>

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
    <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['thoigian']), "d-m-Y (H:i:s)"); ?>
    <td style="white-space: normal; padding:5px 0 5px 0"> <?php echo $row_bai_viet['bv_tieude'] ?> </td>
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
                <a href="Sua_BaiViet.php?id=<?php echo $id ?>&bv=<?php echo $row_bai_viet['bv_ma'] ?>"
                    class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                    <span><i class="bx bx-edit-alt me-2"></i>Sửa</span>
                </a>

                <a href="#"
                    onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&id=<?php echo $row_bai_viet['nd_username'] ?>');"
                    class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                    <span><i class="bx bx-trash me-2"></i>Xoá</span>
                </a>

            </div>
        </div>

    </td>
</tr>
<?php } ?>