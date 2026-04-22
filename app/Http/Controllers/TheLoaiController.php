<?php

namespace App\Http\Controllers;

use App\Models\TheLoai;
use App\Models\Truyen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TheLoaiController extends Controller
{
    public function danhSach(Request $request, string $slug)
    {
        // Cache thể loại 1 giờ (ít thay đổi)
        $theLoai = Cache::remember("the_loai_{$slug}", 3600, function () use ($slug) {
            return TheLoai::where('slug', $slug)->firstOrFail();
        });

        $query = $theLoai->truyen()->daXuatBan()->with(['theLoai', 'chuongMoiNhat']);

        if ($request->filled('trang_thai')) {
            $query->trangThai($request->input('trang_thai'));
        }

        $query->sapXep($request->input('sap_xep', 'moi_cap_nhat'));

        $truyens = $query->paginate(18)->withQueryString();

        return view('the-loai.danh-sach', compact('theLoai', 'truyens'));
    }
}
