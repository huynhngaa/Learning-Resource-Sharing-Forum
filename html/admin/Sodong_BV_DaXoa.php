<?php
session_start();
include("./includes/connect.php");

if (!isset($_SESSION['Admin'])) {
    echo "<script>alert('Bạn chưa đăng nhập! Hãy đăng nhập để tiếp tục.');</script>";
    header("Refresh: 0;url=login.php");
} else {
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['apdung'])) {
    $selectedAction = $_POST['tacvu'];

    $user = $_SESSION['Admin'];
    $selectedIds = isset($_POST['check']) ? $_POST['check'] : [];

    if ($selectedAction == "4") {
        // Use a transaction to ensure data integrity
        mysqli_begin_transaction($conn);

        try {
            foreach ($selectedIds as $id) {
                $kt = "SELECT * FROM kiem_duyet WHERE bv_ma = '$id'";
                $result_kt = mysqli_query($conn, $kt);
                $row_kt = mysqli_fetch_assoc($result_kt);

                if ($row_kt && $id == $row_kt['bv_ma']) {
                    if ($row_kt['tt_ma'] == 4) {
                        // If the status is already 4, delete related records
                        $delete_query = "DELETE FROM kiem_duyet WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);

                        $delete_query = "DELETE FROM tai_lieu WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);

                        $delete_query = "DELETE FROM lich_su_xem WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);

                        $delete_query = "DELETE FROM bai_viet WHERE bv_ma = '$id'";
                        mysqli_query($conn, $delete_query);
                    } else {
                        // Otherwise, update the status to 4
                        $update_query = "UPDATE kiem_duyet SET tt_ma = '$selectedAction' WHERE bv_ma = '$id'";
                        mysqli_query($conn, $update_query);
                    }
                } else {
                    // Insert a new record
                    $insert_query = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma) VALUES ('$id', '$user', '$selectedAction')";
                    mysqli_query($conn, $insert_query);
                }
            }

            // If everything is successful, commit the transaction
            mysqli_commit($conn);
            echo "<script>alert('Bạn đã xóa dữ liệu thành công!');</script>";
        } catch (Exception $e) {
            // If an error occurs, rollback the transaction and display an error message
            mysqli_rollback($conn);
            echo "<script>alert('Có lỗi xảy ra trong quá trình xóa dữ liệu.');</script>";
            echo "Error: " . $e->getMessage();
        }
    } elseif ($selectedAction == "1" || $selectedAction == "2") {
        // Update the status for selected IDs
        foreach ($selectedIds as $id) {
            $kt = "SELECT * FROM kiem_duyet WHERE bv_ma = '$id'";
            $result_kt = mysqli_query($conn, $kt);
            $row_kt = mysqli_fetch_assoc($result_kt);

            if ($row_kt && $id == $row_kt['bv_ma']) {
                // If a record exists, update the status
                $update_query = "UPDATE kiem_duyet SET tt_ma = '$selectedAction' WHERE bv_ma = '$id'";
                mysqli_query($conn, $update_query);
            } else {
                // If no record exists, insert a new record
                $insert_query = "INSERT INTO kiem_duyet (bv_ma, nd_username, tt_ma) VALUES ('$id', '$user', '$selectedAction')";
                mysqli_query($conn, $insert_query);
            }
        }

        echo "<script>alert('Cập nhật trạng thái bài viết thành công!');</script>";
        header("Refresh: 0;url=QL_BaiViet.php");
    }
}



$so_dong = 5;

$sap_xep = 'DESC';

$tungay = "2000-01-01";
$denngay = date('Y-m-d');
$id = isset($_SESSION['Admin']) ? $_SESSION['Admin'] : "";
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


        $bai_viet = "SELECT a.*, e.*,c.tt_ma, c.thoigian, d.* FROM bai_viet a
             LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
             LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
             LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
             where c.tt_ma = 4
             And DATE(thoigian) BETWEEN '$tungay' AND '$denngay'
             And c.nd_username = '$id'
            ORDER BY a.bv_ngaydang $sap_xep LIMIT $so_dong";

            $result_bai_viet = mysqli_query($conn, $bai_viet);
            unset($_SESSION['sl_dong']);
            $sl_dong_hientai = mysqli_num_rows($result_bai_viet);
            $_SESSION['sl_dong'] = $sl_dong_hientai;


            $kq="SELECT count(*) as tong
            FROM bai_viet a
            LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            where c.tt_ma = 4
            And c.nd_username = '$id'
            And DATE(thoigian) BETWEEN '$tungay' AND '$denngay'";

            $result_kq = mysqli_query($conn,$kq);
            $row_kq = mysqli_fetch_assoc($result_kq);
            $_SESSION['tong_sd'] = array();
            $_SESSION['tong_sd'] = $row_kq['tong'];


?>

<input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
<?php
                                                $stt = 0;
                                              
                                                while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {

                                                    $sl_baiviet= array(
                                                       
                                                        "hientai"=> $stt + 1
                                                    );
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
                                                <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['thoigian']), "d-m-Y (H:i:s)"); ?>
                                                <td style="white-space: normal">
                                                    <?php echo $row_bai_viet['bv_tieude'] ?>
                                                </td>
                                                <td style="white-space: normal"><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>

                                                </td>
                                                <td> <?php echo $row_bai_viet['nd_hoten'] ?> </td>


                                                <td>
                                                    <a id="dropdownHanhDong" data-bs-toggle="dropdown"
                                                        aria-expanded="false"
                                                        style="display:math; padding:0.1rem 0.6rem"
                                                        class="dropdown-item"
                                                        href="Xem_BinhLuan.php?this_bl_ma=<?php echo $row_binh_luan['bl_ma']?>&tg=<?php echo $row_binh_luan['nd_username']?>">
                                                        <i class="fa fa-ellipsis-v" aria-hidden="true"></i>
                                                    </a>

                                                    <div class="dt-button-collection dropdown-menu"
                                                        style="top: 55.9375px; left: 419.797px;min-width:7rem"
                                                        aria-labelledby="dropdownHanhDong">
                                                        <div role="menu">
                                                            <a href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                                                                class="dt-button buttons-print dropdown-item"
                                                                tabindex="0" type="button">
                                                                <span><i class=" fa fa-eye me-2"></i>Xem</span>
                                                            </a>
                                                            <a href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>"
                                                                class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                type="button">
                                                                <span><i class="bx bx-edit-alt me-2"></i>Sửa</span>
                                                            </a>

                                                            <a href="#"
                                                                onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>');"
                                                                class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                type="button">
                                                                <span><i class="bx bx-trash me-2"></i>Xoá</span>
                                                            </a>

                                                        </div>
                                                    </div>

                                                    <!-- <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                    href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                    href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>">
                                                    <i class="bx bx-edit-alt me-1"></i>
                                                </a>
                                                <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                                                    href="#"
                                                    onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma'] ?>&tg=<?php echo $row_bai_viet['nd_username'] ?>');">
                                                    <i class="bx bx-trash me-1"></i>
                                                </a> -->

                                                </td>
                                            </tr>
                                            <?php } ?>