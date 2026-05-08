@extends('layouts.app')

@section('title', $theLoai->ten . ' - Truyện Chữ')
@section('meta_description', $theLoai->mo_ta ?? ('Đọc truyện thể loại ' . $theLoai->ten . ' được tuyển chọn và cập nhật đều tại Truyện Chữ.'))

@section('content')
<div x-data="{ openFilters: false }" class="shell-container page-stack">
    {{-- Page header --}}
    <section class="hero-panel">
        <div class="flex items-center gap-2 text-xs mb-2" style="color: var(--ui-muted);">
            <a href="{{ route('trang-chu') }}" class="hover:underline">Trang chủ</a>
            <span>/</span>
            <span>Thể loại</span>
        </div>
        <h1 class="page-title">{{ $theLoai->ten }}</h1>
        <p class="page-copy mt-1">
            {{ $theLoai->mo_ta ?: ('Danh sách truyện thuộc thể loại ' . mb_strtolower($theLoai->ten) . '.') }}
        </p>
        <p class="mt-2 text-sm font-medium" style="color: var(--ui-text-secondary);">{{ number_format($truyens->total()) }} truyện</p>
    </section>

    {{-- Filters --}}
    <section class="surface-panel p-3 sm:p-5">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-sm font-semibold" style="color: var(--ui-text);">Sắp xếp & lọc</h2>
            <div class="flex gap-2">
                <button type="button" @click="openFilters = true" class="btn-secondary text-xs lg:hidden">Bộ lọc</button>
                <a href="{{ route('the-loai.danh-sach', $theLoai->slug) }}" class="btn-quiet text-xs">Làm mới</a>
            </div>
        </div>

        <form method="GET" class="hidden gap-3 lg:grid lg:grid-cols-[1fr_1fr_auto]">
            <select name="trang_thai" class="field-shell">
                <option value="">Tất cả trạng thái</option>
                <option value="dang_ra" @selected(request('trang_thai') === 'dang_ra')>Đang ra</option>
                <option value="hoan_thanh" @selected(request('trang_thai') === 'hoan_thanh')>Hoàn thành</option>
                <option value="tam_ngung" @selected(request('trang_thai') === 'tam_ngung')>Tạm ngưng</option>
            </select>

            <select name="sap_xep" class="field-shell">
                <option value="moi_cap_nhat" @selected(request('sap_xep', 'moi_cap_nhat') === 'moi_cap_nhat')>Mới cập nhật</option>
                <option value="xem_nhieu" @selected(request('sap_xep') === 'xem_nhieu')>Xem nhiều</option>
                <option value="ten_az" @selected(request('sap_xep') === 'ten_az')>Tên A-Z</option>
            </select>

            <button type="submit" class="btn-primary text-sm">Áp dụng</button>
        </form>
    </section>

    {{-- Results --}}
    <section class="surface-panel p-3 sm:p-5">
        @if($truyens->count() > 0)
            <div class="story-grid">
                @foreach($truyens as $truyen)
                    @include('components.story-card', ['truyen' => $truyen])
                @endforeach
            </div>

            <div class="mt-6">
                {{ $truyens->links() }}
            </div>
        @else
            <div class="empty-state">
                <p class="text-base font-semibold" style="color: var(--ui-text);">Chưa có truyện trong thể loại này.</p>
            </div>
        @endif
    </section>

    {{-- Mobile filter drawer --}}
    <template x-if="openFilters">
        <div class="fixed inset-0 z-[80] lg:hidden">
            <button type="button" class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="openFilters = false"></button>
            <div class="absolute inset-x-0 bottom-0 max-h-[80vh] overflow-y-auto p-5 shadow-xl" style="background: var(--ui-surface); border-radius: var(--ui-radius-xl) var(--ui-radius-xl) 0 0;">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Bộ lọc</h2>
                    <button type="button" class="icon-button" @click="openFilters = false">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="GET" class="space-y-3">
                    <select name="trang_thai" class="field-shell">
                        <option value="">Tất cả trạng thái</option>
                        <option value="dang_ra" @selected(request('trang_thai') === 'dang_ra')>Đang ra</option>
                        <option value="hoan_thanh" @selected(request('trang_thai') === 'hoan_thanh')>Hoàn thành</option>
                        <option value="tam_ngung" @selected(request('trang_thai') === 'tam_ngung')>Tạm ngưng</option>
                    </select>

                    <select name="sap_xep" class="field-shell">
                        <option value="moi_cap_nhat" @selected(request('sap_xep', 'moi_cap_nhat') === 'moi_cap_nhat')>Mới cập nhật</option>
                        <option value="xem_nhieu" @selected(request('sap_xep') === 'xem_nhieu')>Xem nhiều</option>
                        <option value="ten_az" @selected(request('sap_xep') === 'ten_az')>Tên A-Z</option>
                    </select>

                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a href="{{ route('the-loai.danh-sach', $theLoai->slug) }}" class="btn-secondary justify-center text-sm">Hủy</a>
                        <button type="submit" class="btn-primary text-sm">Áp dụng</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
