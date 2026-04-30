<?php

namespace App\Http\Controllers;

use App\Models\TheLoai;
use App\Models\Truyen;
use Illuminate\Http\Request;

class TheLoaiController extends Controller
{
    public function danhSach(Request $request, string $slug)
    {
        $theLoai = TheLoai::where('slug', $slug)->firstOrFail();

        $query = $theLoai->truyen()->daXuatBan()->with(['theLoai', 'chuongMoiNhat']);

        if ($request->filled('trang_thai')) {
            $query->trangThai($request->input('trang_thai'));
        }

        $query->sapXep($request->input('sap_xep', 'moi_cap_nhat'));

        $truyens = $query->paginate(18)->onEachSide(2)->withQueryString();

        return view('the-loai.danh-sach', compact('theLoai', 'truyens'));
    }
}
