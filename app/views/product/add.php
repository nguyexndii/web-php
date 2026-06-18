<?php include 'app/views/shares/header.php'; ?> 

<!-- Thông báo lỗi khi không có quyền truy cập -->
<div id="access-denied-message" style="display: none;" class="alert alert-danger my-4">
    <i class="fas fa-exclamation-triangle mr-2"></i>Bạn không có quyền truy cập trang này!
</div>

<!-- Container chứa form thêm sản phẩm, mặc định ẩn và chỉ hiển thị cho Admin -->
<div id="admin-form-container" style="display: none;">
    <h1>Thêm sản phẩm mới</h1> 

    <form id="add-product-form" class="shadow-sm p-4 rounded bg-white border"> 
        <div class="form-group"> 
            <label for="name" class="font-weight-bold">Tên sản phẩm:</label> 
            <input type="text" id="name" name="name" class="form-control" placeholder="Nhập tên sản phẩm..." required> 
        </div>
        
        <div class="form-group mt-3"> 
            <label for="description" class="font-weight-bold">Mô tả:</label> 
            <textarea id="description" name="description" class="form-control" rows="4" placeholder="Nhập mô tả sản phẩm..." required></textarea> 
        </div> 
        
        <div class="form-group mt-3"> 
            <label for="price" class="font-weight-bold">Giá (VND):</label> 
            <input type="number" id="price" name="price" class="form-control" placeholder="Nhập giá bán..." required> 
        </div> 
        
        <div class="form-group mt-3"> 
            <label for="category_id" class="font-weight-bold">Danh mục:</label> 
            <select id="category_id" name="category_id" class="form-control" required> 
                <!-- Các danh mục sẽ được tải từ API và hiển thị tại đây --> 
            </select> 
        </div> 
        
        <button type="submit" class="btn btn-primary mt-4 font-weight-bold shadow-sm">Thêm sản phẩm</button> 
    </form> 
</div>

<a href="<?php echo BASE_PATH; ?>/Product/" class="btn btn-secondary mt-3">Quay lại danh sách sản phẩm</a> 

<?php include 'app/views/shares/footer.php'; ?> 

<script> 
const BASE_PATH = '<?php echo BASE_PATH; ?>';

document.addEventListener("DOMContentLoaded", function() { 
    const token = localStorage.getItem('jwtToken');
    
    // Yêu cầu đăng nhập nếu chưa có token
    if (!token) {
        alert('Vui lòng đăng nhập!');
        location.href = BASE_PATH + '/account/login';
        return;
    }

    // Kiểm tra quyền Admin từ Token JWT để cho phép hiển thị form
    try {
        const base64Url = token.split('.')[1];
        const base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        const payload = JSON.parse(decodeURIComponent(escape(window.atob(base64))));
        
        if (payload.data && payload.data.role === 'admin') {
            document.getElementById('admin-form-container').style.display = 'block';
        } else {
            document.getElementById('access-denied-message').style.display = 'block';
            return; // Dừng thực thi các fetch bên dưới nếu không phải admin
        }
    } catch (e) {
        console.error("Lỗi xác minh token:", e);
        localStorage.removeItem('jwtToken');
        location.href = BASE_PATH + '/account/login';
        return;
    }

    // Tải danh mục từ API
    fetch(BASE_PATH + '/api/category') 
    .then(response => response.json()) 
    .then(data => { 
        const categorySelect = document.getElementById('category_id'); 
        data.forEach(category => { 
            const option = document.createElement('option'); 
            option.value = category.id; 
            option.textContent = category.name; 
            categorySelect.appendChild(option); 
        }); 
    }); 

    // Xử lý gửi form bằng AJAX
    document.getElementById('add-product-form').addEventListener('submit', function(event) { 
        event.preventDefault(); 
        const formData = new FormData(this); 
        const jsonData = {}; 
        formData.forEach((value, key) => { 
            jsonData[key] = value; 
        });

        fetch(BASE_PATH + '/api/product', { 
            method: 'POST', 
            headers: { 
                'Content-Type': 'application/json'
            }, 
            body: JSON.stringify(jsonData) 
        }) 
        .then(response => response.json()) 
        .then(data => { 
            if (data.message === 'Product created successfully') { 
                location.href = BASE_PATH + '/Product/'; 
            } else { 
                alert('Thêm sản phẩm thất bại: ' + (data.errors ? Object.values(data.errors).join(', ') : data.message)); 
            } 
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi kết nối máy chủ!');
        });
    }); 
}); 
</script>
