<?php
$pythonScript = "C:/xampp/htdocs/VnCoreNLP-master/tachtu.py";
$pythonScript2 = "C:/xampp/htdocs/VnCoreNLP-master/tachtieude.py";
$command = "python $pythonScript";
$command2 = "python $pythonScript2";
exec($command, $output, $return_var);
exec($command2, $output, $return_var);
// Trả về kết quả (nếu cần)
echo json_encode(array("output" => $output, "return_var" => $return_var));
if ($return_var !== 0) {
  echo "Error executing command. Return code: $return_var\n";
}

?>
