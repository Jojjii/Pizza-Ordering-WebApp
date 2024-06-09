<?php
// Include the database connection file
include 'connection.php';

// Check if the catid is provided in the URL
if (isset($_GET['catid'])) {
    $catid = $_GET['catid'];

    // Check if the connection is established
    if ($conn) {
        // Begin a transaction to ensure atomicity (all-or-nothing)
        mysqli_autocommit($conn, false);

        // Prepare a delete query for associated items
        $deleteItemsQuery = "DELETE FROM items WHERE CatID = ?";
        $stmtItems = mysqli_prepare($conn, $deleteItemsQuery);
        mysqli_stmt_bind_param($stmtItems, "i", $catid);

        // Execute the statement
        $success = mysqli_stmt_execute($stmtItems);

        if ($success) {
            // Prepare a delete query for the category
            $deleteCategoryQuery = "DELETE FROM categories WHERE CatID = ?";
            $stmtCategory = mysqli_prepare($conn, $deleteCategoryQuery);
            mysqli_stmt_bind_param($stmtCategory, "i", $catid);

            // Execute the statement
            $success = mysqli_stmt_execute($stmtCategory);

            if ($success) {
                // Commit the transaction if successful
                mysqli_commit($conn);
                echo "Category and associated items deleted successfully!";
            } else {
                // Rollback the transaction if any statement fails
                mysqli_rollback($conn);
                echo "Error: " . mysqli_error($conn);
            }

            // Close the statements
            mysqli_stmt_close($stmtCategory);
        } else {
            // Rollback the transaction if any statement fails
            mysqli_rollback($conn);
            echo "Error: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmtItems);

        // Close the connection
        mysqli_close($conn);
    } else {
        // Failed to connect to the database
        echo "Error: Unable to connect to the database";
    }
} else {
    // Category ID not provided in the URL
    echo "Error: Category ID not provided";
}

// Redirect to the page displaying categories after deletion (adjust the URL accordingly)
header("Location: admin_page.php");
exit();
?>
