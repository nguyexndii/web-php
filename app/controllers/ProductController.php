<?php
// Require các file cấu hình và Model cần thiết
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Hiển thị danh sách sản phẩm (có tích hợp phân trang dạng lưới)
    public function index() {
        // Cấu hình phân trang: 8 sản phẩm trên một trang (hiển thị vừa vặn 2 hàng ngang, mỗi hàng 4 sản phẩm)
        $limit = 8;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        // Lấy sản phẩm có giới hạn LIMIT và OFFSET phục vụ phân trang
        $products = $this->productModel->getProductsLimit($offset, $limit);
        
        // Tính toán tổng số trang
        $totalProducts = $this->productModel->countProducts();
        $totalPages = ceil($totalProducts / $limit);

        include 'app/views/product/list.php';
    }

    // Xem chi tiết sản phẩm
    public function show($id) {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    }

    // Trang thêm sản phẩm mới
    public function add() {
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    // Xử lý lưu sản phẩm mới
    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;

            // Xử lý upload ảnh minh họa
            $image = $this->uploadImage();

            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
                exit();
            }
        }
    }

    // Giao diện chỉnh sửa sản phẩm
    public function edit($id) {
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            echo "Không tìm thấy sản phẩm.";
        }
    }

    // Xử lý cập nhật thông tin sản phẩm
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];

            // Xử lý upload ảnh minh họa (nếu có ảnh mới được chọn)
            $image = $this->uploadImage();

            // Nếu người dùng không tải ảnh mới lên, $image sẽ là null và giữ nguyên ảnh cũ
            $edit = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            if ($edit) {
                header('Location: /webbanhang/Product');
                exit();
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    // Xử lý xóa sản phẩm
    public function delete($id) {
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
            exit();
        } else {
            echo "Đã xảy ra lỗi khi xóa sản phẩm.";
        }
    }

    // Hàm phụ trợ xử lý upload hình ảnh sản phẩm
    private function uploadImage() {
        $imageName = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['image']['tmp_name'];
            $fileName = $_FILES['image']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            
            // Chỉ cho phép các định dạng ảnh hợp lệ
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileExtension, $allowedExtensions)) {
                $uploadFileDir = 'public/images/';
                if (!is_dir($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }
                
                // Tạo tên tệp ngẫu nhiên độc nhất để tránh bị trùng đè tên tệp
                $imageName = uniqid() . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $imageName;
                
                if (!move_uploaded_file($fileTmpPath, $dest_path)) {
                    $imageName = null;
                }
            }
        }
        return $imageName;
    }
}
?>
