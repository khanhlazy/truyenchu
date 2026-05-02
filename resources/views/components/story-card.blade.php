<a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="story-card group">
    <div class="story-cover relative overflow-hidden rounded-xl shadow-premium transition-all duration-300 group-hover:shadow-premium-hover group-hover:-translate-y-1">
        <img src="{{ $truyen->urlAnhBia() }}" alt="{{ $truyen->tieu_de }}" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">
        
        {{-- Badges/Status --}}
        <div class="absolute top-2 left-2 flex flex-col gap-1">
            @if($truyen->trang_thai === 'hoan_thanh')
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white bg-green-500/90 backdrop-blur-md rounded-md shadow-sm">Full</span>
            @endif
            @if($truyen->hot)
                <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-white bg-red-500/90 backdrop-blur-md rounded-md shadow-sm">Hot</span>
            @endif
        </div>

        {{-- Hover overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
            <span class="text-[10px] font-medium text-white/90 line-clamp-2">{{ $truyen->mo_ta_ngan }}</span>
        </div>
    </div>

    <div class="mt-3 space-y-1">
        <h3 class="text-sm font-bold leading-tight line-clamp-2 transition-colors duration-200 group-hover:text-primary-600 dark:group-hover:text-primary-400" style="color: var(--ui-text);">
            {{ $truyen->tieu_de }}
        </h3>
        <div class="flex items-center gap-2 text-[11px]" style="color: var(--ui-muted);">
            <span class="truncate">{{ $truyen->tac_gia ?: 'Ẩn danh' }}</span>
            <span class="shrink-0">•</span>
            <span class="shrink-0">{{ $truyen->chuong_count ?? 0 }} chương</span>
        </div>
    </div>
</a>
