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

<div class="shell-container space-y-12 pb-12">
    {{-- Premium Hero Section --}}
    <section class="relative overflow-hidden rounded-[2rem] bg-slate-900 shadow-2xl">
        <div class="absolute inset-0 opacity-40">
            @if($bannerAnh)
                <img src="{{ asset('storage/' . $bannerAnh) }}" alt="Hero Background" class="h-full w-full object-cover blur-sm scale-105">
            @else
                <div class="h-full w-full bg-gradient-to-br from-indigo-900 via-slate-900 to-purple-900"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent"></div>
        </div>

        <div class="relative z-10 grid gap-8 px-8 py-12 lg:grid-cols-2 lg:items-center lg:px-16 lg:py-20">
            <div class="max-w-xl space-y-6">
                <div class="inline-flex items-center gap-2 rounded-full bg-indigo-500/10 px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-indigo-400 ring-1 ring-indigo-500/20 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                    </span>
                    Chào mừng bạn đến với {{ \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ') }}
                </div>
                <h1 class="text-4xl font-extrabold leading-[1.1] text-white sm:text-5xl lg:text-6xl">
                    {{ $bannerTieuDe }}
                </h1>
                <p class="text-lg leading-relaxed text-slate-300">
                    {{ $bannerMoTa }}
                </p>
                <div class="flex flex-wrap gap-4 pt-4">
                    <a href="{{ route('truyen.danh-sach') }}" class="inline-flex h-12 items-center justify-center rounded-xl bg-indigo-600 px-8 text-sm font-bold text-white transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                        Bắt đầu đọc ngay
                    </a>
                    <a href="{{ route('tim-kiem') }}" class="inline-flex h-12 items-center justify-center rounded-xl bg-white/10 px-8 text-sm font-bold text-white transition-all hover:bg-white/20 backdrop-blur-md ring-1 ring-white/20">
                        Tìm kiếm truyện
                    </a>
                </div>
            </div>

            <div class="hidden lg:flex justify-end">
                <div class="relative grid grid-cols-2 gap-4 w-full max-w-md">
                    @foreach($truyenHot->take(4) as $story)
                        <div class="group relative aspect-[2/3] overflow-hidden rounded-2xl shadow-premium transition-all duration-500 hover:-translate-y-2 {{ $loop->index % 2 != 0 ? 'mt-8' : '' }}">
                            <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                            <div class="absolute bottom-4 left-4 right-4">
                                <h3 class="text-xs font-bold text-white line-clamp-1">{{ $story->tieu_de }}</h3>
                            </div>
                        </div>
                    @endforeach
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
            {{-- Daily Ranking --}}
            <section class="space-y-6">
                <h3 class="text-xl font-bold" style="color: var(--ui-text);">Bảng xếp hạng</h3>
                
                <div class="rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] overflow-hidden">
                    <div class="flex border-b border-[color:var(--ui-border)]">
                        <button class="flex-1 py-3 text-xs font-bold uppercase tracking-wider text-primary-600 border-b-2 border-primary-600">Ngày</button>
                        <button class="flex-1 py-3 text-xs font-bold uppercase tracking-wider text-[color:var(--ui-muted)] hover:text-[color:var(--ui-text)]">Tháng</button>
                    </div>

                    <div class="p-4 space-y-4">
                        @foreach($trendingTop->take(5) as $story)
                            <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="group flex items-center gap-4">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-md text-xs font-bold {{ $loop->first ? 'bg-amber-500 text-white' : 'bg-[color:var(--ui-surface-muted)] text-[color:var(--ui-muted)]' }}">
                                    {{ $loop->iteration }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-bold line-clamp-1 group-hover:text-primary-600" style="color: var(--ui-text);">{{ $story->tieu_de }}</h4>
                                    <p class="text-[11px]" style="color: var(--ui-muted);">{{ $story->luot_xem_ngay ?? 0 }} lượt xem</p>
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

    {{-- Editor Picks --}}
    <section class="space-y-6">
        <div class="rounded-[2rem] bg-indigo-900 px-8 py-12 lg:px-16">
            <div class="mb-8 flex items-end justify-between">
                <div class="space-y-1">
                    <h2 class="text-2xl font-bold tracking-tight text-white sm:text-3xl">Biên tập viên đề cử</h2>
                    <p class="text-sm text-indigo-300">Những tác phẩm chất lượng không thể bỏ lỡ.</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 lg:gap-6">
                @foreach($editorPicks->take(6) as $story)
                    <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="group block">
                        <div class="relative aspect-[2/3] overflow-hidden rounded-2xl shadow-xl transition-all duration-500 group-hover:-translate-y-2">
                            <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-60"></div>
                        </div>
                        <h3 class="mt-3 text-sm font-bold text-white line-clamp-1 group-hover:text-indigo-300">{{ $story->tieu_de }}</h3>
                        <p class="text-[11px] text-indigo-300">{{ $story->tac_gia ?: 'Ẩn danh' }}</p>
                    </a>
                @endforeach
            </div>
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

    {{-- Community Highlights --}}
    <section class="grid gap-8 lg:grid-cols-2">
        <div class="space-y-6">
            <h3 class="text-xl font-bold" style="color: var(--ui-text);">Bình luận nổi bật</h3>
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
                                    <span class="text-[10px]" style="color: var(--ui-muted);">{{ $message->created_at->format('H:i') }}</span>
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
