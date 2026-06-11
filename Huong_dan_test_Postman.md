# Hướng Dẫn Kiểm Thử RESTful API Với Postman

Tài liệu này hướng dẫn chi tiết cách kiểm thử hệ thống RESTful API của dự án **webbanhang** (hoặc **web-php**) bằng công cụ Postman.

> **Lưu ý quan trọng**: Dự án đã được cấu hình đường dẫn động (`BASE_PATH`). Do đó, nếu bạn chạy thư mục tên là `webbanhang` thì đường dẫn sẽ có dạng `http://localhost/webbanhang/api/...`, còn nếu chạy ở thư mục `web-php` thì đường dẫn tự động đổi thành `http://localhost/web-php/api/...`. 
> *(Dưới đây lấy ví dụ mặc định với tên thư mục chạy là `webbanhang`)*.

---

## 1. Lấy danh sách toàn bộ sản phẩm (GET)
* **Phương thức**: `GET`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product`
* **Kết quả mong muốn**: Trạng thái `200 OK`. Phản hồi trả về một mảng JSON chứa danh sách các sản phẩm (mỗi sản phẩm gồm `id`, `name`, `description`, `price`, `category_name`).

---

## 2. Lấy thông tin chi tiết một sản phẩm (GET)
* **Phương thức**: `GET`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product/1` *(hoặc thay `1` bằng ID bất kỳ có trong bảng)*
* **Kết quả mong muốn**: Trạng thái `200 OK`. Trả về đối tượng JSON chứa thông tin chi tiết của sản phẩm đó.
* **Nếu không tìm thấy**: Trả về trạng thái `404 Not Found` kèm thông báo `{"message": "Product not found"}`.

---

## 3. Thêm mới một sản phẩm (POST)
* **Phương thức**: `POST`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product`
* **Cấu hình trên Postman**:
  1. Chuyển sang tab **Headers**, thêm một dòng:
     * **Key**: `Content-Type`
     * **Value**: `application/json`
  2. Chuyển sang tab **Body**, chọn kiểu dữ liệu là **raw** và chọn định dạng ở cuối dòng là **JSON**.
  3. Dán nội dung JSON mẫu sau vào ô dữ liệu:
     ```json
     {
         "name": "Sản phẩm thử nghiệm API",
         "description": "Mô tả sản phẩm được tạo tự động thông qua Postman",
         "price": 250000,
         "category_id": 1
     }
     ```
* **Kết quả mong muốn**: Trạng thái `201 Created` và phản hồi trả về thông báo:
  ```json
  {
      "message": "Product created successfully"
  }
  ```

---

## 4. Chỉnh sửa thông tin sản phẩm (PUT)
* **Phương thức**: `PUT`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product/1` *(thay `1` bằng ID sản phẩm bạn muốn sửa)*
* **Cấu hình trên Postman**:
  1. Trong tab **Headers**, đảm bảo có `Content-Type`: `application/json`.
  2. Trong tab **Body**, chọn **raw** và **JSON**.
  3. Nhập nội dung JSON mới cần cập nhật:
     ```json
     {
         "name": "iPhone 15 Pro Max (Đã cập nhật)",
         "description": "Cập nhật mô tả mới thông qua phương thức PUT API",
         "price": 31000000,
         "category_id": 1
     }
     ```
* **Kết quả mong muốn**: Trạng thái `200 OK` và phản hồi trả về:
  ```json
  {
      "message": "Product updated successfully"
  }
  ```

---

## 5. Xóa một sản phẩm (DELETE)
* **Phương thức**: `DELETE`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/product/21` *(thay `21` bằng ID sản phẩm bạn muốn xóa)*
* **Kết quả mong muốn**: Trạng thái `200 OK` và phản hồi trả về:
  ```json
  {
      "message": "Product deleted successfully"
  }
  ```

---

## 6. Lấy danh sách danh mục sản phẩm (GET)
* **Phương thức**: `GET`
* **Đường dẫn (URL)**: `http://localhost/webbanhang/api/category`
* **Kết quả mong muốn**: Trạng thái `200 OK`. Trả về mảng JSON chứa thông tin các danh mục sản phẩm.
