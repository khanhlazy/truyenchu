<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\TruyenController;
use App\Http\Controllers\ChuongController;
use App\Http\Controllers\TheLoaiController;
use App\Http\Controllers\TimKiemController;
use App\Http\Controllers\TaiKhoanController;
use App\Http\Controllers\XacThucController;
use App\Http\Controllers\BinhLuanController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\YeuThichController;
use App\Http\Controllers\TheoDoiController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminTruyenController;
use App\Http\Controllers\Admin\AdminChuongController;
use App\Http\Controllers\Admin\AdminTheLoaiController;
use App\Http\Controllers\Admin\AdminBinhLuanController;
use App\Http\Controllers\Admin\AdminNguoiDungController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\AdminCauHinhController;
use App\Http\Controllers\Admin\AdminCrawlerController;

// ==========================================
// TRANG CÔNG KHAI
// ==========================================
Route::get('/', [TrangChuController::class, 'index'])->name('trang-chu');
Route::get('/truyen', [TruyenController::class, 'danhSach'])->name('truyen.danh-sach');
Route::get('/truyen/{slug}', [TruyenController::class, 'chiTiet'])->name('truyen.chi-tiet');
Route::get('/truyen/{truyen_slug}/chuong/{chuong_slug}', [ChuongController::class, 'doc'])->name('chuong.doc');
Route::get('/the-loai/{slug}', [TheLoaiController::class, 'danhSach'])->name('the-loai.danh-sach');
Route::get('/tim-kiem', [TimKiemController::class, 'timKiem'])->name('tim-kiem');
Route::get('/donate', function() {
    if (\App\Models\CauHinh::lay('donate_bat', '0') !== '1') abort(404);
    return view('donate', [
        'noiDung' => \App\Models\CauHinh::lay('donate_noi_dung', '')
    ]);
})->name('donate');

// ==========================================
// XÁC THỰC
// ==========================================
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [XacThucController::class, 'formDangNhap'])->name('dang-nhap');
    Route::post('/dang-nhap', [XacThucController::class, 'dangNhap']);
    Route::get('/dang-ky', [XacThucController::class, 'formDangKy'])->name('dang-ky');
    Route::post('/dang-ky', [XacThucController::class, 'dangKy']);
    Route::get('/quen-mat-khau', [XacThucController::class, 'formQuenMatKhau'])->name('quen-mat-khau');
    Route::post('/quen-mat-khau', [XacThucController::class, 'guiLinkDatLai']);
    Route::get('/dat-lai-mat-khau/{token}', [XacThucController::class, 'formDatLaiMatKhau'])->name('dat-lai-mat-khau');
    Route::post('/dat-lai-mat-khau', [XacThucController::class, 'datLaiMatKhau']);
});

Route::post('/dang-xuat', [XacThucController::class, 'dangXuat'])->name('dang-xuat')->middleware('auth');

// ==========================================
// KHU VỰC NGƯỜI DÙNG (cần đăng nhập)
// ==========================================
Route::middleware(['auth', 'check.trang_thai'])->group(function () {
    // Tài khoản
    Route::get('/tai-khoan', [TaiKhoanController::class, 'hoSo'])->name('tai-khoan');
    Route::put('/tai-khoan', [TaiKhoanController::class, 'capNhatHoSo'])->name('tai-khoan.cap-nhat');
    Route::put('/tai-khoan/mat-khau', [TaiKhoanController::class, 'doiMatKhau'])->name('tai-khoan.doi-mat-khau');

    // Yêu thích
    Route::get('/yeu-thich', [YeuThichController::class, 'danhSach'])->name('yeu-thich');
    Route::post('/yeu-thich/{truyen}', [YeuThichController::class, 'toggle'])->name('yeu-thich.toggle');

    // Theo dõi
    Route::get('/theo-doi', [TheoDoiController::class, 'danhSach'])->name('theo-doi');
    Route::post('/theo-doi/{truyen}', [TheoDoiController::class, 'toggle'])->name('theo-doi.toggle');

    // Lịch sử đọc
    Route::get('/lich-su-doc', [TaiKhoanController::class, 'lichSuDoc'])->name('lich-su-doc');
    Route::delete('/lich-su-doc', [TaiKhoanController::class, 'xoaLichSu'])->name('lich-su-doc.xoa');

    // Bình luận
    Route::post('/binh-luan', [BinhLuanController::class, 'gui'])->name('binh-luan.gui');

    // Chat
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');
    Route::post('/chat/gui', [ChatController::class, 'guiTinNhan'])->name('chat.gui');
    Route::get('/chat/tai', [ChatController::class, 'taiTinNhan'])->name('chat.tai');
});

