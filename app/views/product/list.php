<?php include 'app/views/shares/header.php'; ?> 

<h1>Danh sách sản phẩm</h1> 

<?php if (SessionHelper::isAdmin()): ?>
    <a href="<?php echo BASE_PATH; ?>/Product/add" class="btn btn-success mb-2">Thêm sản phẩm mới</a>
<?php endif; ?>

<ul class="list-group" id="product-list"> 
    <!-- Danh sách sản phẩm sẽ được tải từ API và hiển thị tại đây --> 
</ul> 

<?php include 'app/views/shares/footer.php'; ?> 

<script>
const BASE_PATH = '<?php echo BASE_PATH; ?>';
const isAdmin = <?php echo SessionHelper::isAdmin() ? 'true' : 'false'; ?>;

document.addEventListener("DOMContentLoaded", function() { 
    fetch(BASE_PATH + '/api/product') 
        .then(response => response.json()) 
        .then(data => {
            const productList = document.getElementById('product-list'); 
            data.forEach(product => { 
                const productItem = document.createElement('li'); 
                productItem.className = 'list-group-item my-2 shadow-sm rounded border'; 
                
                let adminButtons = '';
                if (isAdmin) {
                    adminButtons = `
                        <a href="${BASE_PATH}/Product/edit/${product.id}" class="btn btn-warning btn-sm mr-2 text-dark font-weight-bold">Sửa</a> 
                        <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Xóa</button>
                    `;
                }

                productItem.innerHTML = ` 
                    <h2><a href="${BASE_PATH}/Product/show/${product.id}" class="text-primary">${product.name}</a></h2> 
                    <p class="text-muted">${product.description}</p> 
                    <p class="mb-1"><strong>Giá:</strong> ${Number(product.price).toLocaleString('vi-VN')} VND</p> 
                    <p class="mb-2"><strong>Danh mục:</strong> ${product.category_name || 'Chưa phân loại'}</p> 
                    <div class="mt-2">
                        <a href="${BASE_PATH}/Product/addToCart/${product.id}" class="btn btn-primary btn-sm mr-2">Thêm vào giỏ</a>
                        ${adminButtons}
                    </div>
                `; 
                productList.appendChild(productItem); 
            }); 
        }); 
}); 

function deleteProduct(id) { 
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) { 
        fetch(BASE_PATH + `/api/product/${id}`, { 
            method: 'DELETE' 
        }) 
        .then(response => response.json()) 
        .then(data => { 
            if (data.message === 'Product deleted successfully') { 
                location.reload(); 
            } else { 
                alert('Xóa sản phẩm thất bại'); 
            } 
        }); 
    } 
} 
</script>
