<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Truyen;

class CrawlNovelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120; // 2 phút cho việc quét meta
    public $tries = 2;
    public $maxExceptions = 2;
    public $backoff = [30, 60];
    
    protected $url;
    protected $source;

    protected $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    ];

    public function __construct($url, $source)
    {
        $url = rtrim($url, '/');
        // Loại bỏ hậu tố trang nếu người dùng dán vào link đang ở trang X (ví dụ: /trang-2)
        $url = preg_replace('/\/trang-\d+$/i', '', $url);
        
        $this->url = $url;
        $this->source = $source;
    }

    public function handle(): void
    {
        Log::info("CrawlNovelJob: Bắt đầu quét thông tin chính: " . $this->url);
        
        $scannedData = $this->scanNovel();
        if (!$scannedData) {
            Log::error("CrawlNovelJob: Không thể quét được thông tin truyện: " . $this->url);
            return;
        }

        Log::info("CrawlNovelJob: Quét thành công '{$scannedData['tieu_de']}'. Dispatching {$scannedData['total_pages']} page jobs.");
        
        // Dispatch các job con cho từng trang
        for ($page = 1; $page <= $scannedData['total_pages']; $page++) {
            CrawlNovelPageJob::dispatch(
                $scannedData['truyen_id'],
                $scannedData['base_url'],
                $this->source,
                $page
            );
        }
    }

    protected function scanNovel()
    {
        try {
            sleep(1);
            $response = Http::withHeaders($this->headers)
                ->connectTimeout(5)
                ->timeout(15)
                ->retry(2, 2000)
                ->get($this->url);
            if (!$response->successful()) return null;

            $html = $response->body();
            $crawler = new Crawler($html);

            $tenTruyen = "Truyện tự động lấy (" . time() . ")";
            $tacGia = "Đang cập nhật";
            $moTa = "";
            $anhBiaUrl = "";
            $totalPages = 1;
            $theLoaiNames = [];

            if ($this->source === 'truyenfull') {
                if ($crawler->filter('h3.title')->count() > 0) $tenTruyen = trim($crawler->filter('h3.title')->first()->text());
                if ($crawler->filter('a[itemprop="author"]')->count() > 0) $tacGia = trim($crawler->filter('a[itemprop="author"]')->first()->text());
                if ($crawler->filter('div.desc-text')->count() > 0) $moTa = trim($crawler->filter('div.desc-text')->first()->html());
                
                if ($crawler->filter('.info-holder .image img')->count() > 0) {
                    $anhBiaUrl = $crawler->filter('.info-holder .image img')->first()->attr('src');
                } elseif ($crawler->filter('.book img')->count() > 0) {
                    $anhBiaUrl = $crawler->filter('.book img')->first()->attr('src');
                }

                if ($crawler->filter('.info a[itemprop="genre"]')->count() > 0) {
                    $crawler->filter('.info a[itemprop="genre"]')->each(function ($node) use (&$theLoaiNames) {
                        $theLoaiNames[] = trim($node->text());
                    });
                }

                if ($crawler->filter('ul.pagination li a')->count() > 0) {
                    $maxPage = 1;
                    $hrefs = $crawler->filter('ul.pagination li a')->extract(['href']);
                    foreach ($hrefs as $href) {
                        if (preg_match('/trang-(\d+)/i', $href, $matches) && (int)$matches[1] > $maxPage) {
                            $maxPage = (int)$matches[1];
                        }
                    }
                    $totalPages = $maxPage;
                }
            }

            $coverPath = null;
            if (!empty($anhBiaUrl)) {
                try {
                    $imgContent = Http::connectTimeout(5)->timeout(10)->get($anhBiaUrl)->body();
                    $filename = 'covers/crawled_' . time() . '_' . Str::random(5) . '.jpg';
                    Storage::disk('public')->put($filename, $imgContent);
                    $coverPath = $filename;
                } catch (\Exception $e) {}
            }

            // Xử lý mô tả
            $moTa = str_replace(['<br>', '<br/>', '<br />'], "\n", $moTa);
            $moTa = preg_replace('/<(p|div|tr|h\d)[^>]*>/i', "\n", $moTa);
            $moTa = html_entity_decode($moTa, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $moTa = str_replace("\xc2\xa0", ' ', $moTa);
            $lines = explode("\n", $moTa);
            $cleanLines = [];
            $unwanted = ['Tác giả:', 'Thể loại:', 'Nguồn:', 'Trạng thái:', 'Lượt xem:', 'Cập nhật:'];
            foreach ($lines as $line) {
                $line = trim(strip_tags($line));
                if (empty($line)) continue;
                $isJunk = false;
                foreach ($unwanted as $kw) { if (Str::startsWith($line, $kw)) { $isJunk = true; break; } }
                if (!$isJunk) $cleanLines[] = $line;
            }
            $moTaClean = implode("\n\n", $cleanLines);

            $truyen = Truyen::firstOrCreate(
                ['tieu_de' => $tenTruyen],
                [
                    'slug' => Str::slug($tenTruyen) . '-' . time(),
                    'tac_gia' => $tacGia,
                    'mo_ta_ngan' => Str::limit($moTaClean, 200),
                    'mo_ta_day_du' => $moTaClean,
                    'anh_bia' => $coverPath,
                    'trang_thai' => 'dang_ra',
                    'tong_luot_xem' => 0,
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );

            if (!empty($theLoaiNames)) {
                $theLoaiIds = [];
                foreach ($theLoaiNames as $name) {
                    $name = trim($name);
                    if (empty($name)) continue;
                    $tl = \App\Models\TheLoai::firstOrCreate(
                        ['ten' => $name],
                        ['slug' => Str::slug($name), 'mo_ta' => '']
                    );
                    $theLoaiIds[] = $tl->id;
                }
                if (!empty($theLoaiIds)) {
                    $truyen->theLoai()->syncWithoutDetaching($theLoaiIds);
                }
            }

            return [
                'truyen_id' => $truyen->id,
                'tieu_de' => $truyen->tieu_de,
                'total_pages' => $totalPages,
                'base_url' => $this->url
            ];

        } catch (\Exception $e) {
            Log::error("Lỗi scan novel meta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Xử lý khi job thất bại hoàn toàn.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("CrawlNovelJob FAILED: URL {$this->url}. Lỗi: " . $exception->getMessage());
    }
}
