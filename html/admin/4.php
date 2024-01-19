<?php
session_start();
include("./includes/connect.php");


?>

<!DOCTYPE html>
<html>
<head>
    <title>Chọn danh mục</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    .select-container {
        display: inline-block;
        position: relative;
    }

    select {
        width: 150px;
    }

    #danhmuccon {
        display: none;
        position: absolute;
        top: 100%;
    }
</style>
<body>
    <div class="select-container">
        <label for="danhmuc" class="form-label">Danh mục</label>
        <select name="danhmuc" id="danhmuc" class="form-select">
            <option disabled selected>Chọn danh mục</option>
            <?php
    $data = array();

    $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
            FROM danh_muc d
            LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con ";
    $result = mysqli_query($conn, $sql);

    // Tạo mảng chứa danh mục cha
    $danhmuc_goc = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = array(
            "id" => $row['dm_ma'],
            "name" => $row['dm_ten'],
            "parent" => $row['dm_cha']
        );

        // Lọc danh mục goc
        if ($row['dm_cha'] == 0) {
            $danhmuc_goc[] = $row;
        }
    }

    // Duyệt qua danh mục cha và hiển thị
    foreach ($danhmuc_goc as $goc) {
        echo '<optgroup label="' . $goc['dm_ten'] . '">';
        // Gọi hàm đệ quy để hiển thị danh mục con của danh mục góc
        dequy($data, $goc['dm_ma']);
        echo '</optgroup>';
    }

    function dequy($data, $parent, $level =0) {
        foreach ($data as $k => $value) {
            if ($value['parent'] == $parent) {
                echo '<option value="' . $value['id'] . '">';
                echo str_repeat('- ', $level) . $value['name'];
                echo '</option>';
                unset($data[$k]);
                dequy($data, $value['id'], $level+1);
            }
        }
    }
    ?>
        </select>
    </div>

    <div class="select-container">
        <label for="danhmuccon" class="form-label">Danh mục con</label>
        <select name="danhmuccon" id="danhmuccon" class="form-select">
            <option disabled selected>Chọn danh mục con</option>
        </select>
    </div><br>

    <script>
        $('#danhmuc').on('change', function() {
    const selectedDanhMucCha = $(this).val();
    const danhMucCon = $('#danhmuccon');

    // Xóa tất cả danh sách danh mục con hiện có
    danhMucCon.empty();

    // Thêm danh mục con tương ứng vào danh sách
    if (selectedDanhMucCha in data) {
        const children = data[selectedDanhMucCha].children;
        children.forEach(child => {
            danhMucCon.append('<option value="' + child.id + '">' + child.name + '</option>');
        });
        danhMucCon.prop('disabled', false);
    } else {
        danhMucCon.prop('disabled', true);
    }
});

    </script>
    
<script>
var data = <?php echo json_encode($data); ?>;
console.log(data);
</script>
</body>
</html>
