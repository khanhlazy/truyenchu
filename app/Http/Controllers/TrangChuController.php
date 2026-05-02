<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\TheLoai;
use App\Models\BinhLuan;
use App\Models\TinNhanChat;
use Illuminate\Http\Request;

class TrangChuController extends Controller
{
    public function index()
    {
        $excludeIds = [];

        // 1. Truyện Hot (Đọc nhiều nhất - Grid 1)
        $truyenHot = Truyen::daXuatBan()
            ->with(['theLoai', 'chuongMoiNhat'])
            ->orderByDesc('tong_luot_xem')
            ->take(10)
            ->get();
        $excludeIds = array_merge($excludeIds, $truyenHot->pluck('id')->toArray());

        // 2. Biên tập viên đề cử (Yêu thích nhất - Banner Indigo)
        $editorPicks = Truyen::daXuatBan()
            ->whereNotIn('id', $excludeIds)
            ->orderByDesc('tong_luot_yeu_thich')
            ->take(10)
            ->get();
        $excludeIds = array_merge($excludeIds, $editorPicks->pluck('id')->toArray());

        // 3. Mới cập nhật (Dựa trên thời gian đăng chương - Grid 2)
        $truyenMoiCapNhat = Truyen::daXuatBan()
            ->whereNotIn('id', $excludeIds)
            ->with(['theLoai', 'chuongMoiNhat'])
            ->orderByDesc('chuong_cap_nhat_luc')
            ->take(12)
            ->get();
        $excludeIds = array_merge($excludeIds, $truyenMoiCapNhat->pluck('id')->toArray());

        // 4. Bảng xếp hạng (Vẫn giữ nguyên top thật, cho phép trùng vì là BXH)
        $trendingTop = Truyen::daXuatBan()
            ->orderByDesc('tong_luot_xem')
            ->take(5)
            ->get();

        $monthlyTop = Truyen::daXuatBan()
            ->orderByDesc('tong_luot_theo_doi')
            ->take(5)
            ->get();

        // 5. Truyện đã hoàn thành
        $truyenHoanThanh = Truyen::hoanThanh()
            ->with(['theLoai', 'chuongMoiNhat'])
            ->orderByDesc('tong_luot_xem')
            ->take(10)
            ->get();

        $theLoaiNoiBat = TheLoai::withCount(['truyen' => fn($q) => $q->daXuatBan()])
            ->sapXep()
            ->take(12)
            ->get();

        $topBinhLuans = BinhLuan::hienThi()
            ->goc()
            ->with([
                'nguoiDung:id,ten_hien_thi,anh_dai_dien',
                'truyen:id,tieu_de,slug',
            ])
            ->latest()
            ->take(6)
            ->get();

        $tinNhanCongDong = TinNhanChat::with('nguoiDung:id,ten_hien_thi,anh_dai_dien')
            ->latest()
            ->take(6)
            ->get();

        return view('trang-chu', compact(
            'truyenMoiCapNhat',
            'truyenHot',
            'truyenHoanThanh',
            'theLoaiNoiBat',
            'trendingTop',
            'monthlyTop',
            'editorPicks',
            'topBinhLuans',
            'tinNhanCongDong'
        ));
    }
}
