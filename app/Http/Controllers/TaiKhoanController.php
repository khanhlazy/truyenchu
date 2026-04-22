<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TaiKhoanController extends Controller
{
    public function hoSo()
    {
        return view('tai-khoan.ho-so');
    }

    public function capNhatHoSo(Request $request)
    {
        $nguoiDung = auth()->user();

        $validated = $request->validate([
            'ten_hien_thi' => 'required|string|max:100',
            'email' => 'required|email|max:191|unique:nguoi_dung,email,' . $nguoiDung->id,
            'anh_dai_dien' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'ten_hien_thi.required' => 'Vui lòng nhập tên hiển thị.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email này đã được sử dụng.',
            'anh_dai_dien.image' => 'File phải là hình ảnh.',
            'anh_dai_dien.max' => 'Hình ảnh không được vượt quá 2MB.',
        ]);

        if ($request->hasFile('anh_dai_dien')) {
            $path = $request->file('anh_dai_dien')->store('avatars', 'public');
            $nguoiDung->anh_dai_dien = $path;
        }

        $nguoiDung->ten_hien_thi = $validated['ten_hien_thi'];
        $nguoiDung->email = $validated['email'];
        $nguoiDung->save();

        return back()->with('thanh_cong', 'Cập nhật hồ sơ thành công!');
    }

    public function doiMatKhau(Request $request)
    {
        $request->validate([
            'mat_khau_cu' => 'required',
            'mat_khau_moi' => ['required', 'confirmed', Password::min(6)],
        ], [
            'mat_khau_cu.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'mat_khau_moi.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau_moi.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'mat_khau_moi.min' => 'Mật khẩu mới tối thiểu :min ký tự.',
        ]);

        if (!Hash::check($request->mat_khau_cu, auth()->user()->mat_khau)) {
            return back()->withErrors(['mat_khau_cu' => 'Mật khẩu hiện tại không đúng.']);
        }

        auth()->user()->update(['mat_khau' => Hash::make($request->mat_khau_moi)]);

        return back()->with('thanh_cong', 'Đổi mật khẩu thành công!');
    }

    public function lichSuDoc()
    {
        $lichSu = auth()->user()->lichSuDoc()
            ->with(['truyen:id,tieu_de,slug,anh_bia', 'chuong:id,so_chuong,tieu_de,slug'])
            ->orderByDesc('thoi_diem_doc_cuoi')
            ->paginate(20);

        return view('tai-khoan.lich-su-doc', compact('lichSu'));
    }

    public function xoaLichSu()
    {
        auth()->user()->lichSuDoc()->delete();
        return back()->with('thanh_cong', 'Đã xóa lịch sử đọc!');
    }
}
