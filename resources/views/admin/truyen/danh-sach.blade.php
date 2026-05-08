@extends('layouts.admin')
@section('title', 'Quản Lý Truyện')
@section('page_title', 'Quản Lý Truyện')

@section('content')
<div class="mb-6 flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
    <form method="GET" class="flex flex-col gap-2 sm:flex-row">
        <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tìm truyện..."
               class="field-shell sm:w-64">
        <select name="trang_thai" class="field-shell sm:w-40">
            <option value="">Tất cả</option>
            <option value="dang_ra" {{ request('trang_thai') == 'dang_ra' ? 'selected' : '' }}>Đang ra</option>
            <option value="hoan_thanh" {{ request('trang_thai') == 'hoan_thanh' ? 'selected' : '' }}>Hoàn thành</option>
            <option value="tam_ngung" {{ request('trang_thai') == 'tam_ngung' ? 'selected' : '' }}>Tạm ngưng</option>
        </select>
        <button type="submit" class="btn-primary">Lọc</button>
    </form>
    <a href="{{ route('admin.truyen.tao-moi') }}" class="btn-primary">+ Thêm truyện</a>
</div>

<div class="app-table-wrap">
    <table class="app-table">
        <thead>
            <tr>
                <th class="px-4 py-3 text-left font-medium">Truyện</th>
                <th class="px-4 py-3 text-left font-medium hidden md:table-cell">Trạng thái</th>
                <th class="px-4 py-3 text-center font-medium hidden sm:table-cell">Lượt xem</th>
                <th class="px-4 py-3 text-center font-medium hidden sm:table-cell">Xuất bản</th>
                <th class="px-4 py-3 text-right font-medium">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($truyens as $t)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3">
                        <div>
                            <p class="font-medium">{{ $t->tieu_de }}</p>
                            <p class="text-xs text-gray-400">{{ $t->tac_gia }} · {{ $t->theLoai->pluck('ten')->join(', ') }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell">
                        <span class="px-2 py-1 text-xs rounded-full {{ $t->trang_thai === 'hoan_thanh' ? 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400' : ($t->trang_thai === 'tam_ngung' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400') }}">
                            {{ $t->tenTrangThai() }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell">{{ number_format($t->tong_luot_xem) }}</td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell">
                        <form method="POST" action="{{ route('admin.truyen.toggle-publish', $t->id) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="px-2 py-1 text-xs rounded-full {{ $t->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                                {{ $t->is_published ? 'Đã XB' : 'Nháp' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.chuong.danh-sach', $t->id) }}" class="px-2 py-1 text-xs bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded hover:bg-purple-100 transition">Chương</a>
                            <a href="{{ route('admin.truyen.sua', $t->id) }}" class="px-2 py-1 text-xs bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded hover:bg-blue-100 transition">Sửa</a>
                            <form method="POST" action="{{ route('admin.truyen.xoa', $t->id) }}" onsubmit="return confirm('Xóa truyện này?')">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 text-xs bg-red-50 dark:bg-red-900/20 text-red-600 rounded hover:bg-red-100 transition">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-400">Chưa có truyện nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $truyens->links() }}</div>
@endsection
