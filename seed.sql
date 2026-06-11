-- File co so du lieu va du lieu mau du an WEBBANHANG

CREATE DATABASE if NOT EXISTS my_store;
USE my_store;

-- 1. Tao bang Category (Danh muc san pham)
CREATE TABLE if NOT EXISTS Category
(
    Id INT auto_increment PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Description TEXT
);

-- 2. Tao bang Product (San pham)
CREATE TABLE if NOT EXISTS Product
(
    Id INT auto_increment PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10, 2) NOT NULL,
    Image VARCHAR(255) default NULL,
    Category_Id INT,
    FOREIGN KEY (Category_Id) references Category(Id) ON DELETE cascade        
);

-- 3. Tao bang Orders (Don dat hang)
CREATE TABLE if NOT EXISTS Orders
(
    Id INT auto_increment PRIMARY KEY,
    Name VARCHAR(60) NOT NULL,
    Address TEXT,
    Phone VARCHAR(20),
    Created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. Tao bang Orders_Detail (Chi tiet don hang)
CREATE TABLE if NOT EXISTS Orders_Detail
(
    Id INT auto_increment PRIMARY KEY,
    Order_Id INT NOT NULL,
    Product_Id INT NOT NULL,
    Quantity INT NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,    
    FOREIGN KEY (Order_Id) references Orders(Id) ON DELETE CASCADE,    
    FOREIGN KEY (Product_Id) references Product(Id) ON DELETE NO ACTION
);

-- 5. Tao bang account (Tai khoan nguoi dung)
CREATE TABLE if NOT EXISTS account
(
    Id INT auto_increment PRIMARY KEY,
    Username VARCHAR(40) NOT NULL unique,
    Fullname VARCHAR(100) NOT NULL,
    Password VARCHAR(255) NOT NULL,    
    ROLE ENUM('admin','user') DEFAULT 'user'
);

-- Chen du lieu mau cho danh muc san pham (Category)
INSERT INTO Category(Name, Description) VALUES
('Điện thoại', 'Danh mục các loại điện thoại di động thông minh chính hãng'),
('Laptop', 'Danh mục các loại máy tính xách tay văn phòng và gaming'),
('Máy tính bảng', 'Danh mục các loại máy tính bảng màn hình lớn, cấu hình cao'),
('Phụ kiện', 'Danh mục các loại phụ kiện điện tử (chuột, bàn phím, cáp sạc...)'),
('Thiết bị âm thanh', 'Danh mục các loại loa Bluetooth, tai nghe không dây, micro...');

-- Chen du lieu mau cho 20 san pham (Product - khong kem anh)
INSERT INTO Product(Name, Description, Price, Image, Category_Id) VALUES
('iPhone 15 Pro Max', 'Điện thoại thông minh cao cấp nhất của Apple năm 2024, dung lượng 256GB.', 29990000, NULL, 1),
('MacBook Pro M3', 'Laptop cấu hình mạnh mẽ cho dân lập trình và đồ họa chuyên nghiệp.', 39990000, NULL, 2),
('iPad Air 5', 'Máy tính bảng sử dụng chip M1 mạnh mẽ, hỗ trợ Apple Pencil và Magic Keyboard.', 14500000, NULL, 3),
('Tai nghe AirPods Pro 2', 'Tai nghe không dây chống ồn chủ động vượt trội, âm thanh đỉnh cao.', 5990000, NULL, 5),
('Samsung Galaxy S24 Ultra', 'Flagship cao cấp nhất của Samsung với camera zoom 100x và bút S-Pen thông minh.', 27990000, NULL, 1),
('Laptop Gaming ASUS ROG', 'Dòng máy tính xách tay chuyên game với card đồ họa RTX 4060 cực khủng.', 24990000, NULL, 2),
('Bàn phím cơ Keychron', 'Bàn phím cơ không dây gõ cực êm, hỗ trợ tốt cho cả Windows và macOS.', 2150000, NULL, 4),
('Chuột Bluetooth Logitech', 'Chuột không dây công thái học, giúp làm việc văn phòng thoải mái, không mỏi tay.', 2490000, NULL, 4),
('Loa Bluetooth JBL Charge 5', 'Loa di động kháng nước bụi IP67, âm bass sâu và pin trâu lên tới 20 giờ.', 3950000, NULL, 5),
('Xiaomi Pad 6', 'Máy tính bảng phân khúc tầm trung cấu hình siêu mạnh mẽ, màn hình 144Hz.', 7990000, NULL, 3),
('iPhone 14 Pro', 'Điện thoại di động phân khúc cao cấp sở hữu Dynamic Island thời thượng.', 21500000, NULL, 1),
('Laptop Dell XPS 13', 'Dòng laptop doanh nhân siêu mỏng nhẹ, màn hình vô cực tràn viền tuyệt đẹp.', 32900000, NULL, 2),
('iPad Pro M2', 'Máy tính bảng mạnh nhất thế giới với chip M2 và màn hình Liquid Retina XDR.', 23990000, NULL, 3),
('Bàn phím cơ Akko 3098', 'Bàn phím cơ mang phong cách thiết kế retro cổ điển độc đáo, gõ rất đầm tay.', 1650000, NULL, 4),
('Chuột Razer DeathAdder V3', 'Chuột gaming siêu nhẹ với mắt đọc quang học độ nhạy cực cao cho game thủ.', 1890000, NULL, 4),
('Tai nghe Sony WH-1000XM5', 'Tai nghe chụp tai chống ồn chủ động tốt nhất thế giới hiện nay.', 7490000, NULL, 5),
('Loa Marshall Emberton II', 'Loa Bluetooth mang phong cách cổ điển, chất âm chi tiết, chống nước tốt.', 4190000, NULL, 5),
('Samsung Galaxy Tab S9', 'Máy tính bảng kháng nước bụi IP68 cao cấp kèm bút S-Pen thông minh.', 16990000, NULL, 3),
('Google Pixel 8 Pro', 'Điện thoại thông minh của Google hỗ trợ trí tuệ nhân tạo AI và camera siêu nét.', 19990000, NULL, 1),
('Laptop HP Envy 16', 'Laptop đồ họa mỏng nhẹ hiệu năng cao, màn hình OLED 120Hz sống động.', 28500000, NULL, 2);

-- Chen du lieu mau cho account (Tai khoan: admin/123qwe123, user1/123qwe123)
INSERT INTO account(Username, Fullname, Password, ROLE) VALUES
('admin', 'Admin User', '$2y$10$xeIJ06XVK765XH4Hn8Ee2OkcC27cuiOiPb9SJZ/pwyUmGEcbLNXfC', 'admin'),
('user1', 'Normal User', '$2y$10$xeIJ06XVK765XH4Hn8Ee2OkcC27cuiOiPb9SJZ/pwyUmGEcbLNXfC', 'user');

