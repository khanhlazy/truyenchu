@extends('layouts.app')

@section('title', 'Ủng hộ duy trì website - ' . \App\Models\CauHinh::lay('ten_website', 'Truyện Chữ'))

@section('content')
@php
    $momo = \App\Models\CauHinh::lay('donate_qr_momo');
    $bank = \App\Models\CauHinh::lay('donate_qr_bank');
@endphp

<div class="shell-container page-stack">
    <section class="hero-panel">
        <span class="section-kicker">Ủng hộ website</span>
        <h1 class="page-title mt-4">Một khoản đóng góp nhỏ sẽ giúp thư viện truyện chạy mượt và được nâng cấp đều hơn.</h1>
        <p class="page-copy mt-4">
            Nếu bạn thấy trải nghiệm đọc ở đây hữu ích, sự ủng hộ của bạn sẽ là động lực rất lớn để tiếp tục duy trì máy chủ, tối ưu giao diện và cập nhật tính năng mới.
        </p>
    </section>

    @if($momo || $bank)
        <section class="surface-panel p-6">
            <div class="section-heading">
                <div>
                    <span class="section-kicker">Quét mã</span>
                    <h2 class="section-title">Các phương thức hiện có</h2>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                @if($momo)
                    <div class="surface-panel-strong p-5 text-center">
                        <img src="{{ asset('storage/' . $momo) }}" alt="Mã QR MoMo" class="mx-auto aspect-square w-full max-w-[280px] rounded-[28px] object-cover shadow-xl">
                        <div class="mt-5 flex justify-center">
                            <span class="tag-pill">Quét bằng MoMo</span>
                        </div>
                    </div>
                @endif

                @if($bank)
                    <div class="surface-panel-strong p-5 text-center">
                        <img src="{{ asset('storage/' . $bank) }}" alt="Mã QR ngân hàng" class="mx-auto aspect-square w-full max-w-[280px] rounded-[28px] object-cover shadow-xl">
                        <div class="mt-5 flex justify-center">
                            <span class="tag-pill">Chuyển khoản ngân hàng</span>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <section class="surface-panel p-6 sm:p-8">
        <div class="section-heading">
            <div>
                <span class="section-kicker">Lời nhắn</span>
                <h2 class="section-title">Thông tin thêm</h2>
            </div>
        </div>

        <div class="reading-copy max-w-none text-base leading-8">
            @if($noiDung)
                {!! $noiDung !!}
            @elseif(!$momo && !$bank)
                <p class="text-[color:var(--ui-muted)]">Nội dung ủng hộ đang được cập nhật thêm.</p>
            @endif
        </div>
    </section>
</div>
@endsection
