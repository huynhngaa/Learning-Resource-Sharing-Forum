<?php
include "include/conn.php"; // Include your database connection file

// Check if the 'khoiLop' parameter is set in the request
if (isset($_GET['monHoc'])) {
    $khoiLop = $_GET['monHoc'];

    // Use prepared statements to prevent SQL injection
    
    $sql = " SELECT dm_ma, dm_ten
    FROM danh_muc
    WHERE mh_ma = ?
    AND NOT EXISTS (
        SELECT 1
        FROM danhmuc_phancap
        WHERE danh_muc.dm_ma = danhmuc_phancap.dm_con
    )";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        // Bind the parameter
        mysqli_stmt_bind_param($stmt, "i", $khoiLop);
        
        // Execute the statement
        mysqli_stmt_execute($stmt);

        // Get the result
        $result = mysqli_stmt_get_result($stmt);

        // Fetch data as an associative array
        $monHocArray = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Output the data as JSON
        header('Content-Type: application/json');
        echo json_encode($monHocArray);
    } else {
        // Handle errors
        echo "Error in prepared statement: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    // If 'khoiLop' parameter is not set, return an empty array
    header('Content-Type: application/json');
    echo json_encode([]);
}

// Close the database connection
mysqli_close($conn);
?>
