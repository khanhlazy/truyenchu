@extends('layouts.app')

@section('title', $chuong->tieu_de . ' - ' . $truyen->tieu_de)
@section('meta_description', 'Đọc ' . $chuong->tieu_de . ' của truyện ' . $truyen->tieu_de)

@section('content')
<div x-data="readerPreferences({ 
    fontSize: {{ request()->cookie('fontSize', 20) }}, 
    lineHeight: {{ request()->cookie('lineHeight', 1.8) }},
    theme: '{{ request()->cookie('readerTheme', 'default') }}',
    maxWidth: '{{ request()->cookie('readerWidth', '800px') }}'
})" class="pb-24">
    
    {{-- Top Progress Bar --}}
    <div class="fixed inset-x-0 top-0 z-[100] h-1 bg-slate-200 dark:bg-slate-800">
        <div class="h-full bg-indigo-600 transition-all duration-150" :style="{ width: scrollProgress + '%' }"></div>
    </div>

    <div class="shell-container">
        {{-- Reading Header --}}
        <header class="mx-auto mt-8 mb-12 text-center space-y-4" :style="{ maxWidth: maxWidth }">
            <nav class="flex items-center justify-center gap-2 text-[10px] font-bold uppercase tracking-widest text-[color:var(--ui-muted)]">
                <a href="{{ route('trang-chu') }}" class="hover:text-primary-600">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="hover:text-primary-600">{{ $truyen->tieu_de }}</a>
            </nav>
            <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl" style="color: var(--ui-text);">
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
        <article class="mx-auto" :style="{ maxWidth: maxWidth }">
            <div class="reading-content leading-relaxed transition-all duration-300" 
                 :style="{ fontSize: fontSize + 'px', lineHeight: lineHeight }">
                {!! $chuong->noi_dung !!}
            </div>
        </article>

        {{-- Bottom Navigation --}}
        <div class="mx-auto mt-16 mb-24 grid grid-cols-2 gap-4 sm:grid-cols-3" :style="{ maxWidth: maxWidth }">
            @if($chuongTruoc)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="group flex items-center gap-3 rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-4 transition-all hover:border-primary-600/30 hover:shadow-premium">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600/10 text-primary-600 transition-colors group-hover:bg-primary-600 group-hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-[color:var(--ui-muted)]">Chương trước</p>
                        <p class="text-xs font-bold line-clamp-1" style="color: var(--ui-text);">Ch.{{ $chuongTruoc->so_chuong }}: {{ $chuongTruoc->tieu_de }}</p>
                    </div>
                </a>
            @else
                <div></div>
            @endif

            <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="hidden sm:flex items-center justify-center gap-2 rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-4 text-xs font-bold transition-all hover:bg-slate-50 dark:hover:bg-slate-800" style="color: var(--ui-text);">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                Mục lục
            </a>

            @if($chuongSau)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="group flex items-center justify-end gap-3 rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-4 text-right transition-all hover:border-primary-600/30 hover:shadow-premium">
                    <div class="min-w-0">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-[color:var(--ui-muted)]">Chương sau</p>
                        <p class="text-xs font-bold line-clamp-1" style="color: var(--ui-text);">Ch.{{ $chuongSau->so_chuong }}: {{ $chuongSau->tieu_de }}</p>
                    </div>
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-primary-600/10 text-primary-600 transition-colors group-hover:bg-primary-600 group-hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
            @endif
        </div>
    </div>

    {{-- Floating Reader Toolbar --}}
    <div class="fixed bottom-6 left-1/2 z-[80] -translate-x-1/2" x-data="{ open: false }">
        <div class="flex items-center gap-2 rounded-2xl bg-slate-900/90 p-2 shadow-premium backdrop-blur-xl ring-1 ring-white/20">
            @if($chuongTruoc)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="flex h-10 w-10 items-center justify-center rounded-xl text-slate-400 hover:bg-white/10 hover:text-white" title="Chương trước">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
            @endif

            <div class="h-6 w-[1px] bg-white/10 mx-1"></div>

            <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl text-xs font-bold text-white hover:bg-white/10" title="Cài đặt đọc">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <span class="hidden sm:inline">Tuỳ chỉnh</span>
            </button>

            <div class="h-6 w-[1px] bg-white/10 mx-1"></div>

            @if($chuongSau)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="flex h-10 w-10 items-center justify-center rounded-xl text-indigo-400 hover:bg-indigo-500/20" title="Chương sau">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            @endif
        </div>

        {{-- Preferences Panel --}}
        <div x-show="open" @click.away="open = false" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="absolute bottom-full left-1/2 mb-4 w-72 -translate-x-1/2 rounded-3xl bg-slate-900 p-6 shadow-2xl ring-1 ring-white/20 backdrop-blur-xl">
            <div class="space-y-6">
                <div>
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Cỡ chữ</p>
                    <div class="flex items-center justify-between gap-4">
                        <button @click="adjustFontSize(-2)" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10">A-</button>
                        <span class="text-sm font-bold text-white" x-text="fontSize + 'px'"></span>
                        <button @click="adjustFontSize(2)" class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-white hover:bg-white/10">A+</button>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Độ giãn dòng</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="lineHeight = 1.6" :class="lineHeight == 1.6 ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="h-10 rounded-xl text-xs font-bold transition-all">Sát</button>
                        <button @click="lineHeight = 1.8" :class="lineHeight == 1.8 ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="h-10 rounded-xl text-xs font-bold transition-all">Vừa</button>
                        <button @click="lineHeight = 2.2" :class="lineHeight == 2.2 ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="h-10 rounded-xl text-xs font-bold transition-all">Rộng</button>
                    </div>
                </div>

                <div>
                    <p class="mb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400">Chiều rộng hiển thị</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button @click="maxWidth = '600px'" :class="maxWidth == '600px' ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="h-10 rounded-xl text-xs font-bold">Hẹp</button>
                        <button @click="maxWidth = '800px'" :class="maxWidth == '800px' ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="h-10 rounded-xl text-xs font-bold">Vừa</button>
                        <button @click="maxWidth = '1000px'" :class="maxWidth == '1000px' ? 'bg-indigo-600 text-white' : 'bg-white/5 text-slate-400'" class="h-10 rounded-xl text-xs font-bold">Rộng</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
