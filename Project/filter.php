<?php

include "connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $st = mysqli_real_escape_string($conn, $_POST['sb']);
    $sql = "SELECT Name, Price, URL FROM items WHERE Price <= '$st'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($rows);
    }
    else {
        // Handle the case where the query fails
        header("HTTP/1.1 500 Internal Server Error");
        echo json_encode(['error' => 'Error in the database query: ' . mysqli_error($conn)]);
    }

}

?>