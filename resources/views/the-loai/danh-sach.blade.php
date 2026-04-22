@extends('layouts.app')

@section('title', $theLoai->ten . ' - TruyệnChữ')
@section('meta_description', $theLoai->mo_ta ?? 'Đọc truyện thể loại ' . $theLoai->ten . ' hay nhất tại TruyệnChữ.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
        <a href="{{ route('trang-chu') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span>›</span>
        <span class="text-gray-800 dark:text-gray-200">{{ $theLoai->ten }}</span>
    </nav>

    <h1 class="text-2xl font-bold mb-2">Thể loại: {{ $theLoai->ten }}</h1>
    @if($theLoai->mo_ta)
        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">{{ $theLoai->mo_ta }}</p>
    @endif

    {{-- Filters --}}
    <form method="GET" class="flex flex-wrap gap-3 mb-6">
        <select name="trang_thai" class="px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <option value="">Tất cả trạng thái</option>
            <option value="dang_ra" {{ request('trang_thai') == 'dang_ra' ? 'selected' : '' }}>Đang ra</option>
            <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
        </select>
        <select name="sap_xep" class="px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
            <option value="moi_cap_nhat" {{ request('sap_xep') == 'moi_cap_nhat' ? 'selected' : '' }}>Mới cập nhật</option>
            <option value="xem_nhieu" {{ request('sap_xep') == 'xem_nhieu' ? 'selected' : '' }}>Xem nhiều</option>
            <option value="ten_az" {{ request('sap_xep') == 'ten_az' ? 'selected' : '' }}>Tên A-Z</option>
        </select>
        <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Lọc</button>
    </form>

    @if($truyens->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($truyens as $truyen)
                @include('components.story-card', ['truyen' => $truyen])
            @endforeach
        </div>
        <div class="mt-6">{{ $truyens->links() }}</div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
            <p class="text-gray-500 text-lg">Chưa có truyện nào trong thể loại này.</p>
        </div>
    @endif
</div>
@endsection
