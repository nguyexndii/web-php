<?php
class CategoryModel {
    private $conn;
    private $table_name = "Category"; // Tên bảng trùng khớp với cơ sở dữ liệu đã tạo (Category)

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy toàn bộ danh mục sản phẩm
    public function getCategories() {
        // Dùng bí danh viết thường để đồng bộ hóa với việc gọi thuộc tính ở View
        $query = "SELECT Id as id, Name as name, Description as description FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
}
?>
