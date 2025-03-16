<?php include 'app/views/shares/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg mt-5">
                <div class="card-header text-center bg-warning text-white">
                    <h2>Sửa sản phẩm</h2>
                </div>
                <div class="card-body">
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="/nightowleyes/Product/update" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $product->id; ?>">

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Tên sản phẩm:</label>
                            <input type="text" id="name" name="name" class="form-control rounded" 
                                   value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Mô tả:</label>
                            <textarea id="description" name="description" class="form-control rounded" rows="3" required>
                                <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                            </textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="price" class="form-label">Giá:</label>
                            <input type="number" id="price" name="price" class="form-control rounded" step="0.01"
                                   value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label">Danh mục:</label>
                            <select id="category_id" name="category_id" class="form-control rounded" required>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>" 
                                            <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="image" class="form-label">Hình ảnh:</label>
                            <input type="file" id="image" name="image" class="form-control" 
                                   onchange="previewImage(event)">
                            <input type="hidden" name="existing_image" value="<?php echo $product->image; ?>">

                            <?php if ($product->image): ?>
                                <div class="mt-3">
                                    <img src="/<?php echo $product->image; ?>" id="preview" 
                                         alt="Hình ảnh sản phẩm" class="rounded shadow" 
                                         style="max-width: 150px;">
                                </div>
                            <?php endif; ?>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">Lưu thay đổi</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/nightowleyes/Product" class="btn btn-outline-secondary w-100">Quay lại danh sách sản phẩm</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
            var output = document.getElementById('preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>
