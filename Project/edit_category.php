<?php
include 'connection.php';

// Check if the category ID is provided in the URL
if (isset($_GET['catid'])) {
    $catid = $_GET['catid'];

    // Fetch category details based on the category ID
    $query = "SELECT * FROM categories WHERE catid = $catid";
    $result = mysqli_query($conn, $query);
    $category = mysqli_fetch_assoc($result);

    // Check if the form is submitted for updating category details
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve updated category details from the form
        $categoryname = $_POST['categoryname'];

        // Perform the SQL query to update the category
        $updateQuery = "UPDATE categories SET categoryname = '$categoryname' WHERE catid = $catid";
        $updateResult = mysqli_query($conn, $updateQuery);

        // Check if the query was successful
        if ($updateResult) {
            echo '<div class="alert alert-success" role="alert">
                    Category updated successfully!
                  </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
                    Error updating category: ' . mysqli_error($conn) . '
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
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: #fff;
            border-bottom: none;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
        }

        .card-body {
            padding: 20px;
        }

        .form-label {
            font-weight: bold;
            color: #495057;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2 class="mb-0">Edit Category</h2>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="categoryname" class="form-label">Category Name:</label>
                        <input type="text" class="form-control" name="categoryname" value="<?php if (isset($category['categoryname'])) { echo $category['categoryname']; } ?>" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
