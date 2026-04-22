@extends('layouts.app')
@section('title', 'Lịch Sử Đọc - TruyệnChữ')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Lịch Sử Đọc</h1>
        @if($lichSu->count() > 0)
            <form method="POST" action="{{ route('lich-su-doc.xoa') }}" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ lịch sử đọc?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-3 py-1.5 text-sm text-red-600 border border-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition">Xóa tất cả</button>
            </form>
        @endif
    </div>
    <div class="flex gap-4 mb-6 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('tai-khoan') }}" class="pb-3 text-sm font-medium text-gray-500">Hồ sơ</a>
        <a href="{{ route('yeu-thich') }}" class="pb-3 text-sm font-medium text-gray-500">Yêu thích</a>
        <a href="{{ route('theo-doi') }}" class="pb-3 text-sm font-medium text-gray-500">Theo dõi</a>
        <a href="{{ route('lich-su-doc') }}" class="pb-3 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">Lịch sử</a>
    </div>

    @if($lichSu->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($lichSu as $ls)
                <div class="flex items-center gap-4 p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    @if($ls->truyen)
                        <img src="{{ $ls->truyen->urlAnhBia() }}" alt="" class="w-12 h-16 object-cover rounded-lg flex-shrink-0">
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('truyen.chi-tiet', $ls->truyen->slug) }}" class="font-medium text-sm hover:text-indigo-600 transition truncate block">{{ $ls->truyen->tieu_de }}</a>
                            @if($ls->chuong)
                                <a href="{{ route('chuong.doc', [$ls->truyen->slug, $ls->chuong->slug]) }}" class="text-xs text-gray-500 hover:text-indigo-600 transition">{{ $ls->chuong->tieu_de }}</a>
                            @endif
                        </div>
                        <span class="text-xs text-gray-400 flex-shrink-0">{{ $ls->thoi_diem_doc_cuoi->diffForHumans() }}</span>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $lichSu->links() }}</div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
            <p class="text-gray-500 text-lg">Chưa có lịch sử đọc nào.</p>
        </div>
    @endif
</div>
@endsection
