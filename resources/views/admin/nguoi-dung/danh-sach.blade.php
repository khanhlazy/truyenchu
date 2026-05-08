@extends('layouts.admin')
@section('title', 'Quản Lý Người Dùng')
@section('page_title', 'Quản Lý Người Dùng')

@section('content')
<form method="GET" class="mb-6 flex flex-col gap-2 sm:flex-row">
    <input type="text" name="tu_khoa" value="{{ request('tu_khoa') }}" placeholder="Tìm người dùng..."
           class="field-shell sm:w-64">
    <button type="submit" class="btn-primary">Tìm</button>
</form>

<div class="app-table-wrap">
    <table class="app-table">
        <thead>
            <tr>
                <th class="px-4 py-3 text-left font-medium">Người dùng</th>
                <th class="px-4 py-3 text-left font-medium hidden md:table-cell">Email</th>
                <th class="px-4 py-3 text-center font-medium">Vai trò</th>
                <th class="px-4 py-3 text-center font-medium">Trạng thái</th>
                <th class="px-4 py-3 text-center font-medium hidden sm:table-cell">Chat</th>
                <th class="px-4 py-3 text-right font-medium">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nguoiDungs as $nd)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3">
                        <p class="font-medium">{{ $nd->ten_hien_thi }}</p>
                        <p class="text-xs text-gray-400">{{ '@' . $nd->ten_dang_nhap }}</p>
                    </td>
                    <td class="px-4 py-3 hidden md:table-cell text-gray-500">{{ $nd->email }}</td>
                    <td class="px-4 py-3 text-center">
                        @foreach($nd->vaiTro as $vt)
                            <span class="px-2 py-1 text-xs rounded-full {{ $vt->ma === 'admin' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">{{ $vt->ten }}</span>
                        @endforeach
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $nd->trang_thai === 'hoat_dong' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $nd->trang_thai === 'hoat_dong' ? 'Hoạt động' : 'Khóa' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell">
                        @if($nd->biCamChat())
                            <span class="text-xs text-red-500">Cấm đến {{ $nd->bi_cam_chat_den ? $nd->bi_cam_chat_den->format('d/m H:i') : '' }}</span>
                        @else
                            <span class="text-xs text-green-500">Bình thường</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        @if(!$nd->laAdmin())
                            <div class="flex items-center justify-end gap-1">
                                <form method="POST" action="{{ route('admin.nguoi-dung.toggle-trang-thai', $nd->id) }}">@csrf @method('PATCH')
                                    <button class="px-2 py-1 text-xs {{ $nd->trang_thai === 'hoat_dong' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600' }} rounded hover:opacity-80 transition">
                                        {{ $nd->trang_thai === 'hoat_dong' ? 'Khóa' : 'Mở khóa' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.nguoi-dung.toggle-mute', $nd->id) }}">@csrf @method('PATCH')
                                    <input type="hidden" name="so_gio" value="24">
                                    <button class="px-2 py-1 text-xs {{ $nd->biCamChat() ? 'bg-green-50 text-green-600' : 'bg-yellow-50 text-yellow-600' }} rounded hover:opacity-80 transition">
                                        {{ $nd->biCamChat() ? 'Gỡ cấm chat' : 'Cấm chat 24h' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="px-4 py-12 text-center text-gray-400">Không có người dùng nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $nguoiDungs->links() }}</div>
@endsection
