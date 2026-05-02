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
            <div class="relative overflow-hidden rounded-[2rem] bg-white dark:bg-slate-900 p-8 shadow-sm border border-slate-200 dark:border-slate-800 group hover:border-indigo-500 transition-all duration-300">
                <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-{{ $stat['color'] }}-500/5 transition-transform group-hover:scale-150"></div>
                
                <div class="flex items-center justify-between relative z-10">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-600">
                        <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/></svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-500">{{ $stat['trend'] }}</span>
                </div>

                <div class="mt-6 relative z-10">
                    <p class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ number_format($stat['value']) }}</p>
                    <p class="text-xs font-bold uppercase tracking-widest text-slate-400 mt-1">{{ $stat['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        {{-- Recent Stories --}}
        <div class="rounded-[2.5rem] bg-white dark:bg-slate-900 p-8 shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Truyện mới cập nhật</h3>
                    <p class="text-xs font-medium text-slate-400">Danh sách các bộ truyện vừa được thêm</p>
                </div>
                <a href="{{ route('admin.truyen.danh-sach') }}" class="text-xs font-bold text-indigo-600 hover:underline uppercase tracking-widest">Xem tất cả</a>
            </div>

            <div class="space-y-4">
                @foreach($truyenMoiTao as $t)
                    <div class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 dark:bg-slate-800/50 group hover:bg-white dark:hover:bg-slate-800 transition-all border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-xl bg-slate-200 dark:bg-slate-700">
                            <img src="{{ $t->url_anh }}" alt="" class="h-full w-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="truncate text-sm font-bold text-slate-900 dark:text-white">{{ $t->tieu_de }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">Tác giả: {{ $t->tac_gia }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $t->created_at->diffForHumans() }}</p>
                            <a href="{{ route('admin.truyen.sua', $t->id) }}" class="text-[10px] font-bold text-indigo-600 uppercase hover:underline mt-1 block">Chỉnh sửa</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Quick Actions & Activity --}}
        <div class="space-y-8">
            <div class="rounded-[2.5rem] bg-indigo-600 p-8 shadow-xl shadow-indigo-600/20 text-white">
                <h3 class="text-lg font-bold">Thao tác nhanh</h3>
                <p class="text-xs text-indigo-100 mt-1">Các công cụ thường dùng nhất</p>
                
                <div class="grid grid-cols-2 gap-4 mt-8">
                    <a href="{{ route('admin.truyen.tao-moi') }}" class="flex flex-col items-center justify-center gap-3 p-6 rounded-3xl bg-white/10 hover:bg-white/20 transition-all group">
                        <div class="h-12 w-12 flex items-center justify-center rounded-2xl bg-white/20 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest">Thêm truyện</span>
                    </a>
                    <a href="{{ route('admin.crawler.index') }}" class="flex flex-col items-center justify-center gap-3 p-6 rounded-3xl bg-white/10 hover:bg-white/20 transition-all group">
                        <div class="h-12 w-12 flex items-center justify-center rounded-2xl bg-white/20 group-hover:scale-110 transition-transform">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-widest">Cào truyện</span>
                    </a>
                </div>
            </div>

            <div class="rounded-[2.5rem] bg-white dark:bg-slate-900 p-8 shadow-sm border border-slate-200 dark:border-slate-800">
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6">Chương mới nhất</h3>
                <div class="space-y-4">
                    @foreach($chuongMoiTao->take(5) as $c)
                        <div class="flex items-center gap-3 text-sm">
                            <div class="h-2 w-2 rounded-full bg-indigo-500"></div>
                            <p class="flex-1 truncate text-slate-600 dark:text-slate-400">
                                <span class="font-bold text-slate-900 dark:text-white">{{ $c->tieu_de }}</span>
                                <span class="mx-1">trong</span>
                                <span class="italic">{{ $c->truyen?->tieu_de }}</span>
                            </p>
                            <span class="text-[10px] font-bold text-slate-400 uppercase shrink-0">{{ $c->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
