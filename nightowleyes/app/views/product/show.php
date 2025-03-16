<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5">
    <?php if ($product): ?>
        <div class="row">
            <!-- Ảnh sản phẩm -->
            <div class="col-md-5 text-center">
                <?php if ($product->image): ?>
                    <img src="/nightowleyes/<?= htmlspecialchars($product->image ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                         alt="<?= htmlspecialchars($product->name ?? 'Hình ảnh sản phẩm', ENT_QUOTES, 'UTF-8'); ?>"
                         class="img-fluid rounded shadow product-image">
                <?php endif; ?>
            </div>

            <!-- Thông tin sản phẩm -->
            <div class="col-md-7">
                <h2 class="text-primary"><?= htmlspecialchars($product->name ?? 'Chưa có tên sản phẩm', ENT_QUOTES, 'UTF-8'); ?></h2>
                <p class="text-muted"><strong>Mô tả:</strong> <?= htmlspecialchars($product->description ?? 'Chưa có mô tả', ENT_QUOTES, 'UTF-8'); ?></p>
                <p class="h4 text-danger"><strong>Giá:</strong> <?= htmlspecialchars($product->price ?? 'Chưa có giá', ENT_QUOTES, 'UTF-8'); ?> VND</p>
                <p><strong>Danh mục:</strong> <?= htmlspecialchars($product->category_name ?? 'Chưa có danh mục', ENT_QUOTES, 'UTF-8'); ?></p> <!-- Hiển thị danh mục động -->

                <!-- Form thêm vào giỏ hàng -->
                <form action="/nightowleyes/Product/addToCart/<?= htmlspecialchars($product->id ?? '', ENT_QUOTES, 'UTF-8'); ?>" method="POST" class="d-flex align-items-center">
                    <label for="quantity" class="me-2">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" min="1" value="1" class="form-control me-3" style="width: 80px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng
                    </button>
                </form>

                <!-- Các nút điều khiển -->
                <div class="mt-4">
                    <a href="/nightowleyes/Product/edit/<?= htmlspecialchars($product->id ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Sửa
                    </a>
                    <a href="/nightowleyes/Product/delete/<?= htmlspecialchars($product->id ?? '', ENT_QUOTES, 'UTF-8'); ?>"
                       class="btn btn-danger"
                       onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                        <i class="fas fa-trash"></i> Xóa
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-danger">Không tìm thấy sản phẩm.</div>
    <?php endif; ?>
</div>
<?php include 'app/views/shares/footer.php'; ?>