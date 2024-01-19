<?php
  session_start();
  include("./includes/connect.php");
$danh_muc_ql = "SELECT *
FROM nguoi_dung b
JOIN quan_ly a ON a.nd_username = b.nd_username
JOIN danh_muc c ON c.dm_ma = a.dm_ma
";

$result_danh_muc_ql = mysqli_query($conn, $danh_muc_ql);

// Check if the query was successful
if ($result_danh_muc_ql) {
// Initialize an array to store the results
$userArray = array();

// Fetch associative array
while ($row_danh_muc_ql = mysqli_fetch_assoc($result_danh_muc_ql)) {
// Use user as the key to group by user
$userArray[$row_danh_muc_ql['nd_username']]['hoten'] = $row_danh_muc_ql['nd_hoten'];
$userArray[$row_danh_muc_ql['nd_username']]['danh_muc'][] = array(
'dm_ma' => $row_danh_muc_ql['dm_ma'],
'dm_ten' => $row_danh_muc_ql['dm_ten']
);
}

// Print or use the $userArray as needed
foreach ($userArray as $username => $userData) {
    echo "Username: " . $username . "<br>";
echo "User: " . $userData['hoten'] . "<br>";
echo "Categories: <br>";
foreach ($userData['danh_muc'] as $category) {
echo "- " . $category['dm_ten'] . "<br>";
}
echo "<br>";
}
} else {
// Handle query error
echo "Error: " . mysqli_error($conn);
}

// Close the database connection
mysqli_close($conn);

?>