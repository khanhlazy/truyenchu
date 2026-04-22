<?php

namespace App\Http\Controllers;

use App\Models\PhongChat;
use App\Models\TinNhanChat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $phong = PhongChat::where('ma', 'chung')->firstOrFail();
        $tinNhans = $phong->tinNhanMoiNhat()
            ->with('nguoiDung:id,ten_hien_thi,anh_dai_dien')
            ->take(50)
            ->get()
            ->reverse()
            ->values();

        return view('chat.index', compact('phong', 'tinNhans'));
    }

    public function guiTinNhan(Request $request)
    {
        $request->validate([
            'noi_dung' => 'required|string|max:500',
        ], [
            'noi_dung.required' => 'Vui lòng nhập tin nhắn.',
            'noi_dung.max' => 'Tin nhắn không được vượt quá 500 ký tự.',
        ]);

        $nguoiDung = auth()->user();

        // Kiểm tra bị cấm chat
        if ($nguoiDung->biCamChat()) {
            return response()->json(['loi' => 'Bạn đang bị cấm chat đến ' . $nguoiDung->bi_cam_chat_den->format('d/m/Y H:i')], 403);
        }

        // Rate limit: 1 tin nhắn / 5 giây
        $tinNhanGanNhat = TinNhanChat::where('nguoi_dung_id', $nguoiDung->id)
            ->where('created_at', '>', now()->subSeconds(5))
            ->exists();

        if ($tinNhanGanNhat) {
            return response()->json(['loi' => 'Vui lòng đợi vài giây trước khi gửi tiếp.'], 429);
        }

        $phong = PhongChat::where('ma', 'chung')->firstOrFail();

        $tinNhan = TinNhanChat::create([
            'phong_chat_id' => $phong->id,
            'nguoi_dung_id' => $nguoiDung->id,
            'noi_dung' => $request->noi_dung,
        ]);

        $tinNhan->load('nguoiDung:id,ten_hien_thi,anh_dai_dien');

        return response()->json(['tin_nhan' => $tinNhan]);
    }

    public function taiTinNhan(Request $request)
    {
        $phong = PhongChat::where('ma', 'chung')->firstOrFail();
        $lastId = $request->input('last_id', 0);

        $tinNhans = $phong->tinNhan()
            ->where('id', '>', $lastId)
            ->with('nguoiDung:id,ten_hien_thi,anh_dai_dien')
            ->orderBy('id')
            ->take(20)
            ->get();

        return response()->json(['tin_nhans' => $tinNhans]);
    }
}
