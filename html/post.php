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
    .loader-container {
      position: fixed;
      /* Sit on top of the page content */
      display: none;
      /* Hidden by default */
      width: 100%;
      /* Full width (cover the whole page) */
      height: 100%;
      /* Full height (cover the whole page) */
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: rgba(0, 0, 0, 0.5);
      /* Black background with opacity */
      z-index: 2;
      /* Specify a stack order in case you're using a different order for other elements */
      cursor: pointer;
      /* Add a pointer on hover */
    }

    .loader-container img {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 65px;
      /* Adjust the size as needed */
      height: 65px;
      /* Adjust the size as needed */
    }
  </style>



  <style>
    /* This selector targets the editable element (excluding comments). */
    .ck-editor__editable_inline:not(.ck-comment__input *) {
      height: 300px;
      overflow-y: auto;
    }
  </style>

  <div class="loader-container" id="loader">
    <img src="https://superstorefinder.net/support/wp-content/uploads/2018/01/grey_style.gif" alt="">
  </div>
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
                  <?php if (isset($_SESSION['user'])) {
                    $user  = $_SESSION['user'];
                    $username = $user['nd_username'];
                    if (isset($_SESSION['bv'])) {
                      $bv = $_SESSION['bv'];
                    }
                  }?>
                <a href="Xem_BaiViet.php?id=<?php echo $username ?>&bv=<?php echo $bv ?>">
                    <div id="dangbai" class="toast hide bg-warning" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="toast-header">
                        <strong class="me-auto">Đăng bài thành công vui lòng chờ duyệt!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                    </div>
            </a>
                  </div>
                </div>
                <div class="card-body">

                  <form class="needs-validation" method="post" action="dangbai.php" enctype="multipart/form-data" accept-charset="utf-8" id="myForm">
                    <input type="hidden" name="tendangnhap" value="<?php $user = $_SESSION['user'];
                                                                    echo $user['nd_username'] ?>">
                    <style>
                      #tieude.loading {
                        background: url(http://www.xiconeditor.com/image/icons/loading.gif) no-repeat right center;
                      }
                    </style>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="basic-default-fullname">Tiêu đề</label><span style="color: red;font-weight: bold;"> *</span>
                      <input data-ajax="" required oninvalid="this.setCustomValidity('Vui lòng nhập tiêu đề!')" oninput="this.setCustomValidity('')" name="tieude" type="text" class="form-control " id="tieude" placeholder="Nhập tiêu đề" />
                      <!-- <div style="color: red;">Vui lòng nhập vào tiêu đề!</div> -->
                      <style>
    .resultBox {
        display: none;
        position: absolute;
        background-color: #fff;
        border: 1px solid #979090;
        padding: 10px;
        border-radius: 0 0 10px 10px;
        width: 96%;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-height: 150px; /* Set a specific height for the scrollbar to appear */
        overflow-y: auto; /* Enable vertical scrollbar */
    }
</style>

                      <?php
                      // if (isset($_GET['tieude']) && !empty($_GET['tieude'])) {
                      ?>
                      <div style="background-color: rgb(232, 234, 235);" id="checkResult" class="resultBox list-group list-group-flush">

                      </div>
                      <?php // } 
                      ?>
                      <script>
                        document.addEventListener("DOMContentLoaded", function() {
                          var resultBox = document.getElementById("checkResult");
                          // if (resultBox =='') {
                          //     // Nếu click bên ngoài .resultBox, ẩn nó
                          //     resultBox.style.display = 'none';
                          //   }
                          document.addEventListener("click", function(event) {
                            var isClickedInsideResultBox = event.target.closest('#checkResult');

                            if (!isClickedInsideResultBox) {
                              // Nếu click bên ngoài .resultBox, ẩn nó
                              resultBox.style.display = 'none';
                            }
                          });

                          // Mô phỏng sự kiện mở .resultBox (ví dụ: click vào một button)
                          var openButton = document.getElementById("openButton");
                          openButton.addEventListener("click", function(event) {
                            // Hiển thị .resultBox khi click vào button
                            resultBox.style.display = 'block';
                            // Ngăn chặn sự kiện lan truyền lên để tránh bị ẩn ngay lập tức
                            event.stopPropagation();
                          });
                        });
                      </script>
                    </div>
                    <div class="mb-2">
                      <label class="form-label" for="selectKhoiLop">Khối lớp</label><span style="color: red;font-weight: bold;"> *</span>
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
                      <label class="form-label" for="selectMonHoc">Môn học</label><span style="color: red;font-weight: bold;"> *</span>
                      <select required class="form-select" id="selectMonHoc" aria-label="Default select example" onchange="getCategories(this.value)">
                        <option value="" hidden>Chọn Môn Học</option>
                        <!-- Options will be dynamically added here based on selected Khối lớp -->
                      </select>
                    </div>


                    <div id="category-dropdowns">
                      <!-- Các select box danh mục sẽ được tạo thông qua JavaScript -->
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-bold" for="basic-default-message">Nội Dung</label><span style="color: red;font-weight: bold;"> *</span>
                      <textarea name="noidung" id="editor" class="form-control" placeholder="Vui lòng nhập nội dung"></textarea>
                      <!-- <div style="color: red;">Vui lòng nhập vào nội dụng</div> -->
                    </div>



                    <div class="mb-3">
  <label for="formFileMultiple" class="form-label fw-bold">Tệp Đính Kèm</label>
  <input class="form-control" type="file" name="formFileMultiple[]" id="formFileMultiple" multiple accept=".docx, .pdf" />
