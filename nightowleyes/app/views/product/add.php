<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center text-primary">🆕 Thêm sản phẩm mới</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/nightowleyes/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
            <div class="mb-3">
                <label for="name" class="form-label">📌 Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">📝 Mô tả:</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Nhập mô tả sản phẩm" required></textarea>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">💰 Giá:</label>
                <input type="number" id="price" name="price" class="form-control" step="0.01" placeholder="Nhập giá sản phẩm" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">📂 Danh mục:</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="" disabled selected>Chọn danh mục</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?php echo $category->id; ?>">
                            <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">🖼 Hình ảnh:</label>
                <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event)">
                <div class="mt-3 text-center">
                    <img id="preview" src="" alt="Xem trước hình ảnh" class="img-thumbnail d-none" style="max-width: 200px;">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Thêm sản phẩm</button>
                <a href="/nightowleyes/Product/list" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
            </div>
        </form>
    </div>
</div>

<script>
    function previewImage(event) {
        let reader = new FileReader();
        reader.onload = function() {
            let imgElement = document.getElementById('preview');
            imgElement.src = reader.result;
            imgElement.classList.remove('d-none');
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>
