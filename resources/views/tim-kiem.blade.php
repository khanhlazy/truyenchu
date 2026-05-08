@extends('layouts.app')

@section('title', 'Tìm kiếm truyện - Truyện Chữ')

@section('content')
@php
    $selectedGenre = $theLoais->firstWhere('id', (int) request('the_loai'));
    $hasSearch = $tuKhoa || request('the_loai') || request('trang_thai');
@endphp

<div x-data="{ openFilters: false }" class="shell-container page-stack">
    {{-- Page header --}}
    <section class="hero-panel">
        <h1 class="page-title">Tìm kiếm truyện</h1>
        <p class="page-copy mt-1">Tìm theo tên truyện, tác giả, thể loại hoặc trạng thái.</p>
    </section>

    {{-- Filters --}}
    <section class="surface-panel p-3 sm:p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="section-title">Bộ lọc</h2>
            <div class="flex gap-2">
                <button type="button" @click="openFilters = true" class="btn-secondary text-sm lg:hidden">Bộ lọc</button>
                <a href="{{ route('tim-kiem') }}" class="btn-quiet text-sm">Xóa bộ lọc</a>
            </div>
        </div>

        <form method="GET" action="{{ route('tim-kiem') }}" class="hidden gap-3 lg:grid lg:grid-cols-5">
            <input type="text" name="tu_khoa" value="{{ $tuKhoa }}" placeholder="Tên truyện, tác giả..." class="field-shell">

            <select name="the_loai" class="field-shell">
                <option value="">Tất cả thể loại</option>
                @foreach($theLoais as $category)
                    <option value="{{ $category->id }}" @selected(request('the_loai') == $category->id)>{{ $category->ten }}</option>
                @endforeach
            </select>

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
                <option value="ten_za" @selected(request('sap_xep') === 'ten_za')>Tên Z-A</option>
            </select>

            <button type="submit" class="btn-primary text-sm">Tìm kiếm</button>
        </form>

        @if($hasSearch)
            <div class="mt-3 flex flex-wrap gap-1.5">
                @if($tuKhoa)
                    <span class="tag-pill">{{ $tuKhoa }}</span>
                @endif
                @if($selectedGenre)
                    <span class="tag-pill-muted">{{ $selectedGenre->ten }}</span>
                @endif
                @if(request('trang_thai'))
                    <span class="tag-pill-muted">{{ str_replace('_', ' ', request('trang_thai')) }}</span>
                @endif
            </div>
        @endif
    </section>

    {{-- Results --}}
    @if($hasSearch)
        <section class="surface-panel p-3 sm:p-5">
            <h2 class="section-title mb-4">
                Tìm thấy {{ $truyens instanceof \Illuminate\Pagination\LengthAwarePaginator ? number_format($truyens->total()) : '0' }} truyện
            </h2>

            @if($truyens instanceof \Illuminate\Pagination\LengthAwarePaginator && $truyens->count() > 0)
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
                    <p class="text-base font-semibold" style="color: var(--ui-text);">Không tìm thấy truyện phù hợp.</p>
                    <p class="mt-1 text-sm" style="color: var(--ui-muted);">Thử đổi từ khóa hoặc nới rộng bộ lọc.</p>
                </div>
            @endif
        </section>
    @else
        <div class="empty-state">
            <p class="text-base font-semibold" style="color: var(--ui-text);">Nhập từ khóa để bắt đầu tìm kiếm.</p>
            <p class="mt-1 text-sm" style="color: var(--ui-muted);">Bạn có thể tìm theo tên truyện, tác giả, thể loại hoặc trạng thái.</p>
        </div>
    @endif

    {{-- Mobile filter drawer --}}
    <template x-if="openFilters">
        <div class="fixed inset-0 z-[80] lg:hidden">
            <button type="button" class="absolute inset-0 bg-black/30 backdrop-blur-sm" @click="openFilters = false"></button>
            <div class="absolute inset-x-0 bottom-0 max-h-[85vh] overflow-y-auto p-5 shadow-xl" style="background: var(--ui-surface); border-radius: var(--ui-radius-xl) var(--ui-radius-xl) 0 0;">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold">Bộ lọc</h2>
                    <button type="button" class="icon-button" @click="openFilters = false">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form method="GET" action="{{ route('tim-kiem') }}" class="space-y-3">
                    <input type="text" name="tu_khoa" value="{{ $tuKhoa }}" placeholder="Tên truyện, tác giả..." class="field-shell">

                    <select name="sap_xep" class="field-shell">
                        <option value="moi_cap_nhat" @selected(request('sap_xep', 'moi_cap_nhat') === 'moi_cap_nhat')>Mới cập nhật</option>
                        <option value="xem_nhieu" @selected(request('sap_xep') === 'xem_nhieu')>Xem nhiều</option>
                        <option value="ten_az" @selected(request('sap_xep') === 'ten_az')>Tên A-Z</option>
                        <option value="ten_za" @selected(request('sap_xep') === 'ten_za')>Tên Z-A</option>
                    </select>

                    <select name="trang_thai" class="field-shell">
                        <option value="">Tất cả trạng thái</option>
                        <option value="dang_ra" @selected(request('trang_thai') === 'dang_ra')>Đang ra</option>
                        <option value="hoan_thanh" @selected(request('trang_thai') === 'hoan_thanh')>Hoàn thành</option>
                        <option value="tam_ngung" @selected(request('trang_thai') === 'tam_ngung')>Tạm ngưng</option>
                    </select>

                    <select name="the_loai" class="field-shell">
                        <option value="">Tất cả thể loại</option>
                        @foreach($theLoais as $category)
                            <option value="{{ $category->id }}" @selected(request('the_loai') == $category->id)>{{ $category->ten }}</option>
                        @endforeach
                    </select>

                    <div class="grid grid-cols-2 gap-2 pt-2">
                        <a href="{{ route('tim-kiem') }}" class="btn-secondary justify-center text-sm">Hủy</a>
                        <button type="submit" class="btn-primary text-sm">Áp dụng</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection
