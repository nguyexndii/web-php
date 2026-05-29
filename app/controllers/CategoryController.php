<?php
// Require cấu hình cơ sở dữ liệu và Model danh mục
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    // Hiển thị danh sách các danh mục
    public function list() {
        $categories = $this->categoryModel->getCategories();
        // Lưu ý: Thư mục 'app/views/category/list.php' hiện tại chưa được định nghĩa trong cấu trúc ảnh mẫu
        include 'app/views/category/list.php';
    }
}
?>
