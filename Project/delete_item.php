<?php
include 'connection.php';
if (isset($_GET['itemid'])) {
    $itemid = $_GET['itemid'];

    if ($conn) {
        $query = "UPDATE items SET active = NOT active WHERE itemID = $itemid";
        

        if (mysqli_query($conn, $query)) {
    
            echo "Item deleted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

       
    } else {
        echo "Error: Unable to connect to the database";
    }
} else {
    echo "Error: Item ID not provided";
}

header("Location: admin_page.php");
exit();
?>
