<!-- edit_user.php -->
<?php
// Include the database connection file
include 'connection.php';

// Check if user ID is provided in the URL
if (isset($_GET['userid'])) {
    $userid = $_GET['userid'];

    // Fetch user details based on the user ID
    $query = "SELECT * FROM users WHERE userid = $userid";
    $result = mysqli_query($conn, $query);
    //$user = mysqli_fetch_assoc($result);
    if ($result) {
        // Fetch the user details into the $user array
        $user = mysqli_fetch_assoc($result);
    } else {
        echo "Error fetching user details: " . mysqli_error($conn);
    }
    // Check if the form is submitted for updating user details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve updated user details from the form
        $name = $_POST['name'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Perform the SQL query to update the user
        $updateQuery = "UPDATE users SET name='$name', username='$username', password='$password' WHERE userid = $userid";
        $updateResult = mysqli_query($conn, $updateQuery);

        // Check if the query was successful
        if ($updateResult) {
            echo "User updated successfully!";
        } else {
            echo "Error updating user: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Other head elements -->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 1em 0;
        }

        .container {
            margin: 20px;
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
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .btn-back {
        background-color: #28a745;
        color: #fff;
        text-decoration: none;
        padding: 10px 15px;
        border-radius: 4px;
        transition: background-color 0.3s;
        }

        .btn-back:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <header>
        <h1>Edit User</h1>
    </header>

    <div class="container">
        
    
    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo isset($user['name']) ? $user['name'] : ''; ?>" required>

        <label for="username">Username:</label>
        <input type="text" name="username" value="<?php echo isset($user['username']) ? $user['username'] : ''; ?>" required>

        <label for="password">Password:</label>
        <input type="password" name="password" value="<?php echo isset($user['password']) ? $user['password'] : ''; ?>" required>

        <input type="submit" value="Update User">
        <a href="admin_page.php" class="btn-back mt-3">Back to Admin Page</a>
    </form>



    </div>
</body>

</html>
