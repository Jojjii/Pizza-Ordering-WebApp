<?php

include 'connection.php';
session_start();


    $username = isset($_GET['username']) ? $_GET['username'] : '';

    function displayUsers()
    {
        global $conn;

        
        if ($conn) {
            $query = "SELECT u.userid, u.name, u.username, u.password, COUNT(o.orderid) AS order_count
                    FROM users u
                    LEFT JOIN orders o ON u.userid = o.userid
                    WHERE u.privileges = 0
                    GROUP BY u.userid, u.name, u.username, u.password";
            $result = mysqli_query($conn, $query);

            echo '&nbsp;Add New User' . '<a href="add_user.php?userid=' . '" class="btn btn-primary btn-sm">' . '&nbsp;&nbsp;&nbsp;<img src="icons\add-user.png" alt="Edit" width="25" height="25">' . '</a>';
            
            echo '<div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Password</th>
                            <th>Order Placed</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['userid'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['username'] . '</td>';
                echo '<td>' . $row['password'] . '</td>';
                echo '<td>' . $row['order_count'] . '</td>';
            
                echo '<td>
    <a href="edit_user.php?userid=' . $row['userid'] . '" class="btn btn-primary btn-sm">' . '<img src="icons\user.png" alt="Edit" width="25" height="25">' . '</a>
    </td>';


                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }
    }

    function displayItems()
    {
        global $conn;

        
        if ($conn) {
            $query = "SELECT itemid, catid, name, flavour, stockcount, price, des, active FROM items";
            $result = mysqli_query($conn, $query);
            echo '&nbsp;Add New Item&nbsp;' . '<a href="add_item.php?userid=' . '" class="btn btn-primary btn-sm">' . '<img src="icons\add-item.png" alt="Edit" width="25" height="25">' . '</a>';
            
            echo '<div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Item ID</th>
                            <th>Category ID</th>
                            <th>Name</th>
                            <th>Flavour</th>
                            <th>Stock Count</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Active</th>
                            <th>Action</th>
                            
                            
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['itemid'] . '</td>';
                echo '<td>' . $row['catid'] . '</td>';
                echo '<td>' . $row['name'] . '</td>';
                echo '<td>' . $row['flavour'] . '</td>';
                echo '<td>' . $row['stockcount'] . '</td>';
                echo '<td>$' . $row['price'] . '</td>';
                echo '<td>' . $row['des'] . '</td>';
                echo '<td>' . ($row['active'] ? 'Yes' : 'No') . '</td>';
             
                echo '<td>
              <a href="edit_item.php?itemid=' . $row['itemid'] . '" class="btn btn-primary btn-sm">' . '<img src="icons\update.png" alt="Edit" width="25" height="25">' . '</a>
              <button class="btn btn-danger btn-sm" onclick="confirmDelete(' . $row['itemid'] . ')">' . '<img src="icons\delete-deal.png" alt="Delete" width="25" height="25">' . '</button>
                  </td>';

                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }
    }

    function displayCategories()
    {
        global $conn;

       
        if ($conn) {
            $query = "SELECT catid, categoryname FROM categories";
            $result = mysqli_query($conn, $query);
            echo '&nbsp;Add New Category&nbsp;' . '<a href="add_category.php' . '" class="btn btn-primary btn-sm">' . '<img src="icons\add.png" alt="Edit" width="25" height="25">' . '</a>';
            
            echo '<div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Category ID</th>
                            <th>Category Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['catid'] . '</td>';
                echo '<td>' . $row['categoryname'] . '</td>';

                
                echo '<td>

              <a href="edit_category.php?catid=' . $row['catid'] . '" class="btn btn-primary btn-sm">' . '<img src="icons\folder.png" alt="Edit" width="25" height="25">' . '</a>
         
                  </td>';

                echo '</tr>';
            }
            // <button class="btn btn-danger btn-sm" onclick="confirmDeletecat(' . $row['catid'] . ')">' . '<img src="icons\delete-item.png" alt="Delete" width="25" height="25">' . '</button>

            echo '</tbody></table></div>';
        }
    }
    function displayOrders()
    {
        global $conn;

        if ($conn) {
            if (isset($_GET['startDate']) && isset($_GET['endDate'])) {
                $startDate = $_GET['startDate'];
                $endDate = $_GET['endDate'];
            
                $query = "SELECT o.orderid, o.userid, o.datetime, o.totalprice, u.username
                        FROM orders o
                        LEFT JOIN users u ON o.userid = u.userid
                        WHERE o.datetime BETWEEN '$startDate' AND '$endDate'";
            } else {
                $query = "SELECT o.orderid, o.userid, o.datetime, o.totalprice, u.username
                        FROM orders o
                        LEFT JOIN users u ON o.userid = u.userid";
            }
            
            $result = mysqli_query($conn, $query);

                echo '<form action="" method="GET">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" name="startDate">

                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" name="endDate">

                <button type="submit">Filter Orders</button>
                <button type="button" onclick="resetFilters()">Reset Filters</button>
                </form>';


            echo '<div class="table-container">
                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Username</th>
                                <th>Date and Time</th>
                                <th>Total Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['orderid'] . '</td>';
                echo '<td>' . $row['username'] . '</td>';
                echo '<td>' . $row['datetime'] . '</td>';
                echo '<td>$' . $row['totalprice'] . '</td>';
       

                echo '<td>
            
            <button class="btn btn-danger btn-sm" onclick="confirmDeleteOrder(' . $row['orderid'] . ')">' . '<img src="icons\delete.png" alt="Delete" width="25" height="25">' . '</button>
            </td>';

                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }
    }

    function displayDeals()
    {
        global $conn;

        if ($conn) {
            $query = "SELECT dealid, dealname, price, active FROM deals";
            $result = mysqli_query($conn, $query);
            echo '&nbsp;Add New Deal&nbsp;' . '<a href="add_deal.php' . '" class="btn btn-primary btn-sm">' . '<img src="icons\add-deal.png" alt="Edit" width="25" height="25">' . '</a>';

            echo '<div class="table-container">
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Deal ID</th>
                            <th>Deal Name</th>
                            <th>Price</th>
                            <th>Active</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>';

            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                echo '<td>' . $row['dealid'] . '</td>';
                echo '<td>' . $row['dealname'] . '</td>';
                echo '<td>$' . $row['price'] . '</td>';
                echo '<td>' . ($row['active'] ? 'Yes' : 'No') . '</td>';
         echo '<td>
                    <a href="edit_deal.php?dealid=' . $row['dealid'] . '" class="btn btn-primary btn-sm">' . '<img src="icons\update-deal.png" alt="Edit" width="25" height="25">' . '</a>
                    <button class="btn btn-danger btn-sm" onclick="confirmDeleteDeal(' . $row['dealid'] . ')">' . '<img src="icons\delete-deal.png" alt="Delete" width="25" height="25">' . '</button>
                    </td>';

                echo '</tr>';
            }

            echo '</tbody></table></div>';
        }
    }



    if (isset($_GET['tab'])) {
        echo '<section id="content">
            <h2>';
        $activeTab = $_GET['tab'];
        if ($activeTab === 'users') {
            echo 'User Management';
            displayUsers();
        } elseif ($activeTab === 'items') {
            echo 'Item Management';
            displayItems();
        } elseif ($activeTab === 'categories') {
            echo 'Category Management';
            displayCategories();
        } elseif ($activeTab === 'orders') {
            echo 'Order Management';
            displayOrders();
        } elseif ($activeTab === 'deals') {
            echo 'Deal Management';
            displayDeals();
        } else {
          
        }

        echo '</h2></section>';
    } else {
       
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5aPvj05NJ6z30lUpj+L49+qlWvGBBGOq26bQlm5a9MHJ1Cr" crossorigin="anonymous">
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

        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            height: 100%;
            width: 250px;
            background-color: #495057;
            padding-top: 1rem;
            padding-bottom: 1rem;
            transition: all 0.3s;
            border-right: 2px solid #fff; /* Add a border on the right side */
        }

        nav ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            text-align: left;
        }

        nav a {
            text-decoration: none;
            color: #fff;
            font-weight: bold;
            font-size: 18px;
            transition: color 0.3s ease, border-left 0.3s ease; /* Add transition for border */
            display: block;
            padding: 0.75rem 1rem;
            border-left: 3px solid transparent; /* Add a transparent border on the left side */
        }

        nav a:hover {
            color: #ffc107;
            border-left: 3px solid #ffc107; /* Change border color on hover */
        }

        .container {
            margin-left: 250px;
            padding: 20px;
        }

        section {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #343a40;
            margin-bottom: 20px;
        }

        footer {
            background-color: #343a40;
            color: #fff;
            text-align: center;
            padding: 1em 0;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        .table-container {
            margin: 20px;
        }

        .styled-table {
            border-collapse: collapse;
            width: 100%;
            margin: 0;
            font-size: 16px;
            text-align: left;
        }

        .styled-table th,
        .styled-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
        }

        .styled-table th {
            background-color: #f2f2f2;
        }

        .styled-table tbody tr:hover {
            background-color: #f5f5f5;
        }
    </style>
  </head>
