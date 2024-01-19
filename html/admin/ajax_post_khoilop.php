<?php
session_start();
include("./includes/connect.php");

if (isset($_GET['khoilop'])) {
    $khoilop = $_GET['khoilop'];?>

<label for="defaultSelect" class="form-label">Môn học</label>
<select name="monhoc" id="monhoc" class="form-select">
    <option disabled selected>Chọn môn học</option>
    <?php
        $mon_hoc = "select * from mon_hoc where kl_ma = '$khoilop'";
        $result_mon_hoc = mysqli_query($conn, $mon_hoc);
        while ($row_mon_hoc = mysqli_fetch_array($result_mon_hoc)) {
        ?>
    <option value="<?php echo $row_mon_hoc['mh_ma']; ?>">
        <?php echo $row_mon_hoc['mh_ten']; ?>
    </option>


    <?php } ?>
</select>
<?php } ?>




<script>
$(document).ready(function() {

    // Xử lý khi năm thay đổi ở biểu đồ người dùng
    $('#monhoc').change(function() {
        HienthiDanhMuc();
    });


    function HienthiDanhMuc() {
        var chon_monhoc = $('#monhoc').val();

        $.ajax({
            url: 'ajax_post_monhoc.php',
            data: {
                monhoc: chon_monhoc
            },
            success: function(data) {
                $('#data-danhmuc').html(data);
            }
        });
    }

});
</script>
