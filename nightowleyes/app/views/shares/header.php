<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <link
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .product-image {
            max-width: 100px;
            height: auto;
        }
        /* Basic Styling for the Cart Icon */
        .cart-icon {
            position: relative;
            display: inline-block; /* Make it an inline element to position it next to the count */
        }

        .cart-count {
            position: absolute;
            top: -8px; /* Adjust as needed */
            right: -8px; /* Adjust as needed */
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem; /* Adjust as needed */
        }
        
        /* Style for user dropdown */
        .user-dropdown .dropdown-toggle::after {
            display: none; /* Hide the default dropdown arrow */
        }
        
        .user-dropdown .dropdown-menu {
            right: 0;
            left: auto;
            margin-top: 10px;
        }
        
        .user-dropdown .username {
            font-weight: bold;
            cursor: pointer;
        }
        
        .user-dropdown .fa-user {
            margin-right: 5px;
        }
    </style>
</head>
<body>
<?php
// Đảm bảo session được bắt đầu ở đầu trang
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Quản lý sản phẩm</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" 
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <!-- Left side menu items -->
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="/nightowleyes/Product/">Danh sách sản phẩm</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/nightowleyes/Product/add">Thêm sản phẩm</a>
            </li>
            <li class="nav-item">
                <!-- Cart Icon with Count -->
                <a class="nav-link cart-icon" href="/nightowleyes/Product/cart">
                    <i class="fas fa-shopping-cart"></i>
                    <?php
                        $cart_count = 0;
                        if (isset($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $cart_count += $item['quantity'];
                            }
                        }
                    ?>
                    <span class="cart-count"><?= $cart_count ?></span>
                </a>
            </li>
        </ul>
        
        <!-- Right side user info -->
        <ul class="navbar-nav ml-auto">
            <?php
            // Thêm debug để kiểm tra session
            if(isset($_SESSION['username'])) {
                echo "<!-- DEBUG: Username is set: " . $_SESSION['username'] . " -->";
            } else {
                echo "<!-- DEBUG: Username is NOT set -->";
            }
            
            if(class_exists('SessionHelper') && SessionHelper::isLoggedIn()){
            ?>
                <li class="nav-item dropdown user-dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i> <?php echo $_SESSION['username']; ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="/nightowleyes/account/logout">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </a>
                    </div>
                </li>
            <?php
            } else {
            ?>
                <li class="nav-item">
                    <a class="nav-link" href="/nightowleyes/account/login">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </a>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
</nav>
<div class="container mt-4">
</div>
<!-- jQuery (Full version, required for AJAX) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script
    src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>