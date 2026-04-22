@extends('layouts.admin')
@section('title', 'Bảng Điều Khiển')
@section('page_title', 'Bảng Điều Khiển')

@section('content')
{{-- Thống kê tổng quan --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
    @php
        $cards = [
            ['label' => 'Tổng truyện', 'value' => $thongKe['tong_truyen'], 'color' => 'indigo', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['label' => 'Tổng chương', 'value' => $thongKe['tong_chuong'], 'color' => 'purple', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
            ['label' => 'Người dùng', 'value' => $thongKe['tong_nguoi_dung'], 'color' => 'blue', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['label' => 'Bình luận chờ duyệt', 'value' => $thongKe['binh_luan_cho_duyet'], 'color' => 'amber', 'icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
            ['label' => 'Tin nhắn hôm nay', 'value' => $thongKe['tin_nhan_hom_nay'], 'color' => 'green', 'icon' => 'M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z'],
        ];
    @endphp

    @foreach($cards as $card)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/30 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"></path></svg>
                </div>
                <div>
                    <p class="text-2xl font-bold">{{ number_format($card['value']) }}</p>
                    <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Truyện mới tạo --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold">Truyện mới tạo</h3>
            <a href="{{ route('admin.truyen.danh-sach') }}" class="text-xs text-indigo-600 hover:underline">Xem tất cả</a>
        </div>
        <div class="space-y-3">
            @foreach($truyenMoiTao as $t)
                <div class="flex items-center justify-between text-sm">
                    <span class="truncate">{{ $t->tieu_de }}</span>
                    <span class="text-xs text-gray-400 flex-shrink-0 ml-2">{{ $t->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Chương mới tạo --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
        <h3 class="font-semibold mb-4">Chương mới tạo</h3>
        <div class="space-y-3">
            @foreach($chuongMoiTao as $c)
                <div class="flex items-center justify-between text-sm">
                    <span class="truncate">{{ $c->tieu_de }} <span class="text-gray-400">- {{ $c->truyen?->tieu_de }}</span></span>
                    <span class="text-xs text-gray-400 flex-shrink-0 ml-2">{{ $c->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Quick actions --}}
<div class="mt-6 flex flex-wrap gap-3">
    <a href="{{ route('admin.truyen.tao-moi') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">+ Thêm truyện</a>
    <a href="{{ route('admin.the-loai.tao-moi') }}" class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">+ Thêm thể loại</a>
    <a href="{{ route('admin.binh-luan.danh-sach', ['trang_thai' => 'cho_duyet']) }}" class="px-4 py-2 bg-amber-600 text-white text-sm rounded-lg hover:bg-amber-700 transition">Duyệt bình luận ({{ $thongKe['binh_luan_cho_duyet'] }})</a>
</div>
@endsection
