<style>
  /* Ratings widget */
  .rate {
    display: inline-block;
    border: 0;
  }

  /* Hide radio */
  .rate>input {
    display: none;
  }

  /* Order correctly by floating highest to the right */
  .rate>label {

    float: right;
  }

  /* The star of the show */
  .rate>label:before {
    display: inline-block;
    font-size: 1.7rem;
    padding: .3rem .2rem;
    margin-right: 0;
    cursor: pointer;
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    content: "\f005";
    /* full star */

  }


  /* Click + hover color */
  input:checked~label,
  /* color current and previous stars on checked */
  label:hover,
  label:hover~label {
    color: #FDCC0D;
  }

  /* color previous stars on hover */

  /* Hover highlights */
  input:checked+label:hover,
  input:checked~label:hover,
  /* highlight current and previous stars */
  input:checked~label:hover~label,
  /* highlight previous selected stars for new rating */
  label:hover~input:checked~label

  /* highlight previous selected stars */
    {
    color: #FDCC0D;
  }
</style>

<div class="row pt-3">
  <div class="col-lg-2 col-sm-3">
    <div class="p-1" style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 120px;">
      <h2 style="margin-bottom: 0.4rem;" class="text-center">
        <?php
        $id = $_GET['id'];
        $tb = "SELECT ROUND(AVG(dg_diem), 1) as trungbinh FROM danh_gia WHERE bv_ma = $id;";
        $sl = "SELECT  count(*) as soluong FROM danh_gia WHERE bv_ma = $id group by bv_ma";
        $rs = mysqli_query($conn, $tb);
        $rtb = mysqli_fetch_assoc($rs);
        $rssl = mysqli_query($conn, $sl);
        $rsl = mysqli_fetch_assoc($rssl);
        $averageRating = '0.0';
        $checkQuery = "SELECT * FROM danh_gia WHERE  bv_ma = $id";
        $result = $conn->query($checkQuery);
        if ($result->num_rows > 0) {
          echo $rtb['trungbinh'];
        } else {
          echo $averageRating;
        } ?>

      </h2>
      <?php
      $score = $rtb['trungbinh']; // Replace this with the actual score you want to represent

      // Make sure the score is within the valid range
      $score = max(0, min(5, $score));
      ?>

      <div style="display: inline-block;" class="stars">
        <?php for ($i = 1; $i <= 5; $i++) : ?>
          <?php
          $starClass = ($i <= $score) ? 'fa-solid fa-star' : (($i == ceil($score) && $score - floor($score) > 0 && $score - floor($score) < 1) ? 'fa-regular fa-star-half-stroke' : 'fa-regular fa-star');
          $starStyle = 'color: #FDCC0D;';
          ?>
          <i class="<?php echo $starClass; ?>" style="<?php echo $starStyle; ?>"></i>
        <?php endfor; ?>
      </div>





      <!-- <div style="text-align: center;margin-bottom: 0.3rem;" class="center">
        <div style="display: inline-block;" class="stars">
          <i class="fa-solid fa-star" style="color: #FDCC0D;"></i>
          <i class="fa-solid fa-star" style="color: #FDCC0D;"></i>
          <i class="fa-solid fa-star" style="color: #FDCC0D;"></i>
          <i class="fa-solid fa-star" style="color: #FDCC0D;"></i>
          <i class="fa-solid fa-star" style="color: #FDCC0D;"></i>
        </div>
      </div> -->
      <small class="text-center"><?php if ($result->num_rows > 0) {

                                    echo $rsl['soluong'];
                                  } else {
                                    echo '0';
                                  }; ?> đánh giá</small>
    </div>

  </div>
  <?php if ($result->num_rows > 0) {

    // SQL query to count the number of ratings for each star level
    $ratingsCountQuery = "SELECT dg_diem, COUNT(*) as count FROM danh_gia WHere bv_ma = $id  GROUP BY dg_diem ORDER BY dg_diem DESC";
    $result = $conn->query($ratingsCountQuery);

    // Array to store the counts for each rating
    $ratingsCount = array();
    while ($row = $result->fetch_assoc()) {
      $ratingsCount[$row['dg_diem']] = $row['count'];
    }


  ?>

    <div class="col-lg-4 col-sm-3 p-2" style="display: flex; flex-direction: column;">
      <?php
      // Loop through each star level and embed the counts into the HTML
      $tong = $rsl['soluong'];
      for ($i = 5; $i >= 1; $i--) {
        $count = isset($ratingsCount[$i]) ? $ratingsCount[$i] : 0;
        $phantram = ($count / $tong) * 100;
      ?>
        <div style="display: flex; align-items: center;">
          <span>
            <span style="margin-right: 10px;"><?php echo $i; ?> <i class="fa-solid fa-star" style="color: #FDCC0D;"></i></span>
          </span>
          <div class="progress" style="flex: 1;">
            <div class="progress-bar bg-warning" role="progressbar" style="width: <?php echo  $phantram; ?>%" aria-valuenow="<?php echo $phantram; ?>" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div style="text-align: right;width:40px">
            <?php echo $count ?>
          </div>
        </div>
      <?php
      }
      ?>
    </div>

  <?php } else {

    // SQL query to count the number of ratings for each star level
    $ratingsCountQuery = "SELECT dg_diem, COUNT(*) as count FROM danh_gia WHere bv_ma = $id  GROUP BY dg_diem ORDER BY dg_diem DESC";
    $result = $conn->query($ratingsCountQuery);

    // Array to store the counts for each rating
    $ratingsCount = array();
    while ($row = $result->fetch_assoc()) {
      $ratingsCount[$row['dg_diem']] = $row['count'];
    }


  ?>

    <div class="col-lg-4 col-sm-3 p-2" style="display: flex; flex-direction: column;">
      <?php
      // Loop through each star level and embed the counts into the HTML

      for ($i = 5; $i >= 1; $i--) {

      ?>
        <div style="display: flex; align-items: center;">
          <span>
            <span style="margin-right: 10px;"><?php echo $i; ?> <i class="fa-solid fa-star" style="color: #FDCC0D;"></i></span>
          </span>
          <div class="progress" style="flex: 1;">
            <div class="progress-bar bg-warning" role="progressbar" style="width:0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
          </div>
          <div style="text-align: right;width:40px">
            0
          </div>
        </div>
      <?php
      }
      ?>
    </div>
  <?php } ?>
  <div class="col-lg-3 col-sm-4">
    <?php
    if (isset($_SESSION['user'])) {
      $user = $_SESSION['user'];
    ?>
      <button style="position: absolute;
bottom:  75px" type="button" class="btn btn-primary btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#modaldanhgia">
        Đánh Giá
      </button>
    <?php } else {
      // If the user session is not set, display a toast message
    ?>
      <button style="position: absolute;
bottom:  75px" type="button" class="btn btn-primary btn-sm ms-2" onclick="showLoginToast()">
        Đánh Giá
      </button>

    <?php
    }
    ?>


    <div class="modal fade" id="modaldanhgia" tabindex="-1" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <h6 style="margin-top: -1rem;" class="mb-3 text-center">Đánh giá bài viết</h6>
            <hr>
            <h4 class="text-center text-dark"> <?php echo  $baiviet['bv_tieude'] ?></h4>
            <?php
            $bv_ma = $_GET['id'];
            if (isset($_SESSION['user'])) {
              $user = $_SESSION['user'];
              $nd_username = $user['nd_username'];
            }
            $checkQuery = "SELECT * FROM danh_gia WHERE nd_username = '$nd_username' AND bv_ma = $bv_ma";
            $result = $conn->query($checkQuery);
            if ($result->num_rows > 0) {
              $row = $result->fetch_assoc();
              $existingRating = $row['dg_diem']; // Get the existing rating value from the database
            ?>
              <div style="display: flex; justify-content: center;">
                <fieldset class="rate">
                  <input type="radio" id="rating10" name="rating" value="5" <?php echo ($existingRating == 5) ? 'checked' : ''; ?> /><label for="rating10" title="5 stars"></label>
                  <input type="radio" id="rating8" name="rating" value="4" <?php echo ($existingRating == 4) ? 'checked' : ''; ?> /><label for="rating8" title="4 stars"></label>
                  <input type="radio" id="rating6" name="rating" value="3" <?php echo ($existingRating == 3) ? 'checked' : ''; ?> /><label for="rating6" title="3 stars"></label>
                  <input type="radio" id="rating4" name="rating" value="2" <?php echo ($existingRating == 2) ? 'checked' : ''; ?> /><label for="rating4" title="2 stars"></label>
                  <input type="radio" id="rating2" name="rating" value="1" <?php echo ($existingRating == 1) ? 'checked' : ''; ?> /><label for="rating2" title="1 star"></label>
                </fieldset>
              </div>
              <hr>
              <div style="display: flex; justify-content: center;">
                <button id="danhgia" class="btn btn-primary btn-sm"> Cập Nhật </button>
              </div>
            <?php } else { ?>
              <div style=" display: flex;
  justify-content: center;">
                <fieldset class="rate">
                  <input type="radio" id="rating10" name="rating" value="5" /><label for="rating10" title="5 stars"></label>
                  <input type="radio" id="rating8" name="rating" value="4" /><label for="rating8" title="4 stars"></label>
                  <input type="radio" id="rating6" name="rating" value="3" /><label for="rating6" title="3 stars"></label>
                  <input type="radio" id="rating4" name="rating" value="2" /><label for="rating4" title="2 stars"></label>
                  <input type="radio" id="rating2" name="rating" value="1" /><label for="rating2" title="1 star"></label>
                </fieldset>
              </div>
              <!-- <div class="text-center" style="color:red;">Lỗi! Vui lòng chọn điểm đánh giá.</div> -->
              <hr>
              <div style=" display: flex; justify-content: center;">
                <button id="danhgia" class="btn btn-primary btn-sm"> Đánh giá </button>
              </div>


            <?php } ?>

          </div>

        </div>
      </div>
    </div>
  </div>
