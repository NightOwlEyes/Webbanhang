<?php
class Database {
private $host = "localhost";
private $db_name = "plant_store";
private $username = "root";
private $password = "";
public $conn;
public function getConnection() {
$this->conn = null;
try {
$this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4", $this->username, $this->password);
$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$this->conn->exec("SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci");
$this->conn->exec("SET CHARACTER SET utf8mb4");
} catch(PDOException $exception) {
echo "Connection error: " . $exception->getMessage();
}
return $this->conn;
}
}