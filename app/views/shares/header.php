<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    <!-- CSS Bootstrap 4.5.2 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome biểu tượng từ CDN -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    
    <!-- Khai báo hàm validateForm mặc định để biểu mẫu Add/Edit không bị lỗi trình duyệt -->
    <script>
        function validateForm() {
            // Trả về true để mặc định cho phép gửi dữ liệu, có thể viết thêm logic kiểm tra sau này
            return true;
        }
    </script>
</head>
<body>
    <!-- Thanh điều hướng navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/webbanhang/Product/">Quản lý sản phẩm</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/Product/">Danh sách sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/Product/add">Thêm sản phẩm</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/webbanhang/Product/cart">Giỏ hàng</a>
                </li>
            </ul>
            <!-- Form tìm kiếm sản phẩm gửi từ khóa qua tham số 'search' bằng phương thức GET -->
            <form class="form-inline ml-auto" action="/webbanhang/Product/" method="GET">
                <input class="form-control mr-2" type="text" name="search" placeholder="Tìm kiếm sản phẩm..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search'], ENT_QUOTES, 'UTF-8') : ''; ?>" required>
                <button class="btn btn-primary" type="submit">Tìm kiếm</button>
            </form>
        </div>
    </nav>
    
    <!-- Mở thẻ container chính để ôm lấy toàn bộ nội dung của các trang con (list, add, edit, show) -->
    <div class="container mt-4">
