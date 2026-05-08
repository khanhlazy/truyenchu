@extends('layouts.app')

@section('title', 'Quên mật khẩu - Truyện Chữ')

@section('content')
<div class="shell-container py-4 sm:py-8">
    <div class="auth-layout">
        <section class="auth-panel">
            <div class="mb-8">
                <span class="section-kicker">Quên mật khẩu</span>
                <h2 class="section-title mt-4">Lấy lại mật khẩu</h2>
                <p class="mt-3 text-sm leading-7 text-[color:var(--ui-muted)]">
                    Điền email để nhận liên kết khôi phục tài khoản.
                </p>
            </div>

            <form method="POST" action="{{ route('quen-mat-khau') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="mb-2 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus class="field-shell">
                    @error('email')
                        <p class="mt-2 text-xs" style="color: var(--ui-danger);">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Gửi liên kết</button>
            </form>

            <div class="mt-8 border-t border-[color:var(--ui-border)] pt-6 text-center">
                <a href="{{ route('dang-nhap') }}" class="text-sm font-semibold text-[color:var(--ui-primary)]">← Quay lại đăng nhập</a>
            </div>
        </section>
    </div>
</div>
@endsection
