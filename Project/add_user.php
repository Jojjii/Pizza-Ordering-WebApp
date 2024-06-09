<?php
// Include the database connection file
include 'connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the "Add User" button is clicked
    //echo $_POST['name'];
    if (isset($_POST['name'])) {
        // Retrieve user details from the form
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        
        // Perform the SQL query to add the user
        $query = "INSERT INTO users (name, username, password) VALUES ('$name', '$username', '$password')";
        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result) {
            // Redirect to the main admin page after adding the user
            header("Location: admin_page.php");
            exit(); // Ensure that no further code is executed after the redirect
        } else {
            echo "Error adding user: " . mysqli_error($conn);
        }
    } elseif (isset($_POST['back'])) {
        // Redirect to the main admin page without considering form fields
        header("Location: admin_page.php");
        exit(); // Ensure that no further code is executed after the redirect
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Other head elements -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 50px;
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
            font-weight: bold;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Add User Form -->
        <form method="post" action="">
            <h2>Add User</h2>

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" name="username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" name="password" required>
            </div>

            <input type="submit" class="btn btn-primary" value="add_user">
            <a href="admin_page.php">Back</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
