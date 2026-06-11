<?php include 'app/views/shares/header.php'; ?> 

<?php
if (!SessionHelper::isAdmin()) {
    echo "<div class='alert alert-danger my-4'><i class='fas fa-exclamation-triangle mr-2'></i>Bạn không có quyền truy cập trang này!</div>";
    include 'app/views/shares/footer.php';
    exit;
}
?>

<h1>Sửa sản phẩm</h1> 

<form id="edit-product-form" class="shadow-sm p-4 rounded bg-white border"> 
    <input type="hidden" id="id" name="id"> 
    
    <div class="form-group"> 
        <label for="name" class="font-weight-bold">Tên sản phẩm:</label> 
        <input type="text" id="name" name="name" class="form-control" required> 
    </div> 
    
    <div class="form-group mt-3"> 
        <label for="description" class="font-weight-bold">Mô tả:</label> 
        <textarea id="description" name="description" class="form-control" rows="4" required></textarea> 
    </div> 
    
    <div class="form-group mt-3"> 
        <label for="price" class="font-weight-bold">Giá (VND):</label>
        <input type="number" id="price" name="price" class="form-control" required> 
    </div> 
    
    <div class="form-group mt-3"> 
        <label for="category_id" class="font-weight-bold">Danh mục:</label> 
        <select id="category_id" name="category_id" class="form-control" required> 
            <!-- Các danh mục sẽ được tải từ API và hiển thị tại đây --> 
        </select> 
    </div> 
    
    <button type="submit" class="btn btn-primary mt-4 font-weight-bold shadow-sm">Lưu thay đổi</button> 
</form> 

<a href="<?php echo BASE_PATH; ?>/Product/" class="btn btn-secondary mt-3"><i class="fas fa-arrow-left"></i> Quay lại danh sách sản phẩm</a> 


<?php include 'app/views/shares/footer.php'; ?> 

<script> 
const BASE_PATH = '<?php echo BASE_PATH; ?>';
const productId = <?php echo $product->id; ?>;

document.addEventListener("DOMContentLoaded", function() { 
    // Tải thông tin chi tiết sản phẩm từ API
    fetch(BASE_PATH + `/api/product/${productId}`) 
        .then(response => response.json()) 
        .then(data => { 
            document.getElementById('id').value = data.id; 
            document.getElementById('name').value = data.name; 
            document.getElementById('description').value = data.description; 
            document.getElementById('price').value = data.price; 
            document.getElementById('category_id').value = data.category_id; 
        }); 

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

    // Xử lý gửi cập nhật bằng AJAX
    document.getElementById('edit-product-form').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const formData = new FormData(this); 
        const jsonData = {}; 
        formData.forEach((value, key) => { 
            jsonData[key] = value; 
        }); 

        fetch(BASE_PATH + `/api/product/${jsonData.id}`, { 
            method: 'PUT', 
            headers: { 
                'Content-Type': 'application/json' 
            }, 
            body: JSON.stringify(jsonData) 
        }) 
        .then(response => response.json()) 
        .then(data => { 
            if (data.message === 'Product updated successfully') { 
                location.href = BASE_PATH + '/Product/'; 
            } else { 
                alert('Cập nhật sản phẩm thất bại'); 
            } 
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Lỗi kết nối máy chủ!');
        });
    }); 
}); 
</script>
