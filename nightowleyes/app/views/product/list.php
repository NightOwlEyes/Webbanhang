<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'app/views/shares/header.php';

$cart_count = 0;
if (SessionHelper::isLoggedIn()) {
    $username = $_SESSION['username'];
    if (isset($_SESSION['cart'][$username])) {
        foreach ($_SESSION['cart'][$username] as $item) {
            $cart_count += $item['quantity'];
        }
    }
}
?>

<div class="container mt-4">
    <?php if (isset($_SESSION['login_message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['login_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['login_message']); ?>
    <?php endif; ?>

    <h1 class="text-center mb-4">Danh sách sản phẩm</h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['success']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $_SESSION['error']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <div class="row">
            <?php foreach ($products as $product): ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <?php if (!empty($product->image)): ?>
                            <img src="/nightowleyes/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>"
                                 class="card-img-top img-fluid"
                                 alt="Product Image"
                                 style="height: 200px; object-fit: cover;">
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/nightowleyes/Product/show/<?php echo $product->id; ?>"
                                   class="text-decoration-none text-dark">
                                    <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </h5>

                            <p class="fw-bold text-success mb-2">Giá:
                                <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                            </p>

                            <a href="/nightowleyes/Product/show/<?php echo $product->id; ?>" 
                               class="btn btn-outline-info btn-sm">
                                <i class="fas fa-info-circle"></i> Xem chi tiết
                            </a>
                        </div>

                        <div class="card-footer bg-white">
                            <?php if (SessionHelper::isAdmin()): ?>
                                <a href="/nightowleyes/Product/edit/<?php echo $product->id; ?>"
                                   class="btn btn-warning btn-sm">
                                    ✏️ Sửa
                                </a>
                                <a href="/nightowleyes/Product/delete/<?php echo $product->id; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                    ❌ Xóa
                                </a>
                            <?php endif; ?>
                            <button class="btn btn-primary btn-sm float-end add-to-cart-btn"
                                    data-product-id="<?php echo $product->id; ?>">
                                🛒 Thêm vào giỏ hàng
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="text-center text-muted">Không có sản phẩm nào.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Tự động đóng thông báo sau 5 giây
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
    
    $('.add-to-cart-btn').on('click', function(e) {
        e.preventDefault(); // Prevent the default link behavior (page redirect)

        var productId = $(this).data('product-id'); // Get the product ID from the data attribute

        $.ajax({
            url: '/nightowleyes/Product/addToCart/' + productId, // URL to your addToCart action
            type: 'GET', // Or 'POST' if you prefer
            dataType: 'json', // Expect JSON response (optional, but good practice)
            success: function(response) {
                if (response && response.success) {
                    // Update the cart count in the header
                    $('.cart-count').text(response.cartCount);
                    alert('Sản phẩm đã được thêm vào giỏ hàng!'); // Optional: Show a confirmation message
                } else {
                    alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX error:", status, error);
                alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
            }
        });
    });
});
</script>

<?php include 'app/views/shares/footer.php'; ?>