@extends('layouts.app')

@section('title', 'Đăng ký - Truyện Chữ')

@section('content')
<div class="shell-container py-4 sm:py-8">
    <div class="auth-layout">
        <section class="auth-panel">
            <div class="mb-8">
                <span class="section-kicker">Đăng ký</span>
                <h2 class="mt-4 text-3xl font-black tracking-tight">Tạo tài khoản mới</h2>
                <p class="mt-3 text-sm leading-7 text-[color:var(--ui-muted)]">
                    Chỉ vài bước đơn giản để cá nhân hóa trải nghiệm đọc truyện của bạn.
                </p>
            </div>

            <form method="POST" action="{{ route('dang-ky') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-semibold">Tên hiển thị</label>
                    <input type="text" name="ten_hien_thi" value="{{ old('ten_hien_thi') }}" required class="field-shell">
                    @error('ten_hien_thi')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Tên đăng nhập</label>
                    <input type="text" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" required class="field-shell">
                    @error('ten_dang_nhap')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="field-shell">
                    @error('email')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Mật khẩu</label>
                        <input type="password" name="mat_khau" required class="field-shell">
                        @error('mat_khau')
                            <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-semibold">Xác nhận</label>
                        <input type="password" name="mat_khau_confirmation" required class="field-shell">
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Tạo tài khoản</button>
            </form>

            <div class="mt-8 border-t border-[color:var(--ui-border)] pt-6 text-sm text-[color:var(--ui-muted)] text-center">
                Đã có tài khoản?
                <a href="{{ route('dang-nhap') }}" class="font-semibold text-[color:var(--ui-primary)]">Đăng nhập</a>
            </div>
        </section>
    </div>
</div>
@endsection