</div>




                    <button type="submit" id="runPythonBtn" name="gui" class="btn btn-primary">Gửi</button>
                    <!-- <button type="button" id="runPythonBtn" class="btn btn-outline-primary">tach từ</button> -->
                    <script>
                      $(document).ready(function() {
                        $("#runPythonBtn").click(function() {
                          $.ajax({
                            url: "run_python.php",
                            type: "POST",
                            success: function(response) {
                              console.log(response);
                              // Xử lý kết quả nếu cần
                            },
                            error: function(error) {
                              console.error(error);
                            }
                          });
                        });
                      });
                    </script>

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

  <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
  <script>
    $(document).ready(function() {
      var resultBox = $("#checkResult");
      var checkTieude = $("#tieude");
      var timeout;

      checkTieude.on("input", function() {
        var keyword = $(this).val();
        clearTimeout(timeout);

        timeout = setTimeout(function() {
          if (keyword.trim() !== '') {
            // Thêm class 'loading' khi bắt đầu AJAX
            checkTieude.addClass('loading');

            $.ajax({
              type: "GET",
              url: "check-tieude.php",
              data: {
                tieude: keyword
              },
              success: function(response) {
                resultBox.html(response);

                // Kiểm tra nếu có nội dung trong khung gợi ý
                if (response.trim() !== '') {
                  resultBox.show(); // Hiển thị khung gợi ý
                } else {
                  resultBox.hide(); // Ẩn khung gợi ý
                }

                // Loại bỏ class 'loading' sau khi nhận được response
                checkTieude.removeClass('loading');
              }
            });
          } else {
            resultBox.hide(); // Ẩn khung gợi ý nếu ô tìm kiếm trống
          }
        }, 700);
      });
    });
  </script>



  <script>
    $(document).ready(function() {
      $("#myForm").submit(function() {
        // Show loader when form is submitted
        $("#loader").show();
      });
    });
  </script>
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
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("dangbai");
        var successToastInstance = new bootstrap.Toast(successToast);
        $("#loader").hide();
        successToastInstance.show();
      });
    <?php
      // Reset the login_success session variable
      unset($_SESSION['dangbai']);
    }
    ?>
  </script>
  <script src="include/ckeditor.js"></script>
  <!-- <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/translations/vi.js"></script> -->
  <script>
    ClassicEditor
      .create(document.querySelector('#editor'), {
        language: 'vi'
      })
      .then(editor => {
        window.editor = editor;
      })
      .catch(err => {
        console.error(err.stack);
      });
  </script>

  <!-- <script>
    function validateForm() {
    var textarea = document.querySelector('textarea[name="noidung"]');
    if (textarea.value.trim() === '') {
        alert('Vui lòng nhập thông tin vào ô textarea.');
        return false; // Ngăn không cho form submit nếu textarea bị trống
    }
    return true; // Cho phép form submit nếu textarea đã được điền thông tin
}
    
  </script> -->

  <script src="../assets/vendor/libs/jquery/jquery.js"></script>
  <script src="../assets/vendor/libs/popper/popper.js"></script>
  <script src="../assets/vendor/js/bootstrap.js"></script>
  <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="../assets/vendor/js/menu.js"></script>
  <script src="../assets/js/main.js"></script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>