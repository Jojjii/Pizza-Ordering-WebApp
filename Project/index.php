<?php
include 'connection.php';

$uid = 0;


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve user input
    $username = $_POST['username'];
    $password = $_POST['password'];


    $sql = "SELECT UserID, Password, Privileges FROM users WHERE Username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $userData = mysqli_fetch_assoc($result);
        $uid = $userData["UserID"];
        if ($userData && $password === $userData['Password']) {
            // Password is correct, set user session or perform other actions
            session_start();
            $_SESSION['user_id'] = $userData['UserID'];
            $_SESSION['username'] = $username;
            $_SESSION['is_admin'] = $userData['Privileges'] == 1;
            if ($_SESSION['is_admin']) {
                // Redirect to admin page if privileges are 1
                header("Location: admin_page.php?username=" . urlencode($username));
                exit();
            }
            
        } else {
            $loginError = 'Invalid username or password';
        }
    } else {
        $loginError = 'Error querying the database';
    }
}



if (isset($_SESSION['user_id']) || isset($_GET['user_id'])) {

    if($uid===0){
    
    session_start();
        $uid = $_GET['user_id'];

    $sql = "SELECT  username, Privileges FROM users WHERE UserID = $uid";
    $result = mysqli_query($conn, $sql);
    $s = mysqli_fetch_assoc($result);
    $_SESSION['user_id'] = $uid;
    $_SESSION['username'] = $s['username'];
    $username = $s['username'];
    $_SESSION['is_admin'] = $s['Privileges'] == 1;
    }
    ?>


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pizza Peace</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
        <style>
            @import url("https://fonts.googleapis.com/css?family=Roboto");

            @-webkit-keyframes come-in {
                0% {
                    -webkit-transform: translatey(100px);
                    transform: translatey(100px);
                    opacity: 0;
                }

                30% {
                    -webkit-transform: translateX(-50px) scale(0.4);
                    transform: translateX(-50px) scale(0.4);
                }

                70% {
                    -webkit-transform: translateX(0px) scale(1.2);
                    transform: translateX(0px) scale(1.2);
                }

                100% {
                    -webkit-transform: translatey(0px) scale(1);
                    transform: translatey(0px) scale(1);
                    opacity: 1;
                }
            }

            @keyframes come-in {
                0% {
                    -webkit-transform: translatey(100px);
                    transform: translatey(100px);
                    opacity: 0;
                }

                30% {
                    -webkit-transform: translateX(-50px) scale(0.4);
                    transform: translateX(-50px) scale(0.4);
                }

                70% {
                    -webkit-transform: translateX(0px) scale(1.2);
                    transform: translateX(0px) scale(1.2);
                }

                100% {
                    -webkit-transform: translatey(0px) scale(1);
                    transform: translatey(0px) scale(1);
                    opacity: 1;
                }
            }


            body,
            html {
                height: 100%;
                margin: 0;
                padding: 0;
            }

            #loadingOverlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.8);

                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;

            }

            .card-img-top {
                object-fit: cover;
                height: 200px;
            }

            .logo {
                max-width: 50px;
                height: auto;
                border-radius: 30px;
                border-color: red;
                background-color: black;
                margin-right: 10px;
            }

            .cart-icon {
                max-width: 50px;
                height: auto;
                border-radius: 30px;
                border-color: red;
                background-color: black;
                cursor: pointer;
            }

            .featured-card {
                margin-bottom: 20px;
                /* Adjust the margin as needed */
            }

            .navbar {
                position: sticky;
                overflow: hidden;
                top: 0;
                z-index: 1000;
            }


            .header-container {
                background: url('https://hips.hearstapps.com/hmg-prod/images/classic-cheese-pizza-recipe-2-64429a0cb408b.jpg?crop=0.6666666666666667xw:1xh;center,top&resize=1200:*') center center/cover;
                /* Background image property */
                color: #ffffff;
                /* White text color */
                padding: 50px 0;
                text-align: center;
                position: relative;
            }

            .header-overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.5);
                /* Overlay color with transparency */

            }

            .category-container {
                background-color: #f8f9fa;
                /* Light background color */
                padding: 20px 0;
                text-align: center;
            }

            .category-button {
                margin: 0 10px;
                padding: 10px 20px;
                font-size: 1.8rem;
                background-color: red;
                /* Button color */
                color: white;
                /* Button text color */
                border: none;
                cursor: pointer;
                border-radius: 5px;
                transition: background-color 0.3s;
            }

            .category-button:hover {
                background-color: black;
                /* Button color on hover */
            }

            .txtcap {
                color: greenyellow;

            }

            .header-text {
                font-size: 2.5rem;
                /* Adjust font size */
                font-weight: bold;
                /* Make the text bold */
                color: white;
                z-index: 1;
                /* Ensure text is above the overlay */
                position: relative;
            }

            .header-subtext {
                font-size: 1.5rem;
                /* Adjust font size */
                font-weight: bold;
                color: white;
                z-index: 1;
                /* Ensure text is above the overlay */
                position: relative;
            }






            * {
                margin: 0;
                padding: 0;
            }

            html,
            body {
                background: #eaedf2;
                font-family: 'Roboto', sans-serif;
            }

            .floating-container {
                position: fixed;
                width: 100px;
                height: 100px;
                bottom: 0;
                right: 0;
                margin: 35px 25px;
            }

            .floating-container:hover {
                height: 300px;
            }

            .floating-container:hover .floating-button {
                box-shadow: 0 10px 25px rgba(44, 179, 240, 0.6);
                -webkit-transform: translatey(5px);
                transform: translatey(5px);
                -webkit-transition: all 0.3s;
                transition: all 0.3s;
            }

            .floating-container:hover .element-container .float-element:nth-child(1) {
                -webkit-animation: come-in 0.4s forwards 0.2s;
                animation: come-in 0.4s forwards 0.2s;
            }

            .floating-container:hover .element-container .float-element:nth-child(2) {
                -webkit-animation: come-in 0.4s forwards 0.4s;
                animation: come-in 0.4s forwards 0.4s;
            }

            .floating-container:hover .element-container .float-element:nth-child(3) {
                -webkit-animation: come-in 0.4s forwards 0.6s;
                animation: come-in 0.4s forwards 0.6s;
            }

            .floating-container .floating-button {
                position: absolute;
                width: 65px;
                height: 65px;
                background: #2cb3f0;
                bottom: 0;
                border-radius: 50%;
                left: 0;
                right: 0;
                margin: auto;
                color: white;
                line-height: 65px;
                text-align: center;
                font-size: 23px;
                z-index: 100;
                box-shadow: 0 10px 25px -5px rgba(44, 179, 240, 0.6);
                cursor: pointer;
                -webkit-transition: all 0.3s;
                transition: all 0.3s;
            }

            .floating-container .float-element {
                position: relative;
                display: block;
                border-radius: 50%;
                width: 50px;
                height: 50px;
                margin: 15px auto;
                color: white;
                font-weight: 500;
                text-align: center;
                line-height: 50px;
                z-index: 0;
                opacity: 0;
                cursor: pointer;
                -webkit-transform: translateY(100px);
                transform: translateY(100px);
            }

            .floating-container .float-element:hover {
                transform: scale(1.2);
            }

            .floating-container .float-element .material-icons {
                vertical-align: middle;
                font-size: 16px;
            }

            .floating-container .float-element:nth-child(1) {
                background: #42A5F5;
                box-shadow: 0 20px 20px -10px rgba(66, 165, 245, 0.5);
            }

            .floating-container .float-element:nth-child(2) {
                background: #4CAF50;
                box-shadow: 0 20px 20px -10px rgba(76, 175, 80, 0.5);
            }

            .floating-container .float-element:nth-child(3) {
                background: #FF9800;
                box-shadow: 0 20px 20px -10px rgba(255, 152, 0, 0.5);
            }

            /* .element-container .elem:hover{
                                            transform: scale(1.2); 
       
                                        } */
            #budgetRange {
                width: 80%;
                margin-bottom: 20px;
            }

            #budgetValue {
                display: inline-block;
                margin-left: 10px;
            }

            #searchButton {
                display: block;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }

            #modal {
                background: white;
                padding: 20px;
                border-radius: 10px;
                opacity: 1;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                justify-content: center;
                align-items: center;
                z-index: 2;
            }

            .search-modal {
                background-color: #fff;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                width: 300px;
            }

            .close-icon {
                cursor: pointer;
            }

            input[type="text"] {
                width: 100%;
                padding: 10px;
                margin-bottom: 10px;
                box-sizing: border-box;
                border: 1px solid #ccc;
                border-radius: 5px;
            }

            button {
                padding: 10px;
                background-color: #4CAF50;
                color: #fff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- Navigation Bar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <img src="https://img.freepik.com/free-photo/appetizing-slice-pizza-flat-lay-generative-ai_169016-28936.jpg?size=338&ext=jpg&ga=GA1.1.1880011253.1699488000&semt=ais"
                    alt="Pizza Place Logo" class="logo">
                <a class="navbar-brand" href="#">Pizza Peace</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">

                        <li class="nav-item">
                            <a class="nav-link" href="#top">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#myFooter">Contact</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" onclick="showUserOrders()">My Orders</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#"><?php echo $username ?></a>
                        </li>
                        <li class="nav-item">
                            <img src="https://static.vecteezy.com/system/resources/thumbnails/004/798/846/small/shopping-cart-logo-or-icon-design-vector.jpg"
                                alt="Cart Icon" class="cart-icon" data-bs-toggle="modal" data-bs-target="#cartModal">
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <header class="header-container text-center">
            <div class="header-overlay"></div>
            <div class="container">
                <h1 class="header-text"><strong>PIZZA PEACE</strong></h1>
                <p class="header-subtext">HAVE A <span class="txtcap"><strong>PEACE</strong></span> OF YOUR FAVORITE <span
                        class="txtcap"><strong>PIZZA</strong></span>!</p>
            </div>
        </header>


        <div class="category-container">
            <button type="button" class="btn btn-outline-info mx-5" style="width: 120px;"
                onclick="showCategory('Pizza')"><strong>PIZZAS</strong></button>
            <button type="button" class="btn btn-outline-info mx-5" style="width: 120px;"
                onclick="showCategory('Calzone')"><strong>CALZONES</strong></button>
            <button type="button" class="btn btn-outline-info mx-4" style="width: 120px;"
                onclick="showCategory('Beverages')"><strong>BEVERAGES</strong></button>
            <button type="button" class="btn btn-outline-info mx-4" style="width: 120px;"
                onclick="showCategory('Deals')"><strong>DEALS</strong></button>
        </div>

        <div class="overlay" id="filterover">
            <div id="modal">
                <span class="close-icon" onclick="closeBudgetModal()">
                    <i class="material-icons">close_small
                    </i></span>
                <h2>Budget Filter</h2>
                <label for="budgetRange">Select your budget:</label>
                <input type="range" id="budgetRange" min="0" max="50" value="25">
                <span id="budgetValue">25</span>
                <button id="searchButton" onclick="submitBudget()">Search</button>
            </div>
        </div>

        <div class="floating-container">
            <div class="floating-button">+</div>
            <div class="element-container">

                <span class="float-element tooltip-left">
                    <span class="search-icon" onclick="openSearchModal()">
                        <i class="material-icons">search
                        </i>
                    </span>
                </span>


                <span class="float-element">
                    <span class="fltr" onclick="openFilter()">
                        <i class="material-icons">filter_alt
                        </i>
                    </span>
                </span>


                <span class="float-element">
                    <span class="clr" onclick="clearFilter()">
                        <i class="material-icons">filter_alt_off
                        </i>
                    </span>
                </span>

            </div>
        </div>
        <div class="overlay" id="searchOverlay">
            <div class="search-modal">
                <span class="close-icon" onclick="closeSearchModal()">
                    <i class="material-icons">close_small
                    </i></span>
                <input type="text" id="searchInput" placeholder="Enter keywords">
                <button onclick="performSearch()">Search</button>
            </div>
        </div>


        <!-- Featured Pizzas Section -->
        <section class="container my-5" id="Pizza">
            <h2 class="text-center mb-4">Featured Pizzas</h2>
            <div class="row">
                <?php
                $sql = "SELECT * FROM items where items.CatID=1 and items.active = 1";
                $result = mysqli_query($conn, $sql);

                if ($result->num_rows > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col-md-4">
                            <div class="card featured-card">
                                <img src="<?php echo $rows['URL']; ?>" class="card-img-top" alt="Supreme Pizza">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $rows['Name'] ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo $rows['Des'] ?>
                                    </p>
                                    <p class="card-price"><strong>$
                                            <?php echo $rows['Price'] ?>
                                        </strong></p>
                                    <div class="input-group mb-3">
                                        <input type="number" id="quantityInput_<?php echo $rows['Name'] ?>" value="1" min="1"
                                            max="10" class="form-control mb-3">
                                        <button type="button" class="btn btn-success btn-sm" style="height: 38px;"
                                            onclick="addToCartHandler('<?php echo $rows['Name'] ?>',<?php echo $rows['Price'] ?>)">Add
                                            to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                } ?>

            </div>
        </section>
        <section class="container my-5" id="Calzone" style="display: none;">
            <h2 class="text-center mb-4">Calzone</h2>
            <div class="row">
                <?php
                $sql = "SELECT * FROM items where items.CatID=2 and items.active = 1";
                $result = mysqli_query($conn, $sql);

                if ($result->num_rows > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col-md-4">
                            <div class="card featured-card">
                                <img src="<?php echo $rows['URL']; ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $rows['Name'] ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo $rows['Des'] ?>
                                    </p>
                                    <p class="card-price"><strong>$
                                            <?php echo $rows['Price'] ?>
                                        </strong></p>
                                    <div class="input-group mb-3">
                                        <input type="number" id="quantityInput_<?php echo $rows['Name'] ?>" value="1" min="1"
                                            max="10" class="form-control mb-3">
                                        <button type="button" class="btn btn-success btn-sm" style="height: 38px;"
                                            onclick="addToCartHandler('<?php echo $rows['Name'] ?>',<?php echo $rows['Price'] ?>)">Add
                                            to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                } ?>

            </div>
        </section>

        <section class="container my-5" id="Beverages" style="display: none;">
            <h2 class="text-center mb-4">Beverages</h2>
            <div class="row">
                <?php
                $sql = "SELECT * FROM items where items.CatID=3 and items.active = 1";
                $result = mysqli_query($conn, $sql);

                if ($result->num_rows > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col-md-4">
                            <div class="card featured-card">
                                <img src="<?php echo $rows['URL']; ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $rows['Name'] ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo $rows['Des'] ?>
                                    </p>
                                    <p class="card-price"><strong>$
                                            <?php echo $rows['Price'] ?>
                                        </strong></p>
                                    <div class="input-group mb-3">
                                        <input type="number" id="quantityInput_<?php echo $rows['Name'] ?>" value="1" min="1"
                                            max="10" class="form-control mb-3">
                                        <button type="button" class="btn btn-success btn-sm" style="height: 38px;"
                                            onclick="addToCartHandler('<?php echo $rows['Name'] ?>',<?php echo $rows['Price'] ?>)">Add
                                            to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                } ?>

            </div>
        </section>

        <section class="container my-5" id="Deals" style="display: none;">
            <h2 class="text-center mb-4">Deals</h2>
            <div class="row">
                <?php
                $sql = "SELECT * FROM deals where active = 1";
                $result = mysqli_query($conn, $sql);

                if ($result->num_rows > 0) {
                    while ($rows = mysqli_fetch_assoc($result)) {
                        ?>
                        <div class="col-md-4">
                            <div class="card featured-card">
                                <img src="<?php echo $rows['URL']; ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo $rows['DealName'] ?>
                                    </h5>
                                    <p class="card-price"><strong>$
                                            <?php echo $rows['Price'] ?>
                                        </strong></p>
                                    <div class="input-group mb-3">
                                        <input type="number" id="quantityInput_<?php echo $rows['DealName'] ?>" value="1" min="1"
                                            max="10" class="form-control mb-3">
                                        <button type="button" class="btn btn-success btn-sm" style="height: 38px;"
                                            onclick="addToCartHandler('<?php echo $rows['DealName'] ?>',<?php echo $rows['Price'] ?>)">Add
                                            to Cart</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php }
                } ?>

            </div>
        </section>
        <!-- Cart Modal -->
        <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="cartModalLabel">Shopping Cart</h5>
                        <button type="button" class="btn-close" aria-label="Close" onclick="closeCartModal()"></button>
                    </div>
                    <div class="modal-body" id="cartItems">
                        <!-- Cart items will be dynamically added here -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeCartModal()">Close</button>
                        <button type="button" class="btn btn-primary" onclick="showCheckoutModal()">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add to Cart Modal -->
        <div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addToCartModalLabel">Add to Cart</h5>
                        <button type="button" class="btn-close" aria-label="Close" onclick="closeAddToCartModal()"></button>
                    </div>
                    <div class="modal-body">
                        <p>Choose quantity:</p>
                        <input type="number" id="quantityInput" value="1" min="1" max="10" class="form-control mb-3">
                        <button type="button" class="btn btn-primary" onclick="addToCartHandler()">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkoutModalLabel">Checkout</h5>
                        <button type="button" class="btn-close" aria-label="Close" onclick="closeCheckoutModal()"></button>
                    </div>
                    <div class="modal-body" id="checkoutItems">
                        <!-- Checkout items will be dynamically added here -->
                    </div>
                    <h5>Choose an Address:</h5>
                    <select id="addressDropdown" class="form-select mb-3">
                        <?php
                        $sql1 = "SELECT DetailId,PhoneNumber, Address FROM details WHERE UserID = $uid";
                        $result1 = mysqli_query($conn, $sql1);

                        if ($result1->num_rows > 0) {
                            while ($rows1 = mysqli_fetch_assoc($result1)) {
                                echo '<option value="' . $rows1['DetailId'] . '">' . $rows1['Address'] . ' , ' . $rows1['PhoneNumber'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <button type="button" class="btn btn-primary" onclick="showNewAddress()">Change</button>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeCheckoutModal()">Back to Cart</button>
                        <button type="button" class="btn btn-primary" onclick="placeOrder()">Place Order</button>
                    </div>
                </div>
            </div>
        </div>
        <section class="container my-5" id="Res" style="display: none;">
            <h2 class="text-center mb-4">Search Results</h2>
            <div id="searchResultsContainer"></div>
        </section>

        <!-- New Address Modal -->
        <div class="modal fade" id="newAddressModal" tabindex="-1" aria-labelledby="newAddressModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newAddressModalLabel">Add New Address</h5>
                        <button type="button" class="btn-close" aria-label="Close"
                            onclick="closeNewAddressModal()"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for adding new address -->
                        <form id="newAddressForm">
                            <div class="mb-3">
                                <label for="newAddress" class="form-label">Address:</label>
                                <input type="text" class="form-control" id="newAddress" name="newAddress" required>
                            </div>
                            <div class="mb-3">
                                <label for="newPhoneNumber" class="form-label">Phone Number:</label>
                                <input type="text" class="form-control" id="newPhoneNumber" name="newPhoneNumber" required>
                            </div>

                            <input type="hidden" name="uid" value="<?php echo $uid; ?>">

                            <button type="button" class="btn btn-primary" onclick="addNewAddress()">Add Address</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <!-- Bootstrap JS Script -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <script>
            var budgetRange = document.getElementById('budgetRange');
            var budgetValue = document.getElementById('budgetValue');

            budgetRange.addEventListener('input', function () {
                budgetValue.textContent = budgetRange.value;
            });

            function openFilter() {
                var fltr = document.getElementById('filterover');
                fltr.style.display = 'flex';

            }

            function closeBudgetModal() {

                var fltr = document.getElementById('filterover');
                fltr.style.display = 'none';

            }


            function submitBudget() {
                var selectedBudget = parseInt(budgetRange.value);
                $.ajax({
                    type: 'POST',
                    url: 'filter.php',
                    data: {
                        sb: selectedBudget,
                    },
                    success: function (result) {
                        $("#searchResultsContainer").empty();

                        if (result && result.length > 0) {
                            closeBudgetModal();
                            var cards = [];

                            showCategory("Res");
                            result.forEach(function (item) {
                                var x = 'quantityInput_' + item.Name + ']';
                                // x.trim();

                                var card =
                                    '<div class="col-md-4">' +
                                    '<div class="card featured-card">' +
                                    '<img src="' + item.URL + '" alt="' + item.Name + '" class="card-img-top" alt="">' +
                                    '<div class="card-body">' +
                                    '<h5 class="card-title">' +
                                    item.Name +
                                    '</h5>' +
                                    '<p class="card-price"><strong>$' +
                                    item.Price +
                                    '</strong></p>' +
                                    '<div class="input-group mb-3">' +
                                    '<input type="number" id="' + x + '" value="1" min="1" max="10" class="form-control mb-3">' +
                                    '<button type="button" class="btn btn-success btn-sm" style="height: 38px;" onclick="addToCartHandler(\'' + item.Name + '3\',' + item.Price + ')">Add to Cart</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                cards.push(card);
                            });
                            $("#searchResultsContainer").append(cards);

                        } else {
                            $("#searchResultsContainer").html('<p>No results found.</p>');
                        }
                    },
                    error: function (error) {
                        console.error(error);
                    }

                });

            }

            function openSearchModal() {
                var searchOverlay = document.getElementById('searchOverlay');
                searchOverlay.style.display = 'flex';
            }
            function closeSearchModal() {
                var searchOverlay = document.getElementById('searchOverlay');
                searchOverlay.style.display = 'none';
            }

            function performSearch() {
                var searchTerm = document.getElementById('searchInput').value;

                $.ajax({
                    type: 'POST',
                    url: 'search.php',
                    data: {
                        st: searchTerm,
                    },
                    success: function (result) {
                        $("#searchResultsContainer").empty();

                        if (result && result.length > 0) {

                            closeSearchModal();
                            showCategory("Res");
                            result.forEach(function (item) {
                                var x = 'quantityInput_' + item.Name + ']';
                                // x.trim();
                                var card =
                                    '<div class="col-md-4">' +
                                    '<div class="card featured-card">' +
                                    '<img src="' + item.URL + '" alt="' + item.Name + '" class="card-img-top" alt="">' +
                                    '<div class="card-body">' +
                                    '<h5 class="card-title">' +
                                    item.Name +
                                    '</h5>' +
                                    '<p class="card-price"><strong>$' +
                                    item.Price +
                                    '</strong></p>' +
                                    '<div class="input-group mb-3">' +
                                    '<input type="number" id="' + x + '" value="1" min="1" max="10" class="form-control mb-3">' +
                                    '<button type="button" class="btn btn-success btn-sm" style="height: 38px;" onclick="addToCartHandler(\'' + item.Name + '2\',' + item.Price + ')">Add to Cart</button>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>' +
                                    '</div>';
                                $("#searchResultsContainer").append(card);
                            });
                        } else {
                            $("#searchResultsContainer").html('<p>No results found.</p>');
                        }
                    },
                    error: function (error) {
                        console.error(error);
                    }

                });
            }



            var cartItems = [];


            function addToCartHandler(itemName, itemPrice) {
                var quantityInput = document.getElementById('quantityInput_' + itemName);
                var quantity = parseInt(quantityInput.value, 10);
                let modifiedStr = itemName.replace(/]$/, '');
                itemName = modifiedStr;
                let modifiedStr2 = itemName.replace(/]$/, '');
                itemName = modifiedStr2;
                var total = parseFloat(itemPrice) * parseInt(quantity);

               
                                // Continue with adding the item to the cart
                                var idx = cartItems.findIndex(function (item) {
                                    return item.name === itemName;
                                });

                                if (idx === -1) {
                                    cartItems.push({ name: itemName, quantity: quantity, price: parseFloat(itemPrice) });
                                } else {
                                    cartItems[idx].quantity += quantity;
                                }

                                var alertDiv = document.createElement('div');
                                alertDiv.className = 'alert alert-success alert-dismissible fade show fixed-top mt-5';
                                alertDiv.role = 'alert';
                                alertDiv.innerHTML = 'Success! Items added to cart!';

                                document.body.appendChild(alertDiv);

                                quantityInput.value = 1;

                                closeAddToCartModal();

                                displayCartItems();

                                setTimeout(function () {
                                    $(alertDiv).alert('close');
                                }, 1500);
                            }
                
                
            


            function hideLoading() {
                var loadingOverlay = document.getElementById('loadingOverlay');
                
                if (loadingOverlay) {
                    loadingOverlay.parentNode.removeChild(loadingOverlay);
                    // showCategory('Pizza');

                }
                          
            }

            function showLoading() {

var loadingOverlay = document.createElement('div');
loadingOverlay.id = 'loadingOverlay';


var loadingGif = document.createElement('img');
loadingGif.src = 'https://i.gifer.com/7efs.gif';
loadingGif.alt = 'Loading...';
loadingGif.id = 'loadingGif';


loadingOverlay.appendChild(loadingGif);

document.body.appendChild(loadingOverlay);
}


            function placeOrder() {
                var det = document.getElementById('addressDropdown').value;
                var uid = <?php echo $uid; ?>;
                var totalPrice = 0;
                var names = [];
                var dealn = [];
                var quans = [];
                var dealq = [];

                if (cartItems.length === 0) {
                    showCheckoutModal();
                } else {
                    for (var i = 0; i < cartItems.length; i++) {
                        var currentItem = cartItems[i];
                        if (currentItem.name.toLowerCase().includes('deal')) {

                            totalPrice += (parseFloat(currentItem.price) * parseInt(currentItem.quantity));
                            dealn.push(currentItem.name);
                            dealq.push(parseInt(currentItem.quantity));
                        } else {

                            totalPrice += (parseFloat(currentItem.price) * parseInt(currentItem.quantity));
                            names.push(currentItem.name);
                            quans.push(parseInt(currentItem.quantity));

                        }

                    }
                    $.ajax({
                        type: 'POST',
                        url: 'orderadd.php', 
                        data: {
                            det: det,
                            uid: uid,
                            tp: totalPrice,
                            names: names,
                            quans: quans,
                            dn: dealn,
                            dq: dealq,
                        },
                        success: function (r) {
                            console.log(r);
                            showLoading();

                            setTimeout(function () {
                                
                                hideLoading();
                                cartItems = [];
                                window.location.href = 'index.php?user_id='+<?php echo $uid ?>; 
                            }, 1500);
                            
                          
                            },
                        error: function (error) {
                            console.error(error);
                        }
                        
                        // showCategory('Pizza');
                    });
                }
            }


            function showNewAddress() {
                document.getElementById('checkoutModal').style.display = 'none';
                $('#newAddressModal').modal('show');

            }

            function closeNewAddressModal() {
                $('#newAddressModal').modal('hide');
                document.getElementById('checkoutModal').style.display = 'block';
            }
            function addNewAddress() {


                var newAddress = document.getElementById('newAddress').value;
                var newPhoneNumber = document.getElementById('newPhoneNumber').value;
                var uid = <?php echo $uid; ?>;

                <?php $lastInsertId = mysqli_insert_id($conn); ?>
                // Perform AJAX request to insert new address into the database
                $.ajax({
                    type: 'POST',
                    url: 'newadd.php', // PHP script to handle the insertion
                    data: {
                        newAddress: newAddress,
                        newPhoneNumber: newPhoneNumber,
                        uid: uid
                    },
                    success: function (r) {
                        closeNewAddressModal();
                       <?php $lastInsertId1 = mysqli_insert_id($conn);?>
                    <?php    if($lastInsertId1===$lastInsertId){?>
                        var dropdown = document.getElementById('addressDropdown');

var newOption = document.createElement('option');

newOption.value = newAddress;
newOption.text = newAddress;

dropdown.add(newOption);
<?php } ?>

                    },
                    
                    error: function (error) {
                        console.error(error);
                        closeCheckoutModal();
                    }

                });

            
            }
            // Function to get and display cart items
            function showCategory(category) {

                document.getElementById('Pizza').style.display = 'none';
                document.getElementById('Calzone').style.display = 'none';
                document.getElementById('Beverages').style.display = 'none';
                document.getElementById('Deals').style.display = 'none';
                document.getElementById('Res').style.display = 'none';

                document.getElementById(category).style.display = 'block';
            }

            function showUserOrders() {
                // Make an AJAX request to fetch user orders from the server
                $.ajax({
                    url: 'usersorders.php', // Replace with your actual API endpoint
                    method: 'GET',
                    success: function (response) {
                        // Update the content of the modal with the fetched orders
                        $('#userOrdersContent').html(response);

                        // Show the modal
                        $('#ordersModal').modal('show');
                    },
                    error: function (error) {
                        console.error('Error fetching user orders:', error);
                    }
                });
            }


            // function displayCartItems() {

            //     var cartItemsDiv = document.getElementById('cartItems');
            //     cartItemsDiv.innerHTML = '';

            //     var totalPrice = 0;

            //     if (cartItems.length === 0) {
            //         cartItemsDiv.innerHTML = '<p>No items in the cart.</p>';
            //     } else {
            //         for (var i = 0; i < cartItems.length; i++) {
            //             cartItemsDiv.innerHTML += `
            //                                                                 <div class="d-flex justify-content-between align-items-center">
            //                                                                                          <div>
            //                                                                           <p>${cartItems[i].quantity}x ${cartItems[i].name} - $${(parseFloat(cartItems[i].price) * parseInt(cartItems[i].quantity).toFixed(2))}</p>
            //                                                                                  <div class="input-group" style="width: 150px;">
            //                                                                                  <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateCartItem(${i}, 'decrement')">-</button>
            //                                                                                 <input type="number" value="${cartItems[i].quantity}" class="form-control" min="1">
            //                                                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateCartItem(${i}, 'increment')">+</button>
            //                                                                  </div>
            //                                                                </div>
            //                                                                </div>
            //                                                          `;
            //             totalPrice += (parseFloat(cartItems[i].price) * parseInt(cartItems[i].quantity));
            //         }

            //         // Display total price
            //         cartItemsDiv.innerHTML += `
            //                                                                                                                                 <p>Total: $${totalPrice.toFixed(2)}</p>
            //                                                                                                                             `;
            //     }
            // }

            function displayCartItems() {
                var cartItemsDiv = document.getElementById('cartItems');
                cartItemsDiv.innerHTML = '';

                var totalPrice = 0;

                if (cartItems.length === 0) {
                    cartItemsDiv.innerHTML = '<p>No items in the cart.</p>';
                } else {
                    for (var i = 0; i < cartItems.length; i++) {
                        cartItemsDiv.innerHTML += `
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p>${cartItems[i].quantity}x ${cartItems[i].name} - $${(parseFloat(cartItems[i].price) * parseInt(cartItems[i].quantity)).toFixed(2)}</p>
                            <div class="input-group" style="width: 150px;">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateCartItem(${i}, 'decrement')">-</button>
                                <input type="number" value="${cartItems[i].quantity}" class="form-control" min="1">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="updateCartItem(${i}, 'increment')">+</button>
                            </div>
                        </div>
                        <div>
                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="removeCartItem(${i})">
                            <i class="material-icons">delete</i>
                            </button>
                        </div>
                    </div>
                `;
                        totalPrice += parseFloat(cartItems[i].price) * parseInt(cartItems[i].quantity);
                    }

                    cartItemsDiv.innerHTML += `
                <p>Total: $${totalPrice.toFixed(2)}</p>
            `;
                }
            }

            function removeCartItem(index) {
                cartItems.splice(index, 1);
                displayCartItems();
            }

            function updateCartItem(index, action) {
                var quantityInput = document.querySelectorAll('#cartItems input')[index];
                var currentQuantity = parseInt(quantityInput.value);

                // Make an AJAX request to check the stock count
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            var stockCount = parseInt(xhr.responseText, 10);

                            if (action === 'increment' && currentQuantity < stockCount) {
                                currentQuantity++;
                            } else if (action === 'decrement' && currentQuantity > 1) {
                                currentQuantity--;
                            } else {
                                // Handle stock limit exceeded or decrementing below 1
                                alert('Error! Your Quantity Exceeds Our Stock Limit.');
                                return;
                            }

                            // Update the cartItems array
                            cartItems[index].quantity = currentQuantity;

                            displayCartItems();
                        } else {
                            // Handle error response from the server
                            console.error('Error fetching stock count: ' + xhr.status);
                        }
                    }
                };

    // Adjust the item name based on your cartItems structure
    var itemName = cartItems[index].name;

    xhr.open('GET', 'check_stock.php?itemName=' + itemName, true);
    xhr.send();
}


            function showCheckoutModal() {
                // Display checkout items
                displayCheckoutItems();

                // Show the checkout modal
                $('#checkoutModal').modal('show');
            }

            function closeCheckoutModal() {
                $('#checkoutModal').modal('hide');
            }

            function displayCheckoutItems() {
                var checkoutItemsDiv = document.getElementById('checkoutItems');
                checkoutItemsDiv.innerHTML = '';

                var totalItems = 0;
                var totalPrice = 0;

                // Loop through cart items
                for (var i = 0; i < cartItems.length; i++) {
                    checkoutItemsDiv.innerHTML += `
                                                                                                                        <div class="d-flex justify-content-between align-items-center">
                                                                                                                            <div>
                                                                                                                                <p>${cartItems[i].quantity}x ${cartItems[i].name} - $${(parseFloat(cartItems[i].price) * parseInt(cartItems[i].quantity)).toFixed(2)}</p>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                        `;

                    totalItems += parseInt(cartItems[i].quantity);
                    totalPrice += parseFloat(cartItems[i].price) * parseInt(cartItems[i].quantity);
                }

                // Display total price and additional charges
                checkoutItemsDiv.innerHTML += `
                                                                                                                        <p>Total Items: ${totalItems}</p>
                                                                                                                        <p>Subtotal: $${totalPrice.toFixed(2)}</p>
                                                                                                                        <p>Delivery Charges: $0.00</p>
                                                                                                                        <hr>
                                                                                                                        <p>Total: $${(totalPrice).toFixed(2)}</p>
                                                                                                                    `;
            }


            function showCartModal() {
                displayCartItems();

                $('#cartModal').modal('show');
            }

            function closeCartModal() {
                $('#cartModal').modal('hide');
            }

            function closeAddToCartModal() {
                $('#addToCartModal').modal('hide');
            }


            function clearFilter() {

                showCategory('Pizza');


            }


        </script>
        <<footer id="myFooter" style="background-color: #343a40; color: #fff; padding: 30px 0;">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h4>Contact Us</h4>
                    <p>
                        <strong>Email:</strong> JDlimited@example.com<br>
                        <strong>Phone:</strong> +1 (555) 123-4567<br>
                        <strong>Address:</strong> 123 Main Street, Cityville, State, 12345
                    </p>
                </div>
                <div class="col-md-6">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                    <a href="#" target="_blank" title="Follow us on Facebook" style="color: #fff;"><img src="icons\facebook.png" alt="Facebook" style="width: 30px; height: 30px;"></a>
                    <a href="#" target="_blank" title="Follow us on Twitter" style="color: #fff;"><img src="icons\twitter.png" alt="Twitter" style="width: 30px; height: 30px;"></a>
                    <a href="#" target="_blank" title="Follow us on Instagram" style="color: #fff;"><img src="icons/instagram.png" alt="Instagram" style="width: 30px; height: 30px;"></a>
                    <!-- Add more social links with local icons as needed -->
                </div>
                </div>
            </div>
        </div>
    </footer>
    <div class="modal fade" id="ordersModal" tabindex="-1" aria-labelledby="ordersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ordersModalLabel">My Orders</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="userOrdersContent">
                <!-- User orders will be dynamically added here -->
            </div>
        </div>
    </div>
