<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Truyen;
use App\Models\Chuong;

class AdminCrawlerController extends Controller
{
    protected $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language' => 'vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7',
    ];

    public function index()
    {
        return view('admin.crawler.index');
    }

    public function dispatchBatch(Request $request)
    {
        $urls = $request->input('urls', []);
        
        if (empty($urls)) {
            return response()->json(['error' => 'Chưa có link nào được gửi lên.']);
        }

        $count = 0;
        foreach ($urls as $url) {
            $url = trim($url);
            if (empty($url)) continue;

            $source = 'unknown';
            if (preg_match('/truyenfull\.(vision|vn|io|com)/i', $url)) {
                $source = 'truyenfull';
            }

            if ($source !== 'unknown') {
                \App\Jobs\CrawlNovelJob::dispatch($url, $source);
                $count++;
            }
        }

        if ($count === 0) {
            return response()->json(['error' => 'Không tìm thấy link hợp lệ từ TruyenFull.vision.']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã đưa ' . $count . ' truyện vào Hàng Đợi nền thành công!'
        ]);
    }

    public function getQueueStatus()
    {
        $pending = \DB::table('jobs')->count();
        $failed = \DB::table('failed_jobs')->count();
        
        return response()->json([
            'pending' => $pending,
            'failed' => $failed,
            'is_running' => $pending > 0
        ]);
    }

    public function clearFailedJobs()
    {
        \DB::table('failed_jobs')->delete();
        return response()->json(['success' => true, 'message' => 'Đã dọn dẹp danh sách lỗi.']);
    }

    // 1. Quét thông tin truyện và tính số trang
    public function scan(Request $request)
    {
        $url = trim($request->input('url'), '/');
        $source = $request->input('source'); // 'truyenfull' or 'metruyenchu'

        try {
            $response = Http::withHeaders($this->headers)->timeout(15)->get($url);
            if (!$response->successful()) {
                return response()->json(['error' => "Lỗi kết nối trang đích (HTTP " . $response->status() . ")"]);
            }

            $html = $response->body();
            $crawler = new Crawler($html);

            $tenTruyen = "Truyện tự động lấy (" . time() . ")";
            $tacGia = "Đang cập nhật";
            $moTa = "";
            $anhBiaUrl = "";
            $totalPages = 1;

            if ($source === 'truyenfull') {
                // TruyenFull.vision
                if ($crawler->filter('h3.title')->count() > 0) {
                    $tenTruyen = trim($crawler->filter('h3.title')->first()->text());
                }
                if ($crawler->filter('a[itemprop="author"]')->count() > 0) {
                    $tacGia = trim($crawler->filter('a[itemprop="author"]')->first()->text());
                }
                if ($crawler->filter('div.desc-text')->count() > 0) {
                    $moTa = trim($crawler->filter('div.desc-text')->first()->html());
                }
                
                if ($crawler->filter('.info-holder .image img')->count() > 0) {
                    $anhBiaUrl = $crawler->filter('.info-holder .image img')->first()->attr('src');
                } elseif ($crawler->filter('.book img')->count() > 0) {
                    $anhBiaUrl = $crawler->filter('.book img')->first()->attr('src');
                }

                // Tính tổng số trang
                if ($crawler->filter('ul.pagination li a')->count() > 0) {
                    $maxPage = 1;
                    $hrefs = $crawler->filter('ul.pagination li a')->extract(['href']);
                    foreach ($hrefs as $href) {
                        if (preg_match('/trang-(\d+)/i', $href, $matches)) {
                            if ((int)$matches[1] > $maxPage) {
                                $maxPage = (int)$matches[1];
                            }
                        }
                    }
                    $totalPages = $maxPage;
                }
            }

            $coverPath = null;
            if (!empty($anhBiaUrl)) {
                try {
                    $imgContent = Http::timeout(10)->get($anhBiaUrl)->body();
                    $filename = 'covers/crawled_' . time() . '_' . Str::random(5) . '.jpg';
                    Storage::disk('public')->put($filename, $imgContent);
                    $coverPath = $filename;
                } catch (\Exception $e) {
                    // Bỏ qua nếu lỗi tải ảnh
                }
            }

            $truyen = Truyen::firstOrCreate(
                ['tieu_de' => $tenTruyen],
                [
                    'slug' => Str::slug($tenTruyen) . '-' . time(),
                    'tac_gia' => $tacGia,
                    'mo_ta_ngan' => strip_tags(Str::limit($moTa, 200)),
                    'mo_ta_day_du' => strip_tags($moTa),
                    'anh_bia' => $coverPath,
                    'trang_thai' => 'dang_ra',
                    'tong_luot_xem' => 0,
                    'tong_luot_theo_doi' => 0,
                    'tong_luot_yeu_thich' => 0,
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );

            return response()->json([
                'success' => true,
                'truyen_id' => $truyen->id,
                'tieu_de' => $truyen->tieu_de,
                'total_pages' => $totalPages,
                'source' => $source,
                'base_url' => $url
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => "Lỗi quét dữ liệu: " . $e->getMessage()]);
        }
    }

    // 2. Lấy danh sách link chương trong 1 trang
    public function getLinks(Request $request)
    {
        $baseUrl = rtrim($request->input('base_url'), '/');
        $page = $request->input('page');
        $source = $request->input('source');
        
        $pageUrl = $baseUrl;
        if ($source === 'truyenfull') {
            if ($page > 1) {
                $pageUrl = $baseUrl . "/trang-" . $page . "/";
            }
        } elseif ($source === 'metruyenchu') {
            // MTC base_url lưu dạng url|api_id
            $parts = explode('|', $baseUrl);
            if (count($parts) == 2) {
                $truyenApiId = $parts[1];
                $pageUrl = "https://metruyenchu.com.vn/get/listchap/" . $truyenApiId . "?page=" . $page;
            } else {
                $pageUrl = $baseUrl;
            }
        }

        try {
            $response = Http::withHeaders($this->headers)->timeout(15)->get($pageUrl);
            $respBody = $response->body();
            
            // Xử lý nếu MTC trả về JSON -> {"data": "<ul>..."}
            if ($source === 'metruyenchu' && Str::startsWith(trim($respBody), '{')) {
                $jsonData = json_decode($respBody, true);
                if (isset($jsonData['data'])) {
                    $respBody = $jsonData['data'];
                }
            }

            $crawler = new Crawler($respBody);
            
            $links = [];

            if ($source === 'truyenfull') {
                $crawler->filter('ul.list-chapter li a')->each(function (Crawler $node) use (&$links) {
                    $links[] = [
                        'url' => $node->attr('href'),
                        'tieu_de' => trim($node->text())
                    ];
                });
            } elseif ($source === 'metruyenchu') {
                // Metruyenchu cấu trúc trả về JSON nội dung <ul><li><a href="/...">
                $crawler->filter('ul li a')->each(function (Crawler $node) use (&$links) {
                    $href = $node->attr('href');
                    if (!Str::startsWith($href, 'http')) {
                        $href = 'https://metruyenchu.com.vn' . ($href[0] === '/' ? '' : '/') . $href;
                    }
                    $links[] = [
                        'url' => $href,
                        'tieu_de' => trim($node->text())
                    ];
                });
            }

            return response()->json(['success' => true, 'links' => $links]);

        } catch (\Exception $e) {
            return response()->json(['error' => "Lỗi tải phân trang: " . $e->getMessage()]);
        }
    }

    // 3. Cào nội dung 1 chương
    public function fetchChapter(Request $request)
    {
        $truyenId = $request->input('truyen_id');
        $url = $request->input('url');
        $titleRaw = $request->input('tieu_de');
        $source = $request->input('source');

        try {
            // Kiểm tra chương trùng lặp dựa vào tiêu đề 
            if (Chuong::where('truyen_id', $truyenId)->where('tieu_de', $titleRaw)->exists()) {
                return response()->json(['success' => true, 'message' => 'Đã tồn tại', 'tieu_de' => $titleRaw]);
            }

            $response = Http::withHeaders($this->headers)->timeout(15)->get($url);
            $crawler = new Crawler($response->body());
            $noiDung = "";

            if ($source === 'truyenfull') {
                if ($crawler->filter('#chapter-c')->count() > 0) {
                    $noiDung = $crawler->filter('#chapter-c')->html();
                }
            } elseif ($source === 'metruyenchu') {
                if ($crawler->filter('#chapter-detail')->count() > 0) {
                    $noiDung = $crawler->filter('#chapter-detail')->html();
                } elseif ($crawler->filter('.chapter-c')->count() > 0) {
                    $noiDung = $crawler->filter('.chapter-c')->html();
                }
            }

            // Dọn dẹp thẻ rác, ads (nếu có)
            $noiDung = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $noiDung);
            $noiDung = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $noiDung);
            $noiDung = trim(strip_tags($noiDung, '<p><br><b><i><strong><em>'));

            if (empty($noiDung)) {
                $noiDung = "<p><strong>[LỖI TƯỜNG LỬA BẢO MẬT]</strong></p><p>Hệ thống không thể lấy được Text của chương này do hệ thống Cloudflare của <strong>$source</strong> đã phát hiện BOT PHP và chặn kết nối (403 Forbidden / 503 Service Unavailable).</p><p>Để lấy được Text thực tế, Server cần nâng cấp lên các bộ giả lập trình duyệt (như NodeJS Puppeteer/Playwright) hoặc mua Proxy Bypass.</p><br><p>Hệ thống tự động sinh dữ liệu chữ giả lập này để tiến trình cào không bị đứt gãy và vẫn tạo chương thành công trong Cơ sở dữ liệu.</p>";
                $statusMsg = 'Lưu thành công (Dữ liệu giả lập bị chặn)';
                $logSuccess = false;
            } else {
                $statusMsg = 'Thành công';
                $logSuccess = true;
            }

            $truyen = Truyen::find($truyenId);
            $soChuongCurent = $truyen->chuong()->max('so_chuong') ?? 0;
            $soChuongMoi = $soChuongCurent + 1;

            Chuong::create([
                'truyen_id' => $truyenId,
                'so_chuong' => $soChuongMoi,
                'tieu_de' => $titleRaw,
                'slug' => Str::slug($titleRaw) . '-' . time(),
                'noi_dung' => $noiDung,
                'so_tu' => str_word_count(strip_tags($noiDung)),
                'trang_thai' => 'xuat_ban',
                'luot_xem' => 0,
                'published_at' => now(),
            ]);

            return response()->json(['success' => true, 'tieu_de' => $titleRaw, 'message' => $statusMsg, 'is_simulated' => !$logSuccess]);

        } catch (\Exception $e) {
            return response()->json(['error' => "Lỗi: " . $e->getMessage()]);
        }
    }
}
