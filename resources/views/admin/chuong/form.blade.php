@extends('layouts.admin')
@section('title', isset($chuong) ? 'Sửa Chương' : 'Tạo Chương Mới')
@section('page_title', isset($chuong) ? 'Sửa: ' . $chuong->tieu_de : 'Tạo Chương Mới - ' . $truyen->tieu_de)

@section('content')
<div class="max-w-4xl">
    <a href="{{ route('admin.chuong.danh-sach', $truyen->id) }}" class="btn-quiet mb-4 inline-flex">← Quay lại danh sách chương</a>

    <form method="POST"
          action="{{ isset($chuong) ? route('admin.chuong.cap-nhat', $chuong->id) : route('admin.chuong.luu', $truyen->id) }}"
          class="space-y-6">
        @csrf
        @if(isset($chuong)) @method('PUT') @endif

        <div class="surface-panel p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Số chương <span class="text-red-500">*</span></label>
                    <input type="number" name="so_chuong" value="{{ old('so_chuong', $chuong->so_chuong ?? $soChuongTiepTheo ?? 1) }}" min="1" required
                           class="field-shell">
                    @error('so_chuong')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Tiêu đề <span class="text-red-500">*</span></label>
                    <input type="text" name="tieu_de" value="{{ old('tieu_de', $chuong->tieu_de ?? '') }}" required
                           class="field-shell">
                    @error('tieu_de')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Nội dung <span class="text-red-500">*</span></label>
                <textarea name="noi_dung" rows="20" required
                          class="field-shell textarea-shell font-mono" style="line-height: 1.8;">{{ old('noi_dung', $chuong->noi_dung ?? '') }}</textarea>
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
            <button type="submit" class="btn-primary">{{ isset($chuong) ? 'Cập nhật' : 'Tạo chương' }}</button>
            <a href="{{ route('admin.chuong.danh-sach', $truyen->id) }}" class="btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
