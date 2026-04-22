@extends('layouts.app')

@section('title', 'Tìm Kiếm Truyện - TruyệnChữ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Tìm Kiếm Truyện</h1>

    <form method="GET" action="{{ route('tim-kiem') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="sm:col-span-2">
                <input type="text" name="tu_khoa" value="{{ $tuKhoa }}" placeholder="Nhập tên truyện, tác giả, hoặc mô tả..."
                       class="w-full px-4 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent" autofocus>
            </div>
            <div>
                <select name="the_loai" class="w-full px-3 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả thể loại</option>
                    @foreach($theLoais as $tl)
                        <option value="{{ $tl->id }}" {{ request('the_loai') == $tl->id ? 'selected' : '' }}>{{ $tl->ten }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="trang_thai" class="w-full px-3 py-3 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả trạng thái</option>
                    <option value="dang_ra" {{ request('trang_thai') == 'dang_ra' ? 'selected' : '' }}>Đang ra</option>
                    <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="tam_ngung" {{ request('trang_thai') == 'tam_ngung' ? 'selected' : '' }}>Tạm ngưng</option>
                </select>
            </div>
        </div>
        <button type="submit" class="mt-4 px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">Tìm kiếm</button>
    </form>

    @if($tuKhoa || request('the_loai') || request('trang_thai'))
        @if($truyens instanceof \Illuminate\Pagination\LengthAwarePaginator && $truyens->count() > 0)
            <p class="text-sm text-gray-500 mb-4">Tìm thấy {{ $truyens->total() }} kết quả {{ $tuKhoa ? 'cho "' . $tuKhoa . '"' : '' }}</p>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($truyens as $truyen)
                    @include('components.story-card', ['truyen' => $truyen])
                @endforeach
            </div>
            <div class="mt-6">{{ $truyens->links() }}</div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 text-lg">Không tìm thấy truyện nào {{ $tuKhoa ? 'cho "' . $tuKhoa . '"' : '' }}.</p>
                <p class="text-gray-400 text-sm mt-1">Thử thay đổi từ khóa hoặc bộ lọc.</p>
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            <p class="text-gray-500 text-lg">Nhập từ khóa để bắt đầu tìm kiếm</p>
        </div>
    @endif
</div>
@endsection
