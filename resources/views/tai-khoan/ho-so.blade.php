@extends('layouts.app')

@section('title', 'Tài khoản của tôi - Truyện Chữ')

@section('content')
<div class="shell-container page-stack">
    <section class="hero-panel">
        <div class="grid gap-6 lg:grid-cols-[auto_1fr] lg:items-center">
            <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="{{ auth()->user()->ten_hien_thi }}" class="h-24 w-24 rounded-full object-cover ring-4 ring-white/70 dark:ring-white/10">
            <div>
                <span class="section-kicker">Khu vực cá nhân</span>
                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">{{ auth()->user()->ten_hien_thi }}</h1>
                <p class="mt-3 text-base leading-8 text-[color:var(--ui-muted)] sm:text-lg">
                    Cập nhật thông tin, đổi mật khẩu và quản lý toàn bộ dữ liệu đọc của bạn từ một nơi duy nhất.
                </p>
            </div>
        </div>
    </section>

    @include('components.account-tabs', ['active' => 'profile'])

    <section class="grid gap-6 xl:grid-cols-2">
        <div class="surface-panel p-6">
            <div class="section-heading">
                <div>
                    <span class="section-kicker">Thông tin</span>
                    <h2 class="section-title text-2xl">Cập nhật hồ sơ</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('tai-khoan.cap-nhat') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-semibold">Tên hiển thị</label>
                    <input type="text" name="ten_hien_thi" value="{{ old('ten_hien_thi', auth()->user()->ten_hien_thi) }}" class="field-shell">
                    @error('ten_hien_thi')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" class="field-shell">
                    @error('email')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div class="surface-panel-strong p-4">
                    <label class="mb-3 block text-sm font-semibold">Ảnh đại diện</label>
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                        <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover ring-4 ring-white/70 dark:ring-white/10">
                        <input type="file" name="anh_dai_dien" accept="image/*" class="field-shell text-sm file:mr-4 file:rounded-full file:border-0 file:bg-[color:var(--ui-primary)] file:px-4 file:py-2 file:font-semibold file:text-white">
                    </div>
                </div>

                <button type="submit" class="btn-primary">Lưu thay đổi</button>
            </form>
        </div>

        <div class="surface-panel p-6">
            <div class="section-heading">
                <div>
                    <span class="section-kicker">Bảo mật</span>
                    <h2 class="section-title text-2xl">Đổi mật khẩu</h2>
                </div>
            </div>

            <form method="POST" action="{{ route('tai-khoan.doi-mat-khau') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="mb-2 block text-sm font-semibold">Mật khẩu hiện tại</label>
                    <input type="password" name="mat_khau_cu" class="field-shell">
                    @error('mat_khau_cu')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Mật khẩu mới</label>
                    <input type="password" name="mat_khau_moi" class="field-shell">
                    @error('mat_khau_moi')
                        <p class="mt-2 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold">Xác nhận mật khẩu mới</label>
                    <input type="password" name="mat_khau_moi_confirmation" class="field-shell">
                </div>

                <button type="submit" class="btn-primary">Cập nhật mật khẩu</button>
            </form>
        </div>
    </section>
</div>
@endsection
