<?php $id = $_GET['id'];
                                  if (isset($_SESSION['user'])) {
                                    $user = $_SESSION['user'];
                                    if ($user['nd_username'] == $id) {
                                  ?>
                                      <form method="post" action="capnhat-hoso.php" enctype="multipart/form-data">
                                        <div class="card-body">
                                          <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img src="../assets/img/avatars/<?php echo $nguoidung['nd_hinh'] ?>" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                                            <div class="button-wrapper">
                                              <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                                <span class="d-none d-sm-block">Tải ảnh lên</span>
                                                <i class="bx bx-upload d-block d-sm-none"></i>
                                                <input name="img" type="file" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                              </label>
                                              <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                                <i class="bx bx-reset d-block d-sm-none"></i>
                                                <span class="d-none d-sm-block">Reset</span>
                                              </button>

                                              <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="card-body">

                                          <?php
                                          if (isset($_SESSION['user'])) {
                                            $user = $_SESSION['user']; ?>
                                            <input name="username" type="hidden" value="<?php echo $user['nd_username'] ?>">
                                          <?php } ?>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-fullname">Họ và tên</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">

                                                <input name="hoten" type="text" class="form-control" id="basic-icon-default-fullname" value="<?php echo $nguoidung['nd_hoten'] ?>" aria-label="John Doe" aria-describedby="basic-icon-default-fullname2" />
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-company">Giới tính</label>
                                            <div class="col-sm-10">
                                              <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gioitinh" id="inlineRadio1" value="2" <?php if ($nguoidung['nd_gioitinh'] == 2) echo 'checked'; ?>>
                                                <label class="form-check-label" for="inlineRadio1">Nam</label>
                                              </div>
                                              <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gioitinh" id="inlineRadio2" value="1" <?php if ($nguoidung['nd_gioitinh'] == 1) echo 'checked'; ?>>
                                                <label class="form-check-label" for="inlineRadio2">Nữ</label>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Ngày sinh</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">

                                                <input name="ngaysinh" class="form-control" type="date" value="<?php echo  $nguoidung['nd_ngaysinh']; ?>" id="html5-date-input" />
                                              </div>
                                              <div id="ngaysinh-error-message" style="color: red;"></div>
                                            </div>

                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-email">Email</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">

                                                <input name="email" type="text" id="basic-icon-default-email" class="form-control" value="<?php echo $nguoidung['nd_email'] ?>" aria-label="john.doe" aria-describedby="basic-icon-default-email2" />
                                              </div>
                                              <div id="email-error-message" style="color: red;"></div>
                                            </div>

                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-phone">Số điện thoại</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">

                                                <input name="sdt" type="text" id="basic-icon-default-phone" class="form-control phone-mask" value="<?php echo $nguoidung['nd_sdt'] ?>" aria-label="658 799 8941" aria-describedby="basic-icon-default-phone2" />
                                              </div>
                                            </div>
                                            <div class="row">
                                              <div class="col-2"></div>
                                              <div class="col-10" id="error-message" style="color: red;"></div>
                                            </div>

                                          </div>

                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-message">Địa chỉ</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">
<input  name="diachi" aria-describedby="basic-icon-default-message2"  value="<?php echo $nguoidung['nd_diachi'] ?>" class="form-control" type="text">
                                                
                                            
                                              
                                              <input  name="tinhtp" id="result" type="hidden">  
                                            </div>
                                            </div>
                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-message">Địa chỉ</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">

                                              <select class="form-control"  id="city">
                                                <option value="" selected>Chọn tỉnh thành</option>
                                              </select>                                              </div>
                                            </div>
                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-message">Địa chỉ</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">

                                              <select class="form-control"   id="district">
                                                <option value="" selected>Chọn tỉnh thành</option>
                                              </select>                                              </div>
                                            </div>
                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-2 col-form-label" for="basic-icon-default-message">Địa chỉ</label>
                                            <div class="col-sm-10">
                                              <div class="input-group input-group-merge">

                                              <select class="form-control"  id="ward">
                                                <option value="" selected>Chọn tỉnh thành</option>
                                              </select>                                              </div>
                                            </div>
                                          </div>
                                          <div >
                                           

                                            <h2 ></h2>
                                          
                                

                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer"></script>
                                            <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>
                                            <script>
                                              const host = "https://provinces.open-api.vn/api/";
                                              var callAPI = (api) => {
                                                return axios.get(api)
                                                  .then((response) => {
                                                    renderData(response.data, "city");
                                                  });
                                              }
                                              callAPI('https://provinces.open-api.vn/api/?depth=1');
                                              var callApiDistrict = (api) => {
                                                return axios.get(api)
                                                  .then((response) => {
                                                    renderData(response.data.districts, "district");
                                                  });
                                              }
                                              var callApiWard = (api) => {
                                                return axios.get(api)
                                                  .then((response) => {
                                                    renderData(response.data.wards, "ward");
                                                  });
                                              }

                                              var renderData = (array, select) => {
                                                let row = ' <option disable value="">Chọn</option>';
                                                array.forEach(element => {
                                                  row += `<option data-id="${element.code}" value="${element.name}">${element.name}</option>`
                                                });
                                                document.querySelector("#" + select).innerHTML = row
                                              }

                                              $("#city").change(() => {
                                                callApiDistrict(host + "p/" + $("#city").find(':selected').data('id') + "?depth=2");
                                                printResult();
                                              });
                                              $("#district").change(() => {
                                                callApiWard(host + "d/" + $("#district").find(':selected').data('id') + "?depth=2");
                                                printResult();
                                              });
                                              $("#ward").change(() => {
                                                printResult();
                                              })

                                              var printResult = () => {
  if ($("#district").find(':selected').data('id') !== "" &&
      $("#city").find(':selected').data('id') !== "" &&
      $("#ward").find(':selected').data('id') !== "") {
    let result = $("#ward option:selected").text() + " " +
      $("#district option:selected").text() + " " +
      $("#city option:selected").text();

    $("#result").val(result);
  }
}

                                            </script>
                                          </div>
                                          <div class="row justify-content-end">
                                            <div class="col-sm-10">
                                              <button name="capnhat" type="submit" class="btn btn-primary">Cập Nhật</button>
                                            </div>
                                          </div>
                                      </form>
                                    <?php  }
                                  }
                                  if (!isset($_SESSION['user']) || (isset($_SESSION['user']) && $_SESSION['user']['nd_username'] !== $id)) { { ?>
                                      <form method="post" action="capnhat-hoso.php" enctype="multipart/form-data">

                                        <div class="card-body">
                                          <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="basic-icon-default-fullname">Họ và tên:</label>
                                            <div class="col-sm-9">
                                              <div class="input-group input-group-merge">
                                                <h6 class="text-dark col-form-label"><?php echo $nguoidung['nd_hoten'] ?></h6>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="basic-icon-default-company">Giới tính</label>
                                            <div class="col-sm-9">

                                              <div class="input-group input-group-merge">
                                                <h6 class="text-dark col-form-label"><?php if ($nguoidung['nd_gioitinh'] == 2) echo 'Nam';
                                                                                      if ($nguoidung['nd_gioitinh'] == 1) echo 'Nữ'; ?></h6>
                                              </div>
                                            </div>
                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="basic-icon-default-email">Ngày sinh</label>
                                            <div class="col-sm-9">
                                              <div class="input-group input-group-merge">
                                                <h6 class=" text-dark col-form-label"><?php echo $nguoidung['nd_ngaysinh'] ?></h6>
                                              </div>
                                              <div id="ngaysinh-error-message" style="color: red;"></div>
                                            </div>

                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="basic-icon-default-email">Email</label>
                                            <div class="col-sm-9">
                                              <div class="input-group input-group-merge">
                                                <h6 class="text-dark col-form-label"><?php echo $nguoidung['nd_email'] ?></h6>
                                              </div>
                                              <div id="email-error-message" style="color: red;"></div>
                                            </div>

                                          </div>
                                          <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="basic-icon-default-phone">Số điện thoại</label>
                                            <div class="col-sm-9">
                                              <div class="input-group input-group-merge">
                                                <h6 class="text-dark col-form-label"><?php echo $nguoidung['nd_sdt'] ?></h6>
                                              </div>
                                            </div>


                                          </div>

                                          <div class="row mb-3">
                                            <label class="col-sm-3 col-form-label" for="basic-icon-default-message">Địa chỉ</label>
                                            <div class="col-sm-9">
                                              <div class="input-group input-group-merge">
                                                <h6 class="text-dark col-form-label"><?php echo $nguoidung['nd_diachi'] ?></h6>
                                              </div>
                                            </div>
                                          </div>

                                      </form>
                                  <?php }
                                  } ?>
                                </div>