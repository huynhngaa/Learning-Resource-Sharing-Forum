<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
      function showLoader() {
        $("#loaderContainer").css("display", "flex");
      }

      function hideLoader() {
        $("#loaderContainer").css("display", "none");
      }
    </script>

      <script>
              $(document).ready(function() {
             hideLoader(); // Hide loader when the search results are loaded
            });
           </script>

<script>
    $(document).ready(function() {
        var timeout; // Biến để lưu trữ ID của timeout
        var searchInput = $("#searchInput");

        // Lấy giá trị từ truy vấn trong URL khi trang được load
        var initialKeyword = getParameterByName("noidung");
        searchInput.val(initialKeyword);

        searchInput.on("input", function() {
            showLoader();
            var keyword = $(this).val();

            // Xóa timeout hiện tại nếu có
            clearTimeout(timeout);

            // Tạo một timeout mới để trì hoãn việc gửi yêu cầu Ajax
            timeout = setTimeout(function() {
                // Cập nhật truy vấn trong URL với giá trị từ ô input
                window.history.pushState({}, '', '?noidung=' + keyword);

                // Gửi yêu cầu Ajax đến trang PHP khi giá trị trong ô input thay đổi
                $.ajax({
                    type: "GET",
                    url: "search-tm.php",
                    data: { noidung: keyword }, // Truyền giá trị noidung cho PHP
                    success: function(response) {
                        // Hiển thị kết quả tìm kiếm
                        $("#searchResult").html(response);
                        hideLoader();
                    }
                });
            }, 400); 
        });

        // Hàm để lấy giá trị từ truy vấn trong URL
        function getParameterByName(name) {
            var url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, ' '));
        }
    });
</script>

  <script>
$(document).ready(function() {
  // Hàm này được thực thi khi tài liệu (trang web) đã tải hoàn toàn

  // Lấy ID bình luận từ tham số URL
  var commentId = getParameterByName('bl_ma'); 

  // Nếu ID bình luận được đặt, cuộn đến nó và giữa màn hình
  if(commentId) {
    scrollToComment(commentId);
  }

  function getParameterByName(name) {
    // Hàm này trích xuất giá trị của tham số URL bằng cách sử dụng biểu thức chính quy
    var url = window.location.href;
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, " "));
  }

  function scrollToComment(commentId) {
    // Tìm phần tử bình luận
    var commentElement = $("#comment-" + commentId);

    // Nếu phần tử tồn tại, cuộn đến giữa màn hình
    if (commentElement.length > 0) {
      var windowHeight = $(window).height();
      var commentTop = commentElement.offset().top;
      var scrollTo = commentTop - (windowHeight / 2);

      // Cuộn đến vị trí được tính toán
      $('html, body').animate({
        scrollTop: scrollTo
      }, 100);
    }
  }
});

</script>


  <script>
    <?php if (isset($_SESSION['binhluan']) && $_SESSION['binhluan']) { ?>
      // Successful login
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("binhluan");
        var successToastInstance = new bootstrap.Toast(successToast);
        successToastInstance.show();
      });
    <?php
      // Reset the login_success session variable
      unset($_SESSION['binhluan']);
    }
    ?>
  </script>

  <script>
    <?php if (isset($_SESSION['repbl']) && $_SESSION['repbl']) { ?>
      // Successful login
      document.addEventListener("DOMContentLoaded", function() {
        const successToast = document.getElementById("binhluan");
        var successToastInstance = new bootstrap.Toast(successToast);
        successToastInstance.show();
      });
    <?php
      // Reset the login_success session variable
      unset($_SESSION['repbl']);
    }
    ?>
  </script>
  <script>
    function showLoginToast() {
      const loginToast = document.getElementById("loginToast");
      var loginToastInstance = new bootstrap.Toast(loginToast);
      loginToastInstance.show();
    }
  </script>





  <!-- Core JS -->
  <!-- build:js assets/vendor/js/core.js -->
 
  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->

  <!-- Page JS -->

  <!-- Place this tag in your head or just before your close body tag. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script>
    document.addEventListener("DOMContentLoaded", function () {
  // Get the value of bl_ma from the URL parameters
  const urlParams = new URLSearchParams(window.location.search);
  const commentIdToScroll = urlParams.get('bl_ma');

  // Check if the commentIdToScroll is not null or undefined
  if (commentIdToScroll) {
    // Construct the CSS selector for the comment element
    const commentSelector = `#comment-${commentIdToScroll}`;

    // Get the comment element
    const commentElement = document.querySelector(commentSelector);

    // Check if the comment element is found
    if (commentElement) {
      // Scroll to the comment element
      commentElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }
});

  </script>
  <script>
    // Wait for the document to be ready
    $(document).ready(function() {
      // Add a click event listener to the button
      $('#liveToastBtn').click(function() {
        // Show the toast
        $('#liveToast').toast('show');
      });
    });
  </script>
  <!-- Add this script at the end of your HTML body -->
  <script>
    $(document).ready(function() {
      // Select all reply buttons
      $('.reply-button').click(function() {
        // Find the corresponding reply form using data-comment-id attribute
        var commentId = $(this).data('comment-id');
        var replyForm = $('.reply-form-' + commentId);

        // Toggle the display of the clicked reply form
        replyForm.slideToggle();

        // Hide all other reply forms
        $('.reply-form').not(replyForm).slideUp();
      });
    });
  </script>


  <script>
    $(document).ready(function() {
      // Select all reply buttons
      $('.reply-button1').click(function() {
        // Find the corresponding reply form using data-comment-id attribute
        var commentId = $(this).data('comment-id');
        var replyForm = $('.reply-form1-' + commentId);

        // Toggle the display of the clicked reply form
        replyForm.slideToggle();
      });
    });
  </script>
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
  <script src="include/ckeditor.js"></script>
<!-- <script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/translations/vi.js"></script> -->
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

  <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/js/menu.js"></script>
    <script src="../../assets/js/main.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
