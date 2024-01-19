<!DOCTYPE html>
<html>
<head>
    <title>Chọn Danh Mục</title>
</head>
<body>
    <h1>Chọn Danh Mục</h1>

    <form id="category-form">
        <div id="category-dropdowns">
            <!-- Các select box danh mục sẽ được tạo thông qua JavaScript -->
        </div>
    </form>

    <script>
        <?php
// Kết nối CSDL
include "include/conn.php";

// Truy vấn để lấy dữ liệu từ bảng danh_muc
$sql = "SELECT dm_ma, dm_ten FROM danh_muc";
$result = $conn->query($sql);

$categories = array();

if ($result->num_rows > 0) {
    // Duyệt qua các dòng kết quả
    while($row = $result->fetch_assoc()) {
        // Tạo một mảng cho mỗi danh mục
        $category = array(
            'id' => $row['dm_ma'],
            'name' => $row['dm_ten'],
            'children' => array()
        );

        // Truy vấn để lấy danh mục con từ bảng danhmuc_phancap
        $subcategories_sql = "SELECT dm_con FROM danhmuc_phancap WHERE dm_cha = " . $row['dm_ma'];
        $subcategories_result = $conn->query($subcategories_sql);

        if ($subcategories_result->num_rows > 0) {
            while($sub_row = $subcategories_result->fetch_assoc()) {
                // Thêm danh mục con vào mảng children của danh mục cha tương ứng
                $subcategory = array(
                    'id' => $sub_row['dm_con'],
                    'name' => 'Danh Mục Con' // Có thể lấy tên của danh mục con từ bảng danh_muc
                );
                $category['children'][] = $subcategory;
            }
        }

        $categories[] = $category;
    }
}

// Chuyển mảng thành JSON và hiển thị
$json_data = json_encode($categories, JSON_PRETTY_PRINT);
echo $json_data;


?>

        // Dữ liệu danh mục (có thể lấy từ cơ sở dữ liệu hoặc từ API)
      

        function createDropdown(categories, level) {
            const dropdown = document.createElement('select');
            dropdown.dataset.level = level;

            const defaultOption = document.createElement('option');
            defaultOption.text = `Chọn danh mục cấp ${level}`;
            defaultOption.disabled = true;
            defaultOption.selected = true;
            dropdown.appendChild(defaultOption);

            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                dropdown.appendChild(option);

                if (category.children) {
                    option.setAttribute('data-has-children', true);
                }
            });

            dropdown.addEventListener('change', function() {
                const currentLevel = parseInt(this.dataset.level);
                const selectedCategoryId = parseInt(this.value);

                // Xóa các select box cấp cao hơn khi thay đổi giá trị
                let nextDropdown = this.nextElementSibling;
                while (nextDropdown) {
                    nextDropdown.remove();
                    nextDropdown = this.nextElementSibling;
                }

                const selectedCategory = categories.find(category => category.id === selectedCategoryId);
                if (selectedCategory && selectedCategory.children) {
                    createDropdown(selectedCategory.children, currentLevel + 1);
                }
            });

            document.getElementById('category-dropdowns').appendChild(dropdown);
        }

        createDropdown(categories, 1);
    </script>
</body>
</html>
