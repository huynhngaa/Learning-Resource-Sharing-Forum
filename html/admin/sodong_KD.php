<?php
session_start();
include ('./includes/connect.php');
$so_dong = 5;
$sap_xep = 'DESC';
$user_name = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";
$tungay = 2015 - 01 - 01;
$denngay = date('Y-m-d');

$similarRows = json_decode($_POST['similarRows'], true); 

if (isset($_POST['sodong'])) {
    $so_dong = intval($_POST['sodong']);
}
if (isset($_POST['sort'])) {
    if ($_POST['sort'] === "asc") {
        $sap_xep = "asc";
    } elseif ($_POST['sort'] === "desc") {
        $sap_xep = "desc";
    }
}
if (isset($_POST['tungay'])) {
    $tungay = $_POST['tungay'];
}
if (isset($_POST['denngay'])) {
    $denngay = $_POST['denngay'];
}

// Kiểm tra quyền truy cập
// Danh sách các trang quản lý của admin
$adminPages = array("QL_DanhMuc.php", "QL_KhoiLop.php", "QL_MonHoc.php", "QL_NguoiDung.php", "QL_BinhLuan.php", "PCQL_DanhMuc.php");
// Lấy đường dẫn hiện tại của người dùng
$currentPage = basename($_SERVER['SCRIPT_FILENAME']);
if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
    header("Refresh: 0;url=error.php");
    exit;
}
if ($_SESSION['vaitro'] == 'Super Admin') {
    $bai_viet = "SELECT a.*, b.*, c.*, d.*, e.*, t.*
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                WHERE (k.tt_ma is null or k.tt_ma = 3)
                And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";
} elseif ($_SESSION['vaitro'] == 'Admin') {
    $bai_viet = "SELECT a.*, b.*, c.*, d.*, e.*, t.*
    FROM bai_viet a
    LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
    LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
    LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
    LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
    LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
    LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
    LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
    WHERE (k.tt_ma is null or k.tt_ma = 3)
    And f.nd_username = '$user_name'
    And DATE(bv_ngaydang) BETWEEN '$tungay' AND '$denngay'
            ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";
}
$result_bai_viet = mysqli_query($conn, $bai_viet);
unset($_SESSION['sl_dong']);
$sl_dong_hientai = mysqli_num_rows($result_bai_viet);
$_SESSION['sl_dong'] = $sl_dong_hientai;
// Kiểm tra quyền truy cập
if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
    header("Refresh: 0;url=error.php");
    exit;
}
if ($_SESSION['vaitro'] == 'Super Admin') {
    $sd = "SELECT count(*) as tong
                FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                WHERE( k.tt_ma is null or k.tt_ma = 3)";
} elseif ($_SESSION['vaitro'] == 'Admin') {
    $sd = "SELECT count(*) as tong
                 FROM bai_viet a
                LEFT JOIN danh_muc b ON a.dm_ma = b.dm_ma
                LEFT JOIN nguoi_dung e ON a.nd_username = e.nd_username
                LEFT JOIN mon_hoc c ON b.mh_ma = c.mh_ma
                LEFT JOIN khoi_lop d ON c.kl_ma = d.kl_ma
                LEFT JOIN tai_lieu f ON f.bv_ma = a.bv_ma
                LEFT JOIN kiem_duyet k ON k.bv_ma = a.bv_ma
                LEFT JOIN trang_thai t ON t.tt_ma = k.tt_ma
                WHERE (k.tt_ma is null or k.tt_ma = 3)
                And f.nd_username = '$user_name'";
}
$result_sd = mysqli_query($conn, $sd);
$row_sd = mysqli_fetch_assoc($result_sd);
$_SESSION['tong_sd'] = array();
$_SESSION['tong_sd'] = $row_sd['tong'];

// ////////////////////
$sumSimilarity = [];
$countSimilarity = [];
if(!empty($similarRows)){


    foreach ($similarRows as $row)
    {
        $id = $row['id'];
        $id2 = $row['id2'];
        // $td1 = $row['tieude'];

        if (!isset($sumSimilarity[$id][$id2]))
        {
            $sumSimilarity[$id][$id2] = 0;
            $countSimilarity[$id][$id2] = 0;
        }

        $sumSimilarity[$id][$id2] += $row['similarity'];
        $countSimilarity[$id][$id2]++;
    }
}
$averageSimilarity = [];

