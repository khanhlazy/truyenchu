@extends('layouts.app')
@section('title', 'Đăng Ký - TruyệnChữ')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Đăng Ký Tài Khoản</h1>
            <p class="text-sm text-gray-500 mt-1">Tạo tài khoản miễn phí để đọc truyện</p>
        </div>

        <form method="POST" action="{{ route('dang-ky') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tên hiển thị</label>
                    <input type="text" name="ten_hien_thi" value="{{ old('ten_hien_thi') }}" required
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('ten_hien_thi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tên đăng nhập</label>
                    <input type="text" name="ten_dang_nhap" value="{{ old('ten_dang_nhap') }}" required
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('ten_dang_nhap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Mật khẩu</label>
                    <input type="password" name="mat_khau" required
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('mat_khau')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Xác nhận mật khẩu</label>
                    <input type="password" name="mat_khau_confirmation" required
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Đăng ký</button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Đã có tài khoản? <a href="{{ route('dang-nhap') }}" class="text-indigo-600 font-medium hover:underline">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection
