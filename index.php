<?php
session_start();
// Tải trước Model của Sản phẩm như giáo trình yêu cầu
require_once 'app/models/ProductModel.php';
require_once 'app/helpers/SessionHelper.php';

// Nhận tham số URL từ .htaccess
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Kiểm tra phần đầu tiên của URL để xác định Controller
$controllerName = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) . 'Controller' : 'DefaultController';

// Kiểm tra phần thứ hai của URL để xác định Action
$action = isset($url[1]) && $url[1] != '' ? $url[1] : 'index';

// Kiểm tra xem tệp tin Controller có tồn tại vật lý không
if (!file_exists('app/controllers/' . $controllerName . '.php')) {
    die('Controller not found: ' . htmlspecialchars($controllerName));
}

// Nạp tệp tin Controller
require_once 'app/controllers/' . $controllerName . '.php';

// Khởi tạo đối tượng Controller
$controller = new $controllerName();

// Kiểm tra xem phương thức Action có tồn tại trong Controller không
if (!method_exists($controller, $action)) {
    die('Action not found: ' . htmlspecialchars($action) . ' in ' . htmlspecialchars($controllerName));
}

// Gọi Action và truyền các tham số còn lại trong URL (nếu có)
call_user_func_array([$controller, $action], array_slice($url, 2));
?>
