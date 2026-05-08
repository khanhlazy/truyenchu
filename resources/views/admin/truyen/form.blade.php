@extends('layouts.admin')
@section('title', isset($truyen) ? 'Sửa Truyện' : 'Tạo Truyện Mới')
@section('page_title', isset($truyen) ? 'Sửa Truyện: ' . $truyen->tieu_de : 'Tạo Truyện Mới')

@section('content')
<div class="max-w-4xl">
    <form method="POST"
          action="{{ isset($truyen) ? route('admin.truyen.cap-nhat', $truyen->id) : route('admin.truyen.luu') }}"
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @if(isset($truyen)) @method('PUT') @endif

        <div class="surface-panel p-6 space-y-4">
            <h3 class="section-title mb-2">Thông tin cơ bản</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Tiêu đề <span class="text-red-500">*</span></label>
                    <input type="text" name="tieu_de" value="{{ old('tieu_de', $truyen->tieu_de ?? '') }}" required
                           class="field-shell">
                    @error('tieu_de')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Tác giả <span class="text-red-500">*</span></label>
                    <input type="text" name="tac_gia" value="{{ old('tac_gia', $truyen->tac_gia ?? '') }}" required
                           class="field-shell">
                    @error('tac_gia')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Mô tả ngắn</label>
                <textarea name="mo_ta_ngan" rows="2" class="field-shell textarea-shell">{{ old('mo_ta_ngan', $truyen->mo_ta_ngan ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Mô tả đầy đủ</label>
                <textarea name="mo_ta_day_du" rows="5" class="field-shell textarea-shell">{{ old('mo_ta_day_du', $truyen->mo_ta_day_du ?? '') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Trạng thái <span class="text-red-500">*</span></label>
                    <select name="trang_thai" class="field-shell">
                        <option value="dang_ra" {{ old('trang_thai', $truyen->trang_thai ?? '') == 'dang_ra' ? 'selected' : '' }}>Đang ra</option>
                        <option value="hoan_thanh" {{ old('trang_thai', $truyen->trang_thai ?? '') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="tam_ngung" {{ old('trang_thai', $truyen->trang_thai ?? '') == 'tam_ngung' ? 'selected' : '' }}>Tạm ngưng</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Ảnh bìa</label>
                    <input type="file" name="anh_bia" accept="image/*"
                           class="field-shell text-sm file:mr-4 file:rounded-lg file:border-0 file:bg-[color:var(--ui-primary)] file:px-4 file:py-2 file:text-white">
                    @if(isset($truyen) && $truyen->anh_bia)
                        <img src="{{ $truyen->urlAnhBia() }}" class="w-20 h-28 object-cover rounded-lg mt-2" alt="">
                    @endif
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium mb-2">Thể loại <span class="text-red-500">*</span></label>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                    @php $selectedTheLoai = old('the_loai', isset($truyen) ? $truyen->theLoai->pluck('id')->toArray() : []); @endphp
                    @foreach($theLoais as $tl)
                        <label class="flex items-center gap-2 text-sm p-2 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition">
                            <input type="checkbox" name="the_loai[]" value="{{ $tl->id }}" {{ in_array($tl->id, $selectedTheLoai) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            {{ $tl->ten }}
                        </label>
                    @endforeach
                </div>
                @error('the_loai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input type="hidden" name="is_published" value="0">
                <input type="checkbox" name="is_published" value="1" id="is_published" {{ old('is_published', $truyen->is_published ?? false) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_published" class="text-sm font-medium">Xuất bản ngay</label>
            </div>
        </div>

        {{-- SEO --}}
        <div class="surface-panel p-6 space-y-4">
            <h3 class="section-title">SEO (tùy chọn)</h3>
            <div>
                <label class="block text-sm font-medium mb-1">Meta Title</label>
                <input type="text" name="meta_title" value="{{ old('meta_title', $truyen->meta_title ?? '') }}" class="field-shell">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Meta Description</label>
                <textarea name="meta_description" rows="2" class="field-shell textarea-shell">{{ old('meta_description', $truyen->meta_description ?? '') }}</textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary">{{ isset($truyen) ? 'Cập nhật' : 'Tạo truyện' }}</button>
            <a href="{{ route('admin.truyen.danh-sach') }}" class="btn-secondary">Hủy</a>
        </div>
    </form>
</div>
@endsection
