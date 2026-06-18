<?php include 'app/views/shares/header.php'; ?>

<div class="row">
    <div class="col-md-10 offset-md-1">
        <h1 class="my-4 text-center text-primary font-weight-bold">Chi tiết sản phẩm</h1>
        
        <div class="card shadow-sm rounded mb-4 border">
            <div class="card-header bg-primary text-white py-3">
                <h2 class="h4 mb-0 font-weight-bold"><?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?></h2>
            </div>
            <div class="card-body p-4">
                <div class="row">
                    <!-- Cột trái: Hình ảnh minh họa sản phẩm -->
                    <div class="col-md-5 col-sm-12 text-center mb-4 mb-md-0 d-flex align-items-center justify-content-center">
                        <div class="border rounded bg-light p-2 shadow-sm d-flex align-items-center justify-content-center" style="width: 100%; max-width: 350px; height: 350px; overflow: hidden;">
                            <?php if (!empty($product->image) && file_exists('public/images/' . $product->image)): ?>
                                <img src="<?php echo BASE_PATH; ?>/public/images/<?php echo $product->image; ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" class="img-fluid rounded" style="max-height: 100%; object-fit: contain;">
                            <?php else: ?>
                                <div class="text-muted text-center">
                                    <i class="fas fa-box-open fa-5x mb-3 text-secondary"></i>
                                    <p class="font-weight-bold mb-0">Sản phẩm chưa có hình ảnh minh họa</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Cột phải: Thông số chi tiết sản phẩm -->
                    <div class="col-md-7 col-sm-12 pl-md-4">
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold text-muted">Mã định danh (ID):</div>
                            <div class="col-sm-8 text-dark font-weight-bold"><?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold text-muted">Danh mục sản phẩm:</div>
                            <div class="col-sm-8 d-flex align-items-center flex-wrap" style="gap: 5px;">
                                <span class="badge badge-info px-3 py-2 font-weight-bold">
                                    <?php echo htmlspecialchars($product->category_name ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8'); ?>
                                </span>
                                <?php if (isset($product->is_best_selling) && $product->is_best_selling == 1): ?>
                                    <span class="badge badge-danger px-3 py-2 font-weight-bold">BÁN CHẠY</span>
                                <?php endif; ?>
                                <?php if (isset($product->is_new) && $product->is_new == 1): ?>
                                    <span class="badge badge-success px-3 py-2 font-weight-bold">MỚI</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold text-muted">Giá bán niêm yết:</div>
                            <div class="col-sm-8 text-danger font-weight-bold h4">
                                <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row mb-3">
                            <div class="col-sm-4 font-weight-bold text-muted">Mô tả chi tiết:</div>
                            <div class="col-sm-8 text-dark text-justify" style="white-space: pre-line; line-height: 1.6;"><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card-footer bg-light d-flex justify-content-between p-3">
                <a href="<?php echo BASE_PATH; ?>/Product/" class="btn btn-secondary">Quay lại danh sách</a>
                <?php if (SessionHelper::isAdmin()): ?>
                    <div>
                        <a href="<?php echo BASE_PATH; ?>/Product/edit/<?php echo $product->id; ?>" class="btn btn-warning mr-2 text-dark font-weight-bold">Sửa sản phẩm</a>
                        <a href="<?php echo BASE_PATH; ?>/Product/delete/<?php echo $product->id; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa sản phẩm</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
