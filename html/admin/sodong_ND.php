<?php
    session_start();
    include("./includes/connect.php");
       
    $so_dong = 5;
    $gioitinh = 'Tất cả';
    $sap_xep = 'DESC';
    $vt = $_GET['vt'];
    
    if (isset($_GET['sodong'])) {
        $so_dong = intval($_GET['sodong']);
    }

    if (isset($_GET['gioitinh'])) {
        $gioitinh = $_GET['gioitinh'];
    }
    
    
    
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === "asc") {
            $sap_xep = "asc";
        } elseif ($_GET['sort'] === "desc") {
            $sap_xep = "desc";
        }
    }
    

    if ($gioitinh == "Tất cả") {
        $nguoi_dung = "SELECT * FROM nguoi_dung 
                        WHERE vt_ma='$vt' 
                        order by nd_hoten $sap_xep 
                        LIMIT $so_dong";
    }else{
        $nguoi_dung = "SELECT * FROM nguoi_dung 
                        WHERE vt_ma='$vt'
                        and nd_gioitinh = '$gioitinh' 
                        order by nd_hoten $sap_xep 
                        LIMIT $so_dong";
    }

        $result_nguoi_dung = mysqli_query($conn,$nguoi_dung);
    
        $vai_tro = "SELECT * FROM vai_tro WHERE vt_ma='$vt'";
        $result_vai_tro = mysqli_query($conn,$vai_tro);
        $row_vai_tro = mysqli_fetch_assoc($result_vai_tro);

        unset($_SESSION['sl_dong']);
        $sl_dong_hientai = mysqli_num_rows($result_nguoi_dung);
        $_SESSION['sl_dong'] = $sl_dong_hientai;
?>

<?php
        $stt = 0;
        while ($row_nguoi_dung = mysqli_fetch_array($result_nguoi_dung)) {
            if($row_nguoi_dung['nd_gioitinh'] == 2) {
                $row_nguoi_dung['nd_gioitinh'] = "Nam";
            }if($row_nguoi_dung['nd_gioitinh'] == 1) {
                $row_nguoi_dung['nd_gioitinh'] = "Nữ";
            }
                $stt = $stt + 1;
        ?>
<tr>
    <td>
        <input class="form-check-input check-item" name="check[]" type="checkbox"
            value="<?php echo $row_nguoi_dung['nd_username'] ?>">
    </td>
    <td class="row-bai-viet"> <?php echo $stt ?> </td>
    <td>
        <img src="assets/img/avatars/<?php echo  $row_nguoi_dung['nd_hinh']; ?>" alt="Avatar"
            class="rounded-circle avatar avatar-xs pull-up" />
    </td>
    <td><strong><?php echo  $row_nguoi_dung['nd_username']; ?></strong></td>
    <td><?php echo  $row_nguoi_dung['nd_hoten']; ?></td>

    <!-- <td>
            <span class="badge bg-label-primary me-1">
                <?php 
                    if($row_nguoi_dung['nd_gioitinh'] == '0'){
                        $row_nguoi_dung['nd_gioitinh'] = "(Chưa có dữ liệu)";
                        echo  "<i>".$row_nguoi_dung['nd_gioitinh']."</i>";
                    }else{
                        echo  $row_nguoi_dung['nd_gioitinh']; 
                    }
                ?>
            </span>
        </td>
        <td>
            <?php 
                                                    if($row_nguoi_dung['nd_ngaysinh'] == '0000-00-00'){
                                                        $row_nguoi_dung['nd_ngaysinh'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nguoi_dung['nd_ngaysinh']."</i>";
                                                    }else{
                                                        echo date_format(date_create($row_nguoi_dung['nd_ngaysinh']), "d-m-Y"); 
                                                    }            
                                                ?>
        </td> -->
    <td>
        <?php //echo  $row_hoc_sinh['nd_email']; ?>
        <?php 
                                                    if($row_nguoi_dung['nd_email'] == ''){
                                                        $row_nguoi_dung['nd_email'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nguoi_dung['nd_email']."</i>";
                                                    }else{
                                                        echo  $row_nguoi_dung['nd_email']; 
                                                    }            
                                                ?>
    </td>
    <td>
        <?php //echo  $row_hoc_sinh['nd_sdt']; ?>
        <?php 
                                                    if($row_nguoi_dung['nd_sdt'] == ''){
                                                        $row_nguoi_dung['nd_sdt'] = "(Chưa có dữ liệu)";
                                                        echo  "<i>".$row_nguoi_dung['nd_sdt']."</i>";
                                                    }else{
                                                        echo  $row_nguoi_dung['nd_sdt']; 
                                                    }            
                                                ?>
    </td>
    <td>
        <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
            href="Xem_NguoiDung.php?this_username=<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>">
            <i class="fa fa-eye"></i>
        </a>
        <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
            href="Sua_NguoiDung.php?this_username=<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>">
            <i class="bx bx-edit-alt me-1"></i>
        </a>
        <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="#"
            onclick="Xoa_Nguoidung('<?php echo $row_nguoi_dung['nd_username']?>&vt=<?php echo $row_nguoi_dung['vt_ma']?>');">
            <i class="bx bx-trash me-1"></i>
        </a>

    </td>
</tr>
<?php } ?>