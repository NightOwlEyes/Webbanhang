<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'app/views/shares/header.php';
?>

<div class="container mt-4">
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

                            <p class="card-text text-muted small">
                                <?php echo mb_substr(htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'), 0, 100) . '...'; ?>
                            </p>

                            <p class="fw-bold text-success">Giá:
                                <?php echo number_format($product->price, 0, ',', '.'); ?> VND
                            </p>

                            <div class="plant-info small">
                                <?php if (!empty($product->sunlight)): ?>
                                <p class="mb-1"><i class="fas fa-sun text-warning"></i> Ánh sáng: <?php echo htmlspecialchars($product->sunlight, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($product->water)): ?>
                                <p class="mb-1"><i class="fas fa-tint text-primary"></i> Nước: <?php echo htmlspecialchars($product->water, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($product->size)): ?>
                                <p class="mb-1"><i class="fas fa-ruler text-secondary"></i> Kích thước: <?php echo htmlspecialchars($product->size, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                                
                                <p class="mb-1"><i class="fas fa-layer-group text-info"></i> Danh mục: 
                                    <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?>
                                </p>
                                
                                <p class="mb-1"><i class="fas fa-boxes text-danger"></i> Còn lại: 
                                    <?php echo htmlspecialchars($product->stock, ENT_QUOTES, 'UTF-8'); ?> cây
                                </p>
                            </div>
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