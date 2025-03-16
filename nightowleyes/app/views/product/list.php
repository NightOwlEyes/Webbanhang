<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'app/views/shares/header.php';
?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Danh sách sản phẩm</h1>

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

                           <!-- REMOVE THIS LINE -->
                           <!--  <p class="card-text text-muted">
                                <?php //echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                            </p> -->

                            <p class="fw-bold text-danger">Giá:
                                <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                            </p>

                            <p class="text-muted">Danh mục:
                                <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                            </p>
                        </div>

                        <div class="card-footer bg-white">
                            <a href="/nightowleyes/Product/edit/<?php echo $product->id; ?>"
                               class="btn btn-warning btn-sm">
                                ✏️ Sửa
                            </a>

                            <a href="/nightowleyes/Product/delete/<?php echo $product->id; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                ❌ Xóa
                            </a>

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