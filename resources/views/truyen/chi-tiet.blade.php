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

<div class="shell-container page-stack">
    {{-- Hero --}}
    <section class="detail-hero">
        <div class="detail-hero__backdrop" style="background-image: url('{{ $truyen->urlAnhBia() }}')"></div>
        <div class="detail-hero__inner">
            <div class="min-w-0">
                <h1 class="text-3xl font-bold leading-tight tracking-tight sm:text-4xl">{{ $truyen->tieu_de }}</h1>

                <div class="detail-stat-row">
                    <span>{{ $truyen->tac_gia ?: 'Đang cập nhật' }}</span>
                    <span>·</span>
                    <span>{{ number_format($truyen->tong_luot_theo_doi) }} theo dõi</span>
                    <span>·</span>
                    <span>{{ number_format($chapterTotal) }} chương</span>
                </div>

                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach($truyen->theLoai as $category)
                        <a href="{{ route('the-loai.danh-sach', $category->slug) }}" class="tag-pill-muted" style="background: rgba(255,255,255,0.08); border-color: rgba(255,255,255,0.15); color: rgba(255,255,255,0.8);">{{ $category->ten }}</a>
                    @endforeach
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if($lichSuDoc && $lichSuDoc->chuong)
                        <a href="{{ route('chuong.doc', [$truyen->slug, $lichSuDoc->chuong->slug]) }}" class="btn-primary">Đọc tiếp</a>
                    @elseif($firstChapter)
                        <a href="{{ route('chuong.doc', [$truyen->slug, $firstChapter->slug]) }}" class="btn-primary">Đọc ngay</a>
                    @endif

                    @auth
                        <form method="POST" action="{{ route('yeu-thich.toggle', $truyen->id) }}">
                            @csrf
                            <button type="submit" class="btn-secondary">
                                {{ $daYeuThich ? '♥ Đã yêu thích' : '♡ Yêu thích' }}
                            </button>
                        </form>
                    @endauth
                </div>
            </div>

            <div class="hidden sm:block mx-auto w-full max-w-[200px] lg:max-w-[240px]">
                <div class="overflow-hidden shadow-lg" style="border-radius: var(--ui-radius-lg); border: 1px solid rgba(255,255,255,0.12);">
                    <img src="{{ $truyen->urlAnhBia() }}" alt="{{ $truyen->tieu_de }}" class="aspect-[2/3] w-full object-cover">
                </div>
            </div>
        </div>
    </section>

    {{-- Content area --}}
    <section class="grid gap-4 sm:gap-6 lg:grid-cols-[minmax(0,1fr)_280px] min-w-0">
        <div class="space-y-4 sm:space-y-6 min-w-0">
            {{-- Synopsis --}}
            <article class="surface-panel p-3 sm:p-5">
                <h2 class="section-title mb-3">Giới thiệu</h2>
                <div class="text-sm leading-relaxed" style="color: var(--ui-text-secondary);">
                    <p>{{ $truyen->mo_ta_ngan }}</p>
                    @if($truyen->mo_ta_day_du)
                        <div x-data="{ expanded: false }" class="mt-3">
                            <div class="whitespace-pre-line" :class="expanded ? '' : 'line-clamp-4'">{{ $truyen->mo_ta_day_du }}</div>
                            <button type="button" @click="expanded = !expanded" class="btn-quiet mt-2 px-0 text-xs">
                                <span x-text="expanded ? 'Thu gọn ↑' : 'Xem thêm ↓'"></span>
                            </button>
                        </div>
                    @endif
                </div>
            </article>

            {{-- Chapters --}}
            <article class="surface-panel p-3 sm:p-5">
                <h2 class="section-title mb-3">Danh sách chương</h2>

                @if($chapterTotal > 0)
                    <div class="chapter-range-grid mb-4">
                        @for ($i = 1; $i <= $chapterTotal; $i += $rangeSize)
                            @php
                                $end = min($i + $rangeSize - 1, $chapterTotal);
                                $targetPage = floor(($i - 1) / 50) + 1;
                                $active = $chuongs->currentPage() === $targetPage;
                            @endphp
                            <a href="{{ request()->fullUrlWithQuery(['page' => $targetPage]) }}"
                               class="chapter-range-pill {{ $active ? 'chapter-range-pill-active' : '' }}">
                                {{ $i }}–{{ $end }}
                            </a>
                        @endfor
                    </div>
                @endif

                <div class="chapter-list-grid">
                    @forelse($chuongs as $chapter)
                        <a href="{{ route('chuong.doc', [$truyen->slug, $chapter->slug]) }}" class="chapter-item">
                            <span class="truncate">Ch.{{ $chapter->so_chuong }} — {{ $chapter->tieu_de }}</span>
                        </a>
                    @empty
                        <p class="col-span-3 text-sm py-6 text-center" style="color: var(--ui-muted);">Truyện chưa có chương xuất bản.</p>
                    @endforelse
                </div>

                @if($chuongs->hasPages())
                    <div class="mt-6">
                        {{ $chuongs->links() }}
                    </div>
                @endif
            </article>

            {{-- Comments --}}
            <article class="surface-panel p-3 sm:p-5">
                <h2 class="section-title mb-3">Bình luận</h2>

                @auth
                    <form method="POST" action="{{ route('binh-luan.gui') }}" class="mb-5 p-4" style="background: var(--ui-surface-muted); border-radius: var(--ui-radius-lg);">
                        @csrf
                        <input type="hidden" name="truyen_id" value="{{ $truyen->id }}">
                        <textarea name="noi_dung" rows="3" placeholder="Viết bình luận..." class="field-shell textarea-shell border-0 bg-transparent" required maxlength="2000">{{ old('noi_dung') }}</textarea>
                        <div class="mt-3 flex items-center gap-3">
                            <button type="submit" class="btn-primary text-sm">Gửi bình luận</button>
                            <span class="text-xs" style="color: var(--ui-muted);">Tối đa 2000 ký tự</span>
                        </div>
                    </form>
                @else
                    <div class="mb-5 p-4 text-sm" style="background: var(--ui-surface-muted); border-radius: var(--ui-radius-lg); color: var(--ui-muted);">
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
                        <p class="text-sm py-6 text-center" style="color: var(--ui-muted);">Chưa có bình luận.</p>
                    @endforelse
                </div>
            </article>
        </div>

        {{-- Sidebar --}}
        <aside class="space-y-5">
            <div class="detail-sidebar-card">
                <h3 class="text-base font-semibold mb-3" style="color: var(--ui-text);">Tương tác</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="p-3 text-center" style="background: var(--ui-surface-muted); border-radius: var(--ui-radius);">
                        <p class="text-2xl font-bold" style="color: var(--ui-text);">{{ number_format($truyen->tong_luot_yeu_thich) }}</p>
                        <p class="text-xs mt-1" style="color: var(--ui-muted);">Yêu thích</p>
                    </div>
                    <div class="p-3 text-center" style="background: var(--ui-surface-muted); border-radius: var(--ui-radius);">
                        <p class="text-2xl font-bold" style="color: var(--ui-success);">{{ number_format($truyen->tong_luot_theo_doi) }}</p>
                        <p class="text-xs mt-1" style="color: var(--ui-muted);">Theo dõi</p>
                    </div>
                </div>

                <div class="mt-4 grid gap-2">
                    @auth
                        <form method="POST" action="{{ route('yeu-thich.toggle', $truyen->id) }}">
                            @csrf
                            <button type="submit" class="btn-primary w-full justify-center text-sm">{{ $daYeuThich ? '♥ Đã yêu thích' : '♡ Yêu thích' }}</button>
                        </form>
                        <form method="POST" action="{{ route('theo-doi.toggle', $truyen->id) }}">
                            @csrf
                            <button type="submit" class="btn-secondary w-full justify-center text-sm">{{ $daTheoDoi ? '✓ Đang theo dõi' : 'Theo dõi' }}</button>
                        </form>
                    @endauth
                </div>
            </div>

            @if($truyenLienQuan->count() > 0)
                <div class="detail-sidebar-card">
                    <h3 class="text-base font-semibold mb-3" style="color: var(--ui-text);">Truyện liên quan</h3>
                    <div class="space-y-3">
                        @foreach($truyenLienQuan->take(4) as $story)
                            <a href="{{ route('truyen.chi-tiet', $story->slug) }}" class="flex items-center gap-3 group">
                                <img src="{{ $story->urlAnhBia() }}" alt="{{ $story->tieu_de }}" class="h-16 w-12 object-cover shrink-0" style="border-radius: var(--ui-radius);">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold line-clamp-2 group-hover:underline" style="color: var(--ui-text);">{{ $story->tieu_de }}</p>
                                    <p class="text-xs mt-0.5" style="color: var(--ui-muted);">{{ $story->tac_gia ?: 'Đang cập nhật' }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </section>
</div>
@endsection