// ==========================================
// KHU VỰC ADMIN
// ==========================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'check.admin'])->group(function () {
    // Dashboard
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Quản lý truyện
    Route::get('/truyen', [AdminTruyenController::class, 'danhSach'])->name('truyen.danh-sach');
    Route::get('/truyen/tao-moi', [AdminTruyenController::class, 'taoMoi'])->name('truyen.tao-moi');
    Route::post('/truyen', [AdminTruyenController::class, 'luu'])->name('truyen.luu');
    Route::get('/truyen/{id}/sua', [AdminTruyenController::class, 'sua'])->name('truyen.sua');
    Route::put('/truyen/{id}', [AdminTruyenController::class, 'capNhat'])->name('truyen.cap-nhat');
    Route::delete('/truyen/{id}', [AdminTruyenController::class, 'xoa'])->name('truyen.xoa');
    Route::patch('/truyen/{id}/toggle-publish', [AdminTruyenController::class, 'togglePublish'])->name('truyen.toggle-publish');

    // Quản lý chương
    Route::get('/truyen/{truyen_id}/chuong', [AdminChuongController::class, 'danhSach'])->name('chuong.danh-sach');
    Route::get('/truyen/{truyen_id}/chuong/tao-moi', [AdminChuongController::class, 'taoMoi'])->name('chuong.tao-moi');
    Route::post('/truyen/{truyen_id}/chuong', [AdminChuongController::class, 'luu'])->name('chuong.luu');
    Route::get('/chuong/{id}/sua', [AdminChuongController::class, 'sua'])->name('chuong.sua');
    Route::put('/chuong/{id}', [AdminChuongController::class, 'capNhat'])->name('chuong.cap-nhat');
    Route::delete('/chuong/{id}', [AdminChuongController::class, 'xoa'])->name('chuong.xoa');
    Route::patch('/chuong/{id}/toggle-publish', [AdminChuongController::class, 'togglePublish'])->name('chuong.toggle-publish');
    Route::post('/chuong/bulk-publish', [AdminChuongController::class, 'bulkPublish'])->name('chuong.bulk-publish');

    // Quản lý thể loại
    Route::get('/the-loai', [AdminTheLoaiController::class, 'danhSach'])->name('the-loai.danh-sach');
    Route::get('/the-loai/tao-moi', [AdminTheLoaiController::class, 'taoMoi'])->name('the-loai.tao-moi');
    Route::post('/the-loai', [AdminTheLoaiController::class, 'luu'])->name('the-loai.luu');
    Route::get('/the-loai/{id}/sua', [AdminTheLoaiController::class, 'sua'])->name('the-loai.sua');
    Route::put('/the-loai/{id}', [AdminTheLoaiController::class, 'capNhat'])->name('the-loai.cap-nhat');
    Route::delete('/the-loai/{id}', [AdminTheLoaiController::class, 'xoa'])->name('the-loai.xoa');

    // Quản lý bình luận
    Route::get('/binh-luan', [AdminBinhLuanController::class, 'danhSach'])->name('binh-luan.danh-sach');
    Route::patch('/binh-luan/{id}/duyet', [AdminBinhLuanController::class, 'duyet'])->name('binh-luan.duyet');
    Route::patch('/binh-luan/{id}/an', [AdminBinhLuanController::class, 'an'])->name('binh-luan.an');
    Route::delete('/binh-luan/{id}', [AdminBinhLuanController::class, 'xoa'])->name('binh-luan.xoa');

    // Quản lý người dùng
    Route::get('/nguoi-dung', [AdminNguoiDungController::class, 'danhSach'])->name('nguoi-dung.danh-sach');
    Route::patch('/nguoi-dung/{id}/toggle-trang-thai', [AdminNguoiDungController::class, 'toggleTrangThai'])->name('nguoi-dung.toggle-trang-thai');
    Route::patch('/nguoi-dung/{id}/toggle-mute', [AdminNguoiDungController::class, 'toggleMute'])->name('nguoi-dung.toggle-mute');

    // Quản lý chat
    Route::get('/chat', [AdminChatController::class, 'danhSach'])->name('chat.danh-sach');
    Route::delete('/chat/{id}', [AdminChatController::class, 'xoa'])->name('chat.xoa');

    // Cài đặt giao diện
    Route::get('/cau-hinh/giao-dien', [AdminCauHinhController::class, 'giaoD'])->name('cau-hinh.giao-dien');
    Route::post('/cau-hinh/giao-dien', [AdminCauHinhController::class, 'capNhatGiaoDien'])->name('cau-hinh.cap-nhat-giao-dien');

    // Cào Truyện (Crawler)
    Route::get('/crawler', [AdminCrawlerController::class, 'index'])->name('crawler.index');
    Route::post('/crawler/dispatch-batch', [AdminCrawlerController::class, 'dispatchBatch'])->name('crawler.dispatch-batch');
    Route::get('/crawler/queue-status', [AdminCrawlerController::class, 'getQueueStatus'])->name('crawler.queue-status');
    Route::post('/crawler/clear-failed', [AdminCrawlerController::class, 'clearFailedJobs'])->name('crawler.clear-failed');
    Route::post('/crawler/scan', [AdminCrawlerController::class, 'scan'])->name('crawler.scan');
    Route::post('/crawler/get-links', [AdminCrawlerController::class, 'getLinks'])->name('crawler.get-links');
    Route::post('/crawler/fetch', [AdminCrawlerController::class, 'fetchChapter'])->name('crawler.fetch');
});
