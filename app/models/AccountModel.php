<?php
class AccountModel {
    private $conn;
    private $table_name = "account";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAccountByUsername($username) {
        // Sử dụng AS để alias các cột trong DB thành chữ thường (id, username, fullname, password, role) 
        // nhằm đồng bộ dữ liệu với code Controller và View của bạn.
        $query = "SELECT Id as id, Username as username, Fullname as fullname, Password as password, ROLE as role 
                  FROM " . $this->table_name . " 
                  WHERE Username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function save($username, $fullName, $password, $role = 'user') {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        // Đồng bộ hóa tên cột viết hoa trong database (Username, Fullname, Password, ROLE)
        $query = "INSERT INTO " . $this->table_name . " SET Username=:username, Fullname=:fullname, Password=:password, ROLE=:role";
        $stmt = $this->conn->prepare($query);
        
        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role));
        
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        
        return $stmt->execute();
    }
}
?>
