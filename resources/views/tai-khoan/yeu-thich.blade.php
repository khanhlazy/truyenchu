@extends('layouts.app')

@section('title', 'Truyện yêu thích - Truyện Chữ')

@section('content')
<div class="shell-container page-stack">
    <section class="hero-panel">
        <span class="section-kicker">Thư viện cá nhân</span>
        <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Danh sách truyện bạn đã đánh dấu yêu thích.</h1>
        <p class="mt-4 max-w-2xl text-base leading-8 text-[color:var(--ui-muted)] sm:text-lg">
            Tập hợp lại những bộ bạn muốn đọc lại, giới thiệu cho bạn bè hoặc tiếp tục theo dõi lâu dài.
        </p>
    </section>

    @include('components.account-tabs', ['active' => 'favorites'])

    @if($truyens->count() > 0)
        <section class="surface-panel p-6">
            <div class="section-heading">
                <div>
                    <span class="section-kicker">Yêu thích</span>
                    <h2 class="section-title">Có {{ number_format($truyens->total()) }} truyện đã lưu</h2>
                </div>
            </div>

            <div class="story-grid">
                @foreach($truyens as $truyen)
                    @include('components.story-card', ['truyen' => $truyen])
                @endforeach
            </div>

            <div class="mt-8">
                {{ $truyens->links() }}
            </div>
        </section>
    @else
        <section class="empty-state">
            <div class="mx-auto max-w-lg">
                <span class="section-kicker">Chưa có dữ liệu</span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight">Bạn chưa thêm truyện nào vào mục yêu thích.</h2>
                <p class="mt-4 text-sm leading-7 text-[color:var(--ui-muted)] sm:text-base">
                    Khi bắt gặp một bộ đủ hợp gu, hãy lưu lại để quay lại nhanh hơn ở những lần đọc tiếp theo.
                </p>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('truyen.danh-sach') }}" class="btn-primary">Đi khám phá truyện</a>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection
