@extends('layouts.app')
@section('title', 'Đăng Nhập - TruyệnChữ')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Đăng Nhập</h1>
            <p class="text-sm text-gray-500 mt-1">Chào mừng bạn quay trở lại!</p>
        </div>

        <form method="POST" action="{{ route('dang-nhap') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Mật khẩu</label>
                    <input type="password" name="mat_khau" required
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @error('mat_khau')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="nho_dang_nhap" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        Nhớ đăng nhập
                    </label>
                    <a href="{{ route('quen-mat-khau') }}" class="text-sm text-indigo-600 hover:underline">Quên mật khẩu?</a>
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Đăng nhập</button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Chưa có tài khoản? <a href="{{ route('dang-ky') }}" class="text-indigo-600 font-medium hover:underline">Đăng ký ngay</a>
        </p>
    </div>
</div>
@endsection
