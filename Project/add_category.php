<?php
// Include the database connection file
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle the form submission to add a new category
    // Ensure to validate and sanitize user input before inserting into the database
    $categoryID = mysqli_real_escape_string($conn, $_POST['categoryID']);
    $categoryName = mysqli_real_escape_string($conn, $_POST['categoryName']);

    // Check if the connection is established
    if ($conn) {
        // Check if the category ID already exists
        $checkQuery = "SELECT * FROM categories WHERE catid = '$categoryID'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            // Category ID already exists
            echo "Error: Category ID already exists!";
        } else {
            // Insert new category into the 'categories' table
            $query = "INSERT INTO categories (catid, categoryname) VALUES ('$categoryID', '$categoryName')";

            if (mysqli_query($conn, $query)) {
                // Successful category addition
                echo "Category added successfully!";
            } else {
                // Failed to add category
                echo "Error: " . mysqli_error($conn);
            }

            header("Location: admin_page.php");
        }
    } else {
        // Failed to connect to the database
        echo "Error: Unable to connect to the database";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Category</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h2 {
            text-align: center;
            color: #007bff;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #495057;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: inline-block;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    
    <form method="post" action="">
    <h2>Add New Category</h2>
        <label for="categoryID">Category ID:</label>
        <input type="text" id="categoryID" name="categoryID" required>
        
        <label for="categoryName">Category Name:</label>
        <input type="text" id="categoryName" name="categoryName" required>
        
        <button type="submit">Add Category</button>
    </form>
</body>
</html>
