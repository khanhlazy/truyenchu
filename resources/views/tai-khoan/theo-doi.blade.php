@extends('layouts.app')

@section('title', 'Truyện theo dõi - Truyện Chữ')

@section('content')
<div class="shell-container page-stack">
    <section class="hero-panel">
        <span class="section-kicker">Nhịp đọc của bạn</span>
        <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Những bộ bạn đang theo dõi được gom lại thành một hàng đợi rất gọn.</h1>
        <p class="mt-4 max-w-2xl text-base leading-8 text-[color:var(--ui-muted)] sm:text-lg">
            Từ đây bạn có thể quay lại các bộ đang đọc dở mà không cần nhớ chính xác tên hay chương cuối.
        </p>
    </section>

    @include('components.account-tabs', ['active' => 'following'])

    @if($truyens->count() > 0)
        <section class="surface-panel p-6">
            <div class="section-heading">
                <div>
                    <span class="section-kicker">Theo dõi</span>
                    <h2 class="section-title">Có {{ number_format($truyens->total()) }} truyện đang theo dõi</h2>
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
                <span class="section-kicker">Theo dõi trống</span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight">Bạn chưa theo dõi truyện nào.</h2>
                <p class="mt-4 text-sm leading-7 text-[color:var(--ui-muted)] sm:text-base">
                    Bật theo dõi ở các bộ bạn đang quan tâm để mỗi lần quay lại không phải tìm lại từ đầu.
                </p>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('truyen.danh-sach') }}" class="btn-primary">Chọn truyện để theo dõi</a>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection
