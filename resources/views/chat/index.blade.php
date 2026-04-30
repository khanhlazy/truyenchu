@extends('layouts.app')

@section('title', 'Chat - Truyện Chữ')

@section('content')
<div class="shell-container page-stack" x-data="chatApp()">
    <section class="hero-panel">
        <span class="section-kicker">Cộng đồng</span>
        <h1 class="mt-4 text-4xl font-black tracking-tight sm:text-5xl">{{ $phong->ten }}</h1>
        <p class="mt-4 max-w-2xl text-base leading-8 text-[color:var(--ui-muted)] sm:text-lg">
            Nơi nói chuyện nhanh về truyện mới, chương hot và những màn plot twist vừa làm bạn đứng hình.
        </p>
    </section>

    <section class="surface-panel overflow-hidden">
        <div class="border-b border-[color:var(--ui-border)] px-6 py-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--ui-muted)]">Phòng chat trực tiếp</p>
                    <h2 class="mt-2 text-2xl font-bold">Tin nhắn đang diễn ra</h2>
                </div>
                <span class="tag-pill-muted">Tự làm mới mỗi 5 giây</span>
            </div>
        </div>

        <div id="chat-messages" class="h-[460px] overflow-y-auto px-4 py-5 sm:px-6" x-ref="messages">
            <div class="space-y-4">
                @foreach($tinNhans as $tn)
                    <div class="flex items-start gap-3">
                        <img src="{{ $tn->nguoiDung->urlAnhDaiDien() }}" alt="{{ $tn->nguoiDung->ten_hien_thi }}"
                             class="h-10 w-10 rounded-full object-cover ring-2 ring-white/60 dark:ring-white/10">
                        <div class="surface-panel-strong max-w-[min(100%,42rem)] px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-semibold">{{ $tn->nguoiDung->ten_hien_thi }}</span>
                                <span class="text-xs text-[color:var(--ui-muted)]">{{ $tn->created_at->format('H:i') }}</span>
                            </div>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--ui-text)]">{{ $tn->noi_dung }}</p>
                        </div>
                    </div>
                @endforeach

                <template x-for="msg in newMessages" :key="msg.id">
                    <div class="flex items-start gap-3">
                        <img :src="msg.nguoi_dung.url_anh_dai_dien" :alt="msg.nguoi_dung.ten_hien_thi"
                             class="h-10 w-10 rounded-full object-cover ring-2 ring-white/60 dark:ring-white/10">
                        <div class="surface-panel-strong max-w-[min(100%,42rem)] px-4 py-3">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="text-sm font-semibold" x-text="msg.nguoi_dung.ten_hien_thi"></span>
                                <span class="text-xs text-[color:var(--ui-muted)]" x-text="new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour:'2-digit', minute:'2-digit'})"></span>
                            </div>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--ui-text)]" x-text="msg.noi_dung"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <div class="border-t border-[color:var(--ui-border)] bg-white/45 px-4 py-4 dark:bg-white/5 sm:px-6">
            <form @submit.prevent="sendMessage" class="flex flex-col gap-3 sm:flex-row">
                <input type="text" x-model="messageText" placeholder="Nhập tin nhắn..." maxlength="500" class="field-shell flex-1">
                <button type="submit" :disabled="sending" class="btn-primary justify-center disabled:cursor-not-allowed disabled:opacity-60">
                    <span x-text="sending ? 'Đang gửi...' : 'Gửi tin nhắn'"></span>
                </button>
            </form>
            <p x-show="error" x-text="error" class="mt-2 text-xs text-red-500"></p>
        </div>
    </section>
</div>

@push('scripts')
<script>
function chatApp() {
    return {
        messageText: '',
        newMessages: [],
        lastId: {{ $tinNhans->last()?->id ?? 0 }},
        sending: false,
        error: '',
        pollInterval: null,

        init() {
            this.scrollToBottom();
            this.pollInterval = setInterval(() => this.fetchMessages(), 5000);
        },

        async sendMessage() {
            if (!this.messageText.trim() || this.sending) return;

            this.sending = true;
            this.error = '';

            try {
                const response = await fetch('{{ route("chat.gui") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ noi_dung: this.messageText })
                });

                const data = await response.json();

                if (response.ok) {
                    this.newMessages.push(data.tin_nhan);
                    this.lastId = data.tin_nhan.id;
                    this.messageText = '';
                    this.$nextTick(() => this.scrollToBottom());
                } else {
                    this.error = data.loi || 'Không thể gửi tin nhắn lúc này.';
                }
            } catch (error) {
                this.error = 'Lỗi kết nối. Vui lòng thử lại.';
            }

            this.sending = false;
        },

        async fetchMessages() {
            try {
                const response = await fetch(`{{ route("chat.tai") }}?last_id=${this.lastId}`);
                const data = await response.json();

                if (data.tin_nhans && data.tin_nhans.length > 0) {
                    this.newMessages.push(...data.tin_nhans);
                    this.lastId = data.tin_nhans[data.tin_nhans.length - 1].id;
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (error) {
                // Silent retry on next interval.
            }
        },

        scrollToBottom() {
            const element = this.$refs.messages;
            if (element) {
                element.scrollTop = element.scrollHeight;
            }
        }
    }
}
</script>
@endpush
@endsection
