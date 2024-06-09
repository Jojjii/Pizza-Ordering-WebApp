<?php
include 'connection.php';

// Check if the orderid is provided in the URL
if (isset($_GET['orderid'])) {
    $orderid = $_GET['orderid'];

   

    // Check the database connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Delete related records in the order_items table
    $deleteOrderItemsSql = "DELETE FROM orderlist WHERE orderid = $orderid";

    if (mysqli_query($conn, $deleteOrderItemsSql)) {
        // Order items deleted successfully

        // Now, delete the order itself
        $deleteOrderSql = "DELETE FROM orders WHERE orderid = $orderid";

        if (mysqli_query($conn, $deleteOrderSql)) {
            // Order deleted successfully, redirect to the orders page
            header("Location: admin_page.php");
            exit();
        } else {
            // Display an error message if the order deletion fails
            echo "Error deleting order: " . mysqli_error($conn);
        }
    } else {
        // Display an error message if the order items deletion fails
        echo "Error deleting order items: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    // Redirect to the orders page if the orderid is not provided
    header("Location: admin_page.php");
    exit();
}
?>
