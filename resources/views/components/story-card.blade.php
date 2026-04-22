{{-- Card truyện tái sử dụng - kích thước đồng nhất --}}
<a href="{{ route('truyen.chi-tiet', $truyen->slug) }}" class="group block h-full">
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg hover:border-indigo-300 dark:hover:border-indigo-600 transition-all duration-300 h-full flex flex-col">
        {{-- Ảnh bìa - tỉ lệ cố định --}}
        <div class="aspect-[3/4] relative overflow-hidden bg-gray-100 dark:bg-gray-700 flex-shrink-0">
            <img src="{{ $truyen->urlAnhBia() }}"
                 alt="{{ $truyen->tieu_de }}"
                 loading="lazy"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

            {{-- Badge trạng thái --}}
            <span class="absolute top-2 left-2 px-2 py-0.5 text-xs font-semibold rounded-md
                {{ $truyen->trang_thai === 'hoan_thanh' ? 'bg-green-500 text-white' : ($truyen->trang_thai === 'tam_ngung' ? 'bg-yellow-500 text-white' : 'bg-indigo-500 text-white') }}">
                {{ $truyen->tenTrangThai() }}
            </span>

            {{-- Chương mới nhất --}}
            @if($truyen->relationLoaded('chuongMoiNhat') && $truyen->chuongMoiNhat)
                <div class="absolute bottom-0 inset-x-0 bg-gradient-to-t from-black/70 to-transparent p-2">
                    <p class="text-xs text-white truncate">Ch.{{ $truyen->chuongMoiNhat->so_chuong }}</p>
                </div>
            @endif
        </div>

        {{-- Thông tin - chiều cao cố định --}}
        <div class="p-3 flex flex-col flex-1">
            <h3 class="text-sm font-semibold leading-tight h-10 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">
                {{ $truyen->tieu_de }}
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate">{{ $truyen->tac_gia }}</p>
            <div class="flex flex-wrap gap-1 mt-auto pt-2">
                @if($truyen->relationLoaded('theLoai') && $truyen->theLoai->count())
                    @foreach($truyen->theLoai->take(2) as $tl)
                        <span class="px-1.5 py-0.5 text-[10px] bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded">{{ $tl->ten }}</span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</a>
