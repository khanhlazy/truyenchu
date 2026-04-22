@extends('layouts.app')

@section('title', $truyen->meta_title ?? $truyen->tieu_de . ' - TruyệnChữ')
@section('meta_description', $truyen->meta_description ?? $truyen->mo_ta_ngan)
@section('og_image', $truyen->urlAnhBia())

@section('meta_seo')
<meta property="og:type" content="book">
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Book",
    "name": "{{ $truyen->tieu_de }}",
    "author": { "@type": "Person", "name": "{{ $truyen->tac_gia }}" },
    "description": "{{ $truyen->mo_ta_ngan }}",
    "image": "{{ $truyen->urlAnhBia() }}",
    "url": "{{ route('truyen.chi-tiet', $truyen->slug) }}"
}
</script>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('trang-chu') }}" class="hover:text-indigo-600 transition">Trang chủ</a>
        <span>›</span>
        <a href="{{ route('truyen.danh-sach') }}" class="hover:text-indigo-600 transition">Truyện</a>
        <span>›</span>
        <span class="text-gray-800 dark:text-gray-200">{{ $truyen->tieu_de }}</span>
    </nav>

    {{-- Story Info --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row gap-6">
                {{-- Ảnh bìa --}}
                <div class="flex-shrink-0 mx-auto sm:mx-0">
                    <img src="{{ $truyen->urlAnhBia() }}" alt="{{ $truyen->tieu_de }}"
                         class="w-48 h-64 object-cover rounded-xl shadow-lg">
                </div>

                {{-- Thông tin --}}
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold mb-2">{{ $truyen->tieu_de }}</h1>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Tác giả: <span class="font-medium text-gray-700 dark:text-gray-300">{{ $truyen->tac_gia }}</span></p>

                    {{-- Thể loại --}}
                    <div class="flex flex-wrap gap-2 mb-4">
                        @foreach($truyen->theLoai as $tl)
                            <a href="{{ route('the-loai.danh-sach', $tl->slug) }}"
                               class="px-3 py-1 text-xs font-medium bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 rounded-full hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition">
                                {{ $tl->ten }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Stats --}}
                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            {{ number_format($truyen->tong_luot_xem) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            {{ number_format($truyen->tong_luot_yeu_thich) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path></svg>
                            {{ number_format($truyen->tong_luot_theo_doi) }}
                        </span>
                        <span class="px-2 py-0.5 text-xs font-semibold rounded-md
                            {{ $truyen->trang_thai === 'hoan_thanh' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : ($truyen->trang_thai === 'tam_ngung' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400') }}">
                            {{ $truyen->tenTrangThai() }}
                        </span>
                    </div>

                    {{-- Mô tả ngắn --}}
                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed mb-4">{{ $truyen->mo_ta_ngan }}</p>

                    {{-- Action buttons --}}
                    <div class="flex flex-wrap gap-3">
                        @php
                            $chuongDau = $truyen->chuongDaXuatBan()->first();
                        @endphp
                        @if($chuongDau)
                            @if($lichSuDoc && $lichSuDoc->chuong)
                                <a href="{{ route('chuong.doc', [$truyen->slug, $lichSuDoc->chuong->slug]) }}" class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition shadow">
                                    Đọc tiếp Ch.{{ $lichSuDoc->chuong->so_chuong }}
                                </a>
                            @endif
                            <a href="{{ route('chuong.doc', [$truyen->slug, $chuongDau->slug]) }}" class="px-6 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                Đọc từ đầu
                            </a>
                        @endif

                        @auth
                            <form method="POST" action="{{ route('yeu-thich.toggle', $truyen->id) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2.5 border-2 rounded-lg font-medium text-sm transition
                                    {{ $daYeuThich ? 'border-red-500 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-red-400 hover:text-red-500' }}">
                                    {{ $daYeuThich ? '❤️ Đã thích' : '🤍 Yêu thích' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('theo-doi.toggle', $truyen->id) }}">
                                @csrf
                                <button type="submit" class="px-4 py-2.5 border-2 rounded-lg font-medium text-sm transition
                                    {{ $daTheoDoi ? 'border-indigo-500 text-indigo-500 hover:bg-indigo-50 dark:hover:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600 hover:border-indigo-400 hover:text-indigo-500' }}">
                                    {{ $daTheoDoi ? '🔔 Đang theo dõi' : '🔕 Theo dõi' }}
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>
            </div>

            {{-- Mô tả đầy đủ --}}
            @if($truyen->mo_ta_day_du)
                <div x-data="{ expanded: false }" class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="font-semibold mb-2">Giới thiệu</h3>
                    <div :class="expanded ? '' : 'line-clamp-4'" class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line">{{ $truyen->mo_ta_day_du }}</div>
                    <button @click="expanded = !expanded" class="text-sm text-indigo-600 dark:text-indigo-400 mt-2 hover:underline" x-text="expanded ? 'Thu gọn ↑' : 'Xem thêm ↓'"></button>
                </div>
            @endif
        </div>
    </div>

    {{-- Danh sách chương --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Danh Sách Chương ({{ $chuongs->total() }} chương)</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1">
            @forelse($chuongs as $ch)
                <a href="{{ route('chuong.doc', [$truyen->slug, $ch->slug]) }}"
                   class="flex items-center justify-between py-2.5 px-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition text-sm group">
                    <span class="truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ $ch->tieu_de }}</span>
                    <span class="flex-shrink-0 text-xs text-gray-400 ml-2">{{ $ch->published_at?->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-gray-500 col-span-2 text-center py-8">Chưa có chương nào.</p>
            @endforelse
        </div>
        @if($chuongs->hasPages())
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                {{ $chuongs->links() }}
            </div>
        @endif
    </div>

    {{-- Bình luận --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h2 class="text-lg font-bold mb-4">Bình Luận</h2>

        @auth
            <form method="POST" action="{{ route('binh-luan.gui') }}" class="mb-6">
                @csrf
                <input type="hidden" name="truyen_id" value="{{ $truyen->id }}">
                <textarea name="noi_dung" rows="3" placeholder="Viết bình luận của bạn..."
                          class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
                          required maxlength="2000">{{ old('noi_dung') }}</textarea>
                @error('noi_dung')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                <button type="submit" class="mt-2 px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Gửi bình luận</button>
            </form>
        @else
            <p class="text-sm text-gray-500 mb-4"><a href="{{ route('dang-nhap') }}" class="text-indigo-600 hover:underline">Đăng nhập</a> để bình luận.</p>
        @endauth

        <div class="space-y-4">
            @forelse($binhLuans as $bl)
                <div class="flex gap-3">
                    <img src="{{ $bl->nguoiDung->urlAnhDaiDien() }}" alt="{{ $bl->nguoiDung->ten_hien_thi }}"
                         class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
                    <div class="flex-1">
                        <p class="text-sm"><span class="font-semibold">{{ $bl->nguoiDung->ten_hien_thi }}</span> <span class="text-xs text-gray-400">{{ $bl->created_at->diffForHumans() }}</span></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $bl->noi_dung }}</p>

                        @if($bl->binhLuanCon->count() > 0)
                            <div class="mt-3 ml-4 space-y-3 border-l-2 border-gray-200 dark:border-gray-700 pl-4">
                                @foreach($bl->binhLuanCon as $con)
                                    <div class="flex gap-2">
                                        <img src="{{ $con->nguoiDung->urlAnhDaiDien() }}" alt="{{ $con->nguoiDung->ten_hien_thi }}"
                                             class="w-7 h-7 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
                                        <div>
                                            <p class="text-sm"><span class="font-semibold">{{ $con->nguoiDung->ten_hien_thi }}</span> <span class="text-xs text-gray-400">{{ $con->created_at->diffForHumans() }}</span></p>
                                            <p class="text-sm text-gray-600 dark:text-gray-300 mt-0.5">{{ $con->noi_dung }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Chưa có bình luận nào.</p>
            @endforelse
        </div>
    </div>

    {{-- Truyện liên quan --}}
    @if($truyenLienQuan->count() > 0)
    <section>
        <h2 class="text-lg font-bold mb-4">Truyện Liên Quan</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($truyenLienQuan as $t)
                @include('components.story-card', ['truyen' => $t])
            @endforeach
        </div>
    </section>
    @endif
</div>
@endsection
