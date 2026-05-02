@extends('layouts.app')

@section('title', $truyen->meta_title ?? ($truyen->tieu_de . ' - ' . \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ')))
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
@php
    $firstChapter = $truyen->chuongDaXuatBan()->first();
    $chapterTotal = $chuongs->total();
    $rangeSize = 50;
@endphp

<div class="shell-container pb-12 space-y-8">
    {{-- Cinematic Hero Section --}}
    <section class="relative overflow-hidden rounded-[2.5rem] bg-slate-950 text-white shadow-2xl">
        {{-- Background Effects --}}
        <div class="absolute inset-0 opacity-40">
            <img src="{{ $truyen->urlAnhBia() }}" alt="Background" class="h-full w-full object-cover blur-2xl scale-110">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-slate-950/40"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
        </div>

        <div class="relative z-10 grid gap-8 px-6 py-10 lg:grid-cols-[auto_1fr] lg:items-end lg:px-12 lg:py-16">
            {{-- Cover Image --}}
            <div class="mx-auto w-full max-w-[200px] lg:max-w-[280px]">
                <div class="aspect-[2/3] overflow-hidden rounded-2xl shadow-2xl ring-1 ring-white/20">
                    <img src="{{ $truyen->urlAnhBia() }}" alt="{{ $truyen->tieu_de }}" class="h-full w-full object-cover">
                </div>
            </div>

            {{-- Story Info --}}
            <div class="space-y-6">
                <nav class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-white/60">
                    <a href="{{ route('trang-chu') }}" class="hover:text-white">Trang chủ</a>
                    <span>/</span>
                    <a href="{{ route('truyen.danh-sach') }}" class="hover:text-white">Truyện</a>
                    <span>/</span>
                    <span class="text-white">{{ $truyen->tieu_de }}</span>
                </nav>

                <div class="space-y-4">
                    <div class="flex flex-wrap gap-2">
                        @foreach($truyen->theLoai as $category)
                            <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="rounded-full bg-white/10 px-4 py-1 text-[10px] font-bold uppercase tracking-wider text-white backdrop-blur-md ring-1 ring-white/20 transition-all hover:bg-white/20">
                                {{ $category->ten }}
                            </a>
                        @endforeach
                        <span class="rounded-full bg-indigo-500/20 px-4 py-1 text-[10px] font-bold uppercase tracking-wider text-indigo-400 backdrop-blur-md ring-1 ring-indigo-500/30">
                            {{ $truyen->trang_thai === 'hoan_thanh' ? 'Hoàn thành' : 'Đang ra' }}
                        </span>
                    </div>
                    <h1 class="text-3xl font-extrabold leading-tight sm:text-4xl lg:text-5xl text-white">
                        {{ $truyen->tieu_de }}
                    </h1>
                    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-white/80">
                        <div class="flex items-center gap-2">
                            <span class="text-white/40">Tác giả:</span>
                            <span class="text-white">{{ $truyen->tac_gia ?: 'Ẩn danh' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-white/40">Số chương:</span>
                            <span class="text-white">{{ number_format($chapterTotal) }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-white/40">Lượt xem:</span>
                            <span class="text-white">{{ number_format($truyen->tong_luot_xem) }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap gap-4 pt-4">
                    @if($lichSuDoc && $lichSuDoc->chuong)
                        <a href="{{ route('chuong.doc', [$truyen->slug, $lichSuDoc->chuong->slug]) }}" class="inline-flex h-12 items-center justify-center rounded-xl bg-indigo-600 px-8 text-sm font-bold text-white transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                            Đọc tiếp: Ch. {{ $lichSuDoc->chuong->so_chuong }}
                        </a>
                    @elseif($firstChapter)
                        <a href="{{ route('chuong.doc', [$truyen->slug, $firstChapter->slug]) }}" class="inline-flex h-12 items-center justify-center rounded-xl bg-indigo-600 px-8 text-sm font-bold text-white transition-all hover:bg-indigo-500 hover:shadow-lg hover:shadow-indigo-500/30">
                            Bắt đầu đọc
                        </a>
                    @endif

                    @auth
                        <form method="POST" action="{{ route('yeu-thich.toggle', $truyen->id) }}" class="inline-block">
                            @csrf
                            <button type="submit" class="inline-flex h-12 items-center justify-center gap-2 rounded-xl bg-white/10 px-8 text-sm font-bold text-white transition-all hover:bg-white/20 backdrop-blur-md ring-1 ring-white/20">
                                @if($daYeuThich)
                                    <svg class="h-4 w-4 fill-pink-500 text-pink-500" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                    Đã yêu thích
                                @else
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                    Yêu thích
                                @endif
                            </button>
                        </form>
                    @endauth
                </div>
            </div>
        </div>
    </section>

    {{-- Content Layout --}}
    <div class="grid gap-8 lg:grid-cols-[1fr_340px]">
        {{-- Main Column --}}
        <div class="space-y-8">
            {{-- Synopsis --}}
            <section class="rounded-3xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-6 sm:p-8">
                <h2 class="text-xl font-bold mb-4" style="color: var(--ui-text);">Giới thiệu nội dung</h2>
                <div class="prose prose-slate dark:prose-invert max-w-none text-sm leading-relaxed" style="color: var(--ui-text-secondary);">
                    <p class="font-medium text-[color:var(--ui-text)] mb-4">{{ $truyen->mo_ta_ngan }}</p>
                    @if($truyen->mo_ta_day_du)
                        <div x-data="{ expanded: false }" class="relative">
                            <div class="whitespace-pre-line overflow-hidden transition-all duration-300" :style="expanded ? '' : 'max-height: 200px; mask-image: linear-gradient(to bottom, black 50%, transparent 100%)'">
                                {{ $truyen->mo_ta_day_du }}
                            </div>
                            <button type="button" @click="expanded = !expanded" class="mt-4 flex items-center gap-2 text-xs font-bold text-primary-600">
                                <span x-text="expanded ? 'Thu gọn' : 'Đọc toàn bộ giới thiệu'"></span>
                                <svg class="h-4 w-4 transition-transform" :class="expanded ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        </div>
                    @endif
                </div>
            </section>

            {{-- Chapter List --}}
            <section class="rounded-3xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-6 sm:p-8">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                    <h2 class="text-xl font-bold" style="color: var(--ui-text);">Danh sách chương</h2>
                    <span class="rounded-full bg-[color:var(--ui-surface-muted)] px-3 py-1 text-xs font-bold" style="color: var(--ui-muted);">{{ number_format($chapterTotal) }} chương</span>
                </div>

                @if($chapterTotal > 0)
                    <div class="flex flex-wrap gap-2 mb-6">
                        @for ($i = 1; $i <= $chapterTotal; $i += $rangeSize)
                            @php
                                $end = min($i + $rangeSize - 1, $chapterTotal);
                                $targetPage = floor(($i - 1) / 50) + 1;
                                $active = $chuongs->currentPage() === $targetPage;
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['page' => $targetPage]) }}"
                               class="rounded-lg px-3 py-1.5 text-xs font-bold transition-all {{ $active ? 'bg-primary-600 text-white' : 'bg-[color:var(--ui-surface-muted)] text-[color:var(--ui-muted)] hover:bg-primary-500/10 hover:text-primary-600' }}">
                                {{ $i }}–{{ $end }}
                            </a>
                        @endfor
                    </div>
                @endif

                <div class="grid gap-2 sm:grid-cols-2">
                    @forelse($chuongs as $chapter)
                        <a href="{{ route('chuong.doc', [$truyen->slug, $chapter->slug]) }}" class="group flex items-center gap-3 rounded-xl p-3 text-sm transition-all hover:bg-primary-600/5">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-[color:var(--ui-surface-muted)] text-[10px] font-bold transition-colors group-hover:bg-primary-600 group-hover:text-white" style="color: var(--ui-muted);">
                                {{ $chapter->so_chuong }}
                            </span>
                            <span class="min-w-0 flex-1 truncate font-medium group-hover:text-primary-600" style="color: var(--ui-text-secondary);">
                                {{ $chapter->tieu_de }}
                            </span>
                            <span class="shrink-0 text-[10px]" style="color: var(--ui-muted);">
                                {{ $chapter->created_at ? $chapter->created_at->format('d/m') : '' }}
                            </span>
                        </a>
                    @empty
                        <div class="col-span-full py-12 text-center">
                            <p class="text-sm font-medium" style="color: var(--ui-muted);">Truyện chưa có chương xuất bản.</p>
                        </div>
                    @endforelse
                </div>

                @if($chuongs->hasPages())
                    <div class="mt-8 border-t border-[color:var(--ui-border)] pt-8">
                        {{ $chuongs->links() }}
                    </div>
                @endif
            </section>

            {{-- Comments --}}
            <section class="rounded-3xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-6 sm:p-8">
                <h2 class="text-xl font-bold mb-6" style="color: var(--ui-text);">Cộng đồng thảo luận</h2>

                @auth
                    <form method="POST" action="{{ route('binh-luan.gui') }}" class="mb-8 space-y-4">
                        @csrf
                        <input type="hidden" name="truyen_id" value="{{ $truyen->id }}">
                        <div class="relative rounded-2xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface-muted)] p-4 focus-within:ring-2 focus-within:ring-primary-600/20 transition-all">
                            <textarea name="noi_dung" rows="3" placeholder="Chia sẻ suy nghĩ của bạn về bộ truyện này..." class="w-full resize-none border-0 bg-transparent p-0 text-sm outline-none placeholder:text-slate-400" required maxlength="2000">{{ old('noi_dung') }}</textarea>
                            <div class="mt-4 flex items-center justify-between border-t border-[color:var(--ui-border)] pt-3">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tối đa 2000 ký tự</span>
                                <button type="submit" class="inline-flex h-9 items-center justify-center rounded-lg bg-primary-600 px-6 text-xs font-bold text-white transition-all hover:bg-primary-500">
                                    Đăng bình luận
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="mb-8 rounded-2xl bg-primary-600/5 p-6 text-center">
                        <p class="text-sm font-medium" style="color: var(--ui-text-secondary);">
                            Vui lòng <a href="{{ route('dang-nhap') }}" class="font-bold text-primary-600 hover:underline">Đăng nhập</a> để tham gia thảo luận cùng cộng đồng.
                        </p>
                    </div>
                @endauth

                <div class="space-y-6">
                    @forelse($binhLuans as $comment)
                        <div class="flex gap-4">
                            <img src="{{ $comment->nguoiDung->urlAnhDaiDien() }}" alt="Avatar" class="h-10 w-10 shrink-0 rounded-full object-cover shadow-sm">
                            <div class="min-w-0 flex-1 space-y-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-bold" style="color: var(--ui-text);">{{ $comment->nguoiDung->ten_hien_thi }}</span>
                                    <span class="text-[10px]" style="color: var(--ui-muted);">{{ $comment->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="rounded-2xl bg-[color:var(--ui-surface-muted)] p-4">
                                    <p class="text-sm leading-relaxed" style="color: var(--ui-text-secondary);">{{ $comment->noi_dung }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center">
                            <p class="text-sm font-medium" style="color: var(--ui-muted);">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-8">
            {{-- Stats & Actions --}}
            <section class="rounded-3xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-6 shadow-sm">
                <h3 class="text-base font-bold mb-4" style="color: var(--ui-text);">Chỉ số truyện</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="rounded-2xl bg-pink-500/5 p-4 text-center ring-1 ring-pink-500/10">
                        <p class="text-xl font-extrabold text-pink-500">{{ number_format($truyen->tong_luot_yeu_thich) }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-pink-500/60 mt-1">Yêu thích</p>
                    </div>
                    <div class="rounded-2xl bg-indigo-500/5 p-4 text-center ring-1 ring-indigo-500/10">
                        <p class="text-xl font-extrabold text-indigo-500">{{ number_format($truyen->tong_luot_theo_doi) }}</p>
                        <p class="text-[10px] font-bold uppercase tracking-wider text-indigo-500/60 mt-1">Theo dõi</p>
                    </div>
                </div>

                @auth
                    <div class="mt-6 space-y-3">
                        <form method="POST" action="{{ route('theo-doi.toggle', $truyen->id) }}">
                            @csrf
                            <button type="submit" class="flex w-full h-11 items-center justify-center gap-2 rounded-xl text-sm font-bold transition-all {{ $daTheoDoi ? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' : 'bg-primary-600 text-white hover:bg-primary-500 shadow-lg shadow-primary-600/20' }}">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $daTheoDoi ? 'M5 13l4 4L19 7' : 'M12 4v16m8-8H4' }}"/></svg>
                                {{ $daTheoDoi ? 'Đang theo dõi' : 'Theo dõi truyện' }}
                            </button>
                        </form>
                    </div>
                @endauth
            </section>

            {{-- Related Stories --}}
            @if($truyenLienQuan->count() > 0)
                <section class="rounded-3xl border border-[color:var(--ui-border)] bg-[color:var(--ui-surface)] p-6 shadow-sm">
                    <h3 class="text-base font-bold mb-4" style="color: var(--ui-text);">Truyện liên quan</h3>
                    <div class="space-y-4">
                        @foreach($truyenLienQuan->take(5) as $story)
                            <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="group flex items-center gap-3">
                                <div class="h-16 w-12 shrink-0 overflow-hidden rounded-lg shadow-sm">
                                    <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
                                </div>
                                <div class="min-w-0 space-y-0.5">
                                    <h4 class="text-sm font-bold line-clamp-1 group-hover:text-primary-600" style="color: var(--ui-text);">{{ $story->tieu_de }}</h4>
                                    <p class="text-[11px]" style="color: var(--ui-muted);">{{ $story->tac_gia ?: 'Ẩn danh' }}</p>
                                    <div class="flex items-center gap-2 text-[10px] font-bold text-indigo-500/60 uppercase">
                                        {{ $story->theLoai->first()?->ten }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </div>
</div>
@endsection
