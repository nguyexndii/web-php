<?php 
require_once('app/config/database.php'); 
require_once('app/models/AccountModel.php'); 
// Nhúng file JWTHandler để tạo token khi đăng nhập thành công
require_once('app/utils/JWTHandler.php'); 

class AccountController { 
    private $accountModel;
    private $db; 
    // Khai báo thuộc tính jwtHandler
    private $jwtHandler;

    public function __construct() { 
        $this->db = (new Database())->getConnection(); 
        $this->accountModel = new AccountModel($this->db); 
        // Khởi tạo đối tượng JWTHandler
        $this->jwtHandler = new JWTHandler();
    } 

    // Hiển thị trang đăng ký
    public function register() { 
        include_once 'app/views/account/register.php'; 
    } 

    // Hiển thị trang đăng nhập
    public function login() { 
        include_once 'app/views/account/login.php'; 
    } 

    // Xử lý lưu tài khoản khi đăng ký
    public function save() { 
        if ($_SERVER['REQUEST_METHOD'] == 'POST') { 
            // Đọc dữ liệu nếu Client gửi dạng JSON raw
            $jsonData = json_decode(file_get_contents("php://input"), true);
            
            if ($jsonData) {
                $username = $jsonData['username'] ?? ''; 
                $fullName = $jsonData['fullname'] ?? $jsonData['fullname'] ?? ''; 
                $password = $jsonData['password'] ?? ''; 
                $confirmPassword = $jsonData['confirmpassword'] ?? $jsonData['password'] ?? ''; 
                $role = $jsonData['role'] ?? 'user'; 
            } else {
                // Đọc dữ liệu nếu gửi từ Form giao diện web thường
                $username = $_POST['username'] ?? ''; 
                $fullName = $_POST['fullname'] ?? ''; 
                $password = $_POST['password'] ?? ''; 
                $confirmPassword = $_POST['confirmpassword'] ?? ''; 
                $role = $_POST['role'] ?? 'user'; 
            }
            
            $errors = []; 
            
            if (empty($username)) $errors['username'] = "Vui lòng nhập username!"; 
            if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập fullname!"; 
            if (empty($password)) $errors['password'] = "Vui lòng nhập password!"; 
            if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!"; 
            if (!in_array($role, ['admin', 'user'])) $role = 'user'; 
            
            if ($this->accountModel->getAccountByUsername($username)) { 
                $errors['account'] = "Tài khoản này đã được đăng ký!"; 
            } 
            
            if (count($errors) > 0) { 
                if ($jsonData) {
                    header('Content-Type: application/json');
                    http_response_code(400);
                    echo json_encode(['errors' => $errors]);
                    exit;
                }
                include_once 'app/views/account/register.php'; 
            } else { 
                $result = $this->accountModel->save($username, $fullName, $password, $role); 
                if ($result) { 
                    if ($jsonData) {
                        header('Content-Type: application/json');
                        echo json_encode(['message' => 'User registered successfully']);
                        exit;
                    }
                    header('Location: ' . BASE_PATH . '/account/login'); 
                    exit; 
                } 
            } 
        } 
    } 

    // Xử lý đăng xuất
    public function logout() {
        SessionHelper::start(); 
        unset($_SESSION['username']); 
        unset($_SESSION['role']); 
        header('Location: ' . BASE_PATH . '/product'); 
        exit; 
    } 

    // Xử lý xác nhận đăng nhập bằng API (trả về JWT Token dưới dạng JSON)
    public function checkLogin() { 
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? ''; 
        $password = $data['password'] ?? ''; 
        
        $user = $this->accountModel->getAccountByUsername($username); 
        
        if ($user && password_verify($password, $user->password)) { 
            // Tạo token chứa id, username và vai trò (role) để phân quyền ở giao diện client
            $token = $this->jwtHandler->encode([
                'id' => $user->id, 
                'username' => $user->username,
                'role' => $user->role
            ]);
            
            echo json_encode(['token' => $token]);
        } else { 
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        } 
    } 
} 
?>
