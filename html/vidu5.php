<?php
include "include/conn.php";
include "include/head.php";

// Kiểm tra nếu người dùng chưa đăng nhập, thực hiện chuyển hướng đến trang "index.php"
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit; // Dừng việc thực thi mã PHP sau khi đã chuyển hướng
}
?>

<body>
  <?php include "include/navbar.php" ?>
  <!-- Layout wrapper -->
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">
      <?php include "include/rightmenu.php" ?>
      <div class="layout-page">
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="col-xl">
              <div class="card mb-4 mt-5">
                <div class="card-header d-flex justify-content-between align-items-center">
                  <h5 class="mb-0">Đăng Bài</h5>
                  <div class="position-fixed top-0 end-0" style="z-index: 11; margin-top:75px">
                    <div id="dangbai" class="toast hide bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="toast-header">
                        <strong class="me-auto">Đăng bài thành công vui lòng đợi duyệt</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <form method="post" action="dangbai.php" enctype="multipart/form-data">
                    <input type="hidden" name="tendangnhap" value="<?php $user = $_SESSION['user'];
                                                                    echo $user['nd_username'] ?>">
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="basic-default-fullname">Tiêu đề</label>
                      <input required oninvalid="this.setCustomValidity('Vui lòng nhập tiêu đề!')" oninput="this.setCustomValidity('')" name="tieude" type="text" class="form-control" id="basic-default-fullname" placeholder="Nhập tiêu đề" />
                    </div>
                    <div class="mb-2">
                      <label class="form-label" for="basic-default-company">Môn học</label>
                      <select required class="form-select" id="selectMonHoc" aria-label="Default select example" onchange="getCategories(this.value)">
                        <option hidden>Chọn Môn Học</option>
                        <?php
                        $sql = "SELECT * FROM mon_hoc";
                        $result = mysqli_query($conn, $sql);
                        while ($monhoc = mysqli_fetch_array($result)) {
                        ?>
                          <option value="<?php echo $monhoc['mh_ma'] ?>"><?php echo $monhoc['mh_ten'] ?></option>
                        <?php } ?>
                      </select>
                    </div>
                    <div id="category-dropdowns">
                      <!-- Các select box danh mục sẽ được tạo thông qua JavaScript -->
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="basic-default-message">Nội Dung</label>
                      <textarea name="noidung" id="basic-default-message" class="form-control" placeholder="Vui lòng nhập nội dung"></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="formFileMultiple" class="form-label fw-bold">Tệp Đính Kèm</label>
                      <input class="form-control" type="file" name="formFileMultiple[]" id="formFileMultiple" multiple />
                    </div>
                    <button type="submit" name="gui" class="btn btn-primary">Gửi</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://cdn.tiny.cloud/1/xzvijpgncm8saa0ygeagl2xsq4k3e443moey5wts5mjc0r2w/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    function getCategories(monHocValue) {
      fetch('get-danh-muc.php?monhoc=' + monHocValue)
        .then(response => response.json())
        .then(data => {
          clearCategoryDropdowns(); // Clear the existing category dropdowns
          if (data.length > 0) {
            createDropdown(data, 1); // Create the new category dropdowns
          }
        })
        .catch(error => console.error('Error:', error));
    }

    function clearCategoryDropdowns() {
      const categoryDropdowns = document.getElementById('category-dropdowns');
      while (categoryDropdowns.firstChild) {
        categoryDropdowns.removeChild(categoryDropdowns.firstChild);
      }
    }
  </script>
  <script>
  function createDropdown(categories, level) {
  const dropdown = document.createElement('select');
  dropdown.dataset.level = level;
  dropdown.classList.add('form-select');
  const label = document.createElement('label');
  label.classList.add('form-label');
  label.textContent = `Danh Mục`;
  label.setAttribute('for', `select${level}`);
  dropdown.setAttribute('id', `select${level}`);

  const divGroup = document.createElement('div');
  divGroup.classList.add('mt-3');
  divGroup.appendChild(label);
  divGroup.appendChild(dropdown);
  document.getElementById('category-dropdowns').appendChild(divGroup);

  const defaultOption = document.createElement('option');
  defaultOption.setAttribute('hidden', true);
  defaultOption.text = `Chọn danh mục`;
  defaultOption.disabled = true;
  defaultOption.selected = true;
  dropdown.appendChild(defaultOption);

  categories.forEach(category => {
    const option = document.createElement('option');
    option.value = category.id;
    option.textContent = category.name;
    dropdown.appendChild(option);

    if (category.children && category.children.length > 0) {
      option.setAttribute('data-has-children', true);
    }
  });

  if (!categories.some(category => category.children && category.children.length > 0)) {
    dropdown.setAttribute('name', 'danh-muc');
  }

  dropdown.addEventListener('change', function() {
    const currentLevel = parseInt(this.dataset.level);
    const selectedCategoryId = this.value;
    const selectedCategory = categories.find(category => category.id === selectedCategoryId);
    let nextDropdown = this.nextElementSibling;
    while (nextDropdown) {
      nextDropdown.remove();
      nextDropdown = this.nextElementSibling;
    }
    if (selectedCategory && selectedCategory.children && selectedCategory.children.length > 0) {
      createDropdown(selectedCategory.children, currentLevel + 1);
    }
  });

  document.getElementById('category-dropdowns').appendChild(dropdown);
}

createDropdown(categoriesData, 1);


    createDropdown(categoriesData, 1);
  </script>
  <script>
    <?php if (isset($_SESSION['dangbai']) && $_SESSION['dangbai']) { ?>
      // Successful login
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("dangbai");
        var successToastInstance = new bootstrap.Toast(successToast);
        successToastInstance.show();
      });
    <?php
      // Reset the login_success session variable
      unset($_SESSION['dangbai']);
    }
    ?>
  </script>
  <script src="include/ckeditor/ckeditor/ckeditor.js"></script>
  <script src="include/ckfinder/ckfinder/ckfinder.js"></script>
  <script>
    CKEDITOR.replace('noidung', {
      filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
      filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
    })
  </script>
  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  <script src="../assets/js/main.js"></script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>