foreach ($sumSimilarity as $id => $idData)
{
    foreach ($idData as $id2 => $total)
    {
        $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2]);
       
        
    }
}

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
            value="<?php echo $row_bai_viet['bv_ma'] . '|' . $row_bai_viet['tt_ma']; ?>">
    </td>
    <td class="row-bai-viet"> <?php echo $stt ?> </td>

    <td>

        <?php
            if ($row_bai_viet['tt_ma'] == 1) {
                echo "<span class='badge bg-label-success me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
            } else if ($row_bai_viet['tt_ma'] == 2) {
                echo "<span class='badge alert-warning me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
            } else if ($row_bai_viet['tt_ma'] == 4) {
                echo "<span class='badge bg-label-danger me-1'>" . $row_bai_viet['tt_ten'] . "</span>";
            } else {
                echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
            }
        ?>

    </td>
    <td style="white-space: normal">
        <?php echo $row_bai_viet['bv_tieude'] ?>

    </td>

    <td><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

    </td>
    <td> <?php echo $row_bai_viet['nd_hoten'] ?> </td>
    <td style="white-space: normal">
        <?php
        if(!empty($similarRows)){
            foreach ($sumSimilarity as $id => $idData) {
                foreach ($idData as $id2 => $total) {
                    if ($id == $row_bai_viet['bv_ma']) {
                        $bai2 = "SELECT * FROM bai_viet where bv_ma = $id2";
                        $result_bai2 = mysqli_query($conn, $bai2);
                        while ($row_bai2 = mysqli_fetch_array($result_bai2)) {
                            $td2 = $row_bai2['bv_tieude'];
                        }
                       
                        $averageSimilarity[$id][$id2] = round($total / $countSimilarity[$id][$id2], 2);
                        echo '' . $averageSimilarity[$id][$id2] . '% (<a href = Xem_BaiViet.php?this_bv_ma=' . $id2 . '>' . $td2 . '</a>) 
                                                                                    <button  onclick="openModal(' . $id . ',' . $id2 . ')"
                                                                                    class="dt-button buttons-print dropdown-item" tabindex="0"
                                                                                    type="button" data-bs-toggle="modal" data-bs-target="#modalCenter"">
                                                                                    <span><i class=" fa fa-eye me-2"></i></span></button><br><br>';
                    }
                }
            }
        }
    ?>
    </td>


    <td>
        <a id="dropdownHanhDong" data-bs-toggle="dropdown" aria-expanded="false"
            style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
            href="Xem_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma'] ?>&tg=<?php echo $row_binh_luan['nd_username'] ?>">
            <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
        </a>

        <div class="dt-button-collection dropdown-menu" style="top: 55.9375px; left: 419.797px;min-width:7rem"
            aria-labelledby="dropdownHanhDong">
            <div role="menu">
                <a href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                    class="dt-button buttons-print dropdown-item" tabindex="0" type="button">
                    <span><i class=" fa fa-eye me-2"></i>Xem</span>
                </a>
                <a href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                    class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                    <span><i class="bx bx-edit-alt me-2"></i>Kiểm
                        duyệt</span>
                </a>

                <?php
                    // Kiểm tra quyền truy cập
                    if ($_SESSION['vaitro'] !== 'Super Admin' && in_array($currentPage, $adminPages)) {
                        header("Refresh: 0;url=error.php");
                        exit;
                    }
                    if ($_SESSION['vaitro'] == 'Super Admin') { ?>
                                <a href="#"
                                    onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>');"
                                    class="dt-button buttons-csv buttons-html5 dropdown-item" type="button">
                                    <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                </a>
                                <?php
                    } elseif ($_SESSION['vaitro'] == 'Admin') {
                    }
                ?>



            </div>
        </div>



    </td>
</tr>
<?php
} ?>