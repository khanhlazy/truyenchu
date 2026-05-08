@extends('layouts.app')

@section('title', 'Thư viện truyện - ' . \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ'))

@section('content')
@php
    $selectedGenre = $theLoais->firstWhere('id', (int) request('the_loai'));
    $sortLabels = [
        'moi_cap_nhat' => 'Mới cập nhật',
        'xem_nhieu' => 'Xem nhiều',
        'ten_az' => 'Tên A-Z',
        'ten_za' => 'Tên Z-A',
    ];
@endphp

<div x-data="{ openFilters: false }" class="shell-container pb-12 space-y-8">
    {{-- Search & Filter Hero --}}
    <section class="hero-panel">
        <div class="space-y-8">
            <div class="space-y-4">
                <h1 class="page-title">Thư viện truyện</h1>
                <p class="page-copy">
                    Khám phá hàng ngàn bộ truyện hấp dẫn, đa dạng thể loại. Tìm kiếm nội dung yêu thích của bạn chỉ trong vài giây.
                </p>
            </div>

            <form method="GET" action="{{ route('truyen.danh-sach') }}" class="group relative max-w-3xl">
                <div class="relative flex items-center">
                    <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" 
                           placeholder="Tên truyện, tác giả hoặc từ khóa..." 
                           class="field-shell h-14 pl-12 pr-32">
                    <svg class="absolute left-5 h-5 w-5" style="color: var(--ui-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <button type="submit" class="btn-primary absolute right-2 h-10 px-5 text-xs">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </section>

    <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
        {{-- Desktop Filter Sidebar --}}
        <aside class="hidden lg:block space-y-8">
            <div class="surface-panel p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-text);">Bộ lọc</h2>
                    <a href="{{ route('truyen.danh-sach') }}" class="btn-quiet text-xs">Xóa tất cả</a>
                </div>

                <form method="GET" action="{{ route('truyen.danh-sach') }}" class="space-y-6">
                    @if(request('tu_khoa'))
                        <input type="hidden" name="tu_khoa" value="{{ request('tu_khoa') }}">
                    @endif

                    <div class="space-y-3">
                        <label class="text-xs font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-muted);">Sắp xếp theo</label>
                        <select name="sap_xep" class="field-shell">
                            @foreach($sortLabels as $key => $label)
                                <option value="{{ $key }}" @selected(request('sap_xep', 'moi_cap_nhat') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-muted);">Thể loại</label>
                        <select name="the_loai" class="field-shell">
                            <option value="">Tất cả thể loại</option>
                            @foreach($theLoais as $category)
                                <option value="{{ $category->id }}" @selected(request('the_loai') == $category->id)>{{ $category->ten }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-muted);">Trạng thái</label>
                        <div class="space-y-2">
                            @foreach(['dang_ra' => 'Đang ra', 'hoan_thanh' => 'Hoàn thành'] as $key => $label)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="trang_thai" value="{{ $key }}" @checked(request('trang_thai') === $key) class="text-[color:var(--ui-primary)] focus:ring-[color:var(--ui-primary)]">
                                    <span class="text-sm font-medium group-hover:text-[color:var(--ui-primary)]" style="color: var(--ui-text-secondary);">{{ $label }}</span>
                                </label>
                            @endforeach
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="trang_thai" value="" @checked(!request('trang_thai')) class="text-[color:var(--ui-primary)] focus:ring-[color:var(--ui-primary)]">
                                <span class="text-sm font-medium group-hover:text-[color:var(--ui-primary)]" style="color: var(--ui-text-secondary);">Tất cả</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full">
                        Áp dụng lọc
                    </button>
                </form>
            </div>

            {{-- Categories Quick Links --}}
            <div class="surface-panel p-6">
                <h3 class="mb-4 text-sm font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-text);">Thể loại phổ biến</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($theLoais->take(12) as $category)
                        <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="tag-pill-muted">
                            {{ $category->ten }}
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Main Results Area --}}
        <main class="min-w-0 space-y-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h2 class="section-title">
                        @if(request('tu_khoa'))
                            Kết quả tìm kiếm cho "{{ request('tu_khoa') }}"
                        @elseif($selectedGenre)
                            Thể loại: {{ $selectedGenre->ten }}
                        @else
                            Tất cả truyện
                        @endif
                    </h2>
                    <p class="text-xs font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-muted);">Tìm thấy {{ number_format($truyens->total()) }} bộ truyện</p>
                </div>
                
                {{-- Mobile Filter Trigger --}}
                <button @click.prevent.stop="openFilters = true" class="btn-secondary lg:hidden text-xs">
                    
                    Bộ lọc
                </button>
            </div>

            @if($truyens->count() > 0)
                <div class="story-grid">
                    @foreach($truyens as $truyen)
                        @include('components.story-card', ['truyen' => $truyen])
                    @endforeach
                </div>

                <div class="mt-12 border-t border-[color:var(--ui-border)] pt-8">
                    {{ $truyens->links() }}
                </div>
            @else
                <div class="empty-state">
                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-[color:var(--ui-surface-variant)] text-[color:var(--ui-muted)]">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold" style="color: var(--ui-text);">Không tìm thấy truyện nào</h3>
                    <p class="mt-2 text-sm max-w-sm" style="color: var(--ui-muted);">Rất tiếc, chúng tôi không tìm thấy bộ truyện nào phù hợp với yêu cầu của bạn. Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                    <a href="{{ route('truyen.danh-sach') }}" class="btn-primary mt-6">Quay lại thư viện</a>
                </div>
            @endif
        </main>
    </div>

    {{-- Mobile filter drawer --}}
    <div x-cloak x-show="openFilters" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         class="fixed inset-0 z-[100] lg:hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div @click.outside="openFilters = false" class="absolute inset-x-0 bottom-0 max-h-[90vh] overflow-y-auto rounded-t-xl bg-[color:var(--ui-surface)] p-6 shadow-overlay ring-1 ring-white/10">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold" style="color: var(--ui-text);">Tùy chỉnh bộ lọc</h2>
                <button @click="openFilters = false" class="flex h-10 w-10 items-center justify-center rounded-full bg-[color:var(--ui-surface-muted)]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="GET" action="{{ route('truyen.danh-sach') }}" class="space-y-8">
                <div class="space-y-4">
                    <label class="text-sm font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-text);">Sắp xếp theo</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($sortLabels as $key => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="sap_xep" value="{{ $key }}" @checked(request('sap_xep', 'moi_cap_nhat') === $key) class="peer hidden">
                                <span class="flex h-11 items-center justify-center rounded-lg bg-[color:var(--ui-surface-variant)] text-xs font-semibold text-[color:var(--ui-muted)] peer-checked:bg-[color:var(--ui-primary)] peer-checked:text-white transition-all">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-sm font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-text);">Thể loại</label>
                    <select name="the_loai" class="field-shell">
                        <option value="">Tất cả thể loại</option>
                        @foreach($theLoais as $category)
                            <option value="{{ $category->id }}" @selected(request('the_loai') == $category->id)>{{ $category->ten }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-4">
                    <label class="text-sm font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-text);">Trạng thái</label>
                    <div class="flex gap-2">
                        @foreach(['' => 'Tất cả', 'dang_ra' => 'Đang ra', 'hoan_thanh' => 'Hoàn thành'] as $key => $label)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="trang_thai" value="{{ $key }}" @checked(request('trang_thai', '') === (string)$key) class="peer hidden">
                                <span class="flex h-11 items-center justify-center rounded-lg bg-[color:var(--ui-surface-variant)] text-xs font-semibold text-[color:var(--ui-muted)] peer-checked:bg-[color:var(--ui-primary)] peer-checked:text-white transition-all">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('truyen.danh-sach') }}" class="btn-secondary flex-1">Xóa lọc</a>
                    <button type="submit" class="btn-primary flex-1">Áp dụng</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