</div>


<input type="hidden" id="selectedRating">
<input type="hidden" id="bv" value="<?php echo $_GET['id']; ?>">

<input type="hidden" id="nd" value="<?php
                                    if (isset($_SESSION['user'])) {
                                      $user = $_SESSION['user'];
                                      echo $user['nd_username'];
                                    } ?>">

<script>
  const ratingInputs = document.querySelectorAll('input[name="rating"]');
  const selectedRating = document.getElementById('selectedRating');
  const bvInput = document.getElementById('bv');
  const ndInput = document.getElementById('nd');
  const danhGiaButton = document.getElementById('danhgia');

  danhGiaButton.addEventListener('click', function() {
    const selectedValue = document.querySelector('input[name="rating"]:checked').value;
    selectedRating.value = "Selected rating: " + selectedValue;

    const rating = selectedValue;
    const bv = bvInput.value;
    const nd = ndInput.value;

    // AJAX call to send the rating value to the server
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'save_rating.php');
    xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');
    xhr.onload = function() {
      if (xhr.status === 200) {
        // On successful rating submission, close modal and display success message
        $('#modaldanhgia').modal('hide'); // Close the modal
        Swal.fire({
          position: "center",
          icon: "success",
          title: "Thành Công!",
          showConfirmButton: false,
          timer: 1500

        }).then(() => {
          // Reload the page
          location.reload();
        });
        console.log('Rating sent successfully');
      } else {
        console.log('Failed to send rating');
      }
    };
    xhr.send(JSON.stringify({
      rating: rating,
      bv_ma: bv,
      nd_username: nd
    }));
  });
</script>


<script>
  // Kiểm tra xem người dùng đã đăng nhập hay chưa
  if (<?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>) {
    // Người dùng đã đăng nhập, không cần mở modal, chỉ chuyển hướng
    // document.getElementById('dangBaiLink').href = 'post.php';
  } else {
    // Người dùng chưa đăng nhập, mở modal khi click vào liên kết
    document.getElementById('danhgia').addEventListener('click', function(event) {
      event.preventDefault(); // Ngăn chặn mở trang mới khi click
      $('#modalCenter').modal('show'); // Mở modal đăng nhập
    });
  }
</script>