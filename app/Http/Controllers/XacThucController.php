<?php

namespace App\Http\Controllers;

use App\Models\NguoiDung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\VaiTro;

class XacThucController extends Controller
{
    public function formDangNhap()
    {
        return view('auth.dang-nhap');
    }

    public function dangNhap(Request $request)
    {
        $request->validate([
            'dang_nhap' => 'required',
            'mat_khau' => 'required',
        ], [
            'dang_nhap.required' => 'Vui lòng nhập tên đăng nhập hoặc email.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        $loginField = $request->dang_nhap;
        $loginType = filter_var($loginField, FILTER_VALIDATE_EMAIL) ? 'email' : 'ten_dang_nhap';

        $nguoiDung = NguoiDung::where($loginType, $loginField)->first();

        if (!$nguoiDung || !Hash::check($request->mat_khau, $nguoiDung->mat_khau)) {
            return back()->withErrors(['dang_nhap' => 'Thông tin đăng nhập không chính xác.'])->withInput();
        }

        if (!$nguoiDung->dangHoatDong()) {
            return back()->withErrors(['dang_nhap' => 'Tài khoản của bạn đã bị khóa.'])->withInput();
        }

        Auth::login($nguoiDung, $request->boolean('nho_dang_nhap'));
        $request->session()->regenerate();

        return redirect()->intended(route('trang-chu'))->with('thanh_cong', 'Đăng nhập thành công!');
    }

    public function formDangKy()
    {
        return view('auth.dang-ky');
    }

    public function dangKy(Request $request)
    {
        $request->validate([
            'ten_dang_nhap' => 'required|string|min:3|max:50|unique:nguoi_dung|regex:/^[a-zA-Z0-9_]+$/',
            'email' => 'required|email|max:191|unique:nguoi_dung',
            'mat_khau' => 'required|confirmed|min:6',
            'ten_hien_thi' => 'required|string|max:100',
        ], [
            'ten_dang_nhap.required' => 'Vui lòng nhập tên đăng nhập.',
            'ten_dang_nhap.unique' => 'Tên đăng nhập đã được sử dụng.',
            'ten_dang_nhap.regex' => 'Tên đăng nhập chỉ chứa chữ cái, số và dấu gạch dưới.',
            'ten_dang_nhap.min' => 'Tên đăng nhập tối thiểu :min ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.unique' => 'Email đã được sử dụng.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'mat_khau.min' => 'Mật khẩu tối thiểu :min ký tự.',
            'ten_hien_thi.required' => 'Vui lòng nhập tên hiển thị.',
        ]);

        $nguoiDung = NguoiDung::create([
            'ten_dang_nhap' => $request->ten_dang_nhap,
            'email' => $request->email,
            'mat_khau' => Hash::make($request->mat_khau),
            'ten_hien_thi' => $request->ten_hien_thi,
            'trang_thai' => 'hoat_dong',
        ]);

        $nguoiDung->vaiTro()->attach(VaiTro::where('ma', 'user')->first()->id);

        Auth::login($nguoiDung);
        $request->session()->regenerate();

        return redirect()->route('trang-chu')->with('thanh_cong', 'Đăng ký thành công! Chào mừng bạn đến với TruyệnChữ.');
    }

    public function dangXuat(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('trang-chu');
    }

    public function formQuenMatKhau()
    {
        return view('auth.quen-mat-khau');
    }

    public function guiLinkDatLai(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không hợp lệ.',
        ]);

        // Hiển thị thông báo thành công dù email có tồn tại hay không (bảo mật)
        return back()->with('thanh_cong', 'Nếu email tồn tại trong hệ thống, bạn sẽ nhận được link đặt lại mật khẩu. Vui lòng kiểm tra hộp thư.');
    }

    public function formDatLaiMatKhau(string $token)
    {
        return view('auth.dat-lai-mat-khau', compact('token'));
    }

    public function datLaiMatKhau(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'mat_khau' => 'required|confirmed|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'mat_khau.required' => 'Vui lòng nhập mật khẩu mới.',
            'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        return redirect()->route('dang-nhap')->with('thanh_cong', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}
