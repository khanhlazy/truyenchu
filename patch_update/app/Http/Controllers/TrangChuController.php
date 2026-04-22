<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrangChuController extends Controller
{
    public function index()
    {
        // Cache trang chủ 10 phút — trang được truy cập nhiều nhất
        $truyenMoiCapNhat = Cache::remember('trang_chu_moi_cap_nhat', 600, function () {
            return Truyen::daXuatBan()
                ->with([
                    'theLoai', 
                    'chuongMoiNhat' => function($query) {
                        $query->select('chuong.id', 'chuong.truyen_id', 'chuong.tieu_de', 'chuong.so_chuong', 'chuong.slug', 'chuong.updated_at');
                    }
                ])
                ->moiCapNhat()
                ->take(12)
                ->get();
        });

        $truyenHot = Cache::remember('trang_chu_truyen_hot', 600, function () {
            return Truyen::daXuatBan()
                ->with([
                    'theLoai',
                    'chuongMoiNhat' => fn($q) => $q->select('chuong.id', 'chuong.truyen_id', 'chuong.so_chuong')
                ])
                ->hot()
                ->take(10)
                ->get();
        });

        $truyenHoanThanh = Cache::remember('trang_chu_hoan_thanh', 600, function () {
            return Truyen::hoanThanh()
                ->with([
                    'theLoai',
                    'chuongMoiNhat' => fn($q) => $q->select('chuong.id', 'chuong.truyen_id', 'chuong.so_chuong')
                ])
                ->orderByDesc('tong_luot_xem')
                ->take(6)
                ->get();
        });

        $theLoaiNoiBat = Cache::remember('trang_chu_the_loai', 3600, function () {
            return TheLoai::withCount(['truyen' => fn($q) => $q->daXuatBan()])
                ->sapXep()
                ->take(10)
                ->get();
        });

        $topXemNhieu = Cache::remember('trang_chu_top_xem', 600, function () {
            return Truyen::daXuatBan()
                ->with(['chuongMoiNhat' => fn($q) => $q->select('chuong.id', 'chuong.truyen_id', 'chuong.so_chuong')])
                ->orderByDesc('tong_luot_xem')
                ->take(10)
                ->get();
        });

        return view('trang-chu', compact(
            'truyenMoiCapNhat',
            'truyenHot',
            'truyenHoanThanh',
            'theLoaiNoiBat',
            'topXemNhieu'
        ));
    }
}
