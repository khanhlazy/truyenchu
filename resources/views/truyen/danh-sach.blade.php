@extends('layouts.app')

@section('title', 'Danh Sách Truyện - TruyệnChữ')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <h1 class="text-2xl font-bold mb-6">Danh Sách Truyện</h1>

    {{-- Filters --}}
    <form method="GET" action="{{ route('truyen.danh-sach') }}" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Tìm kiếm</label>
                <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tên truyện, tác giả..."
                       class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Thể loại</label>
                <select name="the_loai" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả</option>
                    @foreach($theLoais as $tl)
                        <option value="{{ $tl->id }}" {{ request('the_loai') == $tl->id ? 'selected' : '' }}>{{ $tl->ten }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Trạng thái</label>
                <select name="trang_thai" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="">Tất cả</option>
                    <option value="dang_ra" {{ request('trang_thai') == 'dang_ra' ? 'selected' : '' }}>Đang ra</option>
                    <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="tam_ngung" {{ request('trang_thai') == 'tam_ngung' ? 'selected' : '' }}>Tạm ngưng</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Sắp xếp</label>
                <select name="sap_xep" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                    <option value="moi_cap_nhat" {{ request('sap_xep') == 'moi_cap_nhat' ? 'selected' : '' }}>Mới cập nhật</option>
                    <option value="xem_nhieu" {{ request('sap_xep') == 'xem_nhieu' ? 'selected' : '' }}>Xem nhiều</option>
                    <option value="ten_az" {{ request('sap_xep') == 'ten_az' ? 'selected' : '' }}>Tên A-Z</option>
                    <option value="ten_za" {{ request('sap_xep') == 'ten_za' ? 'selected' : '' }}>Tên Z-A</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex gap-2">
            <button type="submit" class="px-4 py-2 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Lọc</button>
            <a href="{{ route('truyen.danh-sach') }}" class="px-4 py-2 text-sm bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition">Xóa lọc</a>
        </div>
    </form>

    {{-- Results --}}
    @if($truyens->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($truyens as $truyen)
                @include('components.story-card', ['truyen' => $truyen])
            @endforeach
        </div>
        <div class="mt-6">
            {{ $truyens->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Không tìm thấy truyện nào.</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Thử thay đổi bộ lọc hoặc từ khóa tìm kiếm.</p>
        </div>
    @endif
</div>
@endsection
