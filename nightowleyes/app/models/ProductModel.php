<?php
class ProductModel
{
private $conn;
private $table_name = "product";
public function __construct($db)
{
$this->conn = $db;
}
public function getProducts()
{
$query = "SELECT p.id, p.name, p.description, p.price, p.image, p.stock, p.care_guide, p.sunlight, p.water, p.size, c.name as category_name 
          FROM " . $this->table_name . " p
          LEFT JOIN category c ON p.category_id = c.id";
$stmt = $this->conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
return $result;
}
public function getProductById($id)
{
$query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_OBJ);
return $result;
}
public function addProduct($name, $description, $price, $category_id, $image, $care_guide = '', $sunlight = '', $water = '', $size = '', $stock = 10)
{
$errors = [];
if (empty($name)) {
$errors['name'] = 'Tên sản phẩm không được để trống';
}
if (empty($description)) {
$errors['description'] = 'Mô tả không được để trống';
}
if (!is_numeric($price) || $price < 0) {
$errors['price'] = 'Giá sản phẩm không hợp lệ';
}
if (count($errors) > 0) {
return $errors;
}
$query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image, care_guide, sunlight, water, size, stock) 
          VALUES (:name, :description, :price, :category_id, :image, :care_guide, :sunlight, :water, :size, :stock)";
$stmt = $this->conn->prepare($query);
$name = htmlspecialchars(strip_tags($name));
$description = htmlspecialchars(strip_tags($description));
$price = htmlspecialchars(strip_tags($price));
$category_id = htmlspecialchars(strip_tags($category_id));
$image = htmlspecialchars(strip_tags($image));
$care_guide = htmlspecialchars(strip_tags($care_guide));
$sunlight = htmlspecialchars(strip_tags($sunlight));
$water = htmlspecialchars(strip_tags($water));
$size = htmlspecialchars(strip_tags($size));
$stock = htmlspecialchars(strip_tags($stock));

$stmt->bindParam(':name', $name);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':price', $price);
$stmt->bindParam(':category_id', $category_id);
$stmt->bindParam(':image', $image);
$stmt->bindParam(':care_guide', $care_guide);
$stmt->bindParam(':sunlight', $sunlight);
$stmt->bindParam(':water', $water);
$stmt->bindParam(':size', $size);
$stmt->bindParam(':stock', $stock);

if ($stmt->execute()) {
return true;
}
return false;
}
public function updateProduct($id, $name, $description, $price, $category_id, $image, $care_guide = '', $sunlight = '', $water = '', $size = '', $stock = 10)
{
$query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, 
          category_id=:category_id, image=:image, care_guide=:care_guide, sunlight=:sunlight, 
          water=:water, size=:size, stock=:stock 
          WHERE id=:id";
$stmt = $this->conn->prepare($query);
$name = htmlspecialchars(strip_tags($name));
$description = htmlspecialchars(strip_tags($description));
$price = htmlspecialchars(strip_tags($price));
$category_id = htmlspecialchars(strip_tags($category_id));
$image = htmlspecialchars(strip_tags($image));
$care_guide = htmlspecialchars(strip_tags($care_guide));
$sunlight = htmlspecialchars(strip_tags($sunlight));
$water = htmlspecialchars(strip_tags($water));
$size = htmlspecialchars(strip_tags($size));
$stock = htmlspecialchars(strip_tags($stock));

$stmt->bindParam(':id', $id);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':price', $price);
$stmt->bindParam(':category_id', $category_id);
$stmt->bindParam(':image', $image);
$stmt->bindParam(':care_guide', $care_guide);
$stmt->bindParam(':sunlight', $sunlight);
$stmt->bindParam(':water', $water);
$stmt->bindParam(':size', $size);
$stmt->bindParam(':stock', $stock);

if ($stmt->execute()) {
return true;
}
return false;
}
public function deleteProduct($id)
{
    // Lấy thông tin sản phẩm trước khi xóa (để lấy đường dẫn ảnh nếu cần)
    $product = $this->getProductById($id);
    
    // Xóa sản phẩm khỏi cơ sở dữ liệu
    $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    try {
        $this->conn->beginTransaction();
        
        // Thực thi câu lệnh xóa
        $result = $stmt->execute();
        
        // Kiểm tra xem có bản ghi nào bị ảnh hưởng không
        if ($result && $stmt->rowCount() > 0) {
            // Xóa file ảnh nếu có
            if ($product && !empty($product->image) && file_exists($product->image)) {
                unlink($product->image);
            }
            
            $this->conn->commit();
            return true;
        } else {
            $this->conn->rollBack();
            error_log("Không thể xóa sản phẩm ID: $id - Không có bản ghi nào bị ảnh hưởng");
            return false;
        }
    } catch (PDOException $e) {
        $this->conn->rollBack();
        error_log("Lỗi xóa sản phẩm: " . $e->getMessage());
        return false;
    }
}

// Phương thức mới để lấy sản phẩm theo danh mục
public function getProductsByCategory($category_id)
{
    $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.stock, p.care_guide, p.sunlight, p.water, p.size, c.name as category_name 
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.category_id = :category_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':category_id', $category_id);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

// Phương thức mới để tìm kiếm sản phẩm
public function searchProducts($keyword)
{
    $query = "SELECT p.id, p.name, p.description, p.price, p.image, p.stock, p.care_guide, p.sunlight, p.water, p.size, c.name as category_name 
              FROM " . $this->table_name . " p
              LEFT JOIN category c ON p.category_id = c.id
              WHERE p.name LIKE :keyword OR p.description LIKE :keyword";
    $stmt = $this->conn->prepare($query);
    $search_term = "%{$keyword}%";
    $stmt->bindParam(':keyword', $search_term);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_OBJ);
    return $result;
}

// Phương thức mới để cập nhật số lượng tồn kho
public function updateStock($id, $quantity)
{
    $query = "UPDATE " . $this->table_name . " SET stock = stock - :quantity WHERE id = :id AND stock >= :quantity";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':quantity', $quantity);
    return $stmt->execute() && $stmt->rowCount() > 0;
}
}
?>