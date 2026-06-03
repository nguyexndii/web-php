<?php include 'app/views/shares/header.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h1 class="my-4 text-center text-primary font-weight-bold">Danh sách sản phẩm</h1>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="text-muted">Tổng số sản phẩm: <strong><?php echo $totalProducts; ?></strong></h5>
            <a href="/webbanhang/Product/add" class="btn btn-success font-weight-bold shadow-sm">Thêm sản phẩm mới</a>
        </div>
        
        <?php if (empty($products)): ?>
            <!-- Hiển thị thông báo khi không tìm thấy sản phẩm hoặc cửa hàng trống -->
            <div class="alert alert-info shadow-sm text-center py-4">
                <i class="fas fa-info-circle fa-2x mb-2 text-info"></i>
                <p class="mb-0"><?php echo !empty($search) ? 'Không tìm thấy sản phẩm nào với từ khóa "' . htmlspecialchars($search, ENT_QUOTES, 'UTF-8') . '".' : 'Hiện chưa có sản phẩm nào trong cửa hàng của bạn.'; ?></p>
            </div>
        <?php else: ?>
            <!-- Lưới hiển thị 4 sản phẩm trên một hàng ngang -->
            <div class="row">
                <?php foreach ($products as $product): ?>
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mb-4 d-flex align-items-stretch">
                        <div class="card shadow-sm w-100 border rounded d-flex flex-column justify-content-between">
                            <!-- Hình ảnh minh họa sản phẩm -->
                            <div class="text-center bg-light border-bottom position-relative" style="height: 200px; line-height: 200px; overflow: hidden;">
                                <?php if (!empty($product->image) && file_exists('public/images/' . $product->image)): ?>
                                    <img src="/webbanhang/public/images/<?php echo $product->image; ?>" alt="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>" class="w-100 h-100" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="h-100 w-100 d-flex flex-column align-items-center justify-content-center text-muted">
                                        <span class="small font-weight-bold">Không có ảnh</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Nội dung thẻ sản phẩm -->
                            <div class="card-body p-3 d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title font-weight-bold mb-2">
                                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="text-dark text-decoration-none" title="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>">
                                            <?php 
                                                // Tránh tiêu đề quá dài làm vỡ bố cục
                                                $name = htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8');
                                                echo strlen($name) > 35 ? substr($name, 0, 32) . '...' : $name;
                                            ?>
                                        </a>
                                    </h5>
                                    
                                    <p class="card-text text-muted mb-2 small" style="min-height: 40px;">
                                        <?php 
                                            // Rút ngắn phần mô tả để các thẻ đều nhau hơn
                                            $desc = htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8');
                                            echo strlen($desc) > 60 ? substr($desc, 0, 57) . '...' : $desc;
                                        ?>
                                    </p>
                                    
                                    <div class="mb-2">
                                        <span class="badge badge-info px-2 py-1"><i class="fas fa-tag"></i> <?php echo htmlspecialchars($product->category_name ?? 'Chưa phân loại', ENT_QUOTES, 'UTF-8'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="mt-2 border-top pt-2">
                                    <div class="text-danger font-weight-bold h6 text-center mb-0">
                                        <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Chân thẻ chứa các nút thao tác nhanh -->
                            <div class="card-footer bg-white border-top-0 p-2 d-flex flex-column">
                                <div class="d-flex justify-content-around mb-2">
                                    <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" class="btn btn-outline-primary btn-sm flex-fill mx-1">Chi tiết</a>
                                    <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" class="btn btn-outline-warning btn-sm text-dark font-weight-bold flex-fill mx-1">Sửa</a>
                                    <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" class="btn btn-outline-danger btn-sm flex-fill mx-1" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">Xóa</a>
                                </div>
                                <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" class="btn btn-primary btn-sm w-100">Thêm vào giỏ</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Thanh điều hướng liên kết chọn trang (Previous 1 2 3 Next) -->
            <?php if ($totalPages > 1): ?>
                <?php 
                    // Tạo chuỗi query tìm kiếm để đính kèm vào các link phân trang
                    $searchParam = !empty($search) ? '&search=' . urlencode($search) : ''; 
                ?>
                <nav aria-label="Page navigation" class="mt-4 d-flex justify-content-center">
                    <ul class="pagination shadow-sm border rounded mb-0">
                        <!-- Liên kết Previous -->
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link border-0 text-secondary" href="?page=<?php echo $page - 1; ?><?php echo $searchParam; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                        
                        <!-- Danh sách các trang dạng số -->
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                <a class="page-link border-0 <?php echo $i == $page ? 'bg-primary text-white font-weight-bold' : 'text-primary'; ?>" href="?page=<?php echo $i; ?><?php echo $searchParam; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <!-- Liên kết Next -->
                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link border-0 text-secondary" href="?page=<?php echo $page + 1; ?><?php echo $searchParam; ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>


<?php include 'app/views/shares/footer.php'; ?>
