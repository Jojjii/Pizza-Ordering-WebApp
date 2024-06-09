<?php
// Include the database connection file
include 'connection.php';
session_start();

if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']) {
    // Check if the deal ID is provided in the URL
    
    if (isset($_GET['dealid'])) {
        $dealId = $_GET['dealid'];
        
        // Fetch deal details from the database
        $fetchDealQuery = "SELECT * FROM deals WHERE dealid = $dealId";
        $dealResult = mysqli_query($conn, $fetchDealQuery);

        if ($dealResult) {
            $deal = mysqli_fetch_assoc($dealResult);

            // Fetch items from the 'items' table
            $fetchItemsQuery = "SELECT itemid, name FROM items";
            $itemsResult = mysqli_query($conn, $fetchItemsQuery);
            $items = mysqli_fetch_all($itemsResult, MYSQLI_ASSOC);

            // Check if the form is submitted
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get form data
                $dealname = $_POST['dealname'];
                $price = $_POST['price'];
                $url = $_POST['url'];
                $quantities = isset($_POST['quantities']) ? $_POST['quantities'] : [];

                // Update 'deals' table
                $updateDealQuery = "UPDATE deals SET dealname = '$dealname', price = $price, URL = '$url' WHERE dealid = $dealId";
                mysqli_query($conn, $updateDealQuery);

                // Delete existing deal items
                $deleteItemsQuery = "DELETE FROM dealitemlist WHERE dealid = $dealId";
                mysqli_query($conn, $deleteItemsQuery);

                // Insert updated deal items
                foreach ($quantities as $itemId => $quantity) {
                    // Check if the quantity is greater than 0 before inserting
                    if ($quantity > 0) {
                        $insertDealItemListQuery = "INSERT INTO dealitemlist (dealid, itemid, quantity) VALUES ($dealId, $itemId, $quantity)";
                        mysqli_query($conn, $insertDealItemListQuery);
                    }
                }

                // Redirect to the deals page or any other appropriate page
                header('Location: admin_page.php');
                exit;
            }
        } else {
            // Handle error fetching deal details
            echo "Error fetching deal details.";
        }
    } else {
        // Deal ID not provided in the URL, handle accordingly
        echo "Deal ID not provided.";
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Deal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            max-width: 400px; /* Adjust the maximum width as needed */
            width: 100%;
        }

        .form-container:hover {
            transform: scale(1.02);
        }

        .form-title {
            text-align: center;
            color: #3498db;
            margin-bottom: 30px;
            font-size: 2.5rem;
        }

        .form-control {
            background: #ecf0f1;
            border: none;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s;
        }

        .form-control:focus {
            outline: none;
            box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
        }

        .form-control-sm {
            width: 60px;
            display: inline-block;
            margin-left: 10px;
        }

        .btn-primary {
            background-color: #3498db;
            border: none;
            padding: 15px;
            width: 100%;
            border-radius: 5px;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #2980b9;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2 class="form-title">Edit Deal</h2>
        <form method="POST" action="">
                <div class="mb-3">
                    <label for="dealname" class="form-label">Deal Name</label>
                    <input type="text" class="form-control" id="dealname" name="dealname" value="<?php if (isset($dealname)){ echo $dealname;} ?>" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" class="form-control" id="price" name="price" value="<?php if (isset($price)){ echo $price;} ?>" required>
                </div>
            <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="url" class="form-control" id="url" name="url" value="<?= $deal['URL'] ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Select Items for Deal</label>
                <?php foreach ($items as $item): ?>
                    <div class="mb-2">
                        <label for="quantity<?= $item['itemid'] ?>" class="form-label"><?= $item['name'] ?></label>
                        <input type="number" class="form-control-sm" id="quantity<?= $item['itemid'] ?>" name="quantities[<?= $item['itemid'] ?>]" min="0" value="<?php echo getQuantityForItem($dealId, $item['itemid']); ?>">
                    </div>

                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-primary">Update Deal</button>
        </form>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Your additional scripts if needed -->
</body>

</html>


<?php
}

// Function to get quantity for a specific item in a deal
function getQuantityForItem($dealId, $itemId) {
    global $conn;
    $fetchQuantityQuery = "SELECT quantity FROM dealitemlist WHERE dealid = $dealId AND itemid = $itemId";
    $quantityResult = mysqli_query($conn, $fetchQuantityQuery);
    $quantity = mysqli_fetch_assoc($quantityResult);

    return $quantity ? $quantity['quantity'] : 0;
}
?>
