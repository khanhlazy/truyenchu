<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\Chuong;
use App\Models\LichSuDoc;
use App\Models\BinhLuan;
use Illuminate\Http\Request;

class ChuongController extends Controller
{
    public function doc(string $truyen_slug, string $chuong_slug)
    {
        $truyen = Truyen::where('slug', $truyen_slug)->daXuatBan()->firstOrFail();

        $chuong = Chuong::where('truyen_id', $truyen->id)
            ->where('slug', $chuong_slug)
            ->daXuatBan()
            ->firstOrFail();

        // Tăng lượt xem thông minh (session-based cooldown)
        // Dùng DB::table để tránh cập nhật updated_at của truyen (ảnh hưởng sắp xếp "mới cập nhật")
        $sessionKey = "xem_chuong_{$chuong->id}";
        if (!session()->has($sessionKey)) {
            $chuong->increment('tong_luot_xem');
            \Illuminate\Support\Facades\DB::table('truyen')
                ->where('id', $truyen->id)
                ->increment('tong_luot_xem');
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

        // Chương trước / sau
        $chuongTruoc = $chuong->chuongTruoc();
        $chuongSau = $chuong->chuongSau();

        // Danh sách chương cho dropdown
        $danhSachChuong = $truyen->chuongDaXuatBan()
            ->select('id', 'so_chuong', 'tieu_de', 'slug')
            ->get();

        // Bình luận
        $binhLuans = $chuong->binhLuan()
            ->hienThi()
            ->goc()
            ->with(['nguoiDung:id,ten_hien_thi,anh_dai_dien', 'binhLuanCon' => fn($q) => $q->hienThi()->with('nguoiDung:id,ten_hien_thi,anh_dai_dien')])
            ->orderByDesc('created_at')
            ->take(30)
            ->get();

        return view('chuong.doc', compact(
            'truyen', 'chuong', 'chuongTruoc', 'chuongSau',
            'danhSachChuong', 'binhLuans'
        ));
    }
}
