<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Truyen;
use App\Models\Chuong;
use App\Models\NhatKyKiemDuyet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminChuongController extends Controller
{
    public function danhSach(Request $request, int $truyen_id)
    {
        $truyen = Truyen::findOrFail($truyen_id);
        $perPage = $request->query('per_page', 50);
        
        $query = $truyen->chuong()->orderBy('so_chuong');
        
        if ($perPage === 'all') {
            $total = $query->count();
            $chuongs = $query->paginate($total > 0 ? $total : 1);
        } else {
            $chuongs = $query->paginate((int)$perPage);
        }

        $chuongs->appends(['per_page' => $perPage]);

        return view('admin.chuong.danh-sach', compact('truyen', 'chuongs', 'perPage'));
    }

    public function taoMoi(int $truyen_id)
    {
        $truyen = Truyen::findOrFail($truyen_id);
        $soChuongTiepTheo = ($truyen->chuong()->max('so_chuong') ?? 0) + 1;

        return view('admin.chuong.form', compact('truyen', 'soChuongTiepTheo'));
    }

    public function luu(Request $request, int $truyen_id)
    {
        $truyen = Truyen::findOrFail($truyen_id);

        $validated = $request->validate([
            'so_chuong' => 'required|integer|min:1|unique:chuong,so_chuong,NULL,id,truyen_id,' . $truyen_id,
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'is_published' => 'boolean',
        ], [
            'so_chuong.required' => 'Vui lòng nhập số chương.',
            'so_chuong.unique' => 'Số chương này đã tồn tại.',
            'tieu_de.required' => 'Vui lòng nhập tiêu đề chương.',
            'noi_dung.required' => 'Vui lòng nhập nội dung chương.',
        ]);

        $chuong = Chuong::create([
            'truyen_id' => $truyen->id,
            'so_chuong' => $validated['so_chuong'],
            'tieu_de' => $validated['tieu_de'],
            'slug' => Str::slug("chuong-{$validated['so_chuong']}"),
            'noi_dung' => $validated['noi_dung'],
            'so_tu' => str_word_count(strip_tags($validated['noi_dung'])),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') ? now() : null,
        ]);

        // Cập nhật thời điểm chương mới nhất
        $truyen->capNhatThoiDiemChuong();

        NhatKyKiemDuyet::ghiLog(auth()->id(), 'tao_chuong', 'chuong', $chuong->id);

        return redirect()->route('admin.chuong.danh-sach', $truyen->id)->with('thanh_cong', 'Tạo chương thành công!');
    }

    public function sua(int $id)
    {
        $chuong = Chuong::with('truyen')->findOrFail($id);
        $truyen = $chuong->truyen;

        return view('admin.chuong.form', compact('chuong', 'truyen'));
    }

    public function capNhat(Request $request, int $id)
    {
        $chuong = Chuong::findOrFail($id);

        $validated = $request->validate([
            'so_chuong' => 'required|integer|min:1|unique:chuong,so_chuong,' . $id . ',id,truyen_id,' . $chuong->truyen_id,
            'tieu_de' => 'required|string|max:255',
            'noi_dung' => 'required|string',
            'is_published' => 'boolean',
        ]);

        $chuong->update([
            'so_chuong' => $validated['so_chuong'],
            'tieu_de' => $validated['tieu_de'],
            'slug' => Str::slug("chuong-{$validated['so_chuong']}"),
            'noi_dung' => $validated['noi_dung'],
            'so_tu' => str_word_count(strip_tags($validated['noi_dung'])),
            'is_published' => $request->boolean('is_published'),
            'published_at' => $request->boolean('is_published') && !$chuong->published_at ? now() : $chuong->published_at,
        ]);

        $chuong->truyen->capNhatThoiDiemChuong();
        NhatKyKiemDuyet::ghiLog(auth()->id(), 'cap_nhat_chuong', 'chuong', $chuong->id);

        return redirect()->route('admin.chuong.danh-sach', $chuong->truyen_id)->with('thanh_cong', 'Cập nhật chương thành công!');
    }

    public function xoa(int $id)
    {
        $chuong = Chuong::findOrFail($id);
        $truyenId = $chuong->truyen_id;
        NhatKyKiemDuyet::ghiLog(auth()->id(), 'xoa_chuong', 'chuong', $chuong->id, ['tieu_de' => $chuong->tieu_de]);
        $chuong->delete();

        return redirect()->route('admin.chuong.danh-sach', $truyenId)->with('thanh_cong', 'Đã xóa chương.');
    }

    public function togglePublish(int $id)
    {
        $chuong = Chuong::findOrFail($id);
        $chuong->is_published = !$chuong->is_published;
        if ($chuong->is_published && !$chuong->published_at) {
            $chuong->published_at = now();
        }
        $chuong->save();
        $chuong->truyen->capNhatThoiDiemChuong();

        return back()->with('thanh_cong', $chuong->is_published ? 'Đã xuất bản chương.' : 'Đã gỡ xuất bản chương.');
    }

    public function bulkPublish(Request $request)
    {
        $request->validate([
            'chapter_ids' => 'required|array',
            'chapter_ids.*' => 'exists:chuong,id',
            'action' => 'required|in:publish,draft'
        ]);

        $isPublished = $request->input('action') === 'publish';
        $ids = $request->input('chapter_ids');

        $updateData = ['is_published' => $isPublished];
        if ($isPublished) {
            // Chỉ cập nhật published_at cho những chương chưa có
            Chuong::whereIn('id', $ids)->whereNull('published_at')->update(['published_at' => now()]);
        }

        Chuong::whereIn('id', $ids)->update($updateData);

        // Update truyen timestamp
        $firstChuong = Chuong::find($ids[0]);
        if ($firstChuong) {
            $firstChuong->truyen->capNhatThoiDiemChuong();
            NhatKyKiemDuyet::ghiLog(auth()->id(), 'thao_tac_hang_loat_chuong', 'chuong', 0, ['so_luong' => count($ids), 'hanh_dong' => $request->input('action')]);
        }

        return back()->with('thanh_cong', 'Đã cập nhật ' . count($ids) . ' chương.');
    }
}
