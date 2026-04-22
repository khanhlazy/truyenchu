<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NguoiDung;
use App\Models\NhatKyKiemDuyet;
use Illuminate\Http\Request;

class AdminNguoiDungController extends Controller
{
    public function danhSach(Request $request)
    {
        $query = NguoiDung::with('vaiTro');

        if ($request->filled('tu_khoa')) {
            $query->timKiem($request->input('tu_khoa'));
        }

        $nguoiDungs = $query->orderByDesc('created_at')->paginate(30)->withQueryString();

        return view('admin.nguoi-dung.danh-sach', compact('nguoiDungs'));
    }

    public function toggleTrangThai(int $id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        if ($nguoiDung->laAdmin()) {
            return back()->with('loi', 'Không thể khóa tài khoản admin.');
        }

        $nguoiDung->trang_thai = $nguoiDung->trang_thai === 'hoat_dong' ? 'khoa' : 'hoat_dong';
        $nguoiDung->save();

        NhatKyKiemDuyet::ghiLog(auth()->id(), $nguoiDung->trang_thai === 'khoa' ? 'khoa_tai_khoan' : 'mo_khoa_tai_khoan', 'nguoi_dung', $id);

        $message = $nguoiDung->trang_thai === 'hoat_dong' ? 'Đã mở khóa tài khoản.' : 'Đã khóa tài khoản.';
        return back()->with('thanh_cong', $message);
    }

    public function toggleMute(Request $request, int $id)
    {
        $nguoiDung = NguoiDung::findOrFail($id);

        if ($nguoiDung->biCamChat()) {
            $nguoiDung->bi_cam_chat_den = null;
            $message = 'Đã gỡ cấm chat.';
            $hanhDong = 'go_cam_chat';
        } else {
            $soGio = $request->input('so_gio', 24);
            $nguoiDung->bi_cam_chat_den = now()->addHours($soGio);
            $message = "Đã cấm chat {$soGio} giờ.";
            $hanhDong = 'cam_chat';
        }

        $nguoiDung->save();
        NhatKyKiemDuyet::ghiLog(auth()->id(), $hanhDong, 'nguoi_dung', $id);

        return back()->with('thanh_cong', $message);
    }
}
