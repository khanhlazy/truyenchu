@extends('layouts.app')
@section('title', 'Truyện Theo Dõi - TruyệnChữ')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Truyện Theo Dõi</h1>
    <div class="flex gap-4 mb-6 border-b border-gray-200 dark:border-gray-700">
        <a href="{{ route('tai-khoan') }}" class="pb-3 text-sm font-medium text-gray-500">Hồ sơ</a>
        <a href="{{ route('yeu-thich') }}" class="pb-3 text-sm font-medium text-gray-500">Yêu thích</a>
        <a href="{{ route('theo-doi') }}" class="pb-3 text-sm font-medium border-b-2 border-indigo-600 text-indigo-600">Theo dõi</a>
        <a href="{{ route('lich-su-doc') }}" class="pb-3 text-sm font-medium text-gray-500">Lịch sử</a>
    </div>

    @if($truyens->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($truyens as $truyen)
                @include('components.story-card', ['truyen' => $truyen])
            @endforeach
        </div>
        <div class="mt-6">{{ $truyens->links() }}</div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
            <p class="text-gray-500 text-lg">Bạn chưa theo dõi truyện nào.</p>
            <a href="{{ route('truyen.danh-sach') }}" class="inline-block mt-4 px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg hover:bg-indigo-700 transition">Khám phá truyện</a>
        </div>
    @endif
</div>
@endsection
