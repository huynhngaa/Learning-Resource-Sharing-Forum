<?php
include "include/conn.php";
?>
<?php
$data = array();

$sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
        FROM danh_muc d
        LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con";
$result = mysqli_query($conn, $sql);

while ($monhoc = mysqli_fetch_array($result)) {
    $data[] = array(
        "id" => $monhoc['dm_ma'],
        "name" => $monhoc['dm_ten'],
        "parent" => $monhoc['dm_cha']
    );
}

function generateSelectOptions($data, $parent = 0, $level = 0)
{
    $options = '';
    foreach ($data as $k => $value) {
        if ($value['parent'] == $parent) {
            $indent = str_repeat('&nbsp;', $level * 4);
            $options .= '<option value="' . $value['id'] . '">' . $indent . $value['name'] . '</option';
            $options .= generateSelectOptions($data, $value['id'], $level + 1);
        }
    }
    return $options;
}

// Generate the hierarchical select dropdown
$selectOptions = generateSelectOptions($data);

// Create the HTML select element
$selectHTML = '<select name="category">' . $selectOptions . '</select>';

echo $selectHTML;
?>
