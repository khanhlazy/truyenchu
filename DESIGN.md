# TÀI LIỆU THIẾT KẾ HỆ THỐNG VÀ GIAO DIỆN (DESIGN.md)
**Dự án:** Nền tảng Đọc Truyện Trực Tuyến (Online Novel Platform)
**Ngôn ngữ:** Tiếng Việt
**Phiên bản:** 1.0

---

## 1. TỔNG QUAN DỰ ÁN
KTruyenNow là một nền tảng đọc truyện trực tuyến được xây dựng trên framework Laravel kết hợp với Tailwind CSS. Hệ thống hướng đến trải nghiệm người dùng tối ưu, tốc độ tải trang nhanh và cung cấp các tính năng từ cơ bản (đọc truyện, bình luận) đến nâng cao (quản lý tài khoản, crawler truyện tự động).

---

## 2. KIẾN TRÚC HỆ THỐNG (SYSTEM ARCHITECTURE)
- **Backend:** Laravel (PHP 8.x). Sử dụng mô hình MVC (Model-View-Controller) truyền thống kết hợp với các service phụ trợ.
- **Frontend:** Blade Templating Engine kết hợp với Tailwind CSS. Có tích hợp Alpine.js hoặc Vanilla JS cho các tương tác động (UI components, sliders, dropdowns).
- **Cơ sở dữ liệu:** MySQL / TiDB.
- **Lưu trữ tĩnh:** Local Storage / AWS S3 (cho ảnh bìa truyện, avatar).
- **Background Jobs:** Laravel Queue xử lý tác vụ cào truyện (Crawler) và gửi email.

---

## 3. PHÂN TÍCH CẤU TRÚC THƯ MỤC (FOLDER STRUCTURE)
Cấu trúc tuân theo chuẩn Laravel, được tinh chỉnh cho phù hợp:
- `app/Http/Controllers/`: Chứa logic điều khiển. Tách biệt rõ ràng `Admin` và frontend (`TrangChuController`, `TruyenController`, v.v.).
- `resources/views/`: 
  - `admin/`: Giao diện quản trị (Dashboard, Quản lý truyện, Crawl).
  - `auth/`: Giao diện đăng nhập, đăng ký.
  - `components/`: Chứa các Blade Component tái sử dụng (Button, Card, Form).
  - `layouts/`: Chứa layout chính của trang (`app.blade.php`, `admin.blade.php`).
  - `truyen/`, `chuong/`, `the-loai/`: Giao diện hiển thị theo từng module.
- `resources/css/app.css`: File chứa Design Tokens, định nghĩa CSS tuỳ chỉnh (Custom Layers) dựa trên Tailwind.
- `routes/web.php`: Nơi định nghĩa toàn bộ luồng điều hướng của ứng dụng.

---

## 4. PHÂN TÍCH MODULE CHỨC NĂNG
- **Người dùng (Frontend):** Trang chủ (Hero banner, top truyện), Danh sách truyện theo thể loại, Chi tiết truyện, Đọc chương, Tìm kiếm, Bình luận, Đánh dấu yêu thích/theo dõi, Lịch sử đọc, Chat cộng đồng.
- **Quản trị (Admin):** Thống kê (Dashboard), Quản lý CRUD (Truyện, Chương, Thể loại, Người dùng, Bình luận, Chat), Hệ thống Crawler tự động (Lấy link, Fetch nội dung), Cấu hình giao diện.

---

## 5. THIẾT KẾ CƠ SỞ DỮ LIỆU (DATABASE DESIGN)
Dựa trên các chức năng, cấu trúc DB lõi bao gồm:
- **users:** Quản lý thông tin tài khoản, phân quyền (admin/user).
- **truyens:** Thông tin truyện (tên, slug, ảnh bìa, mô tả, trạng thái).
- **chuongs:** Chi tiết chương truyện (truyen_id, số thứ tự, tiêu đề, nội dung, lượt xem).
- **the_loais / truyen_the_loai:** Quản lý danh mục thể loại và quan hệ nhiều-nhiều với truyện.
- **binh_luans:** Hệ thống bình luận (ẩn/hiện, đánh giá).
- **lich_su_docs, yeu_thichs, theo_dois:** Lưu trữ hành vi người dùng (pivot tables).
- **cau_hinhs:** Lưu trữ cấu hình động của website.

---

## 6. LUỒNG ĐIỀU HƯỚNG (ROUTE FLOW)
1. **Khách (Guest):** Có thể truy cập `/`, `/truyen`, `/truyen/{slug}`, `/truyen/{slug}/chuong/{slug}`, `/tim-kiem`, `/the-loai`.
2. **Người dùng (Auth):** Kế thừa luồng Guest + Quản lý cá nhân `/tai-khoan`, `/yeu-thich`, `/theo-doi`, `/lich-su-doc`, `/chat`.
3. **Quản trị viên (Admin):** Tiền tố `/admin/*`. Quản lý toàn bộ nội dung, cài đặt hệ thống và chạy công cụ Crawler.

