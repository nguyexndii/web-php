<?php
class ProductModel {
    private $conn;
    private $table_name = "Product"; // Tên bảng trùng khớp với cơ sở dữ liệu đã tạo (Product)

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy toàn bộ sản phẩm và tên danh mục tương ứng
    public function getProducts() {
        // Đồng bộ hóa các trường truy vấn với kiểu chữ của DB và gán bí danh viết thường để View hiển thị đúng
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, p.Image as image, p.is_best_selling, p.is_new, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id
                  ORDER BY p.Id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Lấy danh sách sản phẩm giới hạn phục vụ phân trang
    public function getProductsLimit($offset, $limit) {
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, p.Image as image, p.is_best_selling, p.is_new, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id
                  ORDER BY p.Id DESC
                  LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        // Ràng buộc giá trị số nguyên cho LIMIT và OFFSET
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

    // Tìm kiếm sản phẩm theo tên có giới hạn phân trang
    public function searchProductsLimit($keyword, $offset, $limit) {
        // Truy vấn tìm kiếm sản phẩm liên kết với bảng Category và lọc theo tên sản phẩm bằng LIKE
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, p.Image as image, p.is_best_selling, p.is_new, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id
                  WHERE p.Name LIKE :keyword
                  ORDER BY p.Id DESC
                  LIMIT :offset, :limit";
        $stmt = $this->conn->prepare($query);
        // Thiết lập tham số từ khóa tìm kiếm (bổ sung % ở hai đầu để tìm kiếm tương đối)
        $searchKey = "%" . $keyword . "%";
        $stmt->bindValue(':keyword', $searchKey, PDO::PARAM_STR);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        // Trả về danh sách kết quả dạng đối tượng
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    // Đếm số lượng sản phẩm thỏa mãn điều kiện tìm kiếm để tính phân trang
    public function countSearchProducts($keyword) {
        // Truy vấn đếm số lượng dòng sản phẩm lọc theo tên bằng câu lệnh LIKE
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE Name LIKE :keyword";
        $stmt = $this->conn->prepare($query);
        $searchKey = "%" . $keyword . "%";
        $stmt->bindValue(':keyword', $searchKey, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        // Trả về tổng số sản phẩm tìm thấy
        return $row['total'];
    }

    // Lấy chi tiết sản phẩm theo ID
    public function getProductById($id) {
        // Thực hiện LEFT JOIN với bảng Category và lấy cả trường Image (p.Image as image)
        $query = "SELECT p.Id as id, p.Name as name, p.Description as description, p.Price as price, p.Image as image, p.is_best_selling, p.is_new, p.Category_Id as category_id, c.Name as category_name 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN Category c ON p.Category_Id = c.Id 
                  WHERE p.Id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    // Thêm sản phẩm mới kèm hình ảnh minh họa và các thuộc tính bán chạy / mới
    public function addProduct($name, $description, $price, $category_id, $image, $is_best_selling = 0, $is_new = 0) {
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

        // Tên các cột trong DB: Name, Description, Price, Image, Category_Id, is_best_selling, is_new
        $query = "INSERT INTO " . $this->table_name . " (Name, Description, Price, Image, Category_Id, is_best_selling, is_new) 
                  VALUES (:name, :description, :price, :image, :category_id, :is_best_selling, :is_new)";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $image = htmlspecialchars(strip_tags($image));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $is_best_selling = (int)$is_best_selling;
        $is_new = (int)$is_new;

        // Ràng buộc tham số
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':is_best_selling', $is_best_selling, PDO::PARAM_INT);
        $stmt->bindParam(':is_new', $is_new, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Cập nhật thông tin sản phẩm và hình ảnh (nếu được tải lên mới) kèm thuộc tính bán chạy / mới
    public function updateProduct($id, $name, $description, $price, $category_id, $image = null, $is_best_selling = 0, $is_new = 0) {
        if ($image !== null) {
            $query = "UPDATE " . $this->table_name . " 
                      SET Name=:name, Description=:description, Price=:price, Image=:image, Category_Id=:category_id, is_best_selling=:is_best_selling, is_new=:is_new 
                      WHERE Id=:id";
        } else {
            $query = "UPDATE " . $this->table_name . " 
                      SET Name=:name, Description=:description, Price=:price, Category_Id=:category_id, is_best_selling=:is_best_selling, is_new=:is_new 
                      WHERE Id=:id";
        }
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $name = htmlspecialchars(strip_tags($name));
        $description = htmlspecialchars(strip_tags($description));
        $price = htmlspecialchars(strip_tags($price));
        $category_id = htmlspecialchars(strip_tags($category_id));
        $is_best_selling = (int)$is_best_selling;
        $is_new = (int)$is_new;

        // Ràng buộc tham số
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':is_best_selling', $is_best_selling, PDO::PARAM_INT);
        $stmt->bindParam(':is_new', $is_new, PDO::PARAM_INT);

        if ($image !== null) {
            $image = htmlspecialchars(strip_tags($image));
            $stmt->bindParam(':image', $image);
        }

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
