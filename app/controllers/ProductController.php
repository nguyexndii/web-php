<?php
// Require các file cấu hình và Model cần thiết
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class ProductController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    // Kiểm tra quyền Admin
    private function isAdmin() {
        return SessionHelper::isAdmin();
    }

    // Hiển thị danh sách sản phẩm (có tích hợp phân trang dạng lưới và tìm kiếm)
    public function index() {
        // Cấu hình phân trang: 8 sản phẩm trên một trang
        $limit = 8;
        $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }
        $offset = ($page - 1) * $limit;

        // Nhận từ khóa tìm kiếm nếu có
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Nếu có từ khóa tìm kiếm thì gọi hàm search của model
        if ($search !== '') {
            $products = $this->productModel->searchProductsLimit($search, $offset, $limit);
            $totalProducts = $this->productModel->countSearchProducts($search);
        } else {
            // Ngược lại lấy danh sách bình thường
            $products = $this->productModel->getProductsLimit($offset, $limit);
            $totalProducts = $this->productModel->countProducts();
        }
        
        // Tính toán tổng số trang
        $totalPages = ceil($totalProducts / $limit);

        include 'app/views/product/list.php';
    }

    // Đồng bộ action list về index để tránh lỗi "Action not found: list" khi truy cập /Product/list
    public function list() {
        $this->index();
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
        // Cho phép truy cập công khai trang HTML, quyền bảo mật sẽ được kiểm tra ở client-side bằng JS (JWT)
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    // Xử lý lưu sản phẩm mới
    public function save() {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
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
                header('Location: ' . BASE_PATH . '/Product');
                exit();
            }
        }
    }

    // Giao diện chỉnh sửa sản phẩm
    public function edit($id) {
        // Cho phép truy cập công khai trang HTML, quyền bảo mật sẽ được kiểm tra ở client-side bằng JS (JWT)
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
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
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
                header('Location: ' . BASE_PATH . '/Product');
                exit();
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    // Xử lý xóa sản phẩm
    public function delete($id) {
        if (!$this->isAdmin()) {
            echo "Bạn không có quyền truy cập chức năng này!";
            exit;
        }
        if ($this->productModel->deleteProduct($id)) {
            header('Location: ' . BASE_PATH . '/Product');
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

    // Thêm sản phẩm vào giỏ hàng và lưu trong Session
    public function addToCart($id) {
        $product = $this->productModel->getProductById($id);
        if(!$product){
            echo "Không tìm thấy sản phẩm";
            return;
        }

        // Tạo giỏ hàng hoặc cập nhật số lượng nếu sản phẩm đã có
        if(isset($_SESSION['cart'][$id])){
            $_SESSION['cart'][$id]['quantity']++;
        }else{
            $_SESSION['cart'][$id]=[
                'name' => $product->name,
                'price' => $product->price,
                'quantity'=> 1,
                'image' => $product->image
            ];
        }

        // Chuyển hướng đến trang giỏ hàng và dừng chương trình
        header('Location: ' . BASE_PATH . '/Product/cart');
        exit();
    }

    // Hiển thị trang giỏ hàng
    public function cart(){
        $cart = isset($_SESSION['cart'])? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    // Hiển thị trang thanh toán thông tin đơn hàng
    public function checkout(){
        // Chặn không cho vào trang thanh toán nếu giỏ hàng rỗng
        if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
            header('Location: ' . BASE_PATH . '/Product/cart');
            exit();
        }
        include 'app/views/product/checkout.php';
    }

    // Xử lý lưu đơn hàng và chi tiết đơn hàng
    public function processCheckout(){
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];

            // Kiểm tra giỏ hàng trống trước khi lưu
            if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
                header('Location: ' . BASE_PATH . '/Product/cart');
                exit();
            }

            // Bắt đầu giao dịch an toàn
            $this->db->beginTransaction();

            try{
                // Thêm thông tin đơn hàng mới
                $query = "INSERT INTO Orders (Name, Phone, Address) VALUES (:name, :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt -> bindParam(':name', $name);
                $stmt -> bindParam(':phone', $phone);
                $stmt -> bindParam(':address', $address);
                $stmt -> execute();
                $order_id = $this->db->lastInsertId();

                // Lưu từng sản phẩm từ giỏ hàng vào bảng chi tiết đơn hàng Orders_Detail
                $cart = $_SESSION['cart'];
                foreach($cart as $product_id => $item){
                    $queryDetail = "INSERT INTO Orders_Detail (Order_Id, Product_Id, Quantity, Price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmtDetail = $this->db->prepare($queryDetail);
                    $stmtDetail->execute([
                        ':order_id' => $order_id,
                        ':product_id' => $product_id,
                        ':quantity' => $item['quantity'],
                        ':price' => $item['price']
                    ]);
                }
                
                // Xóa giỏ hàng sau khi đặt thành công
                unset($_SESSION['cart']);

                // Xác nhận giao dịch thành công
                $this->db->commit();

                // Chuyển hướng kèm mã đơn hàng
                header('Location: ' . BASE_PATH . '/Product/orderConfirmation/' . $order_id);
                exit();
            }catch(Exception $e){ 
                $this->db->rollBack();
                echo "Xảy ra lỗi: ". $e->getMessage();
            }
        }
    }

    // Hiển thị trang xác nhận đơn hàng thành công và mã QR thanh toán
    public function orderConfirmation($orderId = null){
        $orderTotal = 0;
        // Nếu có mã đơn hàng, truy vấn tính tổng số tiền đơn hàng để làm mã QR
        if ($orderId) {
            $query = "SELECT SUM(Quantity * Price) as total FROM Orders_Detail WHERE Order_Id = :order_id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            $orderTotal = $result ? $result->total : 0;
        }
        include 'app/views/product/orderConfirmation.php';
    }

    // public 
}
?>
