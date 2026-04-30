@extends('layouts.app')

@section('title', 'Đăng nhập - Truyện Chữ')

@section('content')
<div class="shell-container py-4 sm:py-8">
    <div class="auth-layout">
        <section class="auth-panel">
            <div class="mb-8">
                <span class="section-kicker">Đăng nhập</span>
                <h2 class="mt-4 text-3xl font-black tracking-tight">Chào mừng trở lại</h2>
                <p class="mt-3 text-sm leading-7 text-[color:var(--ui-muted)]">
                    Đăng nhập để tiếp tục khám phá thư viện của bạn.
                </p>
            </div>

            <form method="POST" action="{{ route('dang-nhap') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold">Tên đăng nhập hoặc Email</label>
                    <input type="text" name="dang_nhap" value="{{ old('dang_nhap') }}" required autofocus class="field-shell" placeholder="admin hoặc admin@example.com">
                    @error('dang_nhap')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between gap-3">
                        <label class="block text-sm font-semibold">Mật khẩu</label>
                        <a href="{{ route('quen-mat-khau') }}" class="text-sm font-medium text-[color:var(--ui-primary)]">Quên mật khẩu?</a>
                    </div>
                    <input type="password" name="mat_khau" required class="field-shell">
                    @error('mat_khau')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <label class="inline-flex items-center gap-3 text-sm text-[color:var(--ui-muted)]">
                    <input type="checkbox" name="nho_dang_nhap" class="h-4 w-4 rounded border-[color:var(--ui-border)] text-[color:var(--ui-primary)] focus:ring-[color:var(--ui-primary)]">
                    Ghi nhớ đăng nhập
                </label>

                <button type="submit" class="btn-primary w-full justify-center">Đăng nhập</button>
            </form>

            <div class="mt-8 border-t border-[color:var(--ui-border)] pt-6 text-sm text-[color:var(--ui-muted)] text-center">
                Chưa có tài khoản?
                <a href="{{ route('dang-ky') }}" class="font-semibold text-[color:var(--ui-primary)]">Đăng ký ngay</a>
            </div>
        </section>
    </div>
</div>
@endsection
