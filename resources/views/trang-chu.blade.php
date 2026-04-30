@extends('layouts.app')

@section('title', \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ') . ' - Thư viện truyện online')
@section('meta_description', \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ') . ' - Đọc truyện online với giao diện thư viện hiện đại, danh mục rõ ràng và cập nhật liên tục.')

@section('content')
@php
    $heroStory = $truyenHot->first() ?? $truyenMoiCapNhat->first();
    $updatedRows = $truyenMoiCapNhat->take(9);
@endphp

<div class="shell-container page-stack">
    {{-- Welcome / Hero --}}
    @php
        $bannerTieuDe = \App\Models\CauHinh::lay('banner_tieu_de', 'Đọc Truyện Online');
        $bannerMoTa = \App\Models\CauHinh::lay('banner_mo_ta', 'Hàng nghìn bộ truyện hay được cập nhật liên tục.');
        $bannerAnh = \App\Models\CauHinh::lay('banner');
    @endphp

    <section class="hero-banner">
        <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-center">
            <div>
                <h1 class="text-2xl font-bold tracking-tight sm:text-3xl" style="color: var(--ui-text);">{{ $bannerTieuDe }}</h1>
                <p class="mt-2 text-sm leading-relaxed max-w-lg" style="color: var(--ui-muted);">{{ $bannerMoTa }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ route('truyen.danh-sach') }}" class="btn-primary text-sm">Khám phá ngay</a>
                    <a href="{{ route('tim-kiem') }}" class="btn-secondary text-sm">Tìm truyện</a>
                </div>
            </div>
            @if($bannerAnh)
                <div class="hidden lg:block w-[280px] h-[160px] overflow-hidden" style="border-radius: var(--ui-radius-lg);">
                    <img src="{{ asset('storage/' . $bannerAnh) }}" alt="Featured" class="w-full h-full object-cover">
                </div>
            @endif
        </div>
    </section>

    {{-- Trending --}}
    <section class="surface-panel p-3 sm:p-5">
        <div class="section-heading">
            <h2 class="section-title">Truyện đang được đọc nhiều</h2>
        </div>

        <div class="story-grid">
            @foreach($truyenHot->take(10) as $story)
                @include('components.story-card', ['truyen' => $story])
            @endforeach
        </div>
    </section>

    {{-- Recently Updated --}}
    <section class="surface-panel p-3 sm:p-5">
        <div class="section-heading">
            <h2 class="section-title">Mới cập nhật</h2>
            <a href="{{ route('truyen.danh-sach', ['sap_xep' => 'moi_cap_nhat']) }}" class="btn-quiet text-sm">Xem tất cả</a>
        </div>

        <div class="overflow-x-auto no-scrollbar -mx-5 px-5">
            <table class="w-full text-left text-sm">
                <thead>
                    <tr class="border-b" style="border-color: var(--ui-border);">
                        <th class="pb-2.5 text-xs font-semibold uppercase tracking-wider" style="color: var(--ui-muted);">Truyện</th>
                        <th class="pb-2.5 text-xs font-semibold uppercase tracking-wider hidden sm:table-cell" style="color: var(--ui-muted);">Thể loại</th>
                        <th class="pb-2.5 text-xs font-semibold uppercase tracking-wider" style="color: var(--ui-muted);">Chương mới</th>
                        <th class="pb-2.5 text-xs font-semibold uppercase tracking-wider text-right" style="color: var(--ui-muted);">Thời gian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($truyenMoiCapNhat->take(15) as $story)
                        <tr class="border-b hover:bg-[color:var(--ui-surface-muted)] transition-colors" style="border-color: var(--ui-border);">
                            <td class="py-2.5 pr-3">
                                <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="font-medium hover:underline line-clamp-1" style="color: var(--ui-text);">{{ $story->tieu_de }}</a>
                            </td>
                            <td class="py-2.5 pr-3 hidden sm:table-cell">
                                <span class="text-xs" style="color: var(--ui-muted);">{{ $story->theLoai->first()?->ten ?? '—' }}</span>
                            </td>
                            <td class="py-2.5 pr-3">
                                @if($story->chuongMoiNhat)
                                    <a href="{{ route('chuong.doc', [$story->slug, $story->chuongMoiNhat->slug]) }}" class="text-xs whitespace-nowrap" style="color: var(--ui-primary);">Chương {{ $story->chuongMoiNhat->so_chuong }}</a>
                                @else
                                    <span class="text-xs italic" style="color: var(--ui-muted);">Đang cập nhật</span>
                                @endif
                            </td>
                            <td class="py-2.5 text-right text-xs tabular-nums whitespace-nowrap" style="color: var(--ui-muted);">
                                {{ optional($story->chuongMoiNhat?->updated_at)->diffForHumans() ?? 'Vừa xong' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Editor Picks --}}
    <section class="surface-panel p-3 sm:p-5" x-data="railScroller()">
        <div class="section-heading">
            <h2 class="section-title">Biên tập viên đề cử</h2>
        </div>

        <div class="rail">
            <div x-ref="track"
                 class="rail__track no-scrollbar"
                 @mousedown="handleMouseDown"
                 @mouseleave="handleMouseLeave"
                 @mouseup="handleMouseUp"
                 @mousemove="handleMouseMove"
                 @click.capture="handleLinkClick">
                @foreach($editorPicks as $story)
                    <div class="w-[150px] shrink-0 sm:w-[170px]">
                        @include('components.story-card', ['truyen' => $story])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Rankings --}}
    <section class="rank-columns">
        {{-- Daily trending --}}
        <div class="rank-panel">
            <h2 class="section-title mb-4">Đọc nhiều hôm nay</h2>

            @if($trendingTop->isNotEmpty())
                @php $topStory = $trendingTop->first(); @endphp
                <a href="{{ route('truyen.chi-tiet', $topStory->slug) }}" class="rank-hero block mb-4">
                    <div class="rank-hero__backdrop" style="background-image: url('{{ $topStory->urlAnhBia() }}')"></div>
                    <div class="rank-hero__inner">
                        <img src="{{ $topStory->urlAnhBia() }}" alt="{{ $topStory->tieu_de }}" class="h-28 w-20 rounded-lg object-cover shrink-0">
                        <div class="min-w-0">
                            <span class="rank-badge text-[10px]">#1</span>
                            <h3 class="mt-2 text-lg font-bold leading-tight">{{ $topStory->tieu_de }}</h3>
                            <p class="mt-1 text-xs text-white/60">{{ $topStory->tac_gia ?: 'Đang cập nhật' }}</p>
                        </div>
                    </div>
                </a>
            @endif

            <div class="rank-list">
                @foreach($trendingTop->slice(1) as $story)
                    <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="rank-item group">
                        <span class="rank-badge text-[10px]">{{ $loop->iteration + 1 }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold line-clamp-1 group-hover:underline" style="color: var(--ui-text);">{{ $story->tieu_de }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--ui-muted);">{{ $story->tac_gia ?: 'Đang cập nhật' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Monthly --}}
        <div class="rank-panel">
            <h2 class="section-title mb-4">Nổi bật trong tháng</h2>

            @if($monthlyTop->isNotEmpty())
                @php $monthTop = $monthlyTop->first(); @endphp
                <a href="{{ route('truyen.chi-tiet', $monthTop->slug) }}" class="rank-hero block mb-4">
                    <div class="rank-hero__backdrop" style="background-image: url('{{ $monthTop->urlAnhBia() }}')"></div>
                    <div class="rank-hero__inner">
                        <img src="{{ $monthTop->urlAnhBia() }}" alt="{{ $monthTop->tieu_de }}" class="h-28 w-20 rounded-lg object-cover shrink-0">
                        <div class="min-w-0">
                            <span class="rank-badge text-[10px]">#1</span>
                            <h3 class="mt-2 text-lg font-bold leading-tight">{{ $monthTop->tieu_de }}</h3>
                            <p class="mt-1 text-xs text-white/60">{{ $monthTop->tac_gia ?: 'Đang cập nhật' }}</p>
                        </div>
                    </div>
                </a>
            @endif

            <div class="rank-list">
                @foreach($monthlyTop->slice(1) as $story)
                    <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="rank-item group">
                        <span class="rank-badge text-[10px]">{{ $loop->iteration + 1 }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold line-clamp-1 group-hover:underline" style="color: var(--ui-text);">{{ $story->tieu_de }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--ui-muted);">{{ $story->tac_gia ?: 'Đang cập nhật' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Community comments --}}
        <div class="rank-panel">
            <h2 class="section-title mb-4">Bình luận nổi bật</h2>

            <div class="comment-feed">
                @forelse($topBinhLuans as $comment)
                    <div class="comment-bubble">
                        <div class="flex items-start gap-2.5">
                            <img src="{{ $comment->nguoiDung->urlAnhDaiDien() }}" alt="{{ $comment->nguoiDung->ten_hien_thi }}" class="h-8 w-8 rounded-full object-cover shrink-0">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="text-sm font-semibold" style="color: var(--ui-text);">{{ $comment->nguoiDung->ten_hien_thi }}</span>
                                    <span class="text-xs" style="color: var(--ui-muted);">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 text-sm leading-relaxed" style="color: var(--ui-text-secondary);">{{ \Illuminate\Support\Str::limit($comment->noi_dung, 120) }}</p>
                                @if($comment->truyen)
                                    <a href="{{ route('truyen.chi-tiet', $comment->truyen->slug) }}" class="mt-1.5 inline-block text-xs" style="color: var(--ui-primary);">{{ $comment->truyen->tieu_de }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm py-4 text-center" style="color: var(--ui-muted);">Chưa có bình luận nổi bật.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- Daily Rising + Community --}}
    <div class="grid gap-6 xl:grid-cols-2">
        {{-- Daily Rising --}}
        <section class="surface-panel p-3 sm:p-5" x-data="railScroller()">
            <div class="section-heading">
                <h2 class="section-title">Đang lên nhanh</h2>
            </div>

            <div class="rail">
                <div x-ref="track"
                     class="rail__track no-scrollbar"
                     @mousedown="handleMouseDown"
                     @mouseleave="handleMouseLeave"
                     @mouseup="handleMouseUp"
                     @mousemove="handleMouseMove"
                     @click.capture="handleLinkClick">
                    @foreach($dailyTop as $story)
                        <div class="w-[140px] shrink-0 sm:w-[150px]">
                            @include('components.story-card', ['truyen' => $story])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Genres --}}
        <section class="surface-panel p-3 sm:p-5">
            <div class="section-heading">
                <h2 class="section-title">Thể loại</h2>
                <a href="{{ route('truyen.danh-sach') }}" class="btn-quiet text-sm">Xem tất cả</a>
            </div>

            <div class="genre-chip-grid">
                @foreach($theLoaiNoiBat as $category)
                    <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="genre-chip">{{ $category->ten }}</a>
                @endforeach
            </div>
        </section>
    </div>

    {{-- Completed stories --}}
    <section class="surface-panel p-3 sm:p-5">
        <div class="section-heading">
            <h2 class="section-title">Truyện hoàn thành</h2>
        </div>

        <div class="story-grid">
            @foreach($truyenHoanThanh as $story)
                @include('components.story-card', ['truyen' => $story])
            @endforeach
        </div>
    </section>

    {{-- Community chat sidebar moved to a collapsed section --}}
    @if($tinNhanCongDong->isNotEmpty())
        <section class="surface-panel p-3 sm:p-5">
            <div class="section-heading">
                <h2 class="section-title">Đang trò chuyện</h2>
                @auth
                    <a href="{{ route('chat') }}" class="btn-quiet text-sm">Mở cộng đồng</a>
                @endauth
            </div>

            <div class="community-feed">
                @foreach($tinNhanCongDong as $message)
                    <div class="comment-bubble">
                        <div class="flex items-start gap-2.5">
                            @if($message->nguoiDung)
                                <img src="{{ $message->nguoiDung->urlAnhDaiDien() }}" alt="{{ $message->nguoiDung->ten_hien_thi }}" class="h-8 w-8 rounded-full object-cover shrink-0">
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold" style="background: var(--ui-primary-soft); color: var(--ui-primary);">?</div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold" style="color: var(--ui-text);">{{ $message->nguoiDung?->ten_hien_thi ?? 'Thành viên' }}</span>
                                    <span class="text-xs" style="color: var(--ui-muted);">{{ $message->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="mt-1 text-sm leading-relaxed" style="color: var(--ui-text-secondary);">{{ \Illuminate\Support\Str::limit($message->noi_dung, 120) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
