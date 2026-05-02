@extends('layouts.app')

@section('title', \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ') . ' - Thư viện truyện online')
@section('meta_description', \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ') . ' - Đọc truyện online với giao diện thư viện hiện đại, danh mục rõ ràng và cập nhật liên tục.')

@section('content')
@php
    $updatedRows = $truyenMoiCapNhat->take(12);
    $bannerTieuDe = \App\Models\CauHinh::lay('banner_tieu_de', 'Khám Phá Thế Giới Truyện Chữ');
    $bannerMoTa = \App\Models\CauHinh::lay('banner_mo_ta', 'Hàng nghìn bộ truyện hấp dẫn, đa dạng thể loại, cập nhật mỗi ngày. Trải nghiệm đọc truyện mượt mà nhất.');
    $bannerAnh = \App\Models\CauHinh::lay('banner');
@endphp

<div class="shell-container space-y-8 pb-8">
    {{-- Cinematic Main Hero Slider --}}
    <section x-data="{ active: 0, total: {{ $truyenHot->take(5)->count() }} }" 
             x-init="setInterval(() => active = (active + 1) % total, 5000)"
             class="relative overflow-hidden rounded-[2.5rem] bg-[#0a0a10] shadow-2xl">
        {{-- Backgrounds (Blurred + Trong Dong) --}}
        @foreach($truyenHot->take(5) as $index => $story)
            <div x-show="active === {{ $index }}" {{ $index > 0 ? 'x-cloak' : '' }}
                 class="absolute inset-0 transition-opacity duration-1000">
                <img src="{{ $story->urlAnhBia() }}" class="h-full w-full object-cover blur-[100px] opacity-20 scale-110">
                {{-- Rotating Trong Dong Overlay --}}
                <div class="absolute inset-0 flex items-center justify-center overflow-hidden pointer-events-none">
                    <img src="{{ asset('images/hero-bg.png') }}" class="h-[150%] w-auto max-w-none opacity-10 animate-spin-slow">
                </div>
                <div class="absolute inset-0 bg-gradient-to-r from-[#0a0a10] via-[#0a0a10]/60 to-transparent"></div>
            </div>
        @endforeach

        <div class="relative z-10 px-6 py-6 lg:px-10 lg:py-8">
            <div class="grid gap-6 lg:grid-cols-2 lg:items-center">
                {{-- Left: Content --}}
                @foreach($truyenHot->take(5) as $index => $story)
                    <div x-show="active === {{ $index }}" {{ $index > 0 ? 'x-cloak' : '' }}
                         class="space-y-3">
                        <div class="inline-flex items-center gap-1.5 rounded-full bg-red-500/10 px-2 py-0.5 text-[7px] font-bold uppercase tracking-widest text-red-500 ring-1 ring-red-500/20 backdrop-blur-md">
                            Hot
                        </div>

                        <div class="h-[48px] lg:h-[80px] flex items-end">
                            <div class="space-y-1">
                                <span class="block text-[10px] font-bold uppercase tracking-[0.2em] text-primary-500 opacity-80">Đam Mê Truyện</span>
                                <h1 class="text-2xl font-extrabold leading-tight text-white lg:text-3xl line-clamp-2">
                                    {{ $story->tieu_de }}
                                </h1>
                            </div>
                        </div>
                        
                        <div class="h-[36px] flex items-start">
                            <p class="max-w-md text-[11px] leading-relaxed text-slate-400 line-clamp-2">
                                {{ $story->mo_ta_ngan ?: 'Khám phá tác phẩm hấp dẫn nhất hôm nay.' }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-2.5 pt-1">
                            <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="inline-flex h-9 items-center justify-center rounded-lg bg-red-600 px-6 text-[10px] font-bold text-white shadow-lg shadow-red-600/30 transition-transform active:scale-95">
                                Đọc Ngay
                            </a>
                            <a href="{{ route('tim-kiem') }}" class="inline-flex h-9 items-center justify-center rounded-lg bg-white/5 px-5 text-[10px] font-bold text-white ring-1 ring-white/10 backdrop-blur-md hover:bg-white/10">
                                Tìm kiếm
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- Right: Cover Image --}}
                <div class="relative flex justify-center lg:justify-end">
                    @foreach($truyenHot->take(5) as $index => $story)
                        <div x-show="active === {{ $index }}" {{ $index > 0 ? 'x-cloak' : '' }}
                             class="relative aspect-[2/3] w-36 overflow-hidden rounded-xl shadow-2xl lg:w-48 transition-all duration-700 shadow-red-900/20">
                            <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-full w-full object-cover">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Slider Controls --}}
            <div class="mt-6 flex items-center justify-between border-t border-white/5 pt-4">
                <div class="flex items-center gap-2">
                    <span class="text-base font-bold text-white" x-text="(active + 1).toString().padStart(2, '0')"></span>
                    <span class="text-[10px] font-bold text-slate-500">/</span>
                    <span class="text-[10px] font-bold text-slate-500" x-text="total.toString().padStart(2, '0')"></span>
                </div>

                <div class="flex gap-2">
                    <button @click="active = (active - 1 + total) % total" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/5 text-white ring-1 ring-white/10 hover:bg-white/10 transition-colors">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button @click="active = (active + 1) % total" class="flex h-7 w-7 items-center justify-center rounded-full bg-white/5 text-white ring-1 ring-white/10 hover:bg-white/10 transition-colors">
                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    {{-- Trending Section --}}
    <section class="space-y-6">
        <div class="flex items-end justify-between">
            <div class="space-y-1">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl" style="color: var(--ui-text);">Truyện đang được đọc nhiều</h2>
                <p class="text-sm" style="color: var(--ui-muted);">Khám phá những bộ truyện hot nhất trong cộng đồng.</p>
            </div>
            <a href="{{ route('truyen.danh-sach', ['sap_xep' => 'luot_xem']) }}" class="group flex items-center gap-2 text-sm font-bold text-primary-600 dark:text-primary-400">
                Xem tất cả
                <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4-4m4 4H3"/></svg>
            </a>
        </div>

        <div class="story-grid">
            @foreach($truyenHot->take(10) as $story)
                @include('components.story-card', ['truyen' => $story])
            @endforeach
        </div>
    </section>

    {{-- Rankings & Updates Grid --}}
    <div class="grid gap-12 lg:grid-cols-[1fr_360px]">
        {{-- Recently Updated --}}
        <section class="space-y-6">
            <div class="flex items-end justify-between">
                <div class="space-y-1">
                    <h2 class="text-2xl font-bold tracking-tight sm:text-3xl" style="color: var(--ui-text);">Mới cập nhật</h2>
                    <p class="text-sm" style="color: var(--ui-muted);">Cập nhật từng chương mới nhất cho độc giả.</p>
                </div>
                <a href="{{ route('truyen.danh-sach', ['sap_xep' => 'moi_cap_nhat']) }}" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:underline">
                    Xem toàn bộ
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($truyenMoiCapNhat->take(12) as $story)
                    <div class="group flex items-center gap-4 rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-3 transition-all hover:border-primary-500/30 hover:shadow-premium">
                        <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="relative h-20 w-14 shrink-0 overflow-hidden rounded-lg">
                            <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </a>
                        <div class="min-w-0 flex-1 space-y-1">
                            <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="block text-sm font-bold line-clamp-1 hover:text-primary-600" style="color: var(--ui-text);">
                                {{ $story->tieu_de }}
                            </a>
                            <div class="flex items-center justify-between text-xs" style="color: var(--ui-muted);">
                                <span class="truncate">{{ $story->theLoai->first()?->ten ?: 'Thể loại' }}</span>
                                <span class="shrink-0">{{ optional($story->chuongMoiNhat?->updated_at)->diffForHumans() ?? 'Vừa xong' }}</span>
                            </div>
                            @if($story->chuongMoiNhat)
                                <a href="{{ route('chuong.doc', [$story->slug, $story->chuongMoiNhat->slug]) }}" class="inline-block text-xs font-medium text-primary-600 dark:text-primary-400">
                                    Chương {{ $story->chuongMoiNhat->so_chuong }}: {{ \Illuminate\Support\Str::limit($story->chuongMoiNhat->tieu_de, 20) }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

            {{-- Sidebar Rankings --}}
            <aside class="space-y-12">
                {{-- Rankings --}}
                <section class="space-y-6" x-data="{ tab: 'day' }">
                    <h3 class="text-xl font-bold" style="color: var(--ui-text);">Bảng xếp hạng</h3>
                    
                    <div class="rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] overflow-hidden">
                        <div class="flex border-b border-[color:var(--ui-border)]">
                            <button @click="tab = 'day'" 
                                    :class="tab === 'day' ? 'text-primary-600 border-primary-600' : 'text-[color:var(--ui-muted)] hover:text-[color:var(--ui-text)]'"
                                    class="flex-1 py-3 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">Ngày</button>
                            <button @click="tab = 'month'" 
                                    :class="tab === 'month' ? 'text-primary-600 border-primary-600' : 'text-[color:var(--ui-muted)] hover:text-[color:var(--ui-text)]'"
                                    class="flex-1 py-3 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">Tháng</button>
                        </div>

                        {{-- Day Tab --}}
                        <div x-cloak x-show="tab === 'day'" class="p-4 space-y-4">
                            @foreach($trendingTop->take(5) as $story)
                                <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="group flex items-center gap-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-xs font-bold {{ $loop->first ? 'bg-amber-500 text-white shadow-lg shadow-amber-500/20' : 'bg-[color:var(--ui-surface-muted)] text-[color:var(--ui-muted)]' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-bold line-clamp-1 group-hover:text-primary-600" style="color: var(--ui-text);">{{ $story->tieu_de }}</h4>
                                        <p class="text-[11px]" style="color: var(--ui-muted);">{{ number_format($story->tong_luot_xem) }} lượt xem</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Month Tab --}}
                        <div x-cloak x-show="tab === 'month'" class="p-4 space-y-4">
                            @foreach($monthlyTop->take(5) as $story)
                                <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="group flex items-center gap-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-xs font-bold {{ $loop->first ? 'bg-indigo-500 text-white shadow-lg shadow-indigo-500/20' : 'bg-[color:var(--ui-surface-muted)] text-[color:var(--ui-muted)]' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-bold line-clamp-1 group-hover:text-primary-600" style="color: var(--ui-text);">{{ $story->tieu_de }}</h4>
                                        <p class="text-[11px]" style="color: var(--ui-muted);">{{ number_format($story->tong_luot_theo_doi) }} theo dõi</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- Categories --}}
                <section class="space-y-6">
                    <h3 class="text-xl font-bold" style="color: var(--ui-text);">Thể loại nổi bật</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($theLoaiNoiBat as $category)
                            <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="inline-flex items-center rounded-xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] px-4 py-2 text-sm font-medium transition-all hover:border-primary-500 hover:text-primary-600">
                                {{ $category->ten }}
                            </a>
                        @endforeach
                    </div>
                </section>
            </aside>
    </div>

    {{-- Editor Picks (Standard Grid) --}}
    <section class="space-y-6">
        <div class="flex items-end justify-between">
            <div class="space-y-1">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl" style="color: var(--ui-text);">Biên tập viên đề cử</h2>
                <p class="text-sm" style="color: var(--ui-muted);">Những tác phẩm chất lượng không thể bỏ lỡ.</p>
            </div>
        </div>

        <div class="story-grid">
            @foreach($editorPicks->take(10) as $story)
                @include('components.story-card', ['truyen' => $story])
            @endforeach
        </div>
    </section>

    {{-- Completed stories --}}
    <section class="space-y-6">
        <div class="flex items-end justify-between">
            <div class="space-y-1">
                <h2 class="text-2xl font-bold tracking-tight sm:text-3xl" style="color: var(--ui-text);">Truyện đã hoàn thành</h2>
                <p class="text-sm" style="color: var(--ui-muted);">Đọc trọn bộ mà không cần chờ đợi.</p>
            </div>
            <a href="{{ route('truyen.danh-sach', ['trang_thai' => 'hoan_thanh']) }}" class="text-sm font-bold text-primary-600 dark:text-primary-400 hover:underline">
                Xem tất cả
            </a>
        </div>

        <div class="story-grid">
            @foreach($truyenHoanThanh->take(10) as $story)
                @include('components.story-card', ['truyen' => $story])
            @endforeach
        </div>
    </section>

    <section class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-6">
            <h3 class="text-xl font-bold" style="color: var(--ui-text);">Bình luận mới nhất</h3>
            <div class="space-y-4">
                @foreach($topBinhLuans->take(3) as $comment)
                    <div class="flex items-start gap-4 rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-4 shadow-sm">
                        <img src="{{ $comment->nguoiDung->urlAnhDaiDien() }}" alt="{{ $comment->nguoiDung->ten_hien_thi }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold" style="color: var(--ui-text);">{{ $comment->nguoiDung->ten_hien_thi }}</span>
                                <span class="text-[10px]" style="color: var(--ui-muted);">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs leading-relaxed line-clamp-2" style="color: var(--ui-text-secondary);">"{{ $comment->noi_dung }}"</p>
                            @if($comment->truyen)
                                <a href="{{ route('truyen.chi-tiet', $comment->truyen->slug) }}" class="inline-block text-[10px] font-bold text-primary-600 uppercase tracking-wider">
                                    {{ $comment->truyen->tieu_de }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="text-xl font-bold" style="color: var(--ui-text);">Cộng đồng đang nói gì?</h3>
            <div class="rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-4">
                <div class="space-y-4">
                    @foreach($tinNhanCongDong->take(3) as $message)
                        <div class="flex items-start gap-3">
                            <img src="{{ $message->nguoiDung?->urlAnhDaiDien() }}" alt="Avatar" class="h-8 w-8 shrink-0 rounded-full object-cover">
                            <div class="min-w-0 flex-1 rounded-2xl bg-[color:var(--ui-surface-muted)] px-4 py-2">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold" style="color: var(--ui-text);">{{ $message->nguoiDung?->ten_hien_thi ?: 'Thành viên' }}</span>
                                    <span class="text-[10px]" style="color: var(--ui-muted);">{{ $message->created_at ? $message->created_at->format('H:i') : '' }}</span>
                                </div>
                                <p class="mt-0.5 text-xs" style="color: var(--ui-text-secondary);">{{ $message->noi_dung }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                @auth
                    <a href="{{ route('chat') }}" class="mt-6 flex h-10 items-center justify-center rounded-xl bg-primary-600/10 text-xs font-bold text-primary-600 transition-all hover:bg-primary-600 hover:text-white">
                        Tham gia trò chuyện
                    </a>
                @endauth
            </div>
        </div>
    </section>
</div>
@endsection
