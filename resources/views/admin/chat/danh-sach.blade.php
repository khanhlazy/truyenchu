@extends('layouts.admin')
@section('title', 'Quản Lý Chat')
@section('page_title', 'Quản Lý Chat')

@section('content')
<div class="app-table-wrap">
    <table class="app-table">
        <thead>
            <tr>
                <th class="px-4 py-3 text-left font-medium">Người gửi</th>
                <th class="px-4 py-3 text-left font-medium">Nội dung</th>
                <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">Thời gian</th>
                <th class="px-4 py-3 text-right font-medium">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tinNhans as $tn)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3 font-medium">{{ $tn->nguoiDung?->ten_hien_thi }}</td>
                    <td class="px-4 py-3 max-w-md truncate">{{ $tn->noi_dung }}</td>
                    <td class="px-4 py-3 text-gray-400 hidden sm:table-cell">{{ $tn->created_at ? $tn->created_at->format('d/m/Y H:i') : '' }}</td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.chat.xoa', $tn->id) }}" onsubmit="return confirm('Xóa tin nhắn này?')">
                            @csrf @method('DELETE')
                            <button class="px-2 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 transition">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="px-4 py-12 text-center text-gray-400">Chưa có tin nhắn nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $tinNhans->links() }}</div>
@endsection
