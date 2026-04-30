<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truyen;
use App\Models\Chuong;
use App\Models\NguoiDung;
use App\Models\BinhLuan;
use App\Models\TinNhanChat;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $thongKe = [
            'tong_truyen' => Truyen::count(),
            'tong_chuong' => Chuong::count(),
            'tong_nguoi_dung' => NguoiDung::count(),
            'binh_luan_cho_duyet' => BinhLuan::choDuyet()->count(),
            'tin_nhan_hom_nay' => TinNhanChat::whereDate('created_at', today())->count(),
        ];

        $truyenMoiTao = Truyen::orderByDesc('created_at')->take(5)->get();
        $chuongMoiTao = Chuong::with('truyen:id,tieu_de,slug')->orderByDesc('created_at')->take(5)->get();
        $nguoiDungMoi = NguoiDung::orderByDesc('created_at')->take(5)->get();

        return view('admin.dashboard', compact('thongKe', 'truyenMoiTao', 'chuongMoiTao', 'nguoiDungMoi'));
    }
}
