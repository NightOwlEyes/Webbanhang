<?php
// Require SessionHelper and other necessary files
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController
{
    private $productModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function index()
    {
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function show($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function add()
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['error'] = "Bạn không có quyền truy cập vào trang này.";
            header('Location: /nightowleyes/Product');
            exit;
        }

        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function save()
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['error'] = "Bạn không có quyền thực hiện hành động này.";
            header('Location: /nightowleyes/Product');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $care_guide = $_POST['care_guide'] ?? '';
            $sunlight = $_POST['sunlight'] ?? '';
            $water = $_POST['water'] ?? '';
            $size = $_POST['size'] ?? '';
            $stock = $_POST['stock'] ?? 10;

            try {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                } else {
                    $image = "";
                }

                $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image, $care_guide, $sunlight, $water, $size, $stock);

                if (is_array($result)) {
                    $errors = $result;
                    $categories = (new CategoryModel($this->db))->getCategories();
                    include 'app/views/product/add.php';
                } else {
                    $_SESSION['success'] = "Sản phẩm đã được thêm thành công.";
                    header('Location: /nightowleyes/Product');
                    exit;
                }
            } catch (Exception $e) {
                $errors = ['image' => $e->getMessage()];
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            }
        }
    }

    public function edit($id)
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['error'] = "Bạn không có quyền truy cập vào trang này.";
            header('Location: /nightowleyes/Product');
            exit;
        }

        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();

        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function update()
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['error'] = "Bạn không có quyền thực hiện hành động này.";
            header('Location: /nightowleyes/Product');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            $care_guide = $_POST['care_guide'] ?? '';
            $sunlight = $_POST['sunlight'] ?? '';
            $water = $_POST['water'] ?? '';
            $size = $_POST['size'] ?? '';
            $stock = $_POST['stock'] ?? 10;

            try {
                if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                    $image = $this->uploadImage($_FILES['image']);
                } else {
                    $image = $_POST['existing_image'];
                }

                $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image, $care_guide, $sunlight, $water, $size, $stock);

                if ($edit) {
                    $_SESSION['success'] = "Sản phẩm đã được cập nhật thành công.";
                    header('Location: /nightowleyes/Product');
                    exit;
                } else {
                    $_SESSION['error'] = "Đã xảy ra lỗi khi lưu sản phẩm.";
                    header('Location: /nightowleyes/Product/edit/' . $id);
                    exit;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                header('Location: /nightowleyes/Product/edit/' . $id);
                exit;
            }
        }
    }

    public function delete($id)
    {
        if (!SessionHelper::isAdmin()) {
            $_SESSION['error'] = "Bạn không có quyền thực hiện hành động này.";
            header('Location: /nightowleyes/Product');
            exit;
        }

        // Validate the product ID
        $id = intval($id);
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $_SESSION['error'] = "Sản phẩm không tồn tại hoặc đã bị xóa.";
            header('Location: /nightowleyes/Product');
            exit;
        }

        // Attempt to delete the product
        $result = $this->productModel->deleteProduct($id);

        if ($result) {
            $_SESSION['success'] = "Sản phẩm đã được xóa thành công.";
        } else {
            $_SESSION['error'] = "Đã xảy ra lỗi khi xóa sản phẩm. Vui lòng thử lại.";
        }

        header('Location: /nightowleyes/Product');
        exit;
    }

    private function uploadImage($file)
    {
        $target_dir = "uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            $_SESSION['upload_error'] = "File tải lên không phải là hình ảnh hợp lệ";
            throw new Exception("File không phải là hình ảnh.");
        }

        if ($file["size"] > 10 * 1024 * 1024) {
            $_SESSION['upload_error'] = "File không được vượt quá 10MB";
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" && $imageFileType != "webp") {
            $_SESSION['upload_error'] = "Chỉ chấp nhận các file: JPG, JPEG, PNG, GIF và WEBP";
            throw new Exception("Định dạng file không được hỗ trợ.");
        }

        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            $_SESSION['upload_error'] = "Có lỗi xảy ra khi tải file lên server";
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }

        return $target_file;
    }

    public function addToCart($id)
    {
        $product = $this->productModel->getProductById($id);
        $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
        $username = $_SESSION['username'] ?? null;

        if (!$username) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Bạn cần đăng nhập để thêm vào giỏ hàng.']);
            return;
        }

        if (!$product) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm.']);
            return;
        }

        if (!isset($_SESSION['cart'][$username])) {
            $_SESSION['cart'][$username] = [];
        }

        $currentQuantity = $_SESSION['cart'][$username][$id]['quantity'] ?? 0;
        $newQuantity = $currentQuantity + $quantity;

        if ($newQuantity > $product->stock) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Số lượng vượt quá tồn kho.']);
            return;
        }

        if (isset($_SESSION['cart'][$username][$id])) {
            $_SESSION['cart'][$username][$id]['quantity'] = $newQuantity;
        } else {
            $_SESSION['cart'][$username][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $quantity,
                'image' => $product->image
            ];
        }

        $cart_count = 0;
        foreach ($_SESSION['cart'][$username] as $item) {
            $cart_count += $item['quantity'] ?? 0;
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'cartCount' => $cart_count]);
        exit;
    }

    public function cart()
    {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function checkout()
    {
        include 'app/views/product/checkout.php';
    }

    public function processCheckout()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }

            // Bắt đầu giao dịch
            $this->db->beginTransaction();

            try {
                // Lưu thông tin đơn hàng vào bảng orders
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name, :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();

                // Lưu chi tiết đơn hàng vào bảng order_details
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }

                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);

                // Commit giao dịch
                $this->db->commit();

                // Chuyển hướng đến trang xác nhận đơn hàng
                header('Location: /nightowleyes/Product/orderConfirmation');

            } catch (Exception $e) {
                // Rollback giao dịch nếu có lỗi
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation()
    {
        include 'app/views/product/orderConfirmation.php';
    }

    public function updateQuantity($id)
    {
        if ($_SERVER["REQUEST_METHOD"] == "GET") {
            $action = $_GET['action'] ?? null; // Get the 'action' parameter
            $username = $_SESSION['username'] ?? null;

            if (!$username || !isset($_SESSION['cart'][$username][$id])) {
                header('Location: /nightowleyes/Product/cart');
                exit();
            }

            // Update the quantity based on the action
            if ($action == 'increase') {
                $_SESSION['cart'][$username][$id]['quantity']++;
            } elseif ($action == 'decrease' && $_SESSION['cart'][$username][$id]['quantity'] > 1) {
                $_SESSION['cart'][$username][$id]['quantity']--;
            }

            // Redirect back to the cart page
            header('Location: /nightowleyes/Product/cart');
            exit();
        }
    }

    public function removeFromCart($id)
    {
        $username = $_SESSION['username'] ?? null;

        if (!$username || !isset($_SESSION['cart'][$username][$id])) {
            header('Location: /nightowleyes/Product/cart');
            exit();
        }

        unset($_SESSION['cart'][$username][$id]);

        header('Location: /nightowleyes/Product/cart');
        exit();
    }
}
?>