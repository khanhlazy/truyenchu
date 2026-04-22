@extends('layouts.admin')
@section('title', 'Giao Diện Website')
@section('page_title', 'Cài Đặt Giao Diện')

@section('content')
<div class="max-w-4xl">
    <form method="POST" action="{{ route('admin.cau-hinh.cap-nhat-giao-dien') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- Logo --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Logo Website
            </h3>
            <div class="flex items-start gap-6">
                {{-- Preview --}}
                <div class="flex-shrink-0">
                    <div class="w-32 h-32 bg-gray-100 dark:bg-gray-700 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                        @if($logo)
                            <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                        @else
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs text-gray-400 mt-1">Chưa có logo</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex-1 space-y-3">
                    <div>
                        <label class="block text-sm font-medium mb-1">Tải lên logo mới</label>
                        <input type="file" name="logo" accept="image/*"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400">
                        <p class="text-xs text-gray-400 mt-1">PNG, JPG, SVG, WEBP. Tối đa 2MB. Khuyến nghị: 200×200px</p>
                    </div>
                    @if($logo)
                        <label class="flex items-center gap-2 text-sm text-red-500 cursor-pointer">
                            <input type="checkbox" name="xoa_logo" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                            Xóa logo hiện tại
                        </label>
                    @endif
                </div>
            </div>
            @error('logo')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- Banner --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="font-semibold text-lg mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Banner Trang Chủ
            </h3>
            {{-- Preview --}}
            <div class="mb-4">
                <div class="w-full h-48 bg-gray-100 dark:bg-gray-700 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                    @if($banner)
                        <img src="{{ asset('storage/' . $banner) }}" alt="Banner" class="w-full h-full object-cover">
                    @else
                        <div class="text-center">
                            <svg class="w-12 h-12 text-gray-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path></svg>
                            <p class="text-sm text-gray-400 mt-2">Chưa có banner — đang dùng gradient mặc định</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="block text-sm font-medium mb-1">Tải lên banner mới</label>
                    <input type="file" name="banner" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-purple-50 file:text-purple-600 hover:file:bg-purple-100 dark:file:bg-purple-900/30 dark:file:text-purple-400">
                    <p class="text-xs text-gray-400 mt-1">PNG, JPG, WEBP. Tối đa 5MB. Khuyến nghị: 1920×400px</p>
                </div>
                @if($banner)
                    <label class="flex items-center gap-2 text-sm text-red-500 cursor-pointer">
                        <input type="checkbox" name="xoa_banner" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                        Xóa banner hiện tại (dùng gradient mặc định)
                    </label>
                @endif
            </div>
            @error('banner')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
        </div>

        {{-- Nội dung Banner --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <h3 class="font-semibold text-lg flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Nội Dung Hiển Thị
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tên website</label>
                    <input type="text" name="ten_website" value="{{ old('ten_website', $ten_website) }}"
                           class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tiêu đề banner</label>
                    <input type="text" name="banner_tieu_de" value="{{ old('banner_tieu_de', $banner_tieu_de) }}"
                           class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Mô tả website</label>
                <textarea name="mo_ta_website" rows="2" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('mo_ta_website', $mo_ta_website) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Mô tả ngắn trên banner</label>
                <textarea name="banner_mo_ta" rows="2" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">{{ old('banner_mo_ta', $banner_mo_ta) }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Lưu thay đổi
            </button>
            <a href="{{ route('admin.dashboard') }}" class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 transition">Hủy</a>
        </div>
    </form>
</div>
@endsection
