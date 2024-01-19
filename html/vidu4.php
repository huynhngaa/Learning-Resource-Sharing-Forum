<?php
// Kết nối CSDL
include "include/conn.php";

// // Thay đổi câu truy vấn CSDL để lấy danh mục từ CSDL
$folders = $this->files_model->select('id, file_parent_id, file_display_name, file_type')
->where(array('file_type' => 'folder'))
->find_all();

// Hàm đệ quy tạo danh sách thả xuống
$indent = array();
$get_menu = function($folders, $folder_id = 0) use(&$get_menu, &$indent) {
$menu_html = '';
foreach($folders as $folder) {
    if($folder->file_parent_id == $folder_id) {
        if($folder_id > 0)
            $indent[$folder->id] = $indent[$folder->file_parent_id] + 1;
        else
            $indent[$folder->id] = 0;

        $menu_html .= '<option value="'.$folder->id.'">';
        for($i = 0; $i < $indent[$folder->id]; $i++) {
            $menu_html .= '---';
        }
        $menu_html .= $folder->file_display_name.'</option>';
        $menu_html .= $get_menu($folders, $folder->id);
    }
}
return $menu_html;
};

echo '<select>'.$get_menu($folders).'</select>';
?>