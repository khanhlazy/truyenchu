@extends('layouts.admin')
@section('title', 'Chương - ' . $truyen->tieu_de)
@section('page_title', 'Chương: ' . $truyen->tieu_de)

@section('content')
<div class="mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
    <div class="flex flex-wrap gap-4 items-center">
        <a href="{{ route('admin.truyen.danh-sach') }}" class="btn-quiet">← Quay lại</a>
        <form method="GET" class="flex items-center gap-2" id="filterForm">
            <span class="text-sm text-gray-500">Hiển thị:</span>
            <select name="per_page" onchange="document.getElementById('filterForm').submit()" class="field-shell !min-h-9 !py-1">
                <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                <option value="500" {{ $perPage == 500 ? 'selected' : '' }}>500</option>
                <option value="all" {{ $perPage === 'all' ? 'selected' : '' }}>Tất cả</option>
            </select>
        </form>
    </div>
    <div class="flex items-center gap-2">
        <button type="button" onclick="submitBulk('publish')" class="btn-primary" id="btn-publish-all" style="display: none;">Đăng XB hàng loạt</button>
        <button type="button" onclick="submitBulk('draft')" class="btn-secondary" id="btn-draft-all" style="display: none;">Bỏ XB hàng loạt</button>
        <a href="{{ route('admin.chuong.tao-moi', $truyen->id) }}" class="btn-primary">+ Thêm chương</a>
    </div>
</div>

<div class="app-table-wrap">
    <table class="app-table">
        <thead>
            <tr>
                <th class="px-4 py-3 text-left w-8">
                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="toggleAll(this)">
                </th>
                <th class="px-4 py-3 text-left font-medium w-16">Số</th>
                <th class="px-4 py-3 text-left font-medium">Tiêu đề</th>
                <th class="px-4 py-3 text-center font-medium hidden sm:table-cell">Số từ</th>
                <th class="px-4 py-3 text-center font-medium hidden sm:table-cell">Lượt xem</th>
                <th class="px-4 py-3 text-center font-medium">Xuất bản</th>
                <th class="px-4 py-3 text-right font-medium">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($chuongs as $c)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <td class="px-4 py-3">
                        <input type="checkbox" name="chapter_ids[]" value="{{ $c->id }}" class="chapter-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="toggleActionButtons()">
                    </td>
                    <td class="px-4 py-3 font-medium">{{ $c->so_chuong }}</td>
                    <td class="px-4 py-3">{{ $c->tieu_de }}</td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell">{{ number_format($c->so_tu) }}</td>
                    <td class="px-4 py-3 text-center hidden sm:table-cell">{{ number_format($c->tong_luot_xem) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 text-xs rounded-full {{ $c->is_published ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $c->is_published ? 'Đã XB' : 'Nháp' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right">
                        <div class="flex items-center justify-end gap-1">
                            <a href="{{ route('admin.chuong.sua', $c->id) }}" class="px-2 py-1 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition">Sửa</a>
                            <form method="POST" action="{{ route('admin.chuong.xoa', $c->id) }}" onsubmit="return confirm('Xóa chương này?')">
                                @csrf @method('DELETE')
                                <button class="px-2 py-1 text-xs bg-red-50 text-red-600 rounded hover:bg-red-100 transition">Xóa</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-12 text-center text-gray-400">Chưa có chương nào.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $chuongs->links() }}</div>

@push('scripts')
<script>
    function toggleAll(source) {
        let checkboxes = document.querySelectorAll('.chapter-checkbox');
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = source.checked;
        }
        toggleActionButtons();
    }

    function toggleActionButtons() {
        let checkboxes = document.querySelectorAll('.chapter-checkbox:checked');
        let btnPublish = document.getElementById('btn-publish-all');
        let btnDraft = document.getElementById('btn-draft-all');
        if (checkboxes.length > 0) {
            btnPublish.style.display = 'inline-block';
            btnDraft.style.display = 'inline-block';
        } else {
            btnPublish.style.display = 'none';
            btnDraft.style.display = 'none';
        }
    }

    function submitBulk(action) {
        let checkboxes = document.querySelectorAll('.chapter-checkbox:checked');
        if (checkboxes.length === 0) return;

        let form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('admin.chuong.bulk-publish') }}';
        
        let csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = '{{ csrf_token() }}';
        form.appendChild(csrf);

        let actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = action;
        form.appendChild(actionInput);

        checkboxes.forEach(function(cb) {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'chapter_ids[]';
            input.value = cb.value;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endpush
@endsection
