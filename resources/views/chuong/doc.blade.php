@extends('layouts.app')

@section('title', $chuong->tieu_de . ' - ' . $truyen->tieu_de)
@section('meta_description', 'Đọc ' . $chuong->tieu_de . ' của truyện ' . $truyen->tieu_de)

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">
<style>
    .chapter-content {
        line-height: 2.2 !important;
        letter-spacing: 0.01em;
    }
    .chapter-content p {
        margin-bottom: 2rem !important;
        display: block;
    }
    /* Fix cho trường hợp văn bản bị dính cục do newline */
    .chapter-content {
        white-space: pre-line;
    }
</style>
@endpush

@section('content')
<div x-data="{ fontSize: localStorage.getItem('fontSize') || '18' }" class="min-h-screen">

    {{-- Top Navigation --}}
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-16 z-40">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between gap-4">
            <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="text-sm text-gray-500 hover:text-indigo-600 transition truncate">
                ← {{ $truyen->tieu_de }}
            </a>
            <div class="flex items-center gap-2 flex-shrink-0">
                @if($chuongTruoc)
                    <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="Chương trước">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                @endif

                {{-- Chapter dropdown --}}
                <select x-data onchange="window.location.href = '{{ route('chuong.doc', [$truyen->slug, '']) }}/' + this.value"
                        class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 border-0 rounded-lg focus:ring-2 focus:ring-indigo-500 max-w-[200px]">
                    @foreach($danhSachChuong as $ch)
                        <option value="{{ $ch->slug }}" {{ $ch->id === $chuong->id ? 'selected' : '' }}>Ch.{{ $ch->so_chuong }}: {{ Str::limit($ch->tieu_de, 30) }}</option>
                    @endforeach
                </select>

                @if($chuongSau)
                    <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition" title="Chương sau">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                @endif

                {{-- Font size controls --}}
                <div class="hidden sm:flex items-center gap-1 ml-2 border-l border-gray-200 dark:border-gray-700 pl-2">
                    <button @click="fontSize = Math.max(14, parseInt(fontSize) - 2); localStorage.setItem('fontSize', fontSize)" class="p-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-xs font-bold">A-</button>
                    <span class="text-xs text-gray-400 w-8 text-center" x-text="fontSize + 'px'"></span>
                    <button @click="fontSize = Math.min(28, parseInt(fontSize) + 2); localStorage.setItem('fontSize', fontSize)" class="p-1.5 rounded hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-bold">A+</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Chapter Content --}}
    <article class="max-w-4xl mx-auto px-4 sm:px-8 py-8">
        <h1 class="text-xl sm:text-2xl font-bold mb-2 text-center">{{ $chuong->tieu_de }}</h1>
        <p class="text-sm text-gray-400 text-center mb-8">{{ number_format($chuong->so_tu) }} từ · {{ number_format($chuong->tong_luot_xem) }} lượt xem</p>

        <div class="prose prose-lg dark:prose-invert max-w-none chapter-content"
             :style="'font-size: ' + fontSize + 'px;'"
             style="font-family: 'Merriweather', serif;">
            {!! $chuong->noi_dung !!}
        </div>
    </article>

    {{-- Bottom Navigation --}}
    <div class="max-w-4xl mx-auto px-4 py-6 flex items-center justify-between border-t border-gray-200 dark:border-gray-700">
        @if($chuongTruoc)
            <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="px-5 py-2.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition font-medium text-sm">
                ← Chương trước
            </a>
        @else
            <div></div>
        @endif

        <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="px-4 py-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">Mục lục</a>

        @if($chuongSau)
            <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium text-sm">
                Chương sau →
            </a>
        @else
            <div></div>
        @endif
    </div>

    {{-- Comments --}}
    <div class="max-w-4xl mx-auto px-4 py-8 border-t border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-bold mb-4">Bình Luận ({{ $binhLuans->count() }})</h2>

        @auth
            <form method="POST" action="{{ route('binh-luan.gui') }}" class="mb-6">
                @csrf
                <input type="hidden" name="truyen_id" value="{{ $truyen->id }}">
                <input type="hidden" name="chuong_id" value="{{ $chuong->id }}">
                <textarea name="noi_dung" rows="3" placeholder="Viết bình luận về chương này..."
                          class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 resize-none"
                          required maxlength="2000"></textarea>
                <button type="submit" class="mt-2 px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Gửi</button>
            </form>
        @else
            <p class="text-sm text-gray-500 mb-4"><a href="{{ route('dang-nhap') }}" class="text-indigo-600 hover:underline">Đăng nhập</a> để bình luận.</p>
        @endauth

        <div class="space-y-4">
            @forelse($binhLuans as $bl)
                <div class="flex gap-3">
                    <img src="{{ $bl->nguoiDung->urlAnhDaiDien() }}" alt="{{ $bl->nguoiDung->ten_hien_thi }}"
                         class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
                    <div>
                        <p class="text-sm"><span class="font-semibold">{{ $bl->nguoiDung->ten_hien_thi }}</span> <span class="text-xs text-gray-400">{{ $bl->created_at->diffForHumans() }}</span></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $bl->noi_dung }}</p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Chưa có bình luận nào cho chương này.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
