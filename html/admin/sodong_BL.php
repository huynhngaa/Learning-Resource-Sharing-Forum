<?php
session_start();
include( './includes/connect.php' );
$so_dong = 3;
$trangthai = isset($_GET['tt']) ? $_GET['tt'] : "Tất cả";
$sap_xep = 'DESC';
$tungay = 2022-01-01;
$denngay = date('Y-m-d');


if (isset($_GET['sodong'])) {
    $so_dong = intval($_GET['sodong']);
}



if (isset($_GET['sort'])) {
    if ($_GET['sort'] === "asc") {
        $sap_xep = "asc";
    } elseif ($_GET['sort'] === "desc") {
        $sap_xep = "desc";
    }
}

if (isset($_GET['tungay'])) {
    $tungay = $_GET['tungay'];
}

if (isset($_GET['denngay'])) {
    $denngay = $_GET['denngay'];
}


if (isset($_GET['trangthai'])) {
    $trangthai = $_GET['trangthai'];
}

if ($trangthai == "Tất cả") {
    $binh_luan = "SELECT * FROM binh_luan a 
                LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'
                ORDER BY a.bl_ma $sap_xep LIMIT $so_dong";
}else{
    $binh_luan = "SELECT * FROM binh_luan a 
                LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'
                and a.trangthai = '$trangthai'
                ORDER BY a.nd_username $sap_xep LIMIT $so_dong";
}
$result_binh_luan = mysqli_query($conn,$binh_luan);
unset($_SESSION['sl_dong']);
$sl_dong_hientai = mysqli_num_rows($result_binh_luan);
$_SESSION['sl_dong'] = $sl_dong_hientai;

if($trangthai == 'Tất cả'){
    $kq="SELECT count(*) as tong FROM binh_luan a 
                LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'";
}else{
    $kq="SELECT count(*) as tong FROM binh_luan a 
                LEFT JOIN bai_viet b ON a.bv_ma=b.bv_ma 
                LEFT JOIN nguoi_dung c ON a.nd_username=c.nd_username 
                LEFT JOIN trang_thai t ON t.tt_ma =a.trangthai
                where DATE(bv_ngaydang) >= '$tungay' AND DATE(bv_ngaydang) <= '$denngay'
                and a.trangthai = '$trangthai'";
}

$result_kq = mysqli_query($conn,$kq);
$row_kq = mysqli_fetch_assoc($result_kq);
$_SESSION['tong_sd'] = array();
$_SESSION['tong_sd'] = $row_kq['tong'];



?>
<input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">

<?php
    $stt = 0;
    while ($row_binh_luan = mysqli_fetch_array($result_binh_luan)) {                                    
        $stt = $stt + 1;
    ?>
<tr>

    <td>
        <input class="form-check-input check-item" name="check[]" type="checkbox"
            value="<?php echo $row_binh_luan['bl_ma'] ?>">

    </td>

    <td class="row-bai-viet"> <?php echo $stt; ?> </td>

    <td>

        <?php 
        if ($row_binh_luan["tt_ma"] == 1) {
        echo "<span class='badge bg-label-success me-1'>" .
            $row_binh_luan["tt_ten"] .
            "</span>";
        } elseif ($row_binh_luan["tt_ma"] == 2) {
            echo "<span class='badge bg-label-danger me-1'>" .
                $row_binh_luan["tt_ten"] .
                "</span>";
        } elseif ($row_binh_luan["tt_ma"] == 4) {
            echo "<span class='badge bg-label-dismissible me-1'>" .
                $row_binh_luan["tt_ten"] .
                "</span>";
        } else {
            echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
        }
    ?>

        <!-- </span> -->
    </td>
    <td style="white-space: normal">
        <?php echo $row_binh_luan['bv_tieude'] ?>
    </td>

    <td> <?php echo $row_binh_luan['nd_hoten'] ?> </td>
    <td style="white-space: normal">
        <?php echo $row_binh_luan['bl_noidung'] ?>
    </td>
    <td><?php echo date_format(date_create($row_binh_luan['bl_thoigian']), "d-m-Y (H:i:s)"); ?>
    </td>

    <td>

        <a id="dropdownHanhDong" data-bs-toggle="dropdown" aria-expanded="false"
            style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
            href="Xem_BinhLuan.php?bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
        </a>

        <div class="dt-button-collection dropdown-menu" style="top: 55.9375px; left: 419.797px;min-width:7rem"
            aria-labelledby="dropdownHanhDong">
            <div role="menu">
                <a href="Xem_BinhLuan.php?bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>"
                    class="dt-button buttons-print dropdown-item" tabindex="0" type="button">
                    <span><i class=" fa fa-eye me-2"></i>Xem</span>
                </a>
                <a class="dt-button buttons-csv buttons-html5 dropdown-item" type="button" data-bs-toggle="modal"
                    data-bs-target="#modal_duyet_bl">
                    <span><i class="bx bx-edit-alt me-2"></i>Phê duyệt</span>
                </a>

                <a href="#"
                    onclick="Xoa_Binhluan('<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>');"
                    class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                    <span><i class="bx bx-trash me-2"></i>Xoá</span>
                </a>

            </div>
        </div>

    </td>
</tr>
<?php } ?>