---

## 7. HỆ THỐNG KIỂU CHỮ (TYPOGRAPHY SYSTEM)

### 7.1 Phân tích thực trạng
- **Hiện tại:** Đang sử dụng `Inter` (Sans-serif) cho UI và `Merriweather` (Serif) cho văn bản đọc truyện.
- **Hạn chế:** `Merriweather` đôi khi có dấu tiếng Việt hiển thị chưa thực sự mượt mà với một số weight nhất định ở các trình duyệt cũ.
- **Đề xuất mới:** Xây dựng hệ thống Typography ưu tiên tối đa hiển thị Tiếng Việt (Vietnamese diacritics) và tạo cảm giác cao cấp (Premium feeling).

### 7.2 Đề xuất Font Chữ
- **Primary Font (UI/Dashboard):** **`Be Vietnam Pro`** hoặc **`Inter`**. Cả hai đều có độ đọc tốt, nhưng `Be Vietnam Pro` được thiết kế dành riêng cho tiếng Việt, mang lại trải nghiệm xuất sắc cho các UI component.
- **Reading Font (Nội dung truyện):** **`Lora`** hoặc **`Literata`**. Hai font serif này hỗ trợ dấu tiếng Việt cực tốt, tối ưu hóa cho màn hình kỹ thuật số giúp người đọc không bị mỏi mắt khi đọc hàng chục chương truyện liên tục.

### 7.3 Cấu trúc Typography (H1 - H6 & Body)
*Quy chuẩn dành cho Tailwind CSS & Global:*

- **Font Family:**
  - `font-sans`: `'Be Vietnam Pro', 'Inter', system-ui, sans-serif`
  - `font-reading`: `'Lora', 'Literata', 'Merriweather', serif`

- **Font Sizes (Base 16px):**
  - **H1:** `text-4xl` (36px) / Mobile: `text-3xl` (30px) - Font: Primary
  - **H2:** `text-3xl` (30px) / Mobile: `text-2xl` (24px) - Font: Primary
  - **H3:** `text-2xl` (24px) / Mobile: `text-xl` (20px) - Font: Primary
  - **H4:** `text-xl` (20px) / Mobile: `text-lg` (18px) - Font: Primary
  - **H5:** `text-lg` (18px) - Font: Primary
  - **H6:** `text-base` (16px) uppercase, tracking-wider - Font: Primary
  - **Body (UI):** `text-sm` (14px) đến `text-base` (16px) - Font: Primary
  - **Body (Reading):** `text-lg` (18px) đến `text-xl` (20px) - Font: Reading, Line-height: `leading-loose` (1.8 hoặc 2.0).
  - **Small Text (Meta, Date, Kicker):** `text-xs` (12px) - Font: Primary

- **Font Weights:**
  - UI/Titles: `font-medium` (500), `font-semibold` (600), `font-bold` (700)
  - Reading Body: `font-normal` (400)

- **Line Heights (Độ giãn dòng):**
  - Titles (H1-H3): `leading-tight` (1.2)
  - UI Body: `leading-normal` (1.5)
  - Reading Content: `leading-[1.8]` hoặc `leading-loose` (để tạo sự thoải mái).

### 7.4 Cấu hình Tailwind CSS (`tailwind.config.js`)
```javascript
theme: {
    extend: {
        fontFamily: {
            sans: ['"Be Vietnam Pro"', '"Inter"', 'system-ui', 'sans-serif'],
            serif: ['"Lora"', '"Merriweather"', 'serif'],
            reading: ['"Lora"', '"Literata"', 'serif'], // Font chuyên dụng cho đọc truyện
        },
        fontSize: {
            'reading-sm': ['1.125rem', { lineHeight: '1.8' }], // 18px
            'reading-base': ['1.25rem', { lineHeight: '1.9' }], // 20px
            'reading-lg': ['1.375rem', { lineHeight: '2' }], // 22px
        }
    }
}
```

### 7.5 Cấu hình CSS Toàn cục (`app.css`)
```css
@import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&family=Lora:ital,wght@0,400;0,500;0,600;1,400&display=swap');

@layer base {
    html {
        font-family: var(--font-sans);
    }
    .reading-content {
        font-family: theme('fontFamily.reading');
        font-size: theme('fontSize.reading-base');
        color: var(--ui-text);
        letter-spacing: 0.01em;
    }
}
```

---

## 8. THIẾT KẾ UI/UX (UI/UX DESIGN)

