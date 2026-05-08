<a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="story-card group">
    @php $coverUrl = $truyen->urlAnhBia(); @endphp
    <div class="story-cover">
        @if($coverUrl)
            <img src="{{ $coverUrl }}" alt="{{ $truyen->tieu_de }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        @else
            <x-cover-placeholder class="h-full w-full transition-transform duration-500 group-hover:scale-105" />
        @endif
        
        {{-- Badges/Status --}}
        <div class="absolute top-1.5 left-1.5 flex flex-col gap-1">
            @if($truyen->trang_thai === 'hoan_thanh')
                <span class="rounded-full px-2 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white backdrop-blur-md shadow-sm" style="background: var(--ui-success);">Full</span>
            @endif
        </div>

        {{-- Hover overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-2">
            <span class="text-[9px] font-medium text-white/90 line-clamp-2">{{ $truyen->mo_ta_ngan }}</span>
        </div>
    </div>

    <div class="story-card-body space-y-1">
        <h3 class="story-card-title line-clamp-2 transition-colors group-hover:text-[color:var(--ui-primary)]">
            {{ $truyen->tieu_de }}
        </h3>
        <div class="story-card-meta flex items-center gap-1.5">
            <span class="truncate">{{ $truyen->tac_gia ?: 'Chưa cập nhật tác giả' }}</span>
            <span class="shrink-0 opacity-50">/</span>
            <span class="shrink-0">{{ $truyen->chuong_count ?? 0 }} chương</span>
        </div>
    </div>
</a>
