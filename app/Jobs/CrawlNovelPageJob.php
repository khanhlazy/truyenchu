<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\MaxAttemptsExceededException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Chuong;

// Ngưỡng lỗi tối đa trước khi bỏ qua truyện
const CRAWL_ERROR_THRESHOLD = 15;

class CrawlNovelPageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120; // 2 phút cho mỗi trang (đủ để xử lý ~20 chương)
    public $tries = 2;
    public $maxExceptions = 2; // Fail nhanh nếu gặp lỗi liên tục
    public $backoff = [30, 60]; // Chờ 30s rồi 60s giữa các lần retry

    protected $truyenId;
    protected $baseUrl;
    protected $source;
    protected $page;

    protected $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ];

    public function __construct($truyenId, $baseUrl, $source, $page)
    {
        $this->truyenId = $truyenId;
        $this->baseUrl = $baseUrl;
        $this->source = $source;
        $this->page = $page;
    }

    public function handle(): void
    {
        // Kiểm tra truyện đã bị đánh dấu skip chưa
        $skipKey = "crawl_skip:{$this->truyenId}";
        if (Cache::has($skipKey)) {
            Log::warning("SKIP: Truyện ID {$this->truyenId} đã bị bỏ qua do lỗi quá nhiều. Trang {$this->page} bị hủy.");
            return;
        }

        $startTime = time();
        $timeBudget = (int)($this->timeout * 0.8);
        $processed = 0;
        $skipped = 0;
        $errors = 0;

        Log::info("CrawlNovelPageJob: Trang {$this->page} của truyện ID {$this->truyenId} (budget: {$timeBudget}s)");
        
        $links = $this->getPageLinks($this->baseUrl, $this->page);
        
        if (empty($links)) {
            Log::warning("CrawlNovelPageJob: Trang {$this->page} không có link chương.");
            return;
        }

        foreach ($links as $link) {
            // Kiểm tra nếu truyện bị skip giữa chừng (do job khác đánh dấu)
            if (Cache::has($skipKey)) {
                Log::warning("SKIP: Truyện ID {$this->truyenId} bị skip giữa chừng trang {$this->page}.");
                return;
            }

            // Kiểm tra time budget
            $elapsed = time() - $startTime;
            if ($elapsed >= $timeBudget) {
                $remaining = count($links) - $processed - $skipped - $errors;
                Log::warning("CrawlNovelPageJob: Hết time budget ({$elapsed}s/{$timeBudget}s) tại trang {$this->page}. Còn {$remaining} chương chưa xử lý. Sẽ re-dispatch.");
                self::dispatch($this->truyenId, $this->baseUrl, $this->source, $this->page)
                    ->delay(now()->addSeconds(30));
                return;
            }

            // Kiểm tra tồn tại
            if (Chuong::where('truyen_id', $this->truyenId)->where('tieu_de', $link['tieu_de'])->exists()) {
                $skipped++;
                continue;
            }

            $success = $this->fetchChapter($this->truyenId, $link['url'], $link['tieu_de']);
            if ($success) {
                $processed++;
            } else {
                $errors++;
            }
        }

        $elapsed = time() - $startTime;
        Log::info("CrawlNovelPageJob: Hoàn thành trang {$this->page}. OK: {$processed}, skip: {$skipped}, lỗi: {$errors}, thời gian: {$elapsed}s");
    }

    protected function getPageLinks($baseUrl, $page)
    {
        $links = [];
        try {
            if ($this->source === 'truyenfull') {
                $pageUrl = ($page > 1) ? rtrim($baseUrl, '/') . "/trang-" . $page . "/" : $baseUrl;
            } else {
                $pageUrl = $baseUrl . "/?page=" . $page;
            }

            $response = Http::withHeaders($this->headers)
                ->connectTimeout(5)
                ->timeout(10)
                ->retry(2, 1000)
                ->get($pageUrl);
            if (!$response->successful()) {
                Log::warning("Bị chặn khi tải danh sách: " . $pageUrl);
                return [];
            }

            $crawler = new Crawler($response->body());

            if ($this->source === 'truyenfull') {
                $crawler->filter('ul.list-chapter li a')->each(function (Crawler $node) use (&$links) {
                    $links[] = [
                        'tieu_de' => trim($node->text()),
                        'url' => $node->attr('href')
                    ];
                });
            }
        } catch (\Exception $e) {
            Log::error("Lỗi lấy danh sách chương: " . $e->getMessage());
        }

        return $links;
    }

    /**
     * Fetch và lưu 1 chương. Trả về true nếu thành công, false nếu lỗi.
     */
    protected function fetchChapter($truyenId, $url, $tieuDe): bool
    {
        $errorKey = "crawl_errors:{$this->truyenId}";
        $skipKey = "crawl_skip:{$this->truyenId}";

        try {
            usleep(rand(500000, 1500000));
            $response = Http::withHeaders($this->headers)
                ->connectTimeout(5)
                ->timeout(10)
                ->retry(2, 2000)
                ->get($url);
            
            if (!$response->successful()) {
                Log::warning("DIAG: Bị chặn ({$response->status()}): {$url}");
                $this->trackError($errorKey, $skipKey, $url);
                return false;
            }

            $crawler = new Crawler($response->body());
            $noiDung = "";

            if ($this->source === 'truyenfull') {
                $count = $crawler->filter('.chapter-c')->count();
                if ($count > 0) {
                    $noiDung = $crawler->filter('.chapter-c')->first()->html();
                } else {
                    Log::warning("DIAG: Không tìm thấy .chapter-c tại: {$url}");
                    $this->trackError($errorKey, $skipKey, $url);
                    return false;
                }
            }

            $rawLen = strlen($noiDung);

            // Làm sạch nội dung
            $noiDung = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $noiDung);
            $noiDung = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $noiDung);
            $noiDung = preg_replace('/<div[^>]*>/i', '<p>', $noiDung);
            $noiDung = str_ireplace('</div>', '</p>', $noiDung);
            $noiDung = preg_replace('/<(p|span)\s+[^>]*>/i', '<$1>', $noiDung);
            $noiDung = html_entity_decode($noiDung, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $noiDung = str_replace("\xc2\xa0", ' ', $noiDung);
            $noiDung = trim(strip_tags($noiDung, '<p><br><b><i><strong><em>'));
            $noiDung = preg_replace('/(<p>\s*<\/p>)+/i', '', $noiDung);
            $noiDung = preg_replace('/\n+/', ' ', $noiDung);

            if (strpos($noiDung, '<p>') === false) {
                $paragraphs = explode("\n", str_replace(["\r\n", "\r"], "\n", $noiDung));
                $noiDung = '';
                foreach ($paragraphs as $p) {
                    $p = trim($p);
                    if ($p !== '') $noiDung .= "<p>{$p}</p>";
                }
            }

            if (empty($noiDung)) {
                Log::warning("DIAG: Nội dung rỗng sau cleaning (raw={$rawLen}): {$url}");
                $this->trackError($errorKey, $skipKey, $url);
                return false;
            }

            $soChuong = 0;
            if (preg_match('/(?:Chương|Ch\.|Quyển\s+\d+\s+-\s+Chương)\s+([0-9\.]+)/i', $tieuDe, $matches)) {
                $soChuong = (float)$matches[1];
            } elseif (preg_match('/(\d+)/', $tieuDe, $matches)) {
                $soChuong = (float)$matches[1];
            }

            $chuong = Chuong::create([
                'truyen_id' => $this->truyenId,
                'so_chuong' => $soChuong,
                'tieu_de' => $tieuDe,
                'slug' => Str::slug($tieuDe) . '-' . time(),
                'noi_dung' => $noiDung,
                'is_published' => true,
                'published_at' => now(),
            ]);

            // Cập nhật thời điểm chương mới nhất cho truyện
            $chuong->truyen->capNhatThoiDiemChuong();

            // Thành công → reset bộ đếm lỗi
            Cache::forget($errorKey);

            return true;

        } catch (\Exception $e) {
            Log::error("DIAG: Lỗi ({$url}): " . $e->getMessage());
            $this->trackError($errorKey, $skipKey, $url);
            return false;
        }
    }

    /**
     * Đếm lỗi liên tục. Khi vượt ngưỡng → đánh dấu skip truyện vĩnh viễn.
     */
    protected function trackError(string $errorKey, string $skipKey, string $url): void
    {
        $count = Cache::increment($errorKey);
        Cache::put($errorKey, $count, now()->addMinutes(30));

        if ($count >= CRAWL_ERROR_THRESHOLD) {
            // Skip vĩnh viễn
            Cache::forever($skipKey, true);
            Log::error("AUTO-SKIP: Truyện ID {$this->truyenId} bị skip VĨNH VIỄN do lỗi liên tục ({$count} lần). URL cuối: {$url}");
        }
    }

    /**
     * Xử lý khi job thất bại hoàn toàn.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CrawlNovelPageJob FAILED: Trang {$this->page} của truyện ID {$this->truyenId}. Lỗi: " . $exception->getMessage());
    }
}
