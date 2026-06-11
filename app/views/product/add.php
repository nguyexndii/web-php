<?php include 'app/views/shares/header.php'; ?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="my-4 text-center text-primary">Thêm sản phẩm mới</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><strong>Lỗi:</strong> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Đã thêm thuộc tính enctype="multipart/form-data" để hỗ trợ upload hình ảnh -->
        <form method="POST" action="<?php echo BASE_PATH; ?>/Product/save" enctype="multipart/form-data" onsubmit="return validateForm();" class="shadow-sm p-4 rounded bg-white border">
            <div class="form-group">
                <label for="name" class="font-weight-bold">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm (ví dụ: iPhone 15 Pro Max)" required>
            </div>
            
            <div class="form-group">
                <label for="description" class="font-weight-bold">Mô tả chi tiết:</label>
                <textarea id="description" name="description" class="form-control" rows="4" placeholder="Mô tả các thông số kỹ thuật, bảo hành..." required></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="price" class="font-weight-bold">Giá sản phẩm (VNĐ):</label>
                        <input type="number" id="price" name="price" class="form-control" step="1000" min="0" placeholder="Ví dụ: 15000000" required>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="category_id" class="font-weight-bold">Danh mục:</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <option value="" disabled selected>-- Chọn danh mục sản phẩm --</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->id; ?>"><?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Ô chọn tải tệp tin ảnh minh họa lên -->
            <div class="form-group">
                <label for="image" class="font-weight-bold">Hình ảnh minh họa:</label>
                <div class="custom-file">
                    <input type="file" id="image" name="image" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label" for="image">Chọn hình ảnh sản phẩm...</label>
                </div>
                <small class="form-text text-muted">Hỗ trợ các định dạng ảnh: JPG, JPEG, PNG, GIF.</small>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block mt-4">Thêm sản phẩm</button>
        </form>
        
        <a href="<?php echo BASE_PATH; ?>/Product/" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm</a>
    </div>
</div>

<!-- Thêm Script nhỏ của Bootstrap để tự động hiển thị tên tệp tin ảnh vừa chọn -->
<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("image").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>
