<?php

include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newAddress = mysqli_real_escape_string($conn, $_POST['newAddress']);
    $newPhoneNumber = mysqli_real_escape_string($conn, $_POST['newPhoneNumber']);
    $uid = mysqli_real_escape_string($conn, $_POST['uid']);

    
    $sql = "INSERT INTO details (UserId, Address, PhoneNumber) VALUES ('$uid', '$newAddress', '$newPhoneNumber')";

    $result = mysqli_query($conn, $sql);
    if($result){echo 'Successful:  ' . $lastInsertId;
    }
    else{echo 'Error: ' . mysqli_error($conn);}
    // }
    // if ($result) {
    //     $lastInsertId = mysqli_insert_id($conn);
    
    //    if ($lastInsertId > 0) {
    //                 } else {
    //         echo 'Trigger is triggered.';
    //     }
    // } else {
    //         }

    // if (mysqli_query($conn, $sql)) {
    //     echo 'Address added successfully';
    // } else {
        
    //     $errorInfo = mysqli_error($conn);
    //     echo 'Actual Error: ' . $errorInfo;

    //     if (stripos(trim($errorInfo), 'User cannot have more than 3 addresses') !== false) {
    //         echo 'Error: User cannot have more than 3 addresses';
    //     } else {

    //         echo 'Error: ' . $errorInfo;
    //     }
    // }
} else {
    echo 'Invalid request';
}
?>
