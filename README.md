# TRUYỆNCHỮ - WEBSITE ĐỌC TRUYỆN ONLINE

## Tóm tắt nội dung:
TruyệnChữ là một ứng dụng đọc truyện trực tuyến miễn phí, được phát triển nhằm cung cấp nền tảng đọc và tương tác chất lượng cho độc giả. Dự án tập trung vào việc xây dựng một trang web với giao diện tối ưu, hỗ trợ đọc truyện linh hoạt, chế độ tối (dark mode), bình luận, phòng chat trực tuyến và hệ thống quản trị chuyên sâu đảm bảo hiệu năng và tính bảo mật.

## 1. GIỚI THIỆU CHUNG
Hệ thống được thiết kế tối giản nhưng đầy đủ tính năng cho một website đọc truyện, phân chia người dùng thành 2 vai trò độc lập với các quyền hạn chuyên biệt:
- Người dùng (User): Tiếp cận nội dung (đọc truyện, tìm kiếm, lọc thể loại), tương tác (bình luận, yêu thích, theo dõi truyện, tham gia phòng chat chung) và quản lý tủ truyện (lịch sử đọc).
- Quản trị viên (Admin): Giám sát toàn bộ hệ thống, quản lý dữ liệu truyện, chương, thể loại, kiểm duyệt bình luận, quản lý tài khoản người dùng, giám sát phòng chat và theo dõi nhật ký hoạt động.

## 2. CÁC TÍNH NĂNG CỐT LÕI

### 2.1. Nhóm chức năng dành cho độc giả
- Khám phá nội dung: Trang chủ hiển thị truyện mới, truyện hot, top xem nhiều. Hỗ trợ tìm kiếm nâng cao theo từ khóa, thể loại và trạng thái truyện.
- Trải nghiệm đọc: Tự động lưu tiến trình đọc, tùy chỉnh cỡ chữ, chế độ tối (Dark mode) lưu cấu hình cục bộ, quản lý danh sách truyện yêu thích và theo dõi.
- Tương tác hệ thống: Bình luận trên từng truyện, tham gia phòng chat trực tuyến (cơ chế AJAX polling) với giới hạn tỷ lệ gửi tin nhắn (rate limit) chống spam.
- Quản lý tài khoản: Đăng ký, đăng nhập, quên mật khẩu, cập nhật hồ sơ cá nhân và ảnh đại diện.

### 2.2. Nhóm chức năng quản trị hệ thống
- Quản trị nội dung: Thêm, sửa, xóa truyện và chương. Hỗ trợ tối ưu SEO, tự động đếm số từ của chương truyện. Quản lý danh mục thể loại.
- Quản trị tương tác: Kiểm duyệt, ẩn hoặc xóa bình luận của người dùng. Giám sát và xóa tin nhắn vi phạm trong phòng chat.
- Quản lý định danh: Quản lý danh sách người dùng, thực hiện khóa/mở khóa tài khoản, thiết lập thời hạn cấm chat đối với tài khoản vi phạm.
- Giám sát hệ thống: Bảng điều khiển (Dashboard) thống kê tổng quan số liệu, tự động ghi nhận nhật ký kiểm duyệt (audit log) cho các thao tác của quản trị viên.

## 3. CÔNG NGHỆ VÀ KIẾN TRÚC HỆ THỐNG
Dự án áp dụng mô hình phát triển phần mềm MVC, tích hợp các công nghệ và kỹ thuật hiện đại:
- Hệ thống máy chủ (Backend): Framework Laravel 11 (PHP 8.2+).
- Cơ sở dữ liệu (Database): MySQL / MariaDB 8.0+.
- Giao diện người dùng (Frontend): Blade Template Engine, Tailwind CSS và Alpine.js.
- Quản lý tài nguyên (Build Tool): Vite.
- Tối ưu hóa và Bảo mật:
  - Bảo mật biểu mẫu với CSRF Protection.
  - Hạn chế tần suất gửi yêu cầu (Rate Limiting) cho bình luận (30s) và phòng chat (5s).
  - Cơ chế tính lượt xem thông minh (Smart view counting) dựa trên phiên làm việc.
  - Tối ưu SEO toàn diện (Meta title, description, Open Graph tags, JSON-LD structured data, robots.txt, Semantic HTML).

## 4. CẤU TRÚC THƯ MỤC DỰ ÁN
Cấu trúc mã nguồn tuân thủ tiêu chuẩn của framework Laravel:
- app/Http/Controllers/: Chứa logic xử lý điều hướng, phân tách thành các bộ điều khiển (Admin, TrangChu, Truyen, Chuong, TaiKhoan, Chat, v.v.).
- app/Models/: Các lớp thực thể tương tác với cơ sở dữ liệu qua Eloquent ORM.
- database/: Quản lý cấu trúc bảng (migrations) và dữ liệu mẫu khởi tạo (seeders).
- resources/views/: Các tệp tin giao diện hiển thị cho người dùng và quản trị viên.
- routes/web.php: Phân luồng tất cả các yêu cầu mạng của ứng dụng.
- public/: Lưu trữ tài nguyên tĩnh và là điểm truy cập chính.

## 5. HƯỚNG DẪN CÀI ĐẶT VÀ TRIỂN KHAI

### 5.1. Yêu cầu môi trường
- PHP >= 8.2
- Node.js >= 18
- Composer và NPM
- MySQL 8.0+

### 5.2. Các bước triển khai cục bộ
Bước 1: Tải mã nguồn từ kho lưu trữ và di chuyển vào thư mục dự án.

Bước 2: Cài đặt các gói phụ thuộc:
```bash
composer install
npm install
```

Bước 3: Thiết lập môi trường và khóa bảo mật:
```bash
cp .env.example .env
php artisan key:generate
```

Bước 4: Tạo cơ sở dữ liệu có tên "truyenchu" (utf8mb4_unicode_ci) và cập nhật thông tin kết nối trong tệp .env.

Bước 5: Khởi tạo dữ liệu hệ thống và liên kết lưu trữ:
```bash
php artisan migrate --seed
php artisan storage:link
```

Bước 6: Biên dịch tài nguyên và khởi động máy chủ:
```bash
npm run dev
php artisan serve
```
Hệ thống sẽ chạy tại địa chỉ http://localhost:8000

## 6. THÔNG TIN TÀI KHOẢN MẪU
- Quản trị viên: admin@truyenchu.test (Mật khẩu: password)
- Người dùng: user@truyenchu.test (Mật khẩu: password)

## 7. GIAO DIỆN DỰ ÁN

## 8. THÔNG TIN DỰ ÁN
Nhóm thực hiện: Phoenix Stack

| Thành viên          | Vai trò                                                          |
|---------------------|------------------------------------------------------------------|
| Nguyễn Huỳnh Khánh  | Fullstack Developer, System Architecture, Data Crawling Pipeline |
| Nguyễn Lê Tuấn Kiệt | Backend Developer, Database Design                               |
| Nguyễn Lê Bích Trâm | Frontend Developer, UI/UX Implementation                         |
| Đinh Trọng Hậu      | QA Tester, System Testing                                        |

- Mục đích: Dự án được phát triển nhằm phục vụ mục đích học tập, nghiên cứu và ứng dụng các công nghệ lập trình web hiện đại vào thực tiễn.
