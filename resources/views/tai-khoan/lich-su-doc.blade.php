@extends('layouts.app')

@section('title', 'Lịch sử đọc - Truyện Chữ')

@section('content')
<div class="shell-container page-stack">
    <section class="hero-panel">
        <div class="grid gap-6 lg:grid-cols-[1fr_auto] lg:items-end">
            <div>
                <span class="section-kicker">Dấu vết đọc</span>
                <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">Lịch sử đọc để bạn quay lại đúng chương đang dang dở.</h1>
                <p class="mt-4 max-w-2xl text-base leading-8 text-[color:var(--ui-muted)] sm:text-lg">
                    Mọi lần đọc gần đây đều được lưu lại, giúp bạn nối mạch truyện ngay cả khi đổi thiết bị hoặc quay lại sau vài ngày.
                </p>
            </div>

            @if($lichSu->count() > 0)
                <form method="POST" action="{{ route('lich-su-doc.xoa') }}" onsubmit="return confirm('Bạn có chắc muốn xóa toàn bộ lịch sử đọc?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-secondary">Xóa toàn bộ</button>
                </form>
            @endif
        </div>
    </section>

    @include('components.account-tabs', ['active' => 'history'])

    @if($lichSu->count() > 0)
        <section class="surface-panel p-6">
            <div class="section-heading">
                <div>
                    <span class="section-kicker">Tiếp tục đọc</span>
                    <h2 class="section-title">Gần đây bạn đã mở {{ number_format($lichSu->total()) }} mục</h2>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($lichSu as $item)
                    @if($item->truyen)
                        <div class="surface-panel-strong p-4">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                                <img src="{{ $item->truyen->urlAnhBia() }}" alt="{{ $item->truyen->tieu_de }}" class="h-24 w-20 rounded-2xl object-cover">
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('truyen.chi-tiet', $item->truyen->slug) }}" class="block truncate text-base font-semibold">{{ $item->truyen->tieu_de }}</a>
                                    @if($item->chuong)
                                        <a href="{{ route('chuong.doc', [$item->truyen->slug, $item->chuong->slug]) }}" class="mt-2 inline-block text-sm font-medium text-[color:var(--ui-primary)]">
                                            {{ $item->chuong->tieu_de }}
                                        </a>
                                    @endif
                                    <p class="mt-2 text-xs text-[color:var(--ui-muted)]">{{ $item->thoi_diem_doc_cuoi->diffForHumans() }}</p>
                                </div>
                                @if($item->chuong)
                                    <a href="{{ route('chuong.doc', [$item->truyen->slug, $item->chuong->slug]) }}" class="btn-primary justify-center sm:w-auto">
                                        Đọc tiếp
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <div class="mt-8">
                {{ $lichSu->links() }}
            </div>
        </section>
    @else
        <section class="empty-state">
            <div class="mx-auto max-w-lg">
                <span class="section-kicker">Lịch sử trống</span>
                <h2 class="mt-4 text-3xl font-bold tracking-tight">Bạn chưa có lịch sử đọc nào.</h2>
                <p class="mt-4 text-sm leading-7 text-[color:var(--ui-muted)] sm:text-base">
                    Khi bắt đầu đọc truyện, các lần mở gần đây sẽ tự động xuất hiện tại đây để bạn quay lại nhanh hơn.
                </p>
                <div class="mt-6 flex justify-center">
                    <a href="{{ route('truyen.danh-sach') }}" class="btn-primary">Mở danh sách truyện</a>
                </div>
            </div>
        </section>
    @endif
</div>
@endsection