<body>
    <header class="bg-dark text-white">
        <h1>Welcome to the Admin Panel</h1>
    </header>
    
    <div id="sidebar">
        <nav>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="#"><?php echo $username  ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#users">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#items">Items</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#categories">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#orders">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#deals">Deals</a>
                </li>
            </ul>
        </nav>
    </div>

    <div class="container">
            <div class="tab-content">
                <div class="tab-pane fade" id="users" role="tabpanel">
                    <h2 class="mt-4 mb-4">User Management</h2>
                    <div id="usersContent">
                        <?php displayUsers(); ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="items" role="tabpanel">
                    <h2 class="mt-4 mb-4">Item Management</h2>
                    <div id="itemsContent">
                        <?php displayItems(); ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="categories" role="tabpanel">
                    <h2 class="mt-4 mb-4">Category Management</h2>
                    <div id="categoriesContent">
                            <?php displayCategories(); ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="orders" role="tabpanel">
                    <h2 class="mt-4 mb-4">Order Management</h2>
                    <div id="categoriesContent">
                            <?php displayOrders(); ?>
                    </div>
                </div>
                <div class="tab-pane fade" id="deals" role="tabpanel">
                    <h2 class="mt-4 mb-4">Deal Management</h2>
                    <div id="categoriesContent">
                            <?php displayDeals(); ?>
                    </div>
                </div>
            </div>
        </div>

    <footer class="bg-dark text-white text-center">
        &copy; JD Bros Limited 
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.7.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
     
     function toggleSidebar() {
         const sidebar = document.getElementById('sidebar');
         sidebar.classList.toggle('active');
     }

     function confirmDelete(itemId) {
         var result = confirm("Are you sure you want to delete this item?");
         if (result) {
             window.location.href = 'delete_item.php?itemid=' + itemId;
         }
     }

     function confirmDeleteuser(userId) {
         var result = confirm("Are you sure you want to delete this user?");
         if (result) {
             window.location.href = 'delete_user.php?userid=' + userId;
         }
     }

     function confirmDeleteCategory(catId) {
         var result = confirm("Are you sure you want to delete this category?");
         if (result) {
             window.location.href = 'delete_category.php?catid=' + catId;
         }
     }
     
     function confirmDeleteOrder(orderId) {
             var result = confirm("Are you sure you want to delete this order?");
             if (result) {
                 window.location.href = 'delete_order.php?orderid=' + orderId;
             }
     }

     function confirmDeleteDeal(dealId) {
         var result = confirm("Are you sure you want to deactivate this deal?");
         if (result) {
             window.location.href = 'delete_deal.php?dealid=' + dealId;
         }
     }
     
     function resetFilters() {
         document.getElementById("startDate").value = "";
         document.getElementById("endDate").value = "";
     }

 </script>
    
</body>
</html>

