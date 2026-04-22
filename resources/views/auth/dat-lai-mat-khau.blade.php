@extends('layouts.app')
@section('title', 'Đặt Lại Mật Khẩu - TruyệnChữ')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 shadow-sm">
        <h1 class="text-2xl font-bold text-center mb-8">Đặt Lại Mật Khẩu</h1>
        <form method="POST" action="{{ route('dat-lai-mat-khau') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" required class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Mật khẩu mới</label>
                    <input type="password" name="mat_khau" required class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Xác nhận mật khẩu mới</label>
                    <input type="password" name="mat_khau_confirmation" required class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <button type="submit" class="w-full py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Đặt lại mật khẩu</button>
            </div>
        </form>
    </div>
</div>
@endsection
