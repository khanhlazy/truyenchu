<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TinNhanChat;
use App\Models\NhatKyKiemDuyet;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    public function danhSach()
    {
        $tinNhans = TinNhanChat::with('nguoiDung:id,ten_hien_thi')
            ->orderByDesc('created_at')
            ->paginate(50);

        return view('admin.chat.danh-sach', compact('tinNhans'));
    }

    public function xoa(int $id)
    {
        $tinNhan = TinNhanChat::findOrFail($id);
        NhatKyKiemDuyet::ghiLog(auth()->id(), 'xoa_tin_nhan_chat', 'tin_nhan_chat', $id, ['noi_dung' => $tinNhan->noi_dung]);
        $tinNhan->delete();

        return back()->with('thanh_cong', 'Đã xóa tin nhắn.');
    }
}
