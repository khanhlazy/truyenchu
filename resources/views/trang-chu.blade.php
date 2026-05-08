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
             class="relative overflow-hidden rounded-xl shadow-card" style="background: var(--ui-text);">
        {{-- Backgrounds (Blurred + Trong Dong) --}}
        @foreach($truyenHot->take(5) as $index => $story)
            <div x-show="active === {{ $index }}" {{ $index > 0 ? 'x-cloak' : '' }}
                 class="absolute inset-0 transition-opacity duration-1000">
                <img src="{{ $story->urlAnhBia() }}" class="h-full w-full object-cover blur-[100px] opacity-20 scale-110">
                {{-- Rotating Trong Dong Overlay --}}
                <div class="absolute inset-0 flex items-center justify-center overflow-hidden pointer-events-none">
                    <img src="{{ asset('images/hero-bg.png') }}" class="h-[150%] w-auto max-w-none opacity-10 animate-spin-slow">
                </div>
                <div class="absolute inset-0" style="background: linear-gradient(90deg, var(--ui-text) 0%, rgba(29, 26, 36, 0.72) 58%, transparent 100%);"></div>
            </div>
        @endforeach

        <div class="relative z-10 px-6 py-10 lg:px-10 lg:py-16">
            <div class="grid gap-8 lg:grid-cols-2 lg:items-center">
                {{-- Left: Content --}}
                @foreach($truyenHot->take(5) as $index => $story)
                    <div x-show="active === {{ $index }}" {{ $index > 0 ? 'x-cloak' : '' }}
                         x-transition:enter="transition ease-out duration-500"
                         x-transition:enter-start="opacity-0 translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="space-y-6">
                        
                        <div class="space-y-4">
                            <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.02em] ring-1 backdrop-blur-md" style="background: rgba(253, 86, 167, 0.14); color: #FD56A7; border-color: rgba(253, 86, 167, 0.28);">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background: #FD56A7;"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2" style="background: #FD56A7;"></span>
                                </span>
                                Hot Story
                            </div>

                            <div class="space-y-2">
                                <span class="block text-xs font-medium uppercase tracking-[0.02em]" style="color: rgba(255, 255, 255, 0.72);">Đam Mê Truyện</span>
                                <h1 class="line-clamp-2 text-[32px] font-bold leading-[1.2] text-white">
                                    {{ $story->tieu_de }}
                                </h1>
                            </div>
                            
                            <p class="max-w-md text-sm leading-relaxed text-white/75 line-clamp-3 lg:text-base">
                                {{ $story->mo_ta_ngan ?: 'Khám phá tác phẩm hấp dẫn nhất hôm nay với cốt truyện lôi cuốn và kịch tính.' }}
                            </p>
                        </div>

                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="btn-primary">
                                Đọc Ngay
                            </a>
                            <a href="{{ route('tim-kiem') }}" class="btn-secondary border-white/35 text-white hover:bg-white/10">
                                Tìm kiếm
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- Right: Cover Image --}}
                <div class="relative hidden lg:flex justify-end">
                    @foreach($truyenHot->take(5) as $index => $story)
                        <div x-show="active === {{ $index }}" {{ $index > 0 ? 'x-cloak' : '' }}
                             x-transition:enter="transition ease-out duration-700"
                             x-transition:enter-start="opacity-0 scale-90 translate-x-12"
                             x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                             class="relative aspect-[2/3] w-64 overflow-hidden rounded-lg shadow-overlay transition-all duration-700 ring-1 ring-white/10">
                            <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Slider Controls --}}
            <div class="mt-6 flex items-center justify-between border-t border-white/10 pt-4">
                <div class="flex items-center gap-2">
                    <span class="text-base font-bold text-white" x-text="(active + 1).toString().padStart(2, '0')"></span>
                    <span class="text-[10px] font-bold text-white/45">/</span>
                    <span class="text-[10px] font-bold text-white/45" x-text="total.toString().padStart(2, '0')"></span>
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
                <h2 class="section-title">Truyện đang được đọc nhiều</h2>
                <p class="text-sm" style="color: var(--ui-muted);">Khám phá những bộ truyện hot nhất trong cộng đồng.</p>
            </div>
            <a href="{{ route('truyen.danh-sach', ['sap_xep' => 'luot_xem']) }}" class="btn-quiet">
                Xem tất cả
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
                    <h2 class="section-title">Mới cập nhật</h2>
                    <p class="text-sm" style="color: var(--ui-muted);">Cập nhật từng chương mới nhất cho độc giả.</p>
                </div>
                <a href="{{ route('truyen.danh-sach', ['sap_xep' => 'moi_cap_nhat']) }}" class="btn-quiet">
                    Xem toàn bộ
                </a>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($truyenMoiCapNhat->take(12) as $story)
                    <div class="group flex items-center gap-4 rounded-lg border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-3 transition-all hover:-translate-y-0.5 hover:shadow-card">
                        <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="relative h-20 w-14 shrink-0 overflow-hidden rounded-lg">
                            <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </a>
                        <div class="min-w-0 flex-1 space-y-1">
                            <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="block text-sm font-semibold line-clamp-1 hover:text-[color:var(--ui-primary)]" style="color: var(--ui-text);">
                                {{ $story->tieu_de }}
                            </a>
                            <div class="flex items-center justify-between text-xs" style="color: var(--ui-muted);">
                                <span class="truncate">{{ $story->theLoai->first()?->ten ?: 'Thể loại' }}</span>
                                <span class="shrink-0">{{ optional($story->chuongMoiNhat?->updated_at)->diffForHumans() ?? 'Vừa xong' }}</span>
                            </div>
                            @if($story->chuongMoiNhat)
                                <a href="{{ route('chuong.doc', [$story->slug, $story->chuongMoiNhat->slug]) }}" class="inline-block text-xs font-medium text-[color:var(--ui-primary)]">
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
                    <h3 class="section-title">Bảng xếp hạng</h3>
                    
                    <div class="surface-panel overflow-hidden">
                        <div class="flex border-b border-[color:var(--ui-border)]">
                            <button @click="tab = 'day'" 
                                    :class="tab === 'day' ? 'text-[color:var(--ui-primary)] border-[color:var(--ui-primary)]' : 'text-[color:var(--ui-muted)] hover:text-[color:var(--ui-text)]'"
                                    class="flex-1 py-3 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">Ngày</button>
                            <button @click="tab = 'month'" 
                                    :class="tab === 'month' ? 'text-[color:var(--ui-primary)] border-[color:var(--ui-primary)]' : 'text-[color:var(--ui-muted)] hover:text-[color:var(--ui-text)]'"
                                    class="flex-1 py-3 text-xs font-bold uppercase tracking-wider border-b-2 transition-all">Tháng</button>
                        </div>

                        {{-- Day Tab --}}
                        <div x-cloak x-show="tab === 'day'" class="p-4 space-y-4">
                            @foreach($trendingTop->take(5) as $story)
                                <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="group flex items-center gap-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-xs font-bold {{ $loop->first ? 'text-white' : 'bg-[color:var(--ui-surface-variant)] text-[color:var(--ui-muted)]' }}" style="{{ $loop->first ? 'background: var(--ui-secondary);' : '' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold line-clamp-1 group-hover:text-[color:var(--ui-primary)]" style="color: var(--ui-text);">{{ $story->tieu_de }}</h4>
                                        <p class="text-[11px]" style="color: var(--ui-muted);">{{ number_format($story->tong_luot_xem) }} lượt xem</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Month Tab --}}
                        <div x-cloak x-show="tab === 'month'" class="p-4 space-y-4">
                            @foreach($monthlyTop->take(5) as $story)
                                <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="group flex items-center gap-4">
                                    <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-xs font-bold {{ $loop->first ? 'text-white' : 'bg-[color:var(--ui-surface-variant)] text-[color:var(--ui-muted)]' }}" style="{{ $loop->first ? 'background: var(--ui-primary);' : '' }}">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <h4 class="text-sm font-semibold line-clamp-1 group-hover:text-[color:var(--ui-primary)]" style="color: var(--ui-text);">{{ $story->tieu_de }}</h4>
                                        <p class="text-[11px]" style="color: var(--ui-muted);">{{ number_format($story->tong_luot_theo_doi) }} theo dõi</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </section>

                {{-- Categories --}}
                <section class="space-y-6">
                    <h3 class="section-title">Thể loại nổi bật</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($theLoaiNoiBat as $category)
                            <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="genre-chip">
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
                <h2 class="section-title">Biên tập viên đề cử</h2>
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
                <h2 class="section-title">Truyện đã hoàn thành</h2>
                <p class="text-sm" style="color: var(--ui-muted);">Đọc trọn bộ mà không cần chờ đợi.</p>
            </div>
            <a href="{{ route('truyen.danh-sach', ['trang_thai' => 'hoan_thanh']) }}" class="btn-quiet">
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
            <h3 class="section-title">Bình luận mới nhất</h3>
            <div class="space-y-4">
                @foreach($topBinhLuans->take(3) as $comment)
                    <div class="surface-panel flex items-start gap-4 p-4">
                        <img src="{{ $comment->nguoiDung->urlAnhDaiDien() }}" alt="{{ $comment->nguoiDung->ten_hien_thi }}" class="h-10 w-10 shrink-0 rounded-full object-cover">
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-center justify-between">
                                    <span class="text-sm font-semibold" style="color: var(--ui-text);">{{ $comment->nguoiDung->ten_hien_thi }}</span>
                                <span class="text-[10px]" style="color: var(--ui-muted);">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs leading-relaxed line-clamp-2" style="color: var(--ui-text-secondary);">"{{ $comment->noi_dung }}"</p>
                            @if($comment->truyen)
                                <a href="{{ route('truyen.chi-tiet', $comment->truyen->slug) }}" class="inline-block text-[10px] font-bold text-[color:var(--ui-primary)] uppercase tracking-[0.02em]">
                                    {{ $comment->truyen->tieu_de }}
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-6">
            <h3 class="section-title">Cộng đồng đang nói gì?</h3>
            <div class="surface-panel p-4">
                <div class="space-y-4">
                    @foreach($tinNhanCongDong->take(3) as $message)
                        <div class="flex items-start gap-3">
                            <img src="{{ $message->nguoiDung?->urlAnhDaiDien() }}" alt="Avatar" class="h-8 w-8 shrink-0 rounded-full object-cover">
                            <div class="min-w-0 flex-1 rounded-lg bg-[color:var(--ui-surface-variant)] px-4 py-2">
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
                    <a href="{{ route('chat') }}" class="btn-primary mt-6 w-full text-xs">
                        Tham gia trò chuyện
                    </a>
                @endauth
            </div>
        </div>
    </section>
</div>
@endsection
