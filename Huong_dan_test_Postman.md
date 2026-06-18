### Bước 1: Đăng nhập để lấy mã xác thực JWT Token
Do hệ thống đã được bảo mật, trước tiên bạn cần gọi API đăng nhập để lấy mã token.
* **Phương thức**: `POST`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/account/checkLogin`
* **Cấu hình trên Postman**:
  1. Trong tab **Headers**, thêm một dòng:
     * **Key**: `Content-Type`
     * **Value**: `application/json`
  2. Trong tab **Body**, chọn kiểu dữ liệu **raw** và chọn định dạng **JSON**.
  3. Dán tài khoản mẫu dưới đây vào ô dữ liệu:
     ```json
     {
         "username": "admin",
         "password": "123"
     }
     ```
     *(Lưu ý: Mật khẩu mặc định của tài khoản `admin` trong database mẫu của bạn là `123`)*
* **Kết quả mong muốn**: Trạng thái `200 OK`. Phản hồi trả về có dạng:
  ```json
  {
      "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
  }
  ```
👉 Hãy **sao chép toàn bộ chuỗi token** trong dấu ngoặc kép này để sử dụng cho các bước tiếp theo.

---

### Bước 2: Thiết lập Token xác thực trên Postman
Với mọi yêu cầu API lấy dữ liệu hay thao tác dữ liệu bên dưới, bạn bắt buộc phải đính kèm Token xác thực:
1. Tạo request mới trên Postman.
2. Chuyển sang tab **Authorization** (ngay cạnh tab Headers).
3. Tại mục **Type**, chọn **Bearer Token**.
4. Dán chuỗi token đã sao chép ở Bước 1 vào ô **Token**.
*(Postman sẽ tự động sinh Header `Authorization: Bearer <token>` cho request này)*

---

### Bước 3: Kiểm thử các API quản lý sản phẩm

#### 1. Lấy danh sách toàn bộ sản phẩm (GET)
* **Phương thức**: `GET`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product`
* **Yêu cầu**: Phải có cấu hình Bearer Token ở Bước 2.
* **Kết quả mong muốn**: Trạng thái `200 OK`. Trả về mảng JSON danh sách sản phẩm.
* **Nếu không có token**: Trả về `401 Unauthorized` kèm thông báo `{"message": "Unauthorized"}`.

#### 2. Lấy thông tin chi tiết một sản phẩm (GET)
* **Phương thức**: `GET`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product/1`
* **Kết quả mong muốn**: Trạng thái `200 OK`. Trả về đối tượng JSON chứa thông tin chi tiết sản phẩm.

#### 3. Thêm mới một sản phẩm (POST)
* **Phương thức**: `POST`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product`
* **Cấu hình trên Postman**:
  1. Đảm bảo đã chọn **Bearer Token** ở tab **Authorization**.
  2. Trong tab **Headers**, đảm bảo có `Content-Type`: `application/json`.
  3. Trong tab **Body**, chọn **raw** và **JSON**, sau đó dán dữ liệu mẫu:
     ```json
     {
         "name": "Sản phẩm bảo mật JWT",
         "description": "Sản phẩm được tạo thông qua API có bảo mật bằng Token",
         "price": 450000,
         "category_id": 1
     }
     ```
* **Kết quả mong muốn**: Trạng thái `201 Created` kèm thông báo thành công.

#### 4. Chỉnh sửa thông tin sản phẩm (PUT)
* **Phương thức**: `PUT`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product/1`
* **Cấu hình trên Postman**:
  1. Thiết lập **Bearer Token** ở tab **Authorization**.
  2. Tab **Body** chọn **raw** và **JSON** kèm dữ liệu mới:
     ```json
     {
         "name": "iPhone 15 Pro Max (Đã sửa qua JWT)",
         "description": "Cập nhật mô tả mới thông qua phương thức PUT API có token",
         "price": 32000000,
         "category_id": 1
     }
     ```
* **Kết quả mong muốn**: Trạng thái `200 OK` kèm thông báo cập nhật thành công.

#### 5. Xóa một sản phẩm (DELETE)
* **Phương thức**: `DELETE`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product/21` *(thay 21 bằng ID sản phẩm muốn xóa)*
* **Yêu cầu**: Thiết lập **Bearer Token** ở tab **Authorization**.
* **Kết quả mong muốn**: Trạng thái `200 OK` và thông báo xóa thành công.
