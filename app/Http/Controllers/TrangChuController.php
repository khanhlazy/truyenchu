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
        $truyenMoiCapNhat = Truyen::daXuatBan()
            ->with([
                'theLoai', 
                'chuongMoiNhat' => function($query) {
                    $query->select('chuong.id', 'chuong.truyen_id', 'chuong.tieu_de', 'chuong.so_chuong', 'chuong.slug', 'chuong.updated_at');
                }
            ])
            ->moiCapNhat()
            ->take(12)
            ->get();

        $truyenHot = Truyen::daXuatBan()
            ->with([
                'theLoai',
                'chuongMoiNhat' => function($query) {
                    $query->select('chuong.id', 'chuong.truyen_id', 'chuong.tieu_de', 'chuong.so_chuong', 'chuong.slug', 'chuong.updated_at');
                }
            ])
            ->hot()
            ->take(10)
            ->get();

        $trendingTop = Truyen::daXuatBan()
            ->orderByDesc('tong_luot_xem')
            ->take(5)
            ->get();

        $monthlyTop = Truyen::daXuatBan()
            ->orderByDesc('tong_luot_theo_doi')
            ->take(5)
            ->get();

        $editorPicks = Truyen::daXuatBan()
            ->orderByDesc('tong_luot_yeu_thich')
            ->take(10)
            ->get();

        $dailyTop = Truyen::daXuatBan()
            ->orderByDesc('chuong_cap_nhat_luc')
            ->take(12)
            ->get();

        $truyenHoanThanh = Truyen::hoanThanh()
            ->with([
                'theLoai',
                'chuongMoiNhat' => function($query) {
                    $query->select('chuong.id', 'chuong.truyen_id', 'chuong.tieu_de', 'chuong.so_chuong', 'chuong.slug', 'chuong.updated_at');
                }
            ])
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
            'dailyTop',
            'topBinhLuans',
            'tinNhanCongDong'
        ));
    }
}
