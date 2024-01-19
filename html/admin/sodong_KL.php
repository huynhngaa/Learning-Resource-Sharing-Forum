<?php
    session_start();
    include("./includes/connect.php");
    $so_dong = 5;
    $monhoc = 'Tất cả';
    $sap_xep = 'DESC';
    
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
    $khoi_lop = "SELECT * FROM khoi_lop order by kl_ma $sap_xep LIMIT $so_dong ";
    $result_khoi_lop = mysqli_query($conn,$khoi_lop);
    
    unset($_SESSION['sl_dong']);
    $sl_dong_hientai = mysqli_num_rows($result_khoi_lop);
    $_SESSION['sl_dong'] = $sl_dong_hientai;
?>

    <?php
        $stt = 0;
        while ($row_khoi_lop = mysqli_fetch_array($result_khoi_lop)) {
            $stt = $stt + 1;
        ?>
    <tr>
    <td>
                                                    <input class="form-check-input check-item" name="check[]" type="checkbox"
                                                        value="<?php echo $row_khoi_lop['kl_ma'] ?>">
                                                </td>
        <td class="row-bai-viet">
            <?php echo $stt ?> </td>
        <td><strong><?php echo  $row_khoi_lop['kl_ma']; ?></strong></td>
        <td>
            <?php echo  $row_khoi_lop['kl_ten']; ?>
        </td>

        <td>
            <!-- <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="Xem_KhoiLop.php?this_kl_ma=<?php echo $row_khoi_lop['kl_ma']?>">
                                                                    <i class="fa fa-eye"></i>
                                                                </a> -->
            <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                href="Sua_KhoiLop.php?this_kl_ma=<?php echo $row_khoi_lop['kl_ma']?>">
                <i class="bx bx-edit-alt me-1"></i>
            </a>
            <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="#"
                onclick="Xoa_Khoilop('<?php echo $row_khoi_lop['kl_ma']?>');">
                <i class="bx bx-trash me-1"></i>
            </a>

        </td>
    </tr>
    <?php } ?>

