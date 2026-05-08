@extends('layouts.admin')
@section('title', isset($theLoai) ? 'Sửa Thể Loại' : 'Tạo Thể Loại')
@section('page_title', isset($theLoai) ? 'Sửa: ' . $theLoai->ten : 'Tạo Thể Loại Mới')

@section('content')
<div class="max-w-xl">
    <form method="POST" action="{{ isset($theLoai) ? route('admin.the-loai.cap-nhat', $theLoai->id) : route('admin.the-loai.luu') }}" class="space-y-4">
        @csrf
        @if(isset($theLoai)) @method('PUT') @endif

        <div class="surface-panel p-6 space-y-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tên thể loại <span class="text-red-500">*</span></label>
                <input type="text" name="ten" value="{{ old('ten', $theLoai->ten ?? '') }}" required
                       class="field-shell">
                @error('ten')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Mô tả</label>
                <textarea name="mo_ta" rows="3" class="field-shell textarea-shell">{{ old('mo_ta', $theLoai->mo_ta ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Thứ tự hiển thị</label>
                <input type="number" name="thu_tu" value="{{ old('thu_tu', $theLoai->thu_tu ?? 0) }}" min="0"
                       class="field-shell">
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">{{ isset($theLoai) ? 'Cập nhật' : 'Tạo' }}</button>
            <a href="{{ route('admin.the-loai.danh-sach') }}" class="btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
