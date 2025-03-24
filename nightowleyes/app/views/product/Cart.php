<?php include 'app/views/shares/header.php'; ?>

<?php
    $username = $_SESSION['username'] ?? null;
    $cart = $username && isset($_SESSION['cart'][$username]) ? $_SESSION['cart'][$username] : [];
?>

<div class="container mt-5">
    <h1 class="text-center text-primary">🛒 Giỏ hàng của bạn</h1>

    <?php if (!empty($cart)): ?>
        <div class="row">
            <?php $totalPrice = 0; ?>
            <?php foreach ($cart as $id => $item): ?>
                <?php
                    $subtotal = $item['price'] * $item['quantity'];
                    $totalPrice += $subtotal;
                ?>
                <div class="col-md-6">
                    <div class="card mb-3 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4 text-center p-3">
                                <?php if (!empty($item['image'])): ?>
                                    <img src="/nightowleyes/<?php echo htmlspecialchars($item['image'], ENT_QUOTES, 'UTF-8'); ?>"
                                         alt="Product Image" class="img-fluid rounded shadow">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title text-success"><?php echo htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></h5>
                                    <p class="card-text"><strong>Giá:</strong> <?= number_format($item['price'], 0, ',', '.'); ?> VND</p>
                                    <p class="card-text">
                                        <strong>Số lượng:</strong>
                                        <div class="d-inline-flex align-items-center">
                                            <a href="/nightowleyes/Product/updateQuantity/<?= $id; ?>?action=decrease" class="btn btn-sm btn-outline-secondary">-</a>
                                            <span class="mx-2"><?= htmlspecialchars($item['quantity'], ENT_QUOTES, 'UTF-8'); ?></span>
                                            <a href="/nightowleyes/Product/updateQuantity/<?= $id; ?>?action=increase" class="btn btn-sm btn-outline-secondary">+</a>
                                        </div>
                                    </p>
                                    <p class="card-text"><strong>Thành tiền:</strong> <span class="text-danger"><?= number_format($subtotal, 0, ',', '.'); ?> VND</span></p>

                                    <div class="d-flex gap-2">
                                        <a href="/nightowleyes/Product/removeFromCart/<?= $id; ?>" 
                                           class="btn btn-danger btn-sm" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                            <i class="fas fa-trash-alt"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Hiển thị tổng tiền -->
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <h4>Tổng tiền: <span class="text-danger"><?= number_format($totalPrice, 0, ',', '.'); ?> VND</span></h4>
                </div>
            </div>

            <!-- Nút điều hướng -->
            <div class="col-12 text-center mt-3">
                <a href="/nightowleyes/Product" class="btn btn-secondary">
                    <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
                </a>
                <a href="/nightowleyes/Product/checkout" class="btn btn-success">
                    <i class="fas fa-credit-card"></i> Thanh toán
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            <h4>🛒 Giỏ hàng của bạn đang trống.</h4>
            <a href="/nightowleyes/Product" class="btn btn-primary mt-3">
                <i class="fas fa-shopping-basket"></i> Bắt đầu mua sắm ngay
            </a>
        </div>
    <?php endif; ?>
</div>

<?php include 'app/views/shares/footer.php'; ?>