<?php
    session_start();
    include("./includes/connect.php");
    $so_dong = 5;
    $monhoc = 'Tất cả';
    $sap_xep = 'DESC';
    
    if (isset($_GET['sodong'])) {
        $so_dong = intval($_GET['sodong']);
    }

    if (isset($_GET['monhoc'])) {
        $monhoc = $_GET['monhoc'];
    }
    
    
    
    if (isset($_GET['sort'])) {
        if ($_GET['sort'] === "asc") {
            $sap_xep = "asc";
        } elseif ($_GET['sort'] === "desc") {
            $sap_xep = "desc";
        }
    }
    

    if ($monhoc == "Tất cả") {
        $danh_muc = "SELECT * 
                        FROM danh_muc a, mon_hoc b 
                        where a.mh_ma = b.mh_ma
                        order by a.dm_ma $sap_xep 
                        LIMIT $so_dong";
    }else{
        $danh_muc = "SELECT * 
                        FROM danh_muc a, mon_hoc b 
                        where a.mh_ma = b.mh_ma
                        and a.mh_ma = '$monhoc'
                        order by a.mh_ma $sap_xep 
                        LIMIT $so_dong";
    }
        $result_danh_muc = mysqli_query($conn,$danh_muc);

        unset($_SESSION['sl_dong']);
        $sl_dong_hientai = mysqli_num_rows($result_danh_muc);
        $_SESSION['sl_dong'] = $sl_dong_hientai;

        if($monhoc == 'Tất cả'){
            $kq="SELECT count(*) as tong FROM danh_muc";
        }else{
            $kq="SELECT count(*) as tong FROM danh_muc where mh_ma = '$monhoc'";
        }
       
        $result_kq = mysqli_query($conn,$kq);
        $row_kq = mysqli_fetch_assoc($result_kq);
        $_SESSION['tong_sd'] = array();
        $_SESSION['tong_sd'] = $row_kq['tong'];

?>
<input type="hidden" id="tong_sd" value="<?php echo $_SESSION['tong_sd']; ?>">
<?php
                                                            $stt = 0;
                                                            while ($row_danh_muc = mysqli_fetch_array($result_danh_muc)) {
                                                                $stt = $stt + 1;
                                                            ?>
                                                        <tr>
                                                            <td>
                                                                <input class="form-check-input check-item" name="check[]"
                                                                    type="checkbox"
                                                                    value="<?php echo $row_danh_muc['dm_ma'] ?>">
                                                            </td>
                                                            <td class="row-bai-viet"> <?php echo $stt ?> </td>
                                                            <!-- <td><strong><?php echo  $row_danh_muc['dm_ma']; ?></strong></td> -->
                                                            <td><?php echo  $row_danh_muc['dm_ten']; ?></td>
                                                            <td>
                                                                <?php echo  $row_danh_muc['mh_ten']; ?>

                                                            </td>
                                                            <td>
                                                                <?php 
                                                                    $sl_bv = "SELECT a.dm_ma, count(*) as sl_bai 
                                                                    FROM danh_muc a
                                                                    JOIN bai_viet b ON a.dm_ma=b.dm_ma
                                                                    where a.dm_ma = '".$row_danh_muc['dm_ma']."'
                                                                    GROUP BY a.dm_ma;";
                                                                   
                                                                    $result_sl_bv = mysqli_query($conn,$sl_bv);
                                                                    $row_sl_bv = mysqli_fetch_assoc($result_sl_bv);

                                                                    if(mysqli_num_rows($result_sl_bv) > 0){
                                                                        echo  $row_sl_bv['sl_bai']; 
                                                                    }else{
                                                                        
                                                                    }
                                                                    

                                                                ?>

                                                            </td>
                                                            <td>
                                                                <?php
                                                                $nguoi_ql="select * from quan_ly a, danh_muc b, nguoi_dung c where a.dm_ma = b.dm_ma and c.nd_username=a.nd_username and b.dm_ma= '".$row_danh_muc['dm_ma']."'";
                                                                $result_nguoi_ql = mysqli_query($conn,$nguoi_ql);
                                                                if(mysqli_num_rows($result_nguoi_ql) == 0){
                                                                    echo "<i>(Chưa có người quản lý)<i>";
                                                                }else{
                                                                    while ($row_nguoi_ql = mysqli_fetch_array($result_nguoi_ql)) {
                                                                        echo $row_nguoi_ql['nd_hoten'].'<br>';
                                                                    }
                                                                }
                                                            ?>
                                                            </td>
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
                                                                        <!-- <a href="Xem_DanhMuc.php?this_dm_ma=<?php echo $row_danh_muc['dm_ma']?>"
                                                                        class="dt-button buttons-print dropdown-item"
                                                                        tabindex="0" type="button">
                                                                        <span><i class=" fa fa-eye me-2"></i>Xem</span>
                                                                    </a> -->
                                                                        <a href="Sua_DanhMuc.php?this_dm_ma=<?php echo $row_danh_muc['dm_ma']?>"
                                                                            class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                            type="button">
                                                                            <span><i
                                                                                    class="bx bx-edit-alt me-2"></i>Sửa</span>
                                                                        </a>

                                                                        <a onclick="Xoa_Danhmuc('<?php echo $row_danh_muc['dm_ma']?>');"
                                                                            class="dt-button buttons-csv buttons-html5 dropdown-item"
                                                                            type="button">
                                                                            <span><i
                                                                                    class="bx bx-trash me-2"></i>Xoá</span>
                                                                        </a>

                                                                    </div>
                                                                </div>



                                                            </td>
                                                        </tr>
                                                        <?php } ?>