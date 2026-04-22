<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truyen;
use App\Models\TheLoai;
use App\Models\NhatKyKiemDuyet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminTruyenController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = Truyen::with('theLoai');

        if ($request->filled('tu_khoa')) {
            $query->timKiem($request->input('tu_khoa'));
        }

        if ($request->filled('trang_thai')) {
            $query->trangThai($request->input('trang_thai'));
        }

        $truyens = $query->orderByDesc('updated_at')->paginate(20)->withQueryString();

        return view('admin.truyen.danh-sach', compact('truyens'));
    }

    public function taoMoi()
    {
        $theLoais = TheLoai::sapXep()->get();
        return view('admin.truyen.form', compact('theLoais'));
    }

    public function luu(Request $request)
    {
        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'tac_gia' => 'required|string|max:150',
            'mo_ta_ngan' => 'nullable|string|max:500',
            'mo_ta_day_du' => 'nullable|string',
            'trang_thai' => 'required|in:dang_ra,hoan_thanh,tam_ngung',
            'the_loai' => 'required|array|min:1',
            'the_loai.*' => 'exists:the_loai,id',
            'anh_bia' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ], [
            'tieu_de.required' => 'Vui lòng nhập tiêu đề truyện.',
            'tac_gia.required' => 'Vui lòng nhập tên tác giả.',
            'the_loai.required' => 'Vui lòng chọn ít nhất một thể loại.',
        ]);

        $slug = Str::slug($validated['tieu_de']);
        $slugGoc = $slug;
        $dem = 1;
        while (Truyen::where('slug', $slug)->exists()) {
            $slug = $slugGoc . '-' . $dem++;
        }

        $truyen = new Truyen();
        $truyen->tieu_de = $validated['tieu_de'];
        $truyen->slug = $slug;
        $truyen->tac_gia = $validated['tac_gia'];
        $truyen->mo_ta_ngan = $validated['mo_ta_ngan'] ?? '';
        $truyen->mo_ta_day_du = $validated['mo_ta_day_du'] ?? '';
        $truyen->trang_thai = $validated['trang_thai'];
        $truyen->meta_title = $validated['meta_title'];
        $truyen->meta_description = $validated['meta_description'];
        $truyen->is_published = $request->boolean('is_published');

        if ($request->boolean('is_published')) {
            $truyen->published_at = now();
        }

        if ($request->hasFile('anh_bia')) {
            $truyen->anh_bia = $request->file('anh_bia')->store('covers', 'public');
        }

        $truyen->save();
        $truyen->theLoai()->sync($validated['the_loai']);

        NhatKyKiemDuyet::ghiLog(auth()->id(), 'tao_truyen', 'truyen', $truyen->id);

        return redirect()->route('admin.truyen.danh-sach')->with('thanh_cong', 'Tạo truyện thành công!');
    }

    public function sua(int $id)
    {
        $truyen = Truyen::with('theLoai')->findOrFail($id);
        $theLoais = TheLoai::sapXep()->get();
        return view('admin.truyen.form', compact('truyen', 'theLoais'));
    }

    public function capNhat(Request $request, int $id)
    {
        $truyen = Truyen::findOrFail($id);

        $validated = $request->validate([
            'tieu_de' => 'required|string|max:255',
            'tac_gia' => 'required|string|max:150',
            'mo_ta_ngan' => 'nullable|string|max:500',
            'mo_ta_day_du' => 'nullable|string',
            'trang_thai' => 'required|in:dang_ra,hoan_thanh,tam_ngung',
            'the_loai' => 'required|array|min:1',
            'the_loai.*' => 'exists:the_loai,id',
            'anh_bia' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $truyen->tieu_de = $validated['tieu_de'];
        $truyen->tac_gia = $validated['tac_gia'];
        $truyen->mo_ta_ngan = $validated['mo_ta_ngan'] ?? '';
        $truyen->mo_ta_day_du = $validated['mo_ta_day_du'] ?? '';
        $truyen->trang_thai = $validated['trang_thai'];
        $truyen->meta_title = $validated['meta_title'];
        $truyen->meta_description = $validated['meta_description'];
        $truyen->is_published = $request->boolean('is_published');

        if ($request->boolean('is_published') && !$truyen->published_at) {
            $truyen->published_at = now();
        }

        if ($request->hasFile('anh_bia')) {
            $truyen->anh_bia = $request->file('anh_bia')->store('covers', 'public');
        }

        $truyen->save();
        $truyen->theLoai()->sync($validated['the_loai']);

        NhatKyKiemDuyet::ghiLog(auth()->id(), 'cap_nhat_truyen', 'truyen', $truyen->id);

        return redirect()->route('admin.truyen.danh-sach')->with('thanh_cong', 'Cập nhật truyện thành công!');
    }

    public function xoa(int $id)
    {
        $truyen = Truyen::findOrFail($id);
        NhatKyKiemDuyet::ghiLog(auth()->id(), 'xoa_truyen', 'truyen', $truyen->id, ['tieu_de' => $truyen->tieu_de]);
        $truyen->delete();

        return redirect()->route('admin.truyen.danh-sach')->with('thanh_cong', 'Đã xóa truyện.');
    }

    public function togglePublish(int $id)
    {
        $truyen = Truyen::findOrFail($id);
        $truyen->is_published = !$truyen->is_published;
        if ($truyen->is_published && !$truyen->published_at) {
            $truyen->published_at = now();
        }
        $truyen->save();

        $hanhDong = $truyen->is_published ? 'xuat_ban_truyen' : 'go_xuat_ban_truyen';
        NhatKyKiemDuyet::ghiLog(auth()->id(), $hanhDong, 'truyen', $truyen->id);

        return back()->with('thanh_cong', $truyen->is_published ? 'Đã xuất bản truyện.' : 'Đã gỡ xuất bản truyện.');
    }
}
