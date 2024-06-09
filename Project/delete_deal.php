<?php

include 'connection.php';

if (isset($_GET['dealid'])) {
    $dealId = $_GET['dealid'];


    if ($conn) {
       
        mysqli_begin_transaction($conn);

        try {
            
            $queryToggleActive = "UPDATE deals SET active = NOT active WHERE dealid = $dealId";
            $resultToggleActive = mysqli_query($conn, $queryToggleActive);

            if (!$resultToggleActive) {
                throw new Exception("Error toggling deal status: " . mysqli_error($conn));
            }

            mysqli_commit($conn);
            
            $_SESSION['is_admin'] = $userData['Privileges'] == 1;
            header("Location: admin_page.php");
            exit();
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Transaction failed: " . $e->getMessage();
        }
    } else {
       
        echo "Error connecting to the database.";
    }
} else {
    
    echo "Invalid request. Deal ID not provided.";
}
?>
