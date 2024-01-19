<?php
session_start();
include("./includes/connect.php");

$data = array();

$sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
        FROM danh_muc d
        LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con ";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = array(
        "id" => $row['dm_ma'],
        "name" => $row['dm_ten'],
        "parent" => $row['dm_cha']
    );
}
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

    .form-label {
        font-weight: bold;
    }
</style>

<body>
    <div class="select-container">
        <label class="form-label">Danh mục cha</label>
        <select id="danhmuc" class="form-select">
            <option disabled selected>Chọn danh mục cha</option>
            <?php
            // Hiển thị danh mục cha
            foreach ($data as $item) {
                if ($item['parent'] == 0) {
                    echo '<option value="' . $item['id'] . '">' . $item['name'] . '</option>';
                }
            }
            ?>
        </select>
    </div>

    <div class="select-container" id="danhmuc-con-container">
        <!-- Vùng này sẽ chứa danh sách danh mục con -->
    </div>

    <script>
        function populateDanhMucCon(selectedDanhMucCha, parentContainer) {
            const danhMucCon = $('<select class="form-select">');
            danhMucCon.append('<option>Chọn danh mục con</option>');

            data.forEach(item => {
                if (item['parent'] == selectedDanhMucCha) {
                    danhMucCon.append('<option value="' + item['id'] + '">' + item['name'] + '</option>');
                }
            });

            // Xóa tất cả danh sách danh mục con cũ (nếu có)
            parentContainer.empty();

            if (danhMucCon.children().length > 0) {
                // Thêm danh sách danh mục con mới vào container
                parentContainer.append(danhMucCon);
                danhMucCon.on('change', function () {
                    const selectedDanhMucCon = $(this).val();
                    const newContainer = $('<div class="select-container">');
                    parentContainer.append(newContainer);

                    populateDanhMucCon(selectedDanhMucCon, newContainer);
                });
            }
        }

        $('#danhmuc').on('change', function () {
            const selectedDanhMucCha = $(this).val();
            const danhMucConContainer = $('#danhmuc-con-container');
            danhMucConContainer.empty();

            if (selectedDanhMucCha) {
                populateDanhMucCon(selectedDanhMucCha, danhMucConContainer);
            }
        });
    </script>

    <script>
        var data = <?php echo json_encode($data); ?>;
    </script>
</body>

</html>
