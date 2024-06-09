<?php

// Include your database connection file
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $itemName = mysqli_real_escape_string($conn, $_GET['itemName']);
    
    if (stripos($itemName, 'deal') === false) {
    $query = "SELECT stockcount FROM items WHERE name = '$itemName'";
    }
    else{
        $query = "SELECT stockcount FROM deals WHERE name = '$itemName'";

    }
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        echo $row['stockcount'];
    } else {
        echo 0;
    }
} else {
    // Invalid request method
    echo 0;
}
?>
