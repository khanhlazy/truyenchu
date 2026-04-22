<?php

namespace App\Http\Controllers;

use App\Models\BinhLuan;
use Illuminate\Http\Request;

class BinhLuanController extends Controller
{
    public function gui(Request $request)
    {
        $request->validate([
            'noi_dung' => 'required|string|max:2000',
            'truyen_id' => 'nullable|exists:truyen,id',
            'chuong_id' => 'nullable|exists:chuong,id',
            'binh_luan_cha_id' => 'nullable|exists:binh_luan,id',
        ], [
            'noi_dung.required' => 'Vui lòng nhập nội dung bình luận.',
            'noi_dung.max' => 'Bình luận không được vượt quá 2000 ký tự.',
        ]);

        // Rate limit: 1 bình luận / 30 giây
        $binhLuanGanNhat = BinhLuan::where('nguoi_dung_id', auth()->id())
            ->where('created_at', '>', now()->subSeconds(30))
            ->exists();

        if ($binhLuanGanNhat) {
            return back()->with('loi', 'Vui lòng đợi 30 giây trước khi bình luận tiếp.');
        }

        BinhLuan::create([
            'nguoi_dung_id' => auth()->id(),
            'truyen_id' => $request->truyen_id,
            'chuong_id' => $request->chuong_id,
            'binh_luan_cha_id' => $request->binh_luan_cha_id,
            'noi_dung' => $request->noi_dung,
            'trang_thai' => 'cho_duyet',
        ]);

        return back()->with('thanh_cong', 'Bình luận của bạn đã được gửi và đang chờ duyệt.');
    }
}
