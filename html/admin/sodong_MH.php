<?php 
    
    session_start();
    include("./includes/connect.php");
    $so_dong = 5;
    $khoilop = 'Tất cả';
    $sap_xep = 'DESC';
    
    if (isset($_GET['sodong'])) {
        $so_dong = intval($_GET['sodong']);
    }

    if (isset($_GET['khoilop'])) {
        $khoilop = $_GET['khoilop'];
    }
    
    
    
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === "asc") {
            $sap_xep = "asc";
        } elseif ($_GET['sort'] === "desc") {
            $sap_xep = "desc";
        }
    }
    

    if ($khoilop == "Tất cả") {
        $mon_hoc = "SELECT a.*, kl_ten
                    FROM mon_hoc a,  khoi_lop b 
                    where a.kl_ma = b.kl_ma 
                    order by a.mh_ma $sap_xep 
                    LIMIT $so_dong";
    }else{
        $mon_hoc = "SELECT a.*, kl_ten
                    FROM mon_hoc a,  khoi_lop b 
                    where a.kl_ma = b.kl_ma 
                    and a.kl_ma = '$khoilop'
                    order by a.mh_ma $sap_xep 
                    LIMIT $so_dong";
    }

        $result_mon_hoc = mysqli_query($conn,$mon_hoc);
        
        unset($_SESSION['sl_dong']);
        $sl_dong_hientai = mysqli_num_rows($result_mon_hoc);
        $_SESSION['sl_dong'] = $sl_dong_hientai;

        if($khoilop == 'Tất cả'){
            $kq="SELECT count(*) as tong FROM mon_hoc";
        }else{
            $kq="SELECT count(*) as tong FROM mon_hoc where kl_ma = '$khoilop'";
        }
       
        $result_kq = mysqli_query($conn,$kq);
        $row_kq = mysqli_fetch_assoc($result_kq);
        $_SESSION['tong_sd'] = array();
        $_SESSION['tong_sd'] = $row_kq['tong'];
?>
<input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
<?php
        $stt = 0;
        while ($row_mon_hoc = mysqli_fetch_array($result_mon_hoc)) {
            $stt = $stt + 1;
        ?>
<tr>
    <td>
        <input class="form-check-input check-item" name="check[]" type="checkbox" value="<?php echo $row_mon_hoc['mh_ma'] ?>">
        
    </td>
    <td class="row-bai-viet">
        <?php echo $stt ;?> </td>
    <td><strong><?php echo  $row_mon_hoc['mh_ma']; ?></strong></td>
    <td>
        <?php echo  $row_mon_hoc['mh_ten']; ?>
    </td>
    <td>
        <?php echo  $row_mon_hoc['kl_ten']; ?>

    </td>
    <td>

        <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
            href="Sua_MonHoc.php?this_mh_ma=<?php echo $row_mon_hoc['mh_ma']?>">
            <i class="bx bx-edit-alt me-1"></i>
        </a>
        <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="#"
            onclick="Xoa_Monhoc('<?php echo $row_mon_hoc['mh_ma']?>');">
            <i class="bx bx-trash me-1"></i>
        </a>

    </td>
</tr>
<?php } ?>