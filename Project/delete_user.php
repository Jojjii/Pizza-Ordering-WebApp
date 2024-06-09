<?php
include 'connection.php';

if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    if ($conn) {

        mysqli_autocommit($conn, false);

        $selectOrdersQuery = "SELECT orderid FROM orders WHERE userid = ?";
        $stmtSelectOrders = mysqli_prepare($conn, $selectOrdersQuery);
        mysqli_stmt_bind_param($stmtSelectOrders, "i", $userid);

        mysqli_stmt_execute($stmtSelectOrders);
        $resultOrders = mysqli_stmt_get_result($stmtSelectOrders);

        $deleteOrdersQuery = "DELETE FROM orders WHERE orderid = ?";
        $stmtDeleteOrders = mysqli_prepare($conn, $deleteOrdersQuery);
        mysqli_stmt_bind_param($stmtDeleteOrders, "i", $orderid);

        while ($rowOrders = mysqli_fetch_assoc($resultOrders)) {
            $orderid = $rowOrders['orderid'];
            mysqli_stmt_execute($stmtDeleteOrders);
        }

        $deleteUserQuery = "DELETE FROM users WHERE userid = ?";
        $stmtDeleteUser = mysqli_prepare($conn, $deleteUserQuery);
        mysqli_stmt_bind_param($stmtDeleteUser, "i", $userid);

        
        if (mysqli_stmt_execute($stmtDeleteUser)) {
        
            mysqli_commit($conn);
            echo "User and associated orders deleted successfully!";
        } else {
            
            mysqli_rollback($conn);
            echo "Error: " . mysqli_error($conn);
        }

        
        mysqli_stmt_close($stmtSelectOrders);
        mysqli_stmt_close($stmtDeleteOrders);
        mysqli_stmt_close($stmtDeleteUser);

        
        mysqli_close($conn);
    } else {
       
        echo "Error: Unable to connect to the database";
    }
} else {
    
    echo "Error: User ID not provided";
}


header("Location: admin_page.php");
exit();
?>
