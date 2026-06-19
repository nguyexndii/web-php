<?php include 'app/views/shares/header.php'; ?> 

<h1>Danh sách sản phẩm</h1> 
<!-- Nút thêm sản phẩm ẩn mặc định, hiển thị cho Admin -->
<a href="<?php echo BASE_PATH; ?>/Product/add" id="btn-add-product" class="btn btn-success mb-2" style="display: none;">Thêm sản phẩm mới</a> 

<ul class="list-group" id="product-list"> 
    <!-- Danh sách sản phẩm sẽ được tải từ API và hiển thị tại đây --> 
</ul> 

<?php include 'app/views/shares/footer.php'; ?> 

<script>
const BASE_PATH = '<?php echo BASE_PATH; ?>';

document.addEventListener("DOMContentLoaded", function() { 
    // Lấy token từ localStorage
    const token = localStorage.getItem('jwtToken');
    
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    if (!token) {
        alert('Vui lòng đăng nhập');
        location.href = BASE_PATH + '/account/login';
        return;
    }

    let isAdmin = false;
    // Giải mã token ở client để phân quyền giao diện
    try {
        // Giải mã payload từ JWT 
        const base64Url = token.split('.')[1];
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        const payload = JSON.parse(decodeURIComponent(escape(window.atob(base64))));
        if (payload.data && payload.data.role === 'admin') {
            isAdmin = true;
            document.getElementById('btn-add-product').style.display = 'inline-block';
        }
    } catch (e) {
        console.error("Lỗi giải mã token tại list page:", e);
    }

    // Tải danh sách sản phẩm từ API có đính kèm JWT Token
    fetch(BASE_PATH + '/api/product', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        }
    }) 
    .then(response => response.json()) 
    .then(data => {
        const productList = document.getElementById('product-list'); 
        data.forEach(product => { 
            const productItem = document.createElement('li'); 
            productItem.className = 'list-group-item my-2 shadow-sm rounded border'; 
            
            // Xây dựng các nút quản trị chỉ cho Admin
            let adminButtons = '';
            if (isAdmin) {
                adminButtons = `
                    <a href="${BASE_PATH}/Product/edit/${product.id}" class="btn btn-warning btn-sm mr-2 text-dark font-weight-bold">Sửa</a> 
                    <button class="btn btn-danger btn-sm" onclick="deleteProduct(${product.id})">Xóa</button>
                `;
            }

            // Hiển thị đầy đủ thông tin cùng nút "Thêm vào giỏ"
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

// Hàm xóa sản phẩm
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
