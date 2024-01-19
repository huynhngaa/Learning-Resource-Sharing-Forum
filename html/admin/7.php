<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .tree {
            list-style-type: none;
            margin-left: 0;
            padding-left: 1em;
        }

        .tree>li {
            padding-left: 0.5rem;
            position: relative;
        }

        .tree>li:before {
            content: "";
            position: absolute;
            left: -1em;
            top: 0.5em;
            border-left: 1px solid #000;
            /* Thay đổi màu sắc và chiều dài của đường kẻ */
        }

        .tree label {
            cursor: pointer;
        }

        .tree div {
            display: flex;
            align-items: center;
        }

        .tree div input {
            margin-right: 5px;
        }

        .category-container {
            display: flex;
            flex-wrap: wrap;
        }

        .category {
            width: 50%; /* Chia đều không gian cho hai cột */
            box-sizing: border-box;
            padding: 10px;
            border: 1px solid #ccc;
        }

        @media (max-width: 768px) {
            .category {
                width: 100%; /* Khi màn hình nhỏ, hiển thị một cột */
            }
        }
    </style>
</head>

<body>
    <div class="tree">
        <?php
        session_start();
        include("./includes/connect.php");
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

            // Lọc danh mục gốc
            if ($row['dm_cha'] == 0) {
                $danhmuc_goc[] = $row;
            }
        }

        // Duyệt qua danh mục gốc và hiển thị
        foreach ($danhmuc_goc as $goc) {
            echo '<div >';
            echo '<input class="category" type="checkbox" value="' . $goc['dm_ma'] . '">  ';
            echo '<label>' . $goc['dm_ten'] . '</label>';
            // Gọi hàm đệ quy để hiển thị danh mục con của danh mục gốc
            dequy($data, $goc['dm_ma']);
            echo '</div>';
        }

        function dequy($data, $parent)
        {
            echo '<div>';
            foreach ($data as $k => $value) {
                if ($value['parent'] == $parent) {
                    echo '<div>';
                    echo '<input class="category" type="checkbox" value="' . $value['id'] . '"> ';
                    echo '<label>' . $value['name'] . '</label>';
                    dequy($data, $value['id']);
                    echo '</div>';
                    unset($data[$k]);
                }
            }
            echo '</div>';
        }
        ?>
    </div>

    <script>
        const categoryCheckboxes = document.querySelectorAll('.category');

        categoryCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                const allChildCheckboxes = this.parentElement.querySelectorAll('input[type="checkbox"]');
                allChildCheckboxes.forEach(function (childCheckbox) {
                    childCheckbox.checked = checkbox.checked;
                });
            });
        });
    </script>
</body>

</html>
