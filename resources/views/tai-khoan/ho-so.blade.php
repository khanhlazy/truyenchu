@extends('layouts.app')
@section('title', 'Tài Khoản - TruyệnChữ')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Tài Khoản Của Tôi</h1>

    {{-- Tabs --}}
    <div class="flex gap-4 mb-6 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('tai-khoan') }}" class="pb-3 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">Hồ sơ</a>
        <a href="{{ route('yeu-thich') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition">Yêu thích</a>
        <a href="{{ route('theo-doi') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition">Theo dõi</a>
        <a href="{{ route('lich-su-doc') }}" class="pb-3 text-sm font-medium text-gray-500 hover:text-gray-700 transition">Lịch sử</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Cập nhật hồ sơ --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="font-semibold mb-4">Thông tin cá nhân</h2>
            <form method="POST" action="{{ route('tai-khoan.cap-nhat') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tên hiển thị</label>
                        <input type="text" name="ten_hien_thi" value="{{ old('ten_hien_thi', auth()->user()->ten_hien_thi) }}"
                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        @error('ten_hien_thi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Ảnh đại diện</label>
                        <div class="flex items-center gap-4">
                            <img src="{{ auth()->user()->urlAnhDaiDien() }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                            <input type="file" name="anh_dai_dien" accept="image/*"
                                   class="focus:outline-none text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100">
                        </div>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">Cập nhật</button>
                </div>
            </form>
        </div>

        {{-- Đổi mật khẩu --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="font-semibold mb-4">Đổi mật khẩu</h2>
            <form method="POST" action="{{ route('tai-khoan.doi-mat-khau') }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Mật khẩu hiện tại</label>
                        <input type="password" name="mat_khau_cu"
                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        @error('mat_khau_cu')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Mật khẩu mới</label>
                        <input type="password" name="mat_khau_moi"
                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        @error('mat_khau_moi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Xác nhận mật khẩu mới</label>
                        <input type="password" name="mat_khau_moi_confirmation"
                               class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
