<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow p-4">
        <h2 class="text-center text-success">🌿 Thêm cây cảnh mới</h2>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['error']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <form method="POST" action="/nightowleyes/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">🌱 Tên cây:</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên cây cảnh" required>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">📝 Mô tả:</label>
                        <textarea id="description" name="description" class="form-control" rows="4" placeholder="Nhập mô tả về cây cảnh" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">💰 Giá:</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" placeholder="Nhập giá cây cảnh" required>
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
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="care_guide" class="form-label">🔍 Hướng dẫn chăm sóc:</label>
                        <textarea id="care_guide" name="care_guide" class="form-control" rows="3" placeholder="Nhập hướng dẫn chăm sóc cây"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="sunlight" class="form-label">☀️ Yêu cầu ánh sáng:</label>
                        <select id="sunlight" name="sunlight" class="form-control">
                            <option value="" disabled selected>Chọn yêu cầu ánh sáng</option>
                            <option value="Ánh sáng trực tiếp">Ánh sáng trực tiếp</option>
                            <option value="Ánh sáng gián tiếp">Ánh sáng gián tiếp</option>
                            <option value="Bóng râm một phần">Bóng râm một phần</option>
                            <option value="Bóng râm hoàn toàn">Bóng râm hoàn toàn</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="water" class="form-label">💧 Yêu cầu nước:</label>
                        <select id="water" name="water" class="form-control">
                            <option value="" disabled selected>Chọn yêu cầu nước</option>
                            <option value="Rất ít">Rất ít (1-2 tuần/lần)</option>
                            <option value="Ít">Ít (1 lần/tuần)</option>
                            <option value="Trung bình">Trung bình (2-3 lần/tuần)</option>
                            <option value="Nhiều">Nhiều (Hàng ngày)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="size" class="form-label">📏 Kích thước:</label>
                        <select id="size" name="size" class="form-control">
                            <option value="" disabled selected>Chọn kích thước</option>
                            <option value="Nhỏ">Nhỏ (dưới 30cm)</option>
                            <option value="Trung bình">Trung bình (30-60cm)</option>
                            <option value="Lớn">Lớn (60-100cm)</option>
                            <option value="Rất lớn">Rất lớn (trên 100cm)</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="stock" class="form-label">📦 Số lượng tồn kho:</label>
                        <input type="number" id="stock" name="stock" class="form-control" min="0" value="10">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">🖼 Hình ảnh:</label>
                <input type="file" id="image" name="image" class="form-control" onchange="previewImage(event)">
                <small class="text-muted">Chỉ chấp nhận các định dạng: JPG, JPEG, PNG, GIF, WEBP. Kích thước tối đa: 10MB.</small>
                <div class="mt-3 text-center">
                    <img id="preview" src="" alt="Xem trước hình ảnh" class="img-thumbnail d-none" style="max-width: 200px;">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success"><i class="fas fa-leaf"></i> Thêm cây cảnh</button>
                <a href="/nightowleyes/Product" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Quay lại danh sách</a>
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
    
    function validateForm() {
        // Thêm logic kiểm tra form nếu cần
        return true;
    }
</script>

<?php include 'app/views/shares/footer.php'; ?>
