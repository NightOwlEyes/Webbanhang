<?php include 'app/views/shares/header.php'; ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg mt-5">
                <div class="card-header text-center bg-primary text-white">
                    <h2>Thanh toán</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="/nightowleyes/Product/processCheckout">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Họ tên:</label>
                            <input type="text" id="name" name="name" class="form-control rounded" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Số điện thoại:</label>
                            <input type="text" id="phone" name="phone" class="form-control rounded" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Địa chỉ:</label>
                            <textarea id="address" name="address" class="form-control rounded" rows="3" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2">Thanh toán</button>
                    </form>

                    <div class="text-center mt-3">
                        <a href="/nightowleyes/Product/cart" class="btn btn-outline-secondary w-100">Quay lại giỏ hàng</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
