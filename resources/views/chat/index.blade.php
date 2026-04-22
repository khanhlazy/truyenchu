@extends('layouts.app')
@section('title', 'Chat - TruyệnChữ')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6" x-data="chatApp()">
    <h1 class="text-2xl font-bold mb-4">{{ $phong->ten }}</h1>

    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        {{-- Messages --}}
        <div id="chat-messages" class="h-[500px] overflow-y-auto p-4 space-y-3" x-ref="messages">
            @foreach($tinNhans as $tn)
                <div class="flex items-start gap-2">
                    <img src="{{ $tn->nguoiDung->urlAnhDaiDien() }}" alt="{{ $tn->nguoiDung->ten_hien_thi }}"
                         class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
                    <div>
                        <p class="text-xs"><span class="font-semibold">{{ $tn->nguoiDung->ten_hien_thi }}</span> <span class="text-gray-400">{{ $tn->created_at->format('H:i') }}</span></p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5">{{ $tn->noi_dung }}</p>
                    </div>
                </div>
            @endforeach

            <template x-for="msg in newMessages" :key="msg.id">
                <div class="flex items-start gap-2">
                    <img :src="msg.nguoi_dung.url_anh_dai_dien" :alt="msg.nguoi_dung.ten_hien_thi"
                         class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-gray-600 flex-shrink-0">
                    <div>
                        <p class="text-xs"><span class="font-semibold" x-text="msg.nguoi_dung.ten_hien_thi"></span> <span class="text-gray-400" x-text="new Date(msg.created_at).toLocaleTimeString('vi-VN', {hour:'2-digit',minute:'2-digit'})"></span></p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5" x-text="msg.noi_dung"></p>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <form @submit.prevent="sendMessage" class="flex gap-2">
                <input type="text" x-model="messageText" placeholder="Nhập tin nhắn..." maxlength="500"
                       class="flex-1 px-4 py-2.5 text-sm bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500">
                <button type="submit" :disabled="sending" class="px-5 py-2.5 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 transition disabled:opacity-50">
                    Gửi
                </button>
            </form>
            <p x-show="error" x-text="error" class="text-red-500 text-xs mt-2"></p>
        </div>
    </div>
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
                const res = await fetch('{{ route("chat.gui") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ noi_dung: this.messageText })
                });

                const data = await res.json();
                if (res.ok) {
                    this.newMessages.push(data.tin_nhan);
                    this.lastId = data.tin_nhan.id;
                    this.messageText = '';
                    this.$nextTick(() => this.scrollToBottom());
                } else {
                    this.error = data.loi || 'Không thể gửi tin nhắn.';
                }
            } catch (e) {
                this.error = 'Lỗi kết nối.';
            }
            this.sending = false;
        },

        async fetchMessages() {
            try {
                const res = await fetch(`{{ route("chat.tai") }}?last_id=${this.lastId}`);
                const data = await res.json();
                if (data.tin_nhans && data.tin_nhans.length > 0) {
                    this.newMessages.push(...data.tin_nhans);
                    this.lastId = data.tin_nhans[data.tin_nhans.length - 1].id;
                    this.$nextTick(() => this.scrollToBottom());
                }
            } catch (e) {}
        },

        scrollToBottom() {
            const el = this.$refs.messages;
            if (el) el.scrollTop = el.scrollHeight;
        }
    }
}
</script>
@endpush
@endsection
