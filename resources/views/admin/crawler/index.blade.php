@extends('layouts.admin')

@section('title', 'Quản lý Cào Truyện')

@section('content')
<div class="px-6 py-6" x-data="crawlerApp()">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tool Cào Truyện Tự Động</h1>
            <p class="text-gray-500 text-sm mt-1">Sử dụng HTML Dom Crawler để quét cấu trúc gốc (Hỗ trợ Ổn định: TruyenFull).</p>
        </div>
    </div>

    {{-- Form nhập link --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <label class="block text-sm font-semibold mb-2">Dán danh sách Link truyện cần cào (Nguồn: TruyenFull.vision)</label>
        <div class="flex flex-col gap-3">
            <textarea x-model="urlsInput" rows="5" placeholder="Mỗi link nằm trên một dòng. VD:&#10;https://truyenfull.vision/xuyen-thanh-nu-chinh-lam-nong" 
                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 font-mono text-sm"></textarea>
            
            <button @click="startBatch()" :disabled="isFetching" 
                    class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-lg transition disabled:bg-indigo-300 flex items-center justify-center gap-3 shadow-lg shadow-indigo-500/20">
                <template x-if="!isFetching">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </template>
                <template x-if="isFetching">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </template>
                <span x-text="isFetching ? 'ĐANG ĐẨY LÊN HÀNG ĐỢI...' : 'BẮT ĐẦU CÀO TOÀN BỘ DANH SÁCH'"></span>
            </button>
        </div>
        <p x-show="error" class="text-red-500 text-sm mt-3 font-medium bg-red-50 p-3 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span x-text="error"></span>
        </p>
    </div>

    {{-- Bộ giám sát Hàng đợi --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold">Trạng Thái Hệ Thống Chạy Ngầm</h2>
            <div class="flex items-center gap-2">
                <div :class="queueCount > 0 ? 'bg-green-500' : 'bg-gray-400'" class="w-2.5 h-2.5 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium uppercase tracking-wider text-gray-500" x-text="queueCount > 0 ? 'Worker Đang Chạy' : 'Worker Đang Nghỉ'"></span>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-xl border border-indigo-100 dark:border-indigo-800/50">
                <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-400 uppercase">Truyện Đang Chờ</span>
                <div class="text-3xl font-black text-indigo-700 dark:text-indigo-300 mt-1" x-text="queueCount">0</div>
            </div>
            <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-xl border border-red-100 dark:border-red-800/50 relative group">
                <span class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase">Lỗi (Cần Kiểm Tra)</span>
                <div class="text-3xl font-black text-red-700 dark:text-red-300 mt-1" x-text="failedCount">0</div>
                <button x-show="failedCount > 0" @click="clearFailed()" 
                        class="absolute top-2 right-2 p-1.5 bg-red-100 hover:bg-red-200 text-red-600 rounded-md transition shadow-sm" title="Xóa danh sách lỗi">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
            <div class="bg-gray-50 dark:bg-gray-900/20 p-4 rounded-xl border border-gray-100 dark:border-gray-800/50 flex flex-col justify-center">
                <p class="text-[10px] text-gray-500 leading-tight">Mẹo: Bạn có thể tắt trình duyệt đời máy tự cào. Đảm bảo đã chạy lệnh:</p>
                <code class="text-[11px] bg-black text-green-400 p-1.5 rounded mt-2 select-all">php artisan queue:work</code>
            </div>
        </div>
    </div>

    {{-- Log Terminal --}}
    <template x-if="logs.length > 0">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <h2 class="text-lg font-bold mb-4 border-b pb-2">Nhật Ký Phiên Làm Việc (Mất khi F5)</h2>
            
            <div class="mt-2">
                {{-- Log Window --}}
                <div class="mt-2 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden bg-white dark:bg-gray-900 shadow-inner">
                    <div class="bg-gray-100 dark:bg-gray-800 px-4 py-2 border-b dark:border-gray-700 text-sm font-semibold">
                        Giao tiếp với Server (API Logs)
                    </div>
                    <div class="h-48 overflow-y-auto p-4 text-sm font-mono text-gray-600 dark:text-gray-300 space-y-1" id="log-window">
                        <template x-for="(log, i) in logs" :key="i">
                            <div :class="log.type === 'error' ? 'text-red-500' : (log.type === 'success' ? 'text-green-600 dark:text-green-400' : '')">
                                > <span x-text="log.msg"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>

@push('scripts')
<script>
function crawlerApp() {
    return {
        source: 'truyenfull',
        urlsInput: '',
        error: '',
        scannedData: null,
        
        isFetching: false,
        logs: [],
        queueCount: 0,
        failedCount: 0,

        init() {
            this.updateQueueStatus();
            setInterval(() => this.updateQueueStatus(), 5000);
        },

        async updateQueueStatus() {
            try {
                let res = await fetch("{{ route('admin.crawler.queue-status') }}");
                let data = await res.json();
                this.queueCount = data.pending;
                this.failedCount = data.failed;
            } catch (e) {}
        },

        async clearFailed() {
            if (!confirm('Bạn có chắc muốn xóa sạch danh sách lỗi không?')) return;
            try {
                let res = await fetch("{{ route('admin.crawler.clear-failed') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                let data = await res.json();
                if (data.success) {
                    this.updateQueueStatus();
                    alert(data.message);
                }
            } catch (e) {
                alert('Lỗi khi xóa danh sách lỗi.');
            }
        },

        addLog(msg, type = 'info') {
            this.logs.push({msg, type});
            this.$nextTick(() => {
                let el = document.getElementById('log-window');
                if(el) el.scrollTop = el.scrollHeight;
            });
        },

        async startBatch() {
            let urls = this.urlsInput.split('\n').map(u => u.trim()).filter(u => u.length > 0);
            if(urls.length === 0) { this.error = "Vui lòng nhập ít nhất 1 link"; return; }
            
            this.error = '';
            this.logs = [];
            this.isFetching = true;
            this.shouldStop = false;
            
            this.addLog(`Đang gửi danh sách ${urls.length} truyện lên Server...`, 'info');
            
            try {
                let res = await fetch("{{ route('admin.crawler.dispatch-batch') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ urls: urls, source: this.source })
                });
                
                let data = await res.json();
                if(data.error) {
                    this.addLog(`LỖI: ${data.error}`, 'error');
                } else if(data.success) {
                    this.urlsInput = ''; // clear input
                    this.addLog("🎉 THÀNH CÔNG: " + data.message, 'success');
                    this.addLog("🚀 Server Laravel Horizon/Worker đang tự động chạy ngầm toàn bộ danh sách ở Background.", 'success');
                    this.addLog("Bạn có thể tắt trình duyệt hoặc đi lấy danh sách truyện khác dán vào đây tiếp tục!", 'info');
                }
            } catch(e) {
                this.addLog(`Lỗi kết nối khi đẩy list truyện lên Server.`, 'error');
            }
            
            this.isFetching = false;
        },

        // Các hàm bên dưới không còn dùng trực tiếp nữa nhưng giữ lại phòng sự cố
        async fetchChaptersForCurrentNovel() {
            if(!this.scannedData) return;
            
            this.addLog(`Bắt đầu cào Truyện: ${this.scannedData.tieu_de}. Tổng: ${this.scannedData.total_pages} trang.`);
            
            while(this.currentPage <= this.scannedData.total_pages && !this.shouldStop) {
                this.addLog(`--- ĐANG XỬ LÝ TRANG ${this.currentPage} ---`, 'info');
                
                let linksRes = await this.getPageLinks(this.currentPage);
                
                if (!linksRes || !linksRes.success) {
                    this.addLog(`Lỗi xử lý trang ${this.currentPage}. Bỏ qua trang này.`, 'error');
                    this.currentPage++;
                    continue;
                }
                
                let chapterLinks = linksRes.links || [];
                this.totalLinksThisPage = chapterLinks.length;
                this.linksFetchedThisPage = 0;
                
                this.addLog(`Tìm thấy ${chapterLinks.length} link chương.`);

                for (let i = 0; i < chapterLinks.length; i++) {
                    if (this.shouldStop) break;
                    
                    let chapInfo = chapterLinks[i];
                    let fetchRes = await this.readSingleChapter(chapInfo.url, chapInfo.tieu_de);
                    
                    if (fetchRes.success) {
                        if (fetchRes.is_simulated) {
                            this.addLog(`Tải ảo: ${chapInfo.tieu_de} - Bị chặn`, 'error');
                        } else {
                            this.addLog(`Thành công: ${chapInfo.tieu_de}`, 'success');
                        }
                    } else {
                        this.addLog(`Lỗi tải: ${chapInfo.tieu_de} - ${fetchRes.error}`, 'error');
                    }
                    this.linksFetchedThisPage++;
                }
                
                if (!this.shouldStop) {
                    this.percent = Math.round((this.currentPage / this.scannedData.total_pages) * 100);
                    this.currentPage++;
                }
            }
        },
        
        async getPageLinks(pageNum) {
            try {
                let res = await fetch("{{ route('admin.crawler.get-links') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        base_url: this.scannedData.base_url,
                        page: pageNum,
                        source: this.scannedData.source
                    })
                });
                return await res.json();
            } catch(e) {
                return { success: false };
            }
        },

        async readSingleChapter(url, titleRaw) {
            try {
                let res = await fetch("{{ route('admin.crawler.fetch') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ 
                        truyen_id: this.scannedData.truyen_id,
                        tieu_de: titleRaw,
                        url: url,
                        source: this.scannedData.source
                    })
                });
                return await res.json();
            } catch(e) {
                return { success: false, error: "Gãy kết nối mạng" };
            }
        },
        
        stopFetching() {
            this.shouldStop = true;
            this.addLog("⚠️ Người dùng can thiệp, yêu cầu kích hoạt phanh khẩn cấp!! Tiến trình đang dừng...", 'error');
        }
    }
}
</script>
@endpush
@endsection
