<?php
session_start();
include( './includes/connect.php' );

// $so_dong = 3;
// $trangthai = 'Tất cả';
// $sap_xep = 'DESC';



// if (isset($_GET['sodong'])) {
//     $so_dong = intval($_GET['sodong']);
// }

// if (isset($_GET['trangthai'])) {
//     $trangthai = $_GET['trangthai'];
// }

// if (isset($_GET['sort'])) {
//     if ($_GET['sort'] === "asc") {
//         $sap_xep = "asc";
//     } elseif ($_GET['sort'] === "desc") {
//         $sap_xep = "desc";
//     }
// }

if (isset($_POST['tungay']) && isset($_POST['denngay'])) {
    $tungay = date('Y-m-d', strtotime($_POST['tungay']));
    $denngay = date('Y-m-d', strtotime($_POST['denngay']));

    $bai_viet = "SELECT * FROM bai_viet a
        LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
        LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
        LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
        LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
        where
         DATE(bv_ngaydang) between '$tungay' and '$denngay'";


}else{
    $bai_viet = "SELECT * FROM bai_viet a
    LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
    LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
    LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
    LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma ";

}




// if ($trangthai == "3") {
//     $bai_viet = "SELECT * FROM bai_viet a
//         LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
//         LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
//         LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
//         LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
//         where c.tt_ma is null 
//         order by a.nd_username $sap_xep 
//         LIMIT $so_dong";
// } elseif ($trangthai == "1" || $trangthai == "2" || $trangthai == "4") {

//     $bai_viet = "SELECT * FROM bai_viet a
//         LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
//         LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
//         LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
//         LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
//         where c.tt_ma = '$trangthai'
//         order by a.nd_username $sap_xep 
//         LIMIT $so_dong";
// } else {
//     $bai_viet = "SELECT * FROM bai_viet a
//          LEFT JOIN danh_muc b ON a.dm_ma=b.dm_ma
//          LEFT JOIN nguoi_dung e ON a.nd_username=e.nd_username
//          LEFT JOIN kiem_duyet c ON c.bv_ma = a.bv_ma
//          LEFT JOIN trang_thai d ON c.tt_ma = d.tt_ma 
      
//          order by a.nd_username $sap_xep 
//          LIMIT $so_dong";
// }
$result_bai_viet = mysqli_query($conn, $bai_viet);



?>


    <?php
$stt = 0;
while ( $row_bai_viet = mysqli_fetch_array( $result_bai_viet ) ) {
    $stt = $stt + 1;
    ?>
    <tr>
        <td> <?php echo $stt ?> </td>

        <td>

            <?php
    if ( $row_bai_viet[ 'tt_ma' ] == 1 ) {

        echo "<span class='badge bg-label-success me-1'>".$row_bai_viet[ 'tt_ten' ].'</span>';

    } else if ( $row_bai_viet[ 'tt_ma' ] == 2 ) {
        echo "<span class='badge bg-label-danger me-1'>".$row_bai_viet[ 'tt_ten' ].'</span>';

    } else if ( $row_bai_viet[ 'tt_ma' ] == 4 ) {

        echo "<span class='badge bg-label-dismissible me-1'>".$row_bai_viet[ 'tt_ten' ].'</span>';
    } else {
        echo "<span class='badge bg-label-primary me-1'>Chờ duyệt</span>";
    }
    ?>

        </td>
        <td style="white-space: normal"> <?php echo $row_bai_viet[ 'bv_tieude' ] ?> </td>
        <td><?php echo date_format( date_create( $row_bai_viet[ 'bv_ngaydang' ] ), 'd-m-Y (H:i:s)' );
    ?>
        </td>
        <td> <?php echo $row_bai_viet[ 'nd_hoten' ] ?> </td>

        <td>
            <a style='display:math; padding:0.1rem 0.6rem' class='dropdown-item'
                href="Xem_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma']?>&tg=<?php echo $row_bai_viet['nd_username']?>">
                <i class='fa fa-eye'></i>
            </a>
            <a style='display:math; padding:0.1rem 0.6rem' class='dropdown-item'
                href="Sua_BaiViet.php?this_bv_ma=<?php echo $row_bai_viet['bv_ma']?>&tg=<?php echo $row_bai_viet['nd_username']?>">
                <i class='bx bx-edit-alt me-1'></i>
            </a>
            <a style='display:math; padding:0.1rem 0.6rem' class='dropdown-item' href='#'
                onclick="Xoa_Baiviet('<?php echo $row_bai_viet['bv_ma']?>&tg=<?php echo $row_bai_viet['nd_username']?>');">
                <i class='bx bx-trash me-1'></i>
            </a>

        </td>
    </tr>
    <?php }
    ?>

