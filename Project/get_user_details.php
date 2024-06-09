<?php
// Include the database connection file
include 'connection.php';

// Check if userid is set in the GET request
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    // Fetch user details from the database
    $query = "SELECT * FROM users WHERE userid = $userid";
    $result = mysqli_query($conn, $query);

    // Check if the query was successful
    if ($result) {
        $user = mysqli_fetch_assoc($result);

        // Display user details in the modal body
        echo '<p>User ID: ' . $user['userid'] . '</p>';
        echo '<p>Name: ' . $user['name'] . '</p>';
        echo '<p>Username: ' . $user['username'] . '</p>';
        echo '<p>Password: ' . $user['password'] . '</p>';
        // Add more user details as needed

        // Close the result set
        mysqli_free_result($result);
    } else {
        // Handle the case where the query fails
        echo 'Error fetching user details.';
    }
} else {
    // Handle the case where userid is not set in the GET request
    echo 'User ID not provided.';
}

// Close the database connection
mysqli_close($conn);
?>
