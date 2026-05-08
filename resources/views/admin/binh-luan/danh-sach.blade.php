@extends('layouts.admin')
@section('title', 'Quản Lý Bình Luận')
@section('page_title', 'Quản Lý Bình Luận')

@section('content')
<form method="GET" class="mb-6 flex flex-col gap-2 sm:flex-row">
    <select name="trang_thai" class="field-shell sm:w-40">
        <option value="">Tất cả</option>
        <option value="cho_duyet" {{ request('trang_thai') == 'cho_duyet' ? 'selected' : '' }}>Chờ duyệt</option>
        <option value="hien_thi" {{ request('trang_thai') == 'hien_thi' ? 'selected' : '' }}>Hiển thị</option>
        <option value="an" {{ request('trang_thai') == 'an' ? 'selected' : '' }}>Ẩn</option>
    </select>
    <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tìm nội dung..."
           class="field-shell sm:w-64">
    <button type="submit" class="btn-primary">Lọc</button>
</form>

<div class="app-table-wrap">
    <table class="app-table">
        <thead>
            <tr>
                <th class="px-4 py-3 text-left font-medium">Nội dung</th>
                <th class="px-4 py-3 text-left font-medium hidden md:table-cell">Người dùng</th>
                <th class="px-4 py-3 text-left font-medium hidden lg:table-cell">Truyện / Chương</th>
                <th class="px-4 py-3 text-center font-medium">Trạng thái</th>
                <th class="px-4 py-3 text-right font-medium">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($binhLuans as $bl)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3 max-w-xs truncate">{{ Str::limit($bl->noi_dung, 80) }}</td>
                    <td class="px-4 py-3 hidden md:table-cell">{{ $bl->nguoiDung?->ten_hien_thi }}</td>
                    <td class="px-4 py-3 hidden lg:table-cell text-xs text-gray-400">
                        {{ $bl->truyen?->tieu_de }}
                        @if($bl->chuong) / Ch.{{ $bl->chuong->so_chuong }} @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $bl->trang_thai === 'hien_thi' ? 'bg-green-100 text-green-700' : ($bl->trang_thai === 'cho_duyet' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $bl->tenTrangThai() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            @if($bl->trang_thai !== 'hien_thi')
                                <form method="POST" action="{{ route('admin.binh-luan.duyet', $bl->id) }}">@csrf @method('PATCH')
                                    <button class="px-2 py-1 text-xs bg-green-50 text-green-600 rounded hover:bg-green-100 transition">Duyệt</button>
                                </form>
                            @endif
                            @if($bl->trang_thai !== 'an')
                                <form method="POST" action="{{ route('admin.binh-luan.an', $bl->id) }}">@csrf @method('PATCH')
                                    <button class="px-2 py-1 text-xs bg-yellow-50 text-yellow-600 rounded hover:bg-yellow-100 transition">Ẩn</button>
                                </form>
                            @endif
                            <form method="POST" action="{{ route('admin.binh-luan.xoa', $bl->id) }}" onsubmit="return confirm('Xóa?')">@csrf @method('DELETE')
                                <button class="px-2 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 transition">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">Không có bình luận nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $binhLuans->links() }}</div>
@endsection
