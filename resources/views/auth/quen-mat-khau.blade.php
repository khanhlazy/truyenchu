@extends('layouts.app')
@section('title', 'Quên Mật Khẩu - TruyệnChữ')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold">Quên Mật Khẩu</h1>
            <p class="text-sm text-gray-500 mt-1">Nhập email để nhận link đặt lại mật khẩu</p>
        </div>

        <form method="POST" action="{{ route('quen-mat-khau') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Gửi link đặt lại</button>
            </div>
        </form>
        <p class="text-center text-sm text-gray-500 mt-6"><a href="{{ route('dang-nhap') }}" class="text-indigo-600 hover:underline">← Quay lại đăng nhập</a></p>
    </div>
</div>
@endsection
