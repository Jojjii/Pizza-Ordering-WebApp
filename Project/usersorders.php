<?php
// Include your database connection file
include 'connection.php';

// Check if the user is logged in
session_start();
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Fetch user orders with details and item information using SQL joins
    $sql = "SELECT orders.OrderID, orders.TotalPrice, orderlist.Quantity, items.Name AS ItemName, items.Flavour, items.Price, items.URL
            FROM orders
            INNER JOIN orderlist ON orders.OrderID = orderlist.OrderID
            INNER JOIN items ON orderlist.ItemID = items.ItemID
            WHERE orders.UserID = $userId";

    $result = mysqli_query($conn, $sql);

    if ($result) {
        // Initialize variables to track the current order ID
        $currentOrderId = null;
        $firstIteration = true;

        echo '<div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto;">';

        while ($row = mysqli_fetch_assoc($result)) {
            // Check if the order ID has changed
            if ($currentOrderId !== $row['OrderID']) {
                // If not the first iteration, close the previous section
                if (!$firstIteration) {
                    echo '</ul>';
                    echo '</div>';
                }

                // Start a new section for the current order
                echo '<div style="background-color: #f8f9fa; padding: 15px; margin: 15px 0; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">';
                echo '<h3>Order ID: ' . $row['OrderID'] . '</h3>';
                echo '<p>Total Price: $' . $row['TotalPrice'] . '</p>';
                echo '<ul style="list-style: none; padding: 0;">'; // Start a nested list for order details
                $currentOrderId = $row['OrderID'];
            }

            // Display order details for the current item
            echo '<li style="border-bottom: 1px solid #ccc; padding: 10px; display: flex; align-items: center;">';
            echo '<div style="flex-shrink: 0; margin-right: 10px;">';
            echo '<img src="' . $row['URL'] . '" alt="' . $row['ItemName'] . '" style="max-width: 80px; max-height: 80px; border-radius: 5px;">';
            echo '</div>';
            echo '<div>';
            echo '<h4 style="margin: 0;">' . $row['ItemName'] . '</h4>';
            echo '<p style="margin: 0;">Flavour: ' . $row['Flavour'] . '</p>';
            echo '<p style="margin: 0;">Price: $' . $row['Price'] . '</p>';
            echo '<p style="margin: 0;">Quantity: ' . $row['Quantity'] . '</p>';
            echo '</div>';
            echo '</li>';

            // Set firstIteration to false after the first iteration
            $firstIteration = false;
        }

        // Close the last section
        echo '</ul>';
        echo '</div>';
        echo '</div>';
    } else {
        echo 'Error querying user orders';
    }
} else {
    echo 'User not logged in';
}
?>
