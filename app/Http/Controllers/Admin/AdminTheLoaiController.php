<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TheLoai;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminTheLoaiController extends Controller
{
    public function danhSach()
    {
        $theLoais = TheLoai::withCount('truyen')->sapXep()->paginate(30);
        return view('admin.the-loai.danh-sach', compact('theLoais'));
    }

    public function taoMoi()
    {
        return view('admin.the-loai.form');
    }

    public function luu(Request $request)
    {
        $validated = $request->validate([
            'ten' => 'required|string|max:100|unique:the_loai,ten',
            'mo_ta' => 'nullable|string|max:500',
            'thu_tu' => 'nullable|integer|min:0',
        ], [
            'ten.required' => 'Vui lòng nhập tên thể loại.',
            'ten.unique' => 'Thể loại này đã tồn tại.',
        ]);

        TheLoai::create([
            'ten' => $validated['ten'],
            'slug' => Str::slug($validated['ten']),
            'mo_ta' => $validated['mo_ta'],
            'thu_tu' => $validated['thu_tu'] ?? 0,
        ]);

        return redirect()->route('admin.the-loai.danh-sach')->with('thanh_cong', 'Tạo thể loại thành công!');
    }

    public function sua(int $id)
    {
        $theLoai = TheLoai::findOrFail($id);
        return view('admin.the-loai.form', compact('theLoai'));
    }

    public function capNhat(Request $request, int $id)
    {
        $theLoai = TheLoai::findOrFail($id);

        $validated = $request->validate([
            'ten' => 'required|string|max:100|unique:the_loai,ten,' . $id,
            'mo_ta' => 'nullable|string|max:500',
            'thu_tu' => 'nullable|integer|min:0',
        ]);

        $theLoai->update([
            'ten' => $validated['ten'],
            'slug' => Str::slug($validated['ten']),
            'mo_ta' => $validated['mo_ta'],
            'thu_tu' => $validated['thu_tu'] ?? 0,
        ]);

        return redirect()->route('admin.the-loai.danh-sach')->with('thanh_cong', 'Cập nhật thể loại thành công!');
    }

    public function xoa(int $id)
    {
        $theLoai = TheLoai::findOrFail($id);
        $theLoai->delete();
        return redirect()->route('admin.the-loai.danh-sach')->with('thanh_cong', 'Đã xóa thể loại.');
    }
}
