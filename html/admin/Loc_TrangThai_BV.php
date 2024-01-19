<?php
    session_start();
    include("./includes/connect.php");
        // Kiểm tra xem người dùng đã chọn số dòng hay chưa
        if (isset($_GET["trangthai"])) {
            $trangthai = $_GET["trangthai"];
           
            // Lấy số dòng mà người dùng đã chọn
        } else {
            $trangthai = "Tất cả"; 
            // Nếu không có lựa chọn, mặc định hiển thị 5 dòng
        }
        
         // Kiểm tra xem người dùng đã chọn số dòng hay chưa
         if (isset($_GET["sodong"])) {
            $so_dong = $_GET["sodong"]; 
            // Lấy số dòng mà người dùng đã chọn
        } else {
            $so_dong = 3; 
            // Nếu không có lựa chọn, mặc định hiển thị 5 dòng
        }

        if (isset($_POST["desc_ma"])) {
            $sap_xep = "ASC";
            $button_icon_ma = "<button name='asc_ma' type='submit' style='border: none; background-color: none; border-radius:0.3rem'><i class='fa fa-sort-amount-asc'></i></botton>";
        } 
        else {
            $sap_xep = "DESC";
            $button_icon_ma = "<button name='desc_ma' type='submit' style='border: none; background-color: none; border-radius:0.3rem'><i class='fa fa-sort-amount-desc'></botton></i>";
            // Nếu không có lựa chọn, mặc định hiển thị 5 dòng
        }

        if($trangthai == "3"){
            $bai_viet = "SELECT * FROM bai_viet a
            LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            where c.tt_ma is null
            order by a.nd_username $sap_xep 
            LIMIT $so_dong";
        }
        else if($trangthai == "1" || $trangthai == "2" || $trangthai == "4") {

            $bai_viet = "SELECT * FROM bai_viet a
            LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
            LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
            LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
            LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
            where c.tt_ma = '$trangthai'
            order by a.nd_username $sap_xep 
            LIMIT $so_dong";
           
        }else{
             $bai_viet = "SELECT * FROM bai_viet a
             LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
             LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
             LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
             LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
             
             order by a.nd_username $sap_xep 
             LIMIT $so_dong";
        }

        $result_bai_viet = mysqli_query($conn,$bai_viet);

?>
<tbody class="table-border-bottom-0">
    <?php
        $stt = 0;
        while ($row_bai_viet = mysqli_fetch_array($result_bai_viet)) {
            $stt = $stt + 1;
        ?>
    <tr>
        <td> <?php echo $stt ?> </td>

        <td>

                <?php 
                    if($row_bai_viet['tt_ma'] == 1){

                        echo "<span class='badge bg-label-success me-1'>".$row_bai_viet['tt_ten']."</span>"; 
                    }
                    else if($row_bai_viet['tt_ma'] == 2){
                        echo "<span class='badge bg-label-danger me-1'>".$row_bai_viet['tt_ten']."</span>"; 
                    }else if($row_bai_viet['tt_ma'] == 4){
                       
                        echo "<span class='badge bg-label-dismissible me-1'>".$row_bai_viet['tt_ten']."</span>";
                    }
                    else {
                        echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
                    }
    
                ?>

        </td>
        <td> <?php echo $row_bai_viet['bv_tieude'] ?> </td>
        <td><?php echo date_format(date_create($row_bai_viet['bv_ngaydang']), "d-m-Y (H:i:s)"); ?>
        </td>
        <td> <?php echo $row_bai_viet['nd_hoten'] ?> </td>


        <td>
            <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma']?>&tg=<?php echo $row_bai_viet['nd_username']?>">
                <i class="fa fa-eye"></i>
            </a>
            <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item"
                href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma']?>&tg=<?php echo $row_bai_viet['nd_username']?>">
                <i class="bx bx-edit-alt me-1"></i>
            </a>
            <a style="display:math; padding:0.1rem 0.6rem" class="dropdown-item" href="#"
                onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma']?>&tg=<?php echo $row_bai_viet['nd_username']?>');">
                <i class="bx bx-trash me-1"></i>
            </a>

        </td>
    </tr>
    <?php } ?>

</tbody>