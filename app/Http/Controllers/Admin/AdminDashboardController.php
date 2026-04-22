<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truyen;
use App\Models\Chuong;
use App\Models\NguoiDung;
use App\Models\BinhLuan;
use App\Models\TinNhanChat;
use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Cache thống kê tổng quan 30 phút (1800 giây)
        $thongKe = Cache::remember('admin_dashboard_thong_ke', 1800, function () {
            return [
                'tong_truyen'         => Truyen::count(),
                'tong_chuong'         => Chuong::count(),
                'tong_nguoi_dung'     => NguoiDung::count(),
                'binh_luan_cho_duyet' => BinhLuan::choDuyet()->count(),
            ];
        });

        // Tin nhắn hôm nay - cache 5 phút, key theo ngày để tự reset
        $thongKe['tin_nhan_hom_nay'] = Cache::remember(
            'admin_dashboard_tin_nhan_' . today()->toDateString(),
            300,
            fn () => TinNhanChat::whereBetween('created_at', [today(), today()->endOfDay()])->count()
        );

        // Dữ liệu mới nhất - cache 10 phút, dùng orderByDesc('id') thay vì 'created_at'
        // Vì id auto-increment nên thứ tự id DESC = created_at DESC, nhưng dùng PK index nên cực nhanh
        $truyenMoiTao = Cache::remember('admin_dashboard_truyen_moi', 600,
            fn () => Truyen::orderByDesc('id')->take(5)->get()
        );

        $chuongMoiTao = Cache::remember('admin_dashboard_chuong_moi', 600,
            fn () => Chuong::with('truyen:id,tieu_de,slug')->orderByDesc('id')->take(5)->get()
        );

        $nguoiDungMoi = Cache::remember('admin_dashboard_nguoi_dung_moi', 600,
            fn () => NguoiDung::orderByDesc('id')->take(5)->get()
        );

        return view('admin.dashboard', compact('thongKe', 'truyenMoiTao', 'chuongMoiTao', 'nguoiDungMoi'));
    }
}
