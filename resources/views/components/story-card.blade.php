<a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="story-card group">
    <div class="story-cover">
        <img src="{{ $truyen->urlAnhBia() }}" alt="{{ $truyen->tieu_de }}" loading="lazy">
    </div>

    <div class="story-card-body">
        <h3 class="story-card-title line-clamp-2">{{ $truyen->tieu_de }}</h3>
        <p class="story-card-meta">{{ $truyen->tac_gia ?: 'Đang cập nhật' }}</p>
        @if($truyen->relationLoaded('theLoai') && $truyen->theLoai->count())
            <p class="story-card-meta line-clamp-1">{{ $truyen->theLoai->take(2)->pluck('ten')->join(' · ') }}</p>
        @endif
    </div>
</a>
