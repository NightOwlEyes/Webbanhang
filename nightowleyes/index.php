<?php
session_start();
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Kiểm tra phần đầu tiên của URL để xác định controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'ProductController';

// Kiểm tra phần thứ hai của URL để xác định action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Kiểm tra xem controller có tồn tại không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    // Hiển thị trang 404
    include 'app/views/errors/404.php';
    exit;
}

require_once 'app/controllers/' . $controllerName . '.php';
$controller = new $controllerName();

if (!method_exists($controller, $action)) {
    // Hiển thị trang 404
    include 'app/views/errors/404.php';
    exit;
}

// Gọi action với các tham số còn lại (nếu có) 
call_user_func_array([$controller, $action], array_slice($url, 2));