@extends('layouts.admin')
@section('title', isset($chuong) ? 'Sửa Chương' : 'Tạo Chương Mới')
@section('page_title', isset($chuong) ? 'Sửa: ' . $chuong->tieu_de : 'Tạo Chương Mới - ' . $truyen->tieu_de)

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.chuong.danh-sach', $truyen->id) }}" class="text-sm text-gray-500 hover:text-indigo-600 transition mb-4 inline-block">← Quay lại danh sách chương</a>

    <form method="POST"
          action="{{ isset($chuong) ? route('admin.chuong.cap-nhat', $chuong->id) : route('admin.chuong.luu', $truyen->id) }}"
          class="space-y-6">
        @csrf
        @if(isset($chuong)) @method('PUT') @endif

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Số chương <span class="text-red-500">*</span></label>
                    <input type="number" name="so_chuong" value="{{ old('so_chuong', $chuong->so_chuong ?? $soChuongTiepTheo ?? 1) }}" min="1" required
                           class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('so_chuong')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Tiêu đề <span class="text-red-500">*</span></label>
                    <input type="text" name="tieu_de" value="{{ old('tieu_de', $chuong->tieu_de ?? '') }}" required
                           class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    @error('tieu_de')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nội dung <span class="text-red-500">*</span></label>
                <textarea name="noi_dung" rows="20" required
                          class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono" style="line-height: 1.8;">{{ old('noi_dung', $chuong->noi_dung ?? '') }}</textarea>
                @error('noi_dung')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" id="is_published" {{ old('is_published', $chuong->is_published ?? false) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_published" class="text-sm font-medium">Xuất bản ngay</label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">{{ isset($chuong) ? 'Cập nhật' : 'Tạo chương' }}</button>
            <a href="{{ route('admin.chuong.danh-sach', $truyen->id) }}" class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 transition">Hủy</a>
        </div>
    </form>
</div>
@endsection
