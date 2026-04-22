@extends('layouts.admin')
@section('title', 'Quản Lý Thể Loại')
@section('page_title', 'Quản Lý Thể Loại')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-lg font-semibold">Danh sách thể loại</h2>
    <a href="{{ route('admin.the-loai.tao-moi') }}" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">+ Thêm thể loại</a>
</div>

<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700/50">
            <tr>
                <th class="px-4 py-3 text-left font-medium">Tên</th>
                <th class="px-4 py-3 text-left font-medium hidden sm:table-cell">Slug</th>
                <th class="px-4 py-3 text-center font-medium">Số truyện</th>
                <th class="px-4 py-3 text-center font-medium hidden sm:table-cell">Thứ tự</th>
                <th class="px-4 py-3 text-right font-medium">Hành động</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
            @foreach($theLoais as $tl)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3 font-medium">{{ $tl->ten }}</td>
                    <td class="px-4 py-3 text-gray-400 hidden sm:table-cell">{{ $tl->slug }}</td>
                    <td class="px-4 py-3 text-center">{{ $tl->truyen_count }}</td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell">{{ $tl->thu_tu }}</td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.the-loai.sua', $tl->id) }}" class="px-2 py-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition">Sửa</a>
                            <form method="POST" action="{{ route('admin.the-loai.xoa', $tl->id) }}" onsubmit="return confirm('Xóa thể loại này?')">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 transition">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $theLoais->links() }}</div>
@endsection
