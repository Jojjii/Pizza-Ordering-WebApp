<?php
// Connect to your database (assuming you haven't included this part)

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dealid'])) {
    $dealId = $_POST['dealid'];

    // Fetch deal details from your database using $dealId
    // Replace the following with actual SQL query and data fetching logic
    $dealDetails = "Details for Deal ID $dealId"; 

    // Display the fetched details (you can customize this part based on your actual data structure)
    echo $dealDetails;
} else {
    // Handle invalid request
    echo "Invalid request";
}
?>
