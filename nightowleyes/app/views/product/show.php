<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <?php if ($product): ?>
        <div class="row">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-5 text-center">
                <?php if ($product->image): ?>
                    <img src="/nightowleyes/<?= htmlspecialchars($product->image ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                         alt="<?= htmlspecialchars($product->name ?? 'Hình ảnh sản phẩm', ENT_QUOTES, 'UTF-8'); ?>"
                         class="img-fluid rounded shadow">
                <?php endif; ?>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-7">
                <h2 class="text-primary mb-4"><?= htmlspecialchars($product->name ?? 'Chưa có tên sản phẩm', ENT_QUOTES, 'UTF-8'); ?></h2>
                
                <div class="product-details mb-4">
                    <p class="text-muted"><strong>Mô tả:</strong> <?= htmlspecialchars($product->description ?? 'Chưa có mô tả', ENT_QUOTES, 'UTF-8'); ?></p>
                    <p class="h4 text-danger"><strong>Giá:</strong> <?= number_format($product->price, 0, ',', '.'); ?> VND</p>

                    <div class="plant-info mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <?php if (!empty($product->sunlight)): ?>
                                    <p><i class="fas fa-sun text-warning"></i> <strong>Ánh sáng:</strong> <?= htmlspecialchars($product->sunlight, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                                
                                <?php if (!empty($product->water)): ?>
                                    <p><i class="fas fa-tint text-primary"></i> <strong>Nước:</strong> <?= htmlspecialchars($product->water, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <?php if (!empty($product->size)): ?>
                                    <p><i class="fas fa-ruler text-secondary"></i> <strong>Kích thước:</strong> <?= htmlspecialchars($product->size, ENT_QUOTES, 'UTF-8'); ?></p>
                                <?php endif; ?>
                                
                                <p><i class="fas fa-layer-group text-info"></i> <strong>Danh mục:</strong> <?= htmlspecialchars($product->category_name ?? 'Chưa có danh mục', ENT_QUOTES, 'UTF-8'); ?></p>
                                
                                <p><i class="fas fa-boxes text-danger"></i> <strong>Còn lại:</strong> <?= htmlspecialchars($product->stock, ENT_QUOTES, 'UTF-8'); ?> cây</p>
                            </div>
                        </div>

                        <?php if (!empty($product->care_guide)): ?>
                            <div class="mt-3">
                                <h5><i class="fas fa-leaf text-success"></i> Hướng dẫn chăm sóc:</h5>
                                <p><?= htmlspecialchars($product->care_guide, ENT_QUOTES, 'UTF-8'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Thay thế phần form thêm vào giỏ hàng bằng nút mới -->
                <div class="mt-4">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addToCartModal">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </div>

                <!-- Các nút điều khiển -->
                <div class="mt-4">
                    <?php if (SessionHelper::isAdmin()): ?>
                        <a href="/nightowleyes/Product/edit/<?= htmlspecialchars($product->id ?? '', ENT_QUOTES, 'UTF-8'); ?>" 
                           class="btn btn-warning">
                            <i class="fas fa-edit"></i> Sửa
                        </a>
                        <a href="/nightowleyes/Product/delete/<?= htmlspecialchars($product->id ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                           class="btn btn-danger"
                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                            <i class="fas fa-trash"></i> Xóa
                        </a>
                    <?php endif; ?>
                    <a href="/nightowleyes/Product" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại danh sách
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Không tìm thấy sản phẩm.</div>
    <?php endif; ?>
</div>

<!-- Thêm Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addToCartModalLabel">Thêm vào giỏ hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="product-summary mb-3">
                    <h6><?= htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8') ?></h6>
                    <p class="text-muted">Giá: <?= number_format($product->price, 0, ',', '.') ?> VND</p>
                </div>
                
                <div class="quantity-selector form-group">
                    <label for="modalQuantity">Số lượng:</label>
                    <div class="input-group">
                        <button type="button" class="btn btn-outline-secondary" id="decreaseQuantity">-</button>
                        <input type="number" class="form-control text-center" id="modalQuantity" value="1" min="1" max="<?= $product->stock ?>">
                        <button type="button" class="btn btn-outline-secondary" id="increaseQuantity">+</button>
                    </div>
                </div>

                <div class="price-summary mt-3">
                    <p>Thành tiền: <span class="text-danger fw-bold" id="totalPrice"></span> VND</p>
                </div>

                <div class="alert alert-info mt-3">
                    <small><i class="fas fa-info-circle"></i> Bạn có thể điều chỉnh số lượng sau trong giỏ hàng</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" id="confirmAddToCart" data-product-id="<?= $product->id ?>">
                    Thêm vào giỏ hàng
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Đảm bảo có jQuery trước -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Thêm Bootstrap JS và Popper -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>

<!-- Cập nhật script -->
<script>
$(document).ready(function() {
    const productPrice = <?= $product->price ?>;
    
    function updateTotalPrice() {
        const quantity = parseInt($('#modalQuantity').val());
        const total = productPrice * quantity;
        $('#totalPrice').text(total.toLocaleString('vi-VN'));
    }

    // Cập nhật giá ban đầu
    updateTotalPrice();

    // Xử lý nút tăng/giảm số lượng
    $('#decreaseQuantity').click(function() {
        const currentVal = parseInt($('#modalQuantity').val());
        if(currentVal > 1) {
            $('#modalQuantity').val(currentVal - 1);
            updateTotalPrice();
        }
    });

    $('#increaseQuantity').click(function() {
        const currentVal = parseInt($('#modalQuantity').val());
        const maxStock = <?= $product->stock ?>;
        if(currentVal < maxStock) {
            $('#modalQuantity').val(currentVal + 1);
            updateTotalPrice();
        }
    });

    // Cập nhật giá khi thay đổi số lượng
    $('#modalQuantity').on('change', function() {
        let val = parseInt($(this).val());
        const maxStock = <?= $product->stock ?>;
        
        if(val < 1) val = 1;
        if(val > maxStock) val = maxStock;
        
        $(this).val(val);
        updateTotalPrice();
    });

    // Xử lý thêm vào giỏ hàng
    $('#confirmAddToCart').click(function() {
        const productId = $(this).data('product-id');
        const quantity = $('#modalQuantity').val();

        $.ajax({
            url: '/nightowleyes/Product/addToCart/' + productId,
            type: 'GET',
            data: { quantity: quantity, username: '<?= $_SESSION['username'] ?? '' ?>' },
            dataType: 'json',
            success: function(response) {
                if (response && response.success) {
                    $('.cart-count').text(response.cartCount);
                    $('#addToCartModal').modal('hide');
                    alert('Sản phẩm đã được thêm vào giỏ hàng!');
                } else {
                    alert(response.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng.');
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