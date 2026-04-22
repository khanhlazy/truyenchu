<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\Chuong;
use App\Models\LichSuDoc;
use App\Models\BinhLuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChuongController extends Controller
{
    public function doc(string $truyen_slug, string $chuong_slug)
    {
        // Cache truyện theo slug — tránh query lặp lại cho mỗi chương của cùng 1 truyện
        $truyen = Cache::remember("truyen_slug_{$truyen_slug}", 3600, function () use ($truyen_slug) {
            return Truyen::where('slug', $truyen_slug)->daXuatBan()->firstOrFail();
        });

        // Cache nội dung chương — đây là query nặng nhất (longText noi_dung)
        $cacheKeyChuong = "chuong_{$truyen->id}_{$chuong_slug}";
        $chuong = Cache::remember($cacheKeyChuong, 3600, function () use ($truyen, $chuong_slug) {
            return Chuong::where('truyen_id', $truyen->id)
                ->where('slug', $chuong_slug)
                ->daXuatBan()
                ->firstOrFail();
        });

        // Tăng lượt xem thông minh (session-based cooldown)
        $sessionKey = "xem_chuong_{$chuong->id}";
        if (!session()->has($sessionKey)) {
            $chuong->increment('tong_luot_xem');
            $truyen->increment('tong_luot_xem');
            session()->put($sessionKey, true);
        }

        // Lưu lịch sử đọc
        if (auth()->check()) {
            LichSuDoc::updateOrCreate(
                [
                    'nguoi_dung_id' => auth()->id(),
                    'truyen_id' => $truyen->id,
                ],
                [
                    'chuong_id' => $chuong->id,
                    'thoi_diem_doc_cuoi' => now(),
                ]
            );

            // Cập nhật chương cuối cho theo dõi
            auth()->user()->theoDoi()
                ->where('truyen_id', $truyen->id)
                ->update(['chuong_cuoi_id' => $chuong->id]);
        }

        // Cache chương trước / sau — tránh 2 query mỗi lần đọc
        $chuongTruoc = Cache::remember("chuong_truoc_{$chuong->id}", 3600, function () use ($chuong) {
            return $chuong->chuongTruoc();
        });

        $chuongSau = Cache::remember("chuong_sau_{$chuong->id}", 3600, function () use ($chuong) {
            return $chuong->chuongSau();
        });

        // Cache danh sách chương cho dropdown — nặng nếu truyện có nhiều chương
        $danhSachChuong = Cache::remember("ds_chuong_truyen_{$truyen->id}", 1800, function () use ($truyen) {
            return $truyen->chuongDaXuatBan()
                ->select('id', 'so_chuong', 'tieu_de', 'slug')
                ->get();
        });

        // Bình luận — cache ngắn 5 phút vì cần cập nhật nhanh
        $binhLuans = Cache::remember("binh_luan_chuong_{$chuong->id}", 300, function () use ($chuong) {
            return $chuong->binhLuan()
                ->hienThi()
                ->goc()
                ->with(['nguoiDung:id,ten_hien_thi,anh_dai_dien', 'binhLuanCon' => fn($q) => $q->hienThi()->with('nguoiDung:id,ten_hien_thi,anh_dai_dien')])
                ->orderByDesc('created_at')
                ->take(30)
                ->get();
        });

        return view('chuong.doc', compact(
            'truyen', 'chuong', 'chuongTruoc', 'chuongSau',
            'danhSachChuong', 'binhLuans'
        ));
    }
}
