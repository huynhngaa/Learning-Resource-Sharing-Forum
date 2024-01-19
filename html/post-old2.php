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
  <style>
    /* This selector targets the editable element (excluding comments). */
.ck-editor__editable_inline:not(.ck-comment__input *) {
    height: 300px;
    overflow-y: auto;
}

  </style>
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
                  <form method="post" action="dangbai.php" enctype="multipart/form-data" accept-charset="utf-8">
                    <input type="hidden" name="tendangnhap" value="<?php $user = $_SESSION['user'];
                                                                    echo $user['nd_username'] ?>">
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="basic-default-fullname">Tiêu đề</label>
                      <input required oninvalid="this.setCustomValidity('Vui lòng nhập tiêu đề!')" oninput="this.setCustomValidity('')" name="tieude" type="text" class="form-control" id="basic-default-fullname" placeholder="Nhập tiêu đề" />
                    </div>
                    <div class="mb-2">
  <label class="form-label" for="selectKhoiLop">Khối lớp</label>
  <select required class="form-select" id="selectKhoiLop" aria-label="Default select example" onchange="updateMonHocOptions(this.value)">
    <option value="" hidden>Chọn Khối Lớp</option>
    <?php
    $sql = "SELECT * FROM khoi_lop";
    $result = mysqli_query($conn, $sql);
    while ($khoilop = mysqli_fetch_array($result)) {
    ?>
      <option value="<?php echo $khoilop['kl_ma'] ?>"><?php echo $khoilop['kl_ten'] ?></option>
    <?php } ?>
  </select>
</div>

<div class="mb-2">
  <label class="form-label" for="selectMonHoc">Môn học</label>
  <select required class="form-select" id="selectMonHoc" aria-label="Default select example" onchange="getCategories(this.value)">
    <option value="" hidden>Chọn Môn Học</option>
    <!-- Options will be dynamically added here based on selected Khối lớp -->
  </select>
</div>


                    <div id="category-dropdowns">
                      <!-- Các select box danh mục sẽ được tạo thông qua JavaScript -->
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="basic-default-message">Nội Dung</label>
                      <textarea  name="noidung" id="editor" class="form-control" placeholder="Vui lòng nhập nội dung"></textarea>
                    </div>
                    <div class="mb-3">
                      <label for="formFileMultiple" class="form-label fw-bold">Tệp Đính Kèm</label>
                      <input class="form-control" type="file" name="formFileMultiple[]" id="formFileMultiple" multiple />
                    </div>
                  
                    <button type="submit" id="loadingBtn" name="gui" class="btn btn-primary">Gửi</button>
                    <!-- <script>
    $(document).ready(function() {
        $("#loadingBtn").click(function() {
            // Disable the button
            $(this).prop("disabled", true);

            // Show the loading spinner
            $(this).html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Loading...');

            // Simulate a delay of 2 seconds
            setTimeout(function() {
                // Enable the button
                $("#loadingBtn").prop("disabled", false);
                // Restore the original text
                $("#loadingBtn").html('a');
            }, 2000);
        });
    });
</script> -->
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
function updateMonHocOptions(selectedKhoiLop) {
  var selectMonHoc = document.getElementById("selectMonHoc");
  while (selectMonHoc.options.length > 1) {
    selectMonHoc.remove(1);
  }
  clearCategoryDropdowns();
  if (selectedKhoiLop !== "hidden") {
    var url = "monhoc.php?khoiLop=" + selectedKhoiLop;
    fetch(url)
      .then(response => response.json())
      .then(data => {
        data.forEach(monhoc => {
          var option = document.createElement("option");
          option.value = monhoc.mh_ma;
          option.text = monhoc.mh_ten;
          selectMonHoc.add(option);
        });
      })
      .catch(error => console.error('Error:', error));
  }
}

</script>
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
  dropdown.setAttribute('required', true);
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
  defaultOption.value = "";
  
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

  // if (!categories.some(category => category.children && category.children.length > 0)) {
  //   dropdown.setAttribute('name', 'danh-muc');
  // }

  dropdown.addEventListener('change', function() {
      const currentLevel = parseInt(this.dataset.level);
      const selectedCategoryId = this.value;
      const selectedCategory = categories.find(category => category.id === selectedCategoryId);
      let nextDropdown = this.nextElementSibling;

      // Check if the selected option has data-has-children attribute
      if (selectedCategory && selectedCategory.children && selectedCategory.children.length > 0) {
        // If it has children, remove the name attribute
        dropdown.removeAttribute('name');
        // Create the next dropdown
        createDropdown(selectedCategory.children, currentLevel + 1);
      } else {
        // If it doesn't have children, add the name attribute
        dropdown.setAttribute('name', 'danh-muc');
        // Remove any subsequent dropdowns
        while (nextDropdown) {
          nextDropdown.remove();
          nextDropdown = this.nextElementSibling;
        }
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
  <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/translations/vi.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ), {
            language: 'vi'
        } )
        .then( editor => {
            window.editor = editor;
        } )
        .catch( err => {
            console.error( err.stack );
        } );
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