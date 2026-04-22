@extends('layouts.app')

@section('title', 'TruyệnChữ - Đọc Truyện Online Miễn Phí')
@section('meta_description', 'TruyệnChữ - Website đọc truyện chữ online miễn phí. Truyện tiên hiệp, kiếm hiệp, ngôn tình, đô thị cập nhật liên tục.')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Hero Banner --}}
    @php
        $bannerUrl = \App\Models\CauHinh::urlBanner();
        $bannerTieuDe = \App\Models\CauHinh::lay('banner_tieu_de', 'Đọc Truyện Online');
        $bannerMoTa = \App\Models\CauHinh::lay('banner_mo_ta', 'Hàng nghìn bộ truyện hay được cập nhật liên tục. Trải nghiệm đọc truyện mượt mà trên mọi thiết bị.');
    @endphp
    <section class="relative rounded-2xl overflow-hidden mb-8 p-8 sm:p-12 {{ $bannerUrl ? '' : 'bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500' }}">
        @if($bannerUrl)
            <img src="{{ $bannerUrl }}" alt="Banner" class="absolute inset-0 w-full h-full object-cover">
        @endif
        <div class="absolute inset-0 bg-black/30"></div>
        <div class="relative z-10">
            <h1 class="text-3xl sm:text-4xl font-bold text-white mb-3">{{ $bannerTieuDe }}</h1>
            <p class="text-lg text-white/80 mb-6 max-w-xl">{{ $bannerMoTa }}</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('truyen.danh-sach') }}" class="px-6 py-3 bg-white text-indigo-700 font-semibold rounded-lg hover:bg-gray-100 transition shadow-lg">Khám phá ngay</a>
                <a href="{{ route('tim-kiem') }}" class="px-6 py-3 bg-white/20 text-white font-semibold rounded-lg hover:bg-white/30 transition backdrop-blur">Tìm truyện</a>
            </div>
        </div>
    </section>

    {{-- Truyện mới cập nhật --}}
    <section class="mb-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <span class="w-1 h-6 bg-indigo-500 rounded-full"></span>
                Truyện Mới Cập Nhật
            </h2>
            <a href="{{ route('truyen.danh-sach') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Xem tất cả →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($truyenMoiCapNhat as $truyen)
                @include('components.story-card', ['truyen' => $truyen])
            @endforeach
        </div>
    </section>

    {{-- Truyện Hot + Top xem nhiều --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        {{-- Truyện Hot --}}
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold flex items-center gap-2">
                    <span class="w-1 h-6 bg-red-500 rounded-full"></span>
                    Truyện Hot
                </h2>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                @foreach($truyenHot->take(8) as $truyen)
                    @include('components.story-card', ['truyen' => $truyen])
                @endforeach
            </div>
        </div>

        {{-- Top xem nhiều --}}
        <div>
            <h2 class="text-xl font-bold flex items-center gap-2 mb-5">
                <span class="w-1 h-6 bg-amber-500 rounded-full"></span>
                Top Xem Nhiều
            </h2>
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
                @foreach($topXemNhieu as $index => $truyen)
                    <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="flex items-center gap-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                        <span class="flex-shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold
                            {{ $index < 3 ? 'bg-gradient-to-br from-amber-400 to-orange-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-500' }}">
                            {{ $index + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium truncate">{{ $truyen->tieu_de }}</p>
                            <p class="text-xs text-gray-500">{{ number_format($truyen->tong_luot_xem) }} lượt xem</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Truyện hoàn thành --}}
    @if($truyenHoanThanh->count() > 0)
    <section class="mb-10">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold flex items-center gap-2">
                <span class="w-1 h-6 bg-green-500 rounded-full"></span>
                Truyện Hoàn Thành
            </h2>
            <a href="{{ route('truyen.danh-sach', ['trang_thai' => 'hoan_thanh']) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Xem tất cả →</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($truyenHoanThanh as $truyen)
                @include('components.story-card', ['truyen' => $truyen])
            @endforeach
        </div>
    </section>
    @endif

    {{-- Thể loại nổi bật --}}
    <section class="mb-10">
        <h2 class="text-xl font-bold flex items-center gap-2 mb-5">
            <span class="w-1 h-6 bg-purple-500 rounded-full"></span>
            Thể Loại Nổi Bật
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
            @foreach($theLoaiNoiBat as $tl)
                <a href="{{ route('the-loai.danh-sach', $tl->slug) }}"
                   class="group bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-md transition text-center">
                    <p class="font-semibold text-sm group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $tl->ten }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $tl->truyen_count }} truyện</p>
                </a>
            @endforeach
        </div>
    </section>
</div>
@endsection
