<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BinhLuan;
use App\Models\NhatKyKiemDuyet;
use Illuminate\Http\Request;

class AdminBinhLuanController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = BinhLuan::with(['nguoiDung:id,ten_hien_thi', 'truyen:id,tieu_de,slug', 'chuong:id,so_chuong,tieu_de']);

        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->input('trang_thai'));
        } else {
            $query->where('trang_thai', '!=', 'da_xoa');
        }

        if ($request->filled('tu_khoa')) {
            $query->where('noi_dung', 'like', '%' . $request->input('tu_khoa') . '%');
        }

        $binhLuans = $query->orderByDesc('created_at')->paginate(30)->withQueryString();

        return view('admin.binh-luan.danh-sach', compact('binhLuans'));
    }

    public function duyet(int $id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->update(['trang_thai' => 'hien_thi']);
        NhatKyKiemDuyet::ghiLog(auth()->id(), 'duyet_binh_luan', 'binh_luan', $id);

        return back()->with('thanh_cong', 'Đã duyệt bình luận.');
    }

    public function an(int $id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->update(['trang_thai' => 'an']);
        NhatKyKiemDuyet::ghiLog(auth()->id(), 'an_binh_luan', 'binh_luan', $id);

        return back()->with('thanh_cong', 'Đã ẩn bình luận.');
    }

    public function xoa(int $id)
    {
        $binhLuan = BinhLuan::findOrFail($id);
        $binhLuan->update(['trang_thai' => 'da_xoa']);
        NhatKyKiemDuyet::ghiLog(auth()->id(), 'xoa_binh_luan', 'binh_luan', $id);

        return back()->with('thanh_cong', 'Đã xóa bình luận.');
    }
}
