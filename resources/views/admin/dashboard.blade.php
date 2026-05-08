@extends('layouts.admin')
@section('title', 'Bảng Điều Khiển')
@section('page_title', 'Tổng quan hệ thống')

@section('content')
<div class="space-y-8">
    {{-- Statistics Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                [
                    'label' => 'Tổng truyện', 
                    'value' => $thongKe['tong_truyen'], 
                    'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
                    'color' => 'indigo',
                    'trend' => '+12%'
                ],
                [
                    'label' => 'Tổng chương', 
                    'value' => $thongKe['tong_chuong'], 
                    'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'color' => 'emerald',
                    'trend' => '+5.4%'
                ],
                [
                    'label' => 'Người dùng', 
                    'value' => $thongKe['tong_nguoi_dung'], 
                    'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
                    'color' => 'blue',
                    'trend' => '+2.1%'
                ],
                [
                    'label' => 'Bình luận mới', 
                    'value' => $thongKe['binh_luan_cho_duyet'], 
                    'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
                    'color' => 'amber',
                    'trend' => 'Cần duyệt'
                ],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="surface-panel group relative overflow-hidden p-6 transition-all duration-300 hover:-translate-y-0.5">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full opacity-60 transition-transform group-hover:scale-150" style="background: var(--ui-primary-soft);"></div>
                
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-[color:var(--ui-surface-variant)] text-[color:var(--ui-primary)]">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                    </div>
                    <span class="tag-pill-muted text-[10px]">{{ $stat['trend'] }}</span>
                </div>

                <div class="mt-6 relative z-10">
                    <p class="text-[32px] font-bold leading-[1.2]" style="color: var(--ui-text);">{{ number_format($stat['value']) }}</p>
                    <p class="mt-1 text-xs font-semibold uppercase tracking-[0.02em]" style="color: var(--ui-muted);">{{ $stat['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Stories --}}
        <div class="surface-panel p-6">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="section-title">Truyện mới cập nhật</h3>
                    <p class="text-xs font-medium" style="color: var(--ui-muted);">Danh sách các bộ truyện vừa được thêm</p>
                </div>
                <a href="{{ route('admin.truyen.danh-sach') }}" class="btn-quiet text-xs">Xem tất cả</a>
            </div>

            <div class="space-y-4">
                @foreach($truyenMoiTao as $t)
                    <div class="surface-panel-strong group flex items-center gap-4 p-4 transition-all">
                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-[color:var(--ui-surface-elevated)]">
                            <img src="{{ $t->url_anh }}" alt="" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="truncate text-sm font-semibold" style="color: var(--ui-text);">{{ $t->tieu_de }}</h4>
                            <p class="mt-0.5 text-xs" style="color: var(--ui-muted);">Tác giả: {{ $t->tac_gia }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-semibold uppercase" style="color: var(--ui-muted);">{{ $t->created_at->diffForHumans() }}</p>
                            <a href="{{ route('admin.truyen.sua', $t->id) }}" class="mt-1 block text-[10px] font-semibold uppercase" style="color: var(--ui-primary);">Chỉnh sửa</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Actions & Activity --}}
        <div class="space-y-8">
            <div class="rounded-xl p-6 text-white shadow-card" style="background: var(--ui-gradient-highlight);">
                <h3 class="text-lg font-bold">Thao tác nhanh</h3>
                <p class="mt-1 text-xs text-white/80">Các công cụ thường dùng nhất</p>
                
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <a href="{{ route('admin.truyen.tao-moi') }}" class="group flex flex-col items-center justify-center gap-3 rounded-lg bg-white/10 p-6 transition-all hover:bg-white/20">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-white/20 transition-transform group-hover:scale-105">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-[0.02em]">Thêm truyện</span>
                    </a>
                    <a href="{{ route('admin.crawler.index') }}" class="group flex flex-col items-center justify-center gap-3 rounded-lg bg-white/10 p-6 transition-all hover:bg-white/20">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-white/20 transition-transform group-hover:scale-105">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-[0.02em]">Cào truyện</span>
                    </a>
                </div>
            </div>

            <div class="surface-panel p-6">
                <h3 class="section-title mb-6">Chương mới nhất</h3>
                <div class="space-y-4">
                    @foreach($chuongMoiTao->take(5) as $c)
                        <div class="flex items-center gap-3 text-sm">
                            <div class="h-2 w-2 rounded-full" style="background: var(--ui-highlight);"></div>
                            <p class="flex-1 truncate" style="color: var(--ui-text-secondary);">
                                <span class="font-semibold" style="color: var(--ui-text);">{{ $c->tieu_de }}</span>
                                <span class="mx-1">trong</span>
                                <span class="italic">{{ $c->truyen?->tieu_de }}</span>
                            </p>
                            <span class="shrink-0 text-[10px] font-semibold uppercase" style="color: var(--ui-muted);">{{ $c->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
