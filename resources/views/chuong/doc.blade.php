@extends('layouts.app')

@section('title', $chuong->tieu_de . ' - ' . $truyen->tieu_de)
@section('meta_description', 'Đọc ' . $chuong->tieu_de . ' của truyện ' . $truyen->tieu_de)

@section('content')
<div x-data="readerPreferences({
    fontSize: {{ request()->cookie('fontSize', 18) }},
    lineHeight: {{ request()->cookie('lineHeight', 1.8) }},
    widthPreset: 'balanced'
})" class="pb-24">
    
    {{-- Top Progress Bar --}}
    <div class="fixed inset-x-0 top-0 z-[100] h-1" style="background: var(--ui-surface-elevated);">
        <div class="h-full transition-all duration-150" style="background: var(--ui-highlight);" :style="{ width: scrollProgress + '%' }"></div>
    </div>

    <div class="shell-container">
        {{-- Reading Header --}}
        <header class="mx-auto mt-8 mb-12 text-center space-y-4" :style="{ maxWidth: articleWidth }">
            <nav class="flex items-center justify-center gap-2 text-[10px] font-medium uppercase tracking-[0.02em] text-[color:var(--ui-muted)]">
                <a href="{{ route('trang-chu') }}" class="hover:text-[color:var(--ui-primary)]">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="hover:text-[color:var(--ui-primary)]">{{ $truyen->tieu_de }}</a>
            </nav>
            <h1 class="page-title">
                {{ $chuong->tieu_de }}
            </h1>
            <div class="flex items-center justify-center gap-4 text-xs font-medium" style="color: var(--ui-muted);">
                <span class="flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $chuong->created_at->diffForHumans() }}
                </span>
                <span class="flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    {{ number_format($chuong->so_tu) }} chữ
                </span>
            </div>
        </header>

        {{-- Main Reading Content --}}
        <article class="mx-auto" :style="{ maxWidth: articleWidth }">
            <div class="reading-content mx-auto transition-all duration-300" :style="articleStyle">
                {!! $chuong->noi_dung !!}
            </div>
        </article>

        {{-- Bottom Navigation --}}
        <div class="mx-auto mt-16 mb-24 grid grid-cols-2 gap-4 sm:grid-cols-3" :style="{ maxWidth: articleWidth }">
            @if($chuongTruoc)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="surface-panel group flex items-center gap-3 p-4 transition-all hover:-translate-y-0.5">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-[color:var(--ui-primary)] transition-colors group-hover:bg-[color:var(--ui-primary)] group-hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.02em] text-[color:var(--ui-muted)]">Chương trước</p>
                        <p class="text-xs font-bold line-clamp-1" style="color: var(--ui-text);">Ch.{{ $chuongTruoc->so_chuong }}: {{ $chuongTruoc->tieu_de }}</p>
                    </div>
                </a>
            @else
                <div></div>
            @endif

            <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="surface-panel hidden items-center justify-center gap-2 p-4 text-xs font-semibold transition-all sm:flex" style="color: var(--ui-text);">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Mục lục
            </a>

            @if($chuongSau)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="surface-panel group flex items-center justify-end gap-3 p-4 text-right transition-all hover:-translate-y-0.5">
                    <div class="min-w-0">
                        <p class="text-[10px] font-semibold uppercase tracking-[0.02em] text-[color:var(--ui-muted)]">Chương sau</p>
                        <p class="text-xs font-bold line-clamp-1" style="color: var(--ui-text);">Ch.{{ $chuongSau->so_chuong }}: {{ $chuongSau->tieu_de }}</p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-[color:var(--ui-primary)] transition-colors group-hover:bg-[color:var(--ui-primary)] group-hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @endif
        </div>
    </div>

    {{-- Floating Reader Toolbar --}}
    <div class="fixed bottom-6 left-1/2 z-[80] -translate-x-1/2" x-data="{ open: false }">
        <div class="flex items-center gap-2 rounded-xl p-2 shadow-overlay backdrop-blur-xl ring-1 ring-white/20" style="background: rgba(29, 26, 36, 0.92);">
            @if($chuongTruoc)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="flex h-10 w-10 items-center justify-center rounded-lg text-white/65 hover:bg-white/10 hover:text-white" title="Chương trước">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            <div class="h-6 w-[1px] bg-white/10 mx-1"></div>

            <button @click="open = !open" class="flex items-center gap-2 rounded-lg px-3 py-2 text-xs font-semibold text-white hover:bg-white/10" title="Cài đặt đọc">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <span class="hidden sm:inline">Tuỳ chỉnh</span>
            </button>

            <div class="h-6 w-[1px] bg-white/10 mx-1"></div>

            @if($chuongSau)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="flex h-10 w-10 items-center justify-center rounded-lg text-[color:var(--ui-highlight)] hover:bg-white/10" title="Chương sau">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

        {{-- Preferences Panel --}}
        <div x-show="open" @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute bottom-full left-1/2 mb-4 w-72 -translate-x-1/2 rounded-xl p-6 shadow-overlay ring-1 ring-white/20 backdrop-blur-xl" style="background: rgba(29, 26, 36, 0.96);">
            <div class="space-y-6">
                <div>
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.02em] text-white/60">Cỡ chữ</p>
                    <div class="flex items-center justify-between gap-4">
                        <button @click="adjustFontSize(-2)" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-white hover:bg-white/10">A-</button>
                        <span class="text-sm font-bold text-white" x-text="fontSize + 'px'"></span>
                        <button @click="adjustFontSize(2)" class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/5 text-white hover:bg-white/10">A+</button>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.02em] text-white/60">Độ giãn dòng</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="setLineHeight(1.6)" :class="lineHeight == 1.6 ? 'bg-[color:var(--ui-primary)] text-white' : 'bg-white/5 text-white/65'" class="h-10 rounded-lg text-xs font-semibold transition-all">Sát</button>
                        <button @click="setLineHeight(1.8)" :class="lineHeight == 1.8 ? 'bg-[color:var(--ui-primary)] text-white' : 'bg-white/5 text-white/65'" class="h-10 rounded-lg text-xs font-semibold transition-all">Vừa</button>
                        <button @click="setLineHeight(2.2)" :class="lineHeight == 2.2 ? 'bg-[color:var(--ui-primary)] text-white' : 'bg-white/5 text-white/65'" class="h-10 rounded-lg text-xs font-semibold transition-all">Rộng</button>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-[10px] font-semibold uppercase tracking-[0.02em] text-white/60">Chiều rộng hiển thị</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="setWidthPreset('compact')" :class="widthPreset == 'compact' ? 'bg-[color:var(--ui-primary)] text-white' : 'bg-white/5 text-white/65'" class="h-10 rounded-lg text-xs font-semibold">Hẹp</button>
                        <button @click="setWidthPreset('balanced')" :class="widthPreset == 'balanced' ? 'bg-[color:var(--ui-primary)] text-white' : 'bg-white/5 text-white/65'" class="h-10 rounded-lg text-xs font-semibold">Vừa</button>
                        <button @click="setWidthPreset('expansive')" :class="widthPreset == 'expansive' ? 'bg-[color:var(--ui-primary)] text-white' : 'bg-white/5 text-white/65'" class="h-10 rounded-lg text-xs font-semibold">Rộng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
