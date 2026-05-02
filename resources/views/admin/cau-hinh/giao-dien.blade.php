@extends('layouts.admin')
@section('title', 'Giao Diện Website')
@section('page_title', 'Cài Đặt Website')

@section('content')
<div class="max-w-5xl">
    <form method="POST" action="{{ route('admin.cau-hinh.cap-nhat-giao-dien') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Branding & General --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-lg mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Thông tin cơ bản
                    </h3>
                    
                    <div class="space-y-6">
                        {{-- Website Name --}}
                        <div class="pb-6 border-b border-gray-100 dark:border-gray-700">
                            <label class="block text-xs font-medium mb-1 uppercase tracking-wider text-gray-500">Tên Website</label>
                            <input type="text" name="ten_website" value="{{ $ten_website }}" class="w-full px-3 py-2 text-sm bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>

                        {{-- Logo --}}
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center overflow-hidden shrink-0">
                                @if($logo)
                                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="max-w-full max-h-full object-contain">
                                @else
                                    <span class="text-[10px] text-gray-400">No Logo</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium mb-1 uppercase tracking-wider text-gray-500">Logo Website (Khuyên dùng PNG)</label>
                                <input type="file" name="logo" class="text-xs w-full">
                                @if($logo)
                                    <label class="inline-flex items-center mt-1 text-[10px] text-red-500 cursor-pointer">
                                        <input type="checkbox" name="xoa_logo" value="1" class="mr-1 rounded"> Xóa logo
                                    </label>
                                @endif
                            </div>
                        </div>

                        {{-- Favicon --}}
                        <div class="flex items-center gap-4 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <div class="w-16 h-16 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 flex items-center justify-center overflow-hidden shrink-0">
                                @if($favicon)
                                    <img src="{{ asset('storage/' . $favicon) }}" alt="Favicon" class="w-8 h-8 object-contain">
                                @else
                                    <span class="text-[10px] text-gray-400">No Icon</span>
                                @endif
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-medium mb-1 uppercase tracking-wider text-gray-500">Favicon (Icon thẻ trình duyệt)</label>
                                <input type="file" name="favicon" class="text-xs w-full">
                                @if($favicon)
                                    <label class="inline-flex items-center mt-1 text-[10px] text-red-500 cursor-pointer">
                                        <input type="checkbox" name="xoa_favicon" value="1" class="mr-1 rounded"> Xóa favicon
                                    </label>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Donate Section --}}
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="font-semibold text-lg mb-6 flex items-center gap-2 text-indigo-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        Ủng hộ (Donate)
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-indigo-50 dark:bg-indigo-900/10 rounded-lg">
                            <span class="text-sm font-medium text-indigo-700 dark:text-indigo-400">Hiển thị link Donate</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="donate_bat" value="1" {{ $donate_bat == '1' ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-2">QR MOMO</label>
                                <input type="file" name="donate_qr_momo" class="w-full text-[10px]" />
                                @if(\App\Models\CauHinh::lay('donate_qr_momo'))
                                    <div class="mt-2 flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700/30 rounded border dark:border-gray-600">
                                        <img src="{{ asset('storage/' . \App\Models\CauHinh::lay('donate_qr_momo')) }}" class="w-10 h-10 object-cover rounded" />
                                        <span class="text-[9px] text-green-500 font-bold uppercase">Sẵn có</span>
                                    </div>
                                @endif
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-2">QR Ngân Hàng</label>
                                <input type="file" name="donate_qr_bank" class="w-full text-[10px]" />
                                @if(\App\Models\CauHinh::lay('donate_qr_bank'))
                                    <div class="mt-2 flex items-center gap-2 p-2 bg-gray-50 dark:bg-gray-700/30 rounded border dark:border-gray-600">
                                        <img src="{{ asset('storage/' . \App\Models\CauHinh::lay('donate_qr_bank')) }}" class="w-10 h-10 object-cover rounded" />
                                        <span class="text-[9px] text-green-500 font-bold uppercase">Sẵn có</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1">Nội dung bổ sung (Hỗ trợ HTML)</label>
                            <textarea name="donate_noi_dung" rows="5" placeholder="Ví dụ: <p>Nội dung...</p>"
                                      class="w-full px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg font-mono outline-none focus:ring-2 focus:ring-indigo-500 transition-all">{{ $donate_noi_dung }}</textarea>
                            <p class="text-[10px] text-gray-400 mt-1 italic">Mẹo: Sử dụng thẻ &lt;img&gt; để nhúng thêm các mã QR khác.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end pt-4">
            <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all focus:ring-4 focus:ring-indigo-300">
                Lưu tất cả thay đổi
            </button>
        </div>
    </form>
</div>
@endsection
