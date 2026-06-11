<?php
class ProductModel {
    private $conn;
    private $table_name = "Product"; // Tên bảng trong CSDL (Product)

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy toàn bộ sản phẩm và tên danh mục tương ứng (không kèm image)
    public function getProducts() {
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id
                  ORDER BY p.Id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Lấy danh sách sản phẩm giới hạn phục vụ phân trang (không kèm image)
    public function getProductsLimit($offset, $limit) {
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id
                  ORDER BY p.Id DESC
                  LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Đếm tổng số lượng sản phẩm để tính tổng số trang
    public function countProducts() {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Tìm kiếm sản phẩm theo tên có giới hạn phân trang (không kèm image)
    public function searchProductsLimit($keyword, $offset, $limit) {
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id
                  WHERE p.Name LIKE :keyword
                  ORDER BY p.Id DESC
                  LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        $searchKey = "%" . $keyword . "%";
        $stmt->bindValue(':keyword', $searchKey, PDO::PARAM_STR);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Đếm số lượng sản phẩm thỏa mãn điều kiện tìm kiếm để tính phân trang
    public function countSearchProducts($keyword) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE Name LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $searchKey = "%" . $keyword . "%";
        $stmt->bindValue(':keyword', $searchKey, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Lấy chi tiết sản phẩm theo ID (không kèm image)
    public function getProductById($id) {
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, p.Category_Id as category_id, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id 
                  WHERE p.Id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    // Thêm sản phẩm mới (không kèm image)
    public function addProduct($name, $description, $price, $category_id) {
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

        $query = "INSERT INTO " . $this->table_name . " (Name, Description, Price, Category_Id) 
                  VALUES (:name, :description, :price, :category_id)";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin sản phẩm (không kèm image)
    public function updateProduct($id, $name, $description, $price, $category_id) {
        $query = "UPDATE " . $this->table_name . " 
                  SET Name=:name, Description=:description, Price=:price, Category_Id=:category_id 
                  WHERE Id=:id";
        $stmt = $this->conn->prepare($query);

        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE Id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