</div>
    </body>

    </html>

    <?php
} else {
    ?>

    <!DOCTYPE html>
    <html>

    <head>
        <title>Login Page</title>
        <style>
            * {
                box-sizing: border-box;
                margin: 0;
            }

            body {
                font-family: "Roboto", sans-serif;
            }

            /* FORM STYLE */
            .form-wrapper {
                margin: 0 auto;
                margin-top: 10.4rem;
            }

            .login-form-bd {
                background: #316498;
                color: #fff;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 100vh;
                overflow: hidden;
            }

            .form-container {
                font-family: "Poppins", sans-serif;
                font-size: 1.125rem;
                background: #1b1a1a;
                padding: 5rem 2.5rem;
                border-radius: 0.313rem;
                margin: 5rem 28.125rem;
                box-shadow: 3px 0.25rem 1.25rem rgba(27, 27, 27, 0.2);
            }

            .form-container h1 {
                text-align: center;
                margin-bottom: 2.75rem;
                margin-top: -1.875rem;
                color: #ffff;
                font-weight: normal;
                font-size: 2rem;
            }

            .form-container a {
                text-decoration: none;
                color: #e2dc20;
            }

            .login-btn {
                cursor: pointer;
                display: inline-block;
                width: 100%;
                background: #e2dc20;
                padding: 0.938rem;
                font-family: inherit;
                font-weight: 500;
                font-size: 1.563rem;
                color: #0d0f42;
                border: 0;
                border-radius: 0.313rem;
                margin-bottom: 1.25rem;
            }

            .login-btn:focus {
                outline: 0;
            }

            .login-btn:active {
                transform: scale(0.98);
            }

            .text {
                margin-top: 0.938rem;
            }

            .form-control {
                position: relative;
                margin: 1.25rem 0 2.5rem;
            }

            .form-control input {
                background: transparent;
                border: 0;
                border-bottom: 1px #fff solid;
                display: block;
                width: 100%;
                padding: 1.25rem 0;
                font-size: 1.125rem;
                color: #fff;
            }

            .form-control input:focus {
                outline: 0;
                border-bottom-color: #c2c25a;
            }

            .form-control label {
                position: absolute;
                top: 0.938rem;
                left: 10;
            }

            .form-control label span {
                display: inline-block;
                font-size: 1.125rem;
                min-width: 0.313rem;
                transition: 0.3s cubic-bezier(0.53, 0.246, 0.265, 1.66);
            }

            .form-control input:focus+label span,
            .form-control input:valid+label span {
                color: #c2c25a;
                transform: translateY(-1.875rem);
            }
        </style>

    </head>

    <body>
        <form method="POST" action="index.php">
            <div class="login-form-bd">
                <div class="form-wrapper">
                    <div class="form-container">
                        <h1>Please Login</h1>
                        <div class="form-control">
                            <input type="text" name="username" required>
                            <label>Username</label>
                        </div>

                        <div class="form-control">
                            <input type="password" name="password" required>
                            <label>Password</label>
                        </div>
                        <button type="submit" class="login-btn">Login</button>
                        <p class="text">Don't have an account? <a href="register.php">Register</a></p>
                    </div>
                </div>
            </div>
        </form>
    </body>

    <script>
        const labels = document.querySelectorAll(".form-control label");

        labels.forEach((label) => {
            label.innerHTML = label.innerText
                .split("")
                .map(
                    (letter, idx) =>
                        `<span style="transition-delay:${idx * 50}ms">${letter}</span>`
                )
                .join("");
        });

    </script>

    </html>



    <?php

} ?>