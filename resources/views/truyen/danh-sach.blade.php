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
    <section class="relative overflow-hidden rounded-[2.5rem] bg-indigo-950 px-8 py-12 text-white shadow-xl lg:px-16">
        <div class="absolute inset-0 opacity-20">
            <div class="h-full w-full bg-[radial-gradient(circle_at_50%_50%,rgba(99,102,241,0.5),transparent_70%)]"></div>
        </div>

        <div class="relative z-10 space-y-8">
            <div class="space-y-4">
                <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl lg:text-5xl text-white">Thư viện truyện</h1>
                <p class="max-w-2xl text-lg text-indigo-200">
                    Khám phá hàng ngàn bộ truyện hấp dẫn, đa dạng thể loại. Tìm kiếm nội dung yêu thích của bạn chỉ trong vài giây.
                </p>
            </div>

            <form method="GET" action="{{ route('truyen.danh-sach') }}" class="group relative max-w-3xl">
                <div class="relative flex items-center">
                    <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" 
                           placeholder="Tên truyện, tác giả hoặc từ khóa..." 
                           class="h-16 w-full rounded-2xl border-0 bg-white/10 pl-14 pr-32 text-sm font-medium text-white placeholder:text-indigo-300 ring-1 ring-white/20 backdrop-blur-md transition-all focus:bg-white/20 focus:ring-indigo-500">
                    <svg class="absolute left-6 h-5 w-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <button type="submit" class="absolute right-3 h-10 rounded-xl bg-indigo-600 px-6 text-xs font-bold text-white transition-all hover:bg-indigo-500">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </section>

    <div class="grid gap-8 lg:grid-cols-[280px_1fr]">
        {{-- Desktop Filter Sidebar --}}
        <aside class="hidden lg:block space-y-8">
            <div class="rounded-3xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-sm font-bold uppercase tracking-widest" style="color: var(--ui-text);">Bộ lọc</h2>
                    <a href="{{ route('truyen.danh-sach') }}" class="text-[10px] font-bold text-primary-600 uppercase hover:underline">Xóa tất cả</a>
                </div>

                <form method="GET" action="{{ route('truyen.danh-sach') }}" class="space-y-6">
                    @if(request('tu_khoa'))
                        <input type="hidden" name="tu_khoa" value="{{ request('tu_khoa') }}">
                    @endif

                    <div class="space-y-3">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Sắp xếp theo</label>
                        <select name="sap_xep" class="w-full rounded-xl border-[color:var(--ui-border)] bg-[color:var(--ui-surface-muted)] text-sm font-medium focus:ring-primary-600">
                            @foreach($sortLabels as $key => $label)
                                <option value="{{ $key }}" @selected(request('sap_xep', 'moi_cap_nhat') === $key)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Thể loại</label>
                        <select name="the_loai" class="w-full rounded-xl border-[color:var(--ui-border)] bg-[color:var(--ui-surface-muted)] text-sm font-medium focus:ring-primary-600">
                            <option value="">Tất cả thể loại</option>
                            @foreach($theLoais as $category)
                                <option value="{{ $category->id }}" @selected(request('the_loai') == $category->id)>{{ $category->ten }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="space-y-3">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Trạng thái</label>
                        <div class="space-y-2">
                            @foreach(['dang_ra' => 'Đang ra', 'hoan_thanh' => 'Hoàn thành'] as $key => $label)
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio" name="trang_thai" value="{{ $key }}" @checked(request('trang_thai') === $key) class="text-primary-600 focus:ring-primary-600">
                                    <span class="text-sm font-medium group-hover:text-primary-600" style="color: var(--ui-text-secondary);">{{ $label }}</span>
                                </label>
                            @endforeach
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="radio" name="trang_thai" value="" @checked(!request('trang_thai')) class="text-primary-600 focus:ring-primary-600">
                                <span class="text-sm font-medium group-hover:text-primary-600" style="color: var(--ui-text-secondary);">Tất cả</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="w-full h-11 rounded-xl bg-indigo-600 text-sm font-bold text-white transition-all hover:bg-indigo-500 shadow-lg shadow-indigo-600/20">
                        Áp dụng lọc
                    </button>
                </form>
            </div>

            {{-- Categories Quick Links --}}
            <div class="rounded-3xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-6">
                <h3 class="text-sm font-bold uppercase tracking-widest mb-4" style="color: var(--ui-text);">Thể loại phổ biến</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($theLoais->take(12) as $category)
                        <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="rounded-lg bg-[color:var(--ui-surface-muted)] px-3 py-1.5 text-xs font-bold transition-all hover:bg-primary-600 hover:text-white" style="color: var(--ui-muted);">
                            {{ $category->ten }}
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Main Results Area --}}
        <main class="space-y-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h2 class="text-xl font-bold" style="color: var(--ui-text);">
                        @if(request('tu_khoa'))
                            Kết quả tìm kiếm cho "{{ request('tu_khoa') }}"
                        @elseif($selectedGenre)
                            Thể loại: {{ $selectedGenre->ten }}
                        @else
                            Tất cả truyện
                        @endif
                    </h2>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Tìm thấy {{ number_format($truyens->total()) }} bộ truyện</p>
                </div>
                
                {{-- Mobile Filter Trigger --}}
                <button @click="openFilters = true" class="lg:hidden flex items-center gap-2 rounded-xl bg-[color:var(--ui-surface)] border border-[color:var(--ui-border)] px-4 py-2 text-xs font-bold" style="color: var(--ui-text);">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                    Bộ lọc
                </button>
            </div>

            @if($truyens->count() > 0)
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4">
                    @foreach($truyens as $truyen)
                        @include('components.story-card', ['truyen' => $truyen])
                    @endforeach
                </div>

                <div class="mt-12 border-t border-[color:var(--ui-border)] pt-8">
                    {{ $truyens->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center rounded-3xl border border-dashed border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] py-20 text-center">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 dark:bg-slate-800 text-slate-400 mb-4">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-lg font-bold" style="color: var(--ui-text);">Không tìm thấy truyện nào</h3>
                    <p class="mt-2 text-sm max-w-sm" style="color: var(--ui-muted);">Rất tiếc, chúng tôi không tìm thấy bộ truyện nào phù hợp với yêu cầu của bạn. Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
                    <a href="{{ route('truyen.danh-sach') }}" class="mt-6 rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-600/20">Quay lại thư viện</a>
                </div>
            @endif
        </main>
    </div>

    {{-- Mobile filter drawer --}}
    <div x-show="openFilters" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         class="fixed inset-0 z-[100] lg:hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="openFilters = false"></div>
        <div class="absolute inset-x-0 bottom-0 rounded-t-[2.5rem] bg-[color:var(--ui-surface)] p-8 shadow-2xl ring-1 ring-white/10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-xl font-bold" style="color: var(--ui-text);">Tùy chỉnh bộ lọc</h2>
                <button @click="openFilters = false" class="flex h-10 w-10 items-center justify-center rounded-full bg-[color:var(--ui-surface-muted)]">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="GET" action="{{ route('truyen.danh-sach') }}" class="space-y-8">
                <div class="space-y-4">
                    <label class="text-sm font-bold uppercase tracking-widest" style="color: var(--ui-text);">Sắp xếp theo</label>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($sortLabels as $key => $label)
                            <label class="cursor-pointer">
                                <input type="radio" name="sap_xep" value="{{ $key }}" @checked(request('sap_xep', 'moi_cap_nhat') === $key) class="peer hidden">
                                <span class="flex h-11 items-center justify-center rounded-xl bg-[color:var(--ui-surface-muted)] text-xs font-bold text-[color:var(--ui-muted)] peer-checked:bg-primary-600 peer-checked:text-white transition-all">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-4">
                    <label class="text-sm font-bold uppercase tracking-widest" style="color: var(--ui-text);">Thể loại</label>
                    <select name="the_loai" class="w-full h-12 rounded-xl border-[color:var(--ui-border)] bg-[color:var(--ui-surface-muted)] text-sm font-medium">
                        <option value="">Tất cả thể loại</option>
                        @foreach($theLoais as $category)
                            <option value="{{ $category->id }}" @selected(request('the_loai') == $category->id)>{{ $category->ten }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-4">
                    <label class="text-sm font-bold uppercase tracking-widest" style="color: var(--ui-text);">Trạng thái</label>
                    <div class="flex gap-2">
                        @foreach(['' => 'Tất cả', 'dang_ra' => 'Đang ra', 'hoan_thanh' => 'Hoàn thành'] as $key => $label)
                            <label class="flex-1 cursor-pointer">
                                <input type="radio" name="trang_thai" value="{{ $key }}" @checked(request('trang_thai', '') === (string)$key) class="peer hidden">
                                <span class="flex h-11 items-center justify-center rounded-xl bg-[color:var(--ui-surface-muted)] text-xs font-bold text-[color:var(--ui-muted)] peer-checked:bg-primary-600 peer-checked:text-white transition-all">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <a href="{{ route('truyen.danh-sach') }}" class="flex h-14 flex-1 items-center justify-center rounded-2xl bg-slate-100 text-sm font-bold text-slate-600 dark:bg-slate-800 dark:text-slate-400">Xóa lọc</a>
                    <button type="submit" class="flex h-14 flex-1 items-center justify-center rounded-2xl bg-indigo-600 text-sm font-bold text-white shadow-lg shadow-indigo-600/20">Áp dụng</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
