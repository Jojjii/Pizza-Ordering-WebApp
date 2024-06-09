<?php
// Include the database connection file
include 'connection.php';

// Check if the user ID is provided in the URL
if (isset($_GET['itemid'])) {
    $itemid = $_GET['itemid'];

    // Fetch item details based on the item ID
    $query = "SELECT * FROM items WHERE itemid = $itemid";
    $result = mysqli_query($conn, $query);
    $item = mysqli_fetch_assoc($result);

    // Check if the form is submitted for updating item details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve updated item details from the form
        $name = $_POST['name'];
        $flavour = $_POST['flavour'];
        $stockcount = $_POST['stockcount'];
        $price = $_POST['price'];
        $description = $_POST['description'];
        $url = $_POST['url'];
        $catid = $_POST['catid'];

        // Check if the provided category ID exists in the categories table
        $checkCategoryQuery = "SELECT * FROM categories WHERE catid = $catid";
        $checkCategoryResult = mysqli_query($conn, $checkCategoryQuery);

        if (mysqli_num_rows($checkCategoryResult) > 0) {
            // Category ID exists, proceed with updating the item
            // Perform the SQL query to update the item
            $updateQuery = "UPDATE items SET name='$name', flavour='$flavour', stockcount='$stockcount', 
                            price='$price', description='$description', url='$url', catid='$catid' 
                            WHERE itemid = $itemid";
            $updateResult = mysqli_query($conn, $updateQuery);

            // Check if the query was successful
            if ($updateResult) {
                echo '<div class="alert alert-success" role="alert">
                        Item updated successfully!
                      </div>';
            } else {
                echo '<div class="alert alert-danger" role="alert">
                        Error updating item: ' . mysqli_error($conn) . '
                      </div>';
            }
        } else {
            // Category ID does not exist in the categories table
            echo '<div class="alert alert-danger" role="alert">
                    Error: Category ID ' . $catid . ' does not exist.
                  </div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            color: #007bff;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
            resize: none;
        }

        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
    <script>
        function confirmDelete(itemId) {
            var result = confirm("Are you sure you want to delete this item?");
            if (result) {
                window.location.href = 'delete_item.php?itemid=' + itemId;
            }
        }
    </script>
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">Edit Item</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name:</label>
                        <input type="text" class="form-control" name="name" value="<?php if(isset($item['name'])){ echo $item['name'];} ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="flavour" class="form-label">Flavour:</label>
                        <input type="text" class="form-control" name="flavour" value="<?php if(isset($item['flavour'])){ echo $item['flavour']; }?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="stockcount" class="form-label">Stock Count:</label>
                        <input type="number" class="form-control" name="stockcount" value="<?php if(isset($item['stockcount'])){ echo $item['stockcount']; } ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Price:</label>
                        <input type="number" class="form-control" name="price" value="<?php if(isset($item['price'])){ echo $item['price'];} ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" name="description" required><?php if(isset($item['description'])){ echo $item['description']; } ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="url" class="form-label">URL:</label>
                        <input type="text" class="form-control" name="url" value="<?php if(isset($item['url'])){ echo $item['url'];} ?>" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Item</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
