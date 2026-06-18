<?php
// Nhúng file autoload của Composer để nạp thư viện firebase/php-jwt
require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

// Lớp JWTHandler: tạo (mã hóa) và giải mã (xác thực) JWT token.
class JWTHandler
{
    // Khóa bí mật dùng để ký và xác thực token
    private $secret_key;

    public function __construct()
    {
        // Khóa bí mật HUTECH (phải dài ít nhất 32 ký tự để đáp ứng tiêu chuẩn của thư viện JWT mới)
        $this->secret_key = "HUTECH_secret_key_security_32_bytes_long"; 
    }

    // Hàm sinh JWT Token từ dữ liệu truyền vào
    public function encode($data)
    {
        $issuedAt = time(); // Thời gian phát hành token (hiện tại)
        $expirationTime = $issuedAt + 3600; // Thời gian hết hạn (sau 1 giờ = 3600 giây)
        
        // Cấu trúc Payload của JWT
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        );

        // Mã hóa và ký token bằng thuật toán HS256
        return JWT::encode($payload, $this->secret_key, 'HS256');
    }

    // Hàm giải mã và xác thực JWT Token
    public function decode($jwt)
    {
        try {
            // Giải mã token sử dụng khóa bí mật và thuật toán HS256
            $decoded = JWT::decode($jwt, new Key($this->secret_key, 'HS256'));
            // Trả về phần dữ liệu 'data' trong payload dưới dạng mảng (array)
            return (array) $decoded->data;
        } catch (Exception $e) {
            // Nếu có lỗi (hết hạn, sai chữ ký, token giả mạo), trả về null
            return null;
        }
    }
}
?>
