<?php include 'app/views/shares/header.php'; ?>

<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="my-4 text-center text-primary">Chỉnh sửa sản phẩm</h1>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger shadow-sm">
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><strong>Lỗi:</strong> <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <!-- Đã thêm thuộc tính enctype="multipart/form-data" để hỗ trợ upload hình ảnh mới -->
        <form method="POST" action="<?php echo BASE_PATH; ?>/Product/update" enctype="multipart/form-data" onsubmit="return validateForm();" class="shadow-sm p-4 rounded bg-white border">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>">
            
            <div class="form-group">
                <label for="name" class="font-weight-bold">Tên sản phẩm:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description" class="font-weight-bold">Mô tả chi tiết:</label>
                <textarea id="description" name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="price" class="font-weight-bold">Giá sản phẩm (VNĐ):</label>
                        <input type="number" id="price" name="price" class="form-control" step="1000" value="<?php echo htmlspecialchars((int)$product->price, ENT_QUOTES, 'UTF-8'); ?>" required>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="category_id" class="font-weight-bold">Danh mục:</label>
                        <select id="category_id" name="category_id" class="form-control" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category->id; ?>" <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Xem trước hình ảnh sản phẩm cũ -->
            <div class="form-group">
                <label class="font-weight-bold">Hình ảnh sản phẩm hiện tại:</label>
                <div class="mb-3">
                    <?php if (!empty($product->image) && file_exists('public/images/' . $product->image)): ?>
                        <img src="<?php echo BASE_PATH; ?>/public/images/<?php echo $product->image; ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" class="img-thumbnail" style="max-height: 150px; object-fit: contain;">
                    <?php else: ?>
                        <div class="p-3 bg-light text-muted border rounded text-center d-inline-block" style="width: 150px; height: 150px; line-height: 110px;">
                            Chưa có ảnh
                        </div>
                    <?php endif; ?>
                </div>
                
                <label for="image" class="font-weight-bold">Thay thế bằng hình ảnh mới (Nếu muốn):</label>
                <div class="custom-file">
                    <input type="file" id="image" name="image" class="custom-file-input" accept="image/*">
                    <label class="custom-file-label" for="image">Chọn hình ảnh sản phẩm mới...</label>
                </div>
                <small class="form-text text-muted">Bỏ trống ô này nếu muốn giữ lại ảnh minh họa hiện tại.</small>
            </div>

            <!-- Đánh dấu sản phẩm bán chạy / mới -->
            <div class="form-group">
                <label class="font-weight-bold">Đánh dấu sản phẩm:</label>
                <div class="d-flex">
                    <div class="custom-control custom-checkbox mr-4">
                        <input type="checkbox" class="custom-control-input" id="is_best_selling" name="is_best_selling" value="1" <?php echo $product->is_best_selling ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="is_best_selling">Sản phẩm bán chạy</label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input" id="is_new" name="is_new" value="1" <?php echo $product->is_new ? 'checked' : ''; ?>>
                        <label class="custom-control-label" for="is_new">Sản phẩm mới</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-warning btn-block mt-4 text-dark font-weight-bold">Lưu thay đổi</button>
        </form>
        
        <a href="<?php echo BASE_PATH; ?>/Product/" class="btn btn-secondary mt-3">Quay lại danh sách sản phẩm</a>
    </div>
</div>

<!-- Script để tự động hiển thị tên tệp tin ảnh vừa chọn -->
<script>
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = document.getElementById("image").files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>

<?php include 'app/views/shares/footer.php'; ?>
