<?php
session_start();
include("./includes/connect.php");

if (isset($_GET['monhoc'])) {
    $monhoc = $_GET['monhoc'];?>

<label for="defaultSelect" class="form-label">Danh mục</label>

<select name="danhmuc" id="danhmuc" class="form-select">
            <option disabled selected>Chọn danh mục</option>
            <?php

            $data = array();

            $sql = "SELECT d.*, COALESCE(dp.dm_cha, 0) AS dm_cha
            FROM danh_muc d
            LEFT JOIN danhmuc_phancap dp ON d.dm_ma = dp.dm_con where mh_ma = '$monhoc'";
            $result = mysqli_query($conn, $sql);



            while ($monhoc = mysqli_fetch_array($result)) {

                    $data[] = array(
                        "id" => $monhoc['dm_ma'],
                        "name" => $monhoc['dm_ten'],
                        "parent" => $monhoc['dm_cha']
                      
                      );

              
            }

            function dequy($data, $parent = 0, $level = 0) {
                foreach ($data as $k => $value) {
                    if ($value['parent'] == $parent ) {
                        echo '<option value="' . $value['id'] . '">';
                        echo str_repeat('- ', $level) . $value['name'];
                        echo '</option>';
                        unset($data[$k]);
                        dequy($data, $value['id'], $level + 1);
                    }
                }
            }

            dequy($data);
            ?>
        </select>
<?php } 
?>
 


<script>
var data = <?php echo json_encode($data); ?>;
console.log(data);
</script>
