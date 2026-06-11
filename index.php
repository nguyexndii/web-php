<?php
define('BASE_PATH', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/'));
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

// Định tuyến các yêu cầu API
if ($controllerName === 'ApiController' && isset($url[1])) {
    $apiControllerName = ucfirst($url[1]) . 'ApiController';
    if (file_exists('app/controllers/' . $apiControllerName . '.php')) {
        require_once 'app/controllers/' . $apiControllerName . '.php';
        $controller = new $apiControllerName();
        $method = $_SERVER['REQUEST_METHOD'];
        $id = $url[2] ?? null;
        switch ($method) {
            case 'GET':
                if ($id) {
                    $action = 'show';
                } else {
                    $action = 'index';
                }
                break;
            case 'POST':
                $action = 'store';
                break;
            case 'PUT':
                if ($id) {
                    $action = 'update';
                }
                break;
            case 'DELETE':
                if ($id) {
                    $action = 'destroy';
                }
                break;
            default:
                http_response_code(405);
                echo json_encode(['message' => 'Method Not Allowed']);
                exit;
        }
        if (method_exists($controller, $action)) {
            if ($id) {
                call_user_func_array([$controller, $action], [$id]);
            } else {
                call_user_func_array([$controller, $action], []);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Action not found']);
        }
        exit;
    } else {
        http_response_code(404);
        echo json_encode(['message' => 'Controller not found']);
        exit;
    }
}

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
