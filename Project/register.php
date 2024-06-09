<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the stored procedure to check if the username exists
    $checkUsernameQuery = "CALL CheckUsernameExists('$username', @exists)";
    mysqli_query($conn, $checkUsernameQuery);

    // Retrieve the result from the user-defined variable
    $checkUsernameResult = mysqli_query($conn, 'SELECT @exists AS username_exists');
    $row = mysqli_fetch_assoc($checkUsernameResult);
    $usernameExists = (bool)$row['username_exists'];

    if ($usernameExists) {
        // Username is already taken, display an error message
        echo '<div style="color: red; ">Username is already taken. Please choose another username.</div>';
    } else {
        // Username is not taken, proceed with the registration
        $sql = "INSERT INTO users (Name, Username, Password, Privileges) VALUES ('$name', '$username', '$password', 0)";

        if (mysqli_query($conn, $sql)) {
            header("Location: index.php?username=" . urlencode($username));
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fancy Sign-Up Page</title>
    <style>
        body {
            background-color: darkblue;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .signup-container {
            background-color: black;
            color: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
        }

        .form-control {
            margin-bottom: 15px;
        }

        input {
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
        }

        button {
            background-color: yellow;
            color: black;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: gold;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="register.php" method="POST">
            <div class="form-control">
                <input type="text" name="name" placeholder="Full Name" required>
            </div>
            <div class="form-control">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="form-control">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <?php
            // Add PHP code for checking if the username is already taken and display a message accordingly.
        ?>
    </div>
</body>
</html>
