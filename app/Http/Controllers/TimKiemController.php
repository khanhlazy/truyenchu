<?php

namespace App\Http\Controllers;

use App\Models\Truyen;
use App\Models\TheLoai;
use Illuminate\Http\Request;

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
            $truyens = $query->paginate(18)->onEachSide(2)->withQueryString();
        }

        $theLoais = TheLoai::sapXep()->get();

        return view('tim-kiem', compact('truyens', 'theLoais', 'tuKhoa'));
    }
}
