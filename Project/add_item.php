<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $flavour = $_POST['flavour'];
    $stockcount = $_POST['stockcount'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $catid = $_POST['catid'];

    $checkCategoryQuery = "SELECT * FROM categories WHERE catid = $catid";
    $checkCategoryResult = mysqli_query($conn, $checkCategoryQuery);

    if (mysqli_num_rows($checkCategoryResult) > 0) {
        $query = "INSERT INTO items (catid, name, flavour, stockcount, price, des, url)
                  VALUES ('$catid', '$name', '$flavour', '$stockcount', '$price', '$description', '$url')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            header("Location: admin_page.php");
            exit();
        } else {
            echo "Error adding item: " . mysqli_error($conn);
        }
    } else {
        echo "Error: Category ID $catid does not exist.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Temporary Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        textarea {
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
        <form method="post" action="">
            <h2>Add Item</h2>

            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" required>
            </div>

            <div class="mb-3">
                <label for="flavour" class="form-label">Flavour:</label>
                <input type="text" class="form-control" name="flavour" required>
            </div>

            <div class="mb-3">
                <label for="stockcount" class="form-label">Stock Count:</label>
                <input type="number" class="form-control" name="stockcount" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price:</label>
                <input type="number" class="form-control" name="price" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description:</label>
                <textarea class="form-control" name="description" rows="3" required></textarea>
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">URL:</label>
                <input type="text" class="form-control" name="url" required>
            </div>

            <div class="mb-3">
                <label for="catid" class="form-label">Category ID:</label>
                <input type="number" class="form-control" name="catid" required>
            </div>

            <input type="submit" class="btn btn-primary" value="Add Item">
            <a href="admin_page.php">Back</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