### 8.1 Giao diện từng trang
- **Trang chủ:** Hero banner sử dụng ảnh background làm mờ (glassmorphism) để tạo điểm nhấn. Danh sách truyện xếp dạng Card (lưới 2-5 cột tùy thiết bị).
- **Trang chi tiết truyện:** Khối thông tin bên trên (Hero Detail) sử dụng gradient hoặc màu dark tone tạo sự cao cấp (`linear-gradient(135deg, #1e1b4b, #312e81)`). Dưới là danh sách chương dạng lưới chia cột.
- **Trang đọc truyện (Reading UI):** Tối giản tuyệt đối. Ẩn tối đa thanh công cụ không cần thiết. Thêm tính năng "Reading Progress Bar" trên cùng (fixed top). Giao diện ban đêm (Dark Mode) có nền xám đen (`#16161a`) chữ xám sáng để không chói.
- **Admin Dashboard:** Thanh Sidebar cố định bên trái, Header bên trên chứa thông tin tài khoản và breadcrumb. Sử dụng bảng (Table) có phân trang và thanh tìm kiếm tích hợp cho các trang quản lý.

### 8.2 Thiết kế Layout Component
- **Header:** Sticky top, có bóng đổ nhẹ (`shadow-sm`) khi cuộn trang. Chứa Logo, Thanh tìm kiếm nhanh, Avatar user / Nút Đăng nhập.
- **Footer:** Đơn giản hóa, chứa links giới thiệu, điều khoản, DMCA.
- **Cards (Thẻ truyện):** Tỷ lệ ảnh 2:3. Hiệu ứng hover (`transform scale(1.03)`). Bo góc `rounded-lg` (8px hoặc 12px).
- **Buttons (Nút bấm):** Cấu hình màu Primary (`#4f46e5` - Indigo-600). Bo góc `rounded-md`. Có hiệu ứng hover tối màu dần và hiệu ứng focus ring.
- **Forms & Inputs:** Nền trắng (hoặc nền xám nhẹ ở dark mode), viền `border-gray-200`. Khi focus đổi màu viền sang Primary.

### 8.3 Thiết kế đáp ứng (Responsive Design)
- **Mobile (< 640px):** 
  - Ẩn Sidebar admin thành Menu Off-canvas (Hamburger menu).
  - Lưới truyện chia 2 cột.
  - Form tìm kiếm full width.
  - Phông chữ giảm kích thước để tránh vỡ bố cục.
- **Tablet (640px - 1024px):** Lưới 3 hoặc 4 cột. Các thẻ Card tăng kích cỡ chữ nhẹ.
- **Desktop (> 1024px):** Max-width 1200px (hoặc 1440px). Lưới 5 hoặc 6 cột, trải nghiệm rộng rãi, tận dụng khoảng trắng (White-space).

---

## 9. ĐÁNH GIÁ BẢO MẬT (SECURITY REVIEW)
- **SQL Injection & XSS:** Laravel ORM và Blade (sử dụng `{{ }}`) mặc định ngăn chặn.
- **CSRF:** Luôn gắn `@csrf` vào mọi form.
- **Authentication & Authorization:** Hệ thống route admin đã bọc qua middleware `auth` và `check.admin`.
- **Cần cải thiện:** 
  - Bọc Rate Limiter cho các API Crawler hoặc form đăng nhập/bình luận để chống spam/brute-force.
  - Chắc chắn file `.env` không bị lộ qua web root.

---

## 10. ĐÁNH GIÁ HIỆU SUẤT (PERFORMANCE REVIEW)
- **Tối ưu DB:** Cần gắn chỉ mục (Index) trên các cột thường xuyên tìm kiếm như `slug`, `truyen_id`.
- **Cache:** Bật Redis/Memcached để cache kết quả truy vấn Trang chủ (danh sách truyện mới/hot), tránh query DB liên tục.
- **Ảnh:** Ảnh bìa cần nén (dùng định dạng WebP) và tối ưu hóa trước khi đưa lên S3 hoặc lưu ổ đĩa. Lazy load ảnh (`loading="lazy"`).
- **Pagination:** Sử dụng cursor pagination hoặc simple paginate của Laravel cho các trang có lượng dữ liệu lớn như danh sách chương để giảm tải đếm tổng số bản ghi.

---

## 11. ĐỀ XUẤT REFACTORING (CẢI TIẾN MÃ NGUỒN)
1. **Tách Biệt Crawler Service:** Logic crawler không nên nằm trong Controller. Nên tách ra thành các Job Classes (VD: `FetchChapterJob`, `CrawlStoryJob`) chạy ngầm thông qua Laravel Queue (Supervisor) để không làm block request của Admin.
2. **Tách View Components:** Thay vì lặp lại code HTML của Card Truyện, nên gom thành Blade Component (`<x-story-card :truyen="$truyen" />`).
3. **Thêm hệ thống View Cache:** Dùng `ResponseCache` cho các trang public ít thay đổi để tăng tốc độ phản hồi.
4. **Chuẩn hóa Design Tokens:** Đưa toàn bộ mã màu cứng (`#4f46e5`, `#16161a`) trong CSS ra file cấu hình `tailwind.config.js` để có 1 nguồn chân lý (Single Source of Truth) duy nhất, dễ dàng thay đổi theme sau này.
