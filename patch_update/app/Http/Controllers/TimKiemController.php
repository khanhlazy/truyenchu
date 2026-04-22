<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TimKiemController extends Controller
{
    public function timKiem(Request $request)
    {
        $truyens = collect();
        $tuKhoa = $request->input('tu_khoa');

        if ($tuKhoa || $request->filled('the_loai') || $request->filled('trang_thai')) {
            $query = Truyen::daXuatBan()->with(['theLoai', 'chuongMoiNhat']);

            if ($tuKhoa) {
                $query->timKiem($tuKhoa);
            }

            if ($request->filled('the_loai')) {
                $query->theoTheLoai($request->input('the_loai'));
            }

            if ($request->filled('trang_thai')) {
                $query->trangThai($request->input('trang_thai'));
            }

            $query->sapXep($request->input('sap_xep', 'moi_cap_nhat'));
            $truyens = $query->paginate(18)->withQueryString();
        }

        // Cache danh sách thể loại 1 giờ (dùng chung key với TruyenController)
        $theLoais = Cache::remember('danh_sach_the_loai', 3600, function () {
            return TheLoai::sapXep()->get();
        });

        return view('tim-kiem', compact('truyens', 'theLoais', 'tuKhoa'));
    }
}
