<script>
    function Xoa_Danhmuc(dm_ma) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa danh mục này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_DanhMuc.php?this_dm_ma=' + dm_ma;
            }
        });
    }

    function Xoa_Khoilop(kl_ma) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa khối lớp này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_KhoiLop.php?this_kl_ma=' + kl_ma;
            }
        });
    }

    function Xoa_Nguoidung(nd_username) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa người dùng này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_NguoiDung.php?this_username=' + nd_username;
            }
        });
    }

    function Xoa_Baiviet(bv_ma) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa bài viết này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_BaiViet.php?this_bv_ma=' + bv_ma;
            }
        });
    }

    function Xoa_Baiviet_VV(bv_ma) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa vĩnh viễn bài viết này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_BaiViet_VinhVien.php?this_bv_ma=' + bv_ma;
            }
        });
    }

    function Xoa_Monhoc(mh_ma) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa môn học này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_MonHoc.php?this_mh_ma=' + mh_ma;
            }
        });
    }

    function Xoa_Binhluan(bl_ma) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa bình luận này?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_BinhLuan.php?this_bl_ma=' + bl_ma;
            }
        });
    }

    function Xoa_PC_DanhMuc(nd_username) {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc muốn xóa phân công này không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'Xoa_PC_DanhMuc.php?this_user=' + nd_username;
            }
        });
    }

    function Doi_MK(nd_username) {
        Swal.fire({
            title: 'Xác nhận đặt lại mật khẩu',
            text: 'Bạn có chắc muốn đặt lại mật khẩu không?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Xác nhận',
            cancelButtonText: 'Hủy',
        }).then((result) => {
            if (result.isConfirmed) {
                // Nếu người dùng chấp nhận xóa, chuyển hướng đến trang xóa danh mục với tham số dm_ma
                window.location.href = 'DoiMatKhau.php?this_username=' + nd_username;
            }
        });
    }
</script>