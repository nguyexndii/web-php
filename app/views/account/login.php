<?php include 'app/views/shares/header.php'; ?> 

<div class="row justify-content-center my-5">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow border-0 rounded-lg">
            <div class="card-header bg-white text-dark text-center py-4 rounded-top border-bottom">
                <h3 class="font-weight-bold mb-0 text-dark">Đăng Nhập</h3>
                <p class="text-muted mb-0 mt-1 small">Vui lòng nhập tài khoản và mật khẩu</p>
            </div>
            <div class="card-body p-4">
                <form id="login-form">
                    <div class="form-group">
                        <label for="username" class="font-weight-bold text-secondary">Tên đăng nhập</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Nhập username..." required autofocus>
                    </div>

                    <div class="form-group mt-3">
                        <label for="password" class="font-weight-bold text-secondary">Mật khẩu</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu..." required>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-dark btn-block py-2 font-weight-bold shadow-sm rounded-pill">
                            Đăng nhập
                        </button>
                    </div>
                </form>
            </div>
            <div class="card-footer bg-light text-center py-3 border-top-0 rounded-bottom">
                <span class="text-muted small">Chưa có tài khoản? </span>
                <a href="<?php echo BASE_PATH; ?>/account/register" class="font-weight-bold text-dark small">Đăng ký ngay</a>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
// Định nghĩa đường dẫn cơ sở động
const BASE_PATH = '<?php echo BASE_PATH; ?>';

document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Chặn hành động reload trang mặc định của form
    
    const formData = new FormData(this);
    const jsonData = {};
    formData.forEach((value, key) => {
        jsonData[key] = value;
    });

    // Gọi API đăng nhập dạng JSON
    fetch(BASE_PATH + '/account/checkLogin', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(jsonData)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Đăng nhập thất bại');
        }
        return response.json();
    })
    .then(data => {
        if (data.token) {
            // Lưu token JWT vào localStorage đúng theo hướng dẫn Bài 6 trong PDF
            localStorage.setItem('jwtToken', data.token);
            // Chuyển hướng về trang danh sách sản phẩm
            location.href = BASE_PATH + '/Product';
        } else {
            alert('Đăng nhập thất bại: Không nhận được token!');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Tên đăng nhập hoặc mật khẩu không chính xác!');
    });
});
</script>
