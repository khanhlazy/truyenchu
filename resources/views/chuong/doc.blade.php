@extends('layouts.app')

@section('title', $chuong->tieu_de . ' - ' . $truyen->tieu_de)
@section('meta_description', 'Đọc ' . $chuong->tieu_de . ' của truyện ' . $truyen->tieu_de)

@section('content')
<div x-data="readerPreferences({ fontSize: 18, lineHeight: 1.8, widthPreset: 'balanced' })" class="shell-container page-stack">
    <div class="reader-progress">
        <div class="reader-progress__bar" :style="{ width: scrollProgress + '%' }"></div>
    </div>

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-1.5 text-xs" style="color: var(--ui-muted);">
        <a href="{{ route('trang-chu') }}" class="hover:underline">Trang chủ</a>
        <span>/</span>
        <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="hover:underline">{{ $truyen->tieu_de }}</a>
        <span>/</span>
        <span class="truncate" style="color: var(--ui-text-secondary);">{{ $chuong->tieu_de }}</span>
    </nav>

    {{-- Reader toolbar --}}
    <div class="reader-toolbar">
        <div class="flex flex-wrap items-center justify-between gap-3">
            {{-- Chapter navigation --}}
            <div class="flex items-center gap-1">
                @if($chuongTruoc)
                    <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="icon-button" title="Chương trước">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                @endif
                <select onchange="window.location.href = '{{ route('chuong.doc', [$truyen->slug, '']) }}/' + this.value" class="field-shell !py-1.5 text-xs sm:min-w-[200px]">
                    @foreach($danhSachChuong as $chapter)
                        <option value="{{ $chapter->slug }}" @selected($chapter->id === $chuong->id)>
                            Ch.{{ $chapter->so_chuong }} - {{ \Illuminate\Support\Str::limit($chapter->tieu_de, 30) }}
                        </option>
                    @endforeach
                </select>
                @if($chuongSau)
                    <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="icon-button" title="Chương sau">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @endif
            </div>

            {{-- Reading controls --}}
            <div class="flex items-center gap-1">
                {{-- Radio player --}}
                <div class="flex items-center overflow-hidden border" style="border-color: var(--ui-border); border-radius: var(--ui-radius);" x-data="radioPlayer({ nextUrl: '{{ $chuongSau ? route('chuong.doc', [$truyen->slug, $chuongSau->slug]) : '' }}' })">
                    <button type="button" @click="toggle()" class="h-8 px-3 text-xs font-semibold transition-colors" :class="isPlaying ? 'bg-indigo-600 text-white' : 'hover:bg-[color:var(--ui-surface-muted)]'" :style="!isPlaying ? 'color: var(--ui-primary)' : ''">
                        <span x-text="isPlaying ? '⏸ Dừng' : '▶ Radio'"></span>
                    </button>
                    <select @change="setTimer(parseInt($event.target.value))" class="h-8 bg-transparent border-l px-2 text-xs font-medium focus:ring-0 appearance-none" style="border-color: var(--ui-border); color: var(--ui-muted);">
                        <option value="0">Tự động</option>
                        <option value="30">30p</option>
                        <option value="60">1h</option>
                    </select>
                </div>

                {{-- Font size --}}
                <div class="flex items-center overflow-hidden border" style="border-color: var(--ui-border); border-radius: var(--ui-radius);">
                    <button @click="adjustFontSize(-1)" class="h-8 w-8 text-xs font-bold hover:bg-[color:var(--ui-surface-muted)]">A-</button>
                    <button @click="adjustFontSize(1)" class="h-8 w-8 text-xs font-bold border-l hover:bg-[color:var(--ui-surface-muted)]" style="border-color: var(--ui-border);">A+</button>
                </div>

                {{-- Line height --}}
                <div class="hidden sm:flex items-center overflow-hidden border" style="border-color: var(--ui-border); border-radius: var(--ui-radius);">
                    <button @click="adjustLineHeight(-0.1)" class="h-8 w-8 text-xs font-bold hover:bg-[color:var(--ui-surface-muted)]" title="Thu hẹp dòng">≡</button>
                    <button @click="adjustLineHeight(0.1)" class="h-8 w-8 text-xs font-bold border-l hover:bg-[color:var(--ui-surface-muted)]" style="border-color: var(--ui-border);" title="Giãn dòng">☰</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Chapter content --}}
    <article class="mx-auto w-full" :style="{ maxWidth: articleWidth }">
        <header class="mb-8">
            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl" style="color: var(--ui-text);">{{ $chuong->tieu_de }}</h1>
            <div class="mt-2 flex items-center gap-3 text-xs" style="color: var(--ui-muted);">
                <span>Chương {{ $chuong->so_chuong }}</span>
                <span>·</span>
                <span>{{ number_format($chuong->so_tu) }} từ</span>
            </div>
        </header>

        <div class="reading-copy" :style="{ fontSize: fontSize + 'px', lineHeight: String(lineHeight) }">
            {!! $chuong->noi_dung !!}
        </div>
    </article>

    {{-- Chapter navigation footer --}}
    <div class="flex items-center justify-between gap-3 py-4 border-t" style="border-color: var(--ui-border);">
        <div>
            @if($chuongTruoc)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongTruoc->slug]) }}" class="btn-secondary text-sm">← Chương trước</a>
            @endif
        </div>
        <a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="btn-quiet text-sm">Mục lục</a>
        <div>
            @if($chuongSau)
                <a href="{{ route('chuong.doc', [$truyen->slug, $chuongSau->slug]) }}" class="btn-primary text-sm">Chương sau →</a>
            @endif
        </div>
    </div>

    {{-- Comments --}}
    <section class="surface-panel p-5">
        <h2 class="section-title mb-4">Bình luận chương</h2>

        @auth
            <form method="POST" action="{{ route('binh-luan.gui') }}" class="mb-5">
                @csrf
                <input type="hidden" name="truyen_id" value="{{ $truyen->id }}">
                <input type="hidden" name="chuong_id" value="{{ $chuong->id }}">
                <textarea name="noi_dung" rows="3" placeholder="Viết bình luận..."
                          class="field-shell textarea-shell" required maxlength="2000"></textarea>
                <div class="mt-3 flex items-center gap-3">
                    <button type="submit" class="btn-primary text-sm">Gửi bình luận</button>
                    <span class="text-xs" style="color: var(--ui-muted);">Tối đa 2000 ký tự</span>
                </div>
            </form>
        @else
            <div class="surface-panel-muted p-4 text-sm mb-5" style="color: var(--ui-muted);">
                <a href="{{ route('dang-nhap') }}" class="font-semibold" style="color: var(--ui-primary);">Đăng nhập</a> để bình luận.
            </div>
        @endauth

        <div class="space-y-3">
            @forelse($binhLuans as $comment)
                <div class="comment-bubble">
                    <div class="flex items-start gap-3">
                        <img src="{{ $comment->nguoiDung->urlAnhDaiDien() }}" alt="{{ $comment->nguoiDung->ten_hien_thi }}" class="h-8 w-8 rounded-full object-cover shrink-0">
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-semibold" style="color: var(--ui-text);">{{ $comment->nguoiDung->ten_hien_thi }}</span>
                                <span class="text-xs" style="color: var(--ui-muted);">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-sm leading-relaxed" style="color: var(--ui-text-secondary);">{{ $comment->noi_dung }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-sm py-6 text-center" style="color: var(--ui-muted);">Chưa có bình luận nào.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
