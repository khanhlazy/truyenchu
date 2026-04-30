@extends('layouts.app')

@section('title', 'Đặt lại mật khẩu - Truyện Chữ')

@section('content')
<div class="shell-container py-4 sm:py-8">
    <div class="auth-layout">
        <section class="auth-panel">
            <div class="mb-8">
                <span class="section-kicker">Đặt lại mật khẩu</span>
                <h2 class="mt-4 text-3xl font-black tracking-tight">Mật khẩu mới</h2>
                <p class="mt-3 text-sm leading-7 text-[color:var(--ui-muted)]">
                    Hãy tạo một mật khẩu mới để tiếp tục truy cập tài khoản.
                </p>
            </div>

            <form method="POST" action="{{ route('dat-lai-mat-khau') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="mb-2 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" required class="field-shell">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Mật khẩu mới</label>
                    <input type="password" name="mat_khau" required class="field-shell">
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Xác nhận</label>
                    <input type="password" name="mat_khau_confirmation" required class="field-shell">
                </div>

                <button type="submit" class="btn-primary w-full justify-center">Cập nhật mật khẩu</button>
            </form>
        </section>
    </div>
</div>
@endsection
