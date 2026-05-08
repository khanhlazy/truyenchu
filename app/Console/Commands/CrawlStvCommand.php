<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Truyen;
use App\Models\Chuong;

class CrawlStvCommand extends Command
{
    protected $signature = 'crawler:stv {url : Link truyện trên sangtacviet.com}';
    protected $description = 'Cào truyện từ SangTacViet.com (Hỗ trợ: biquge, qidian, uukanshu, faloo...)';

    protected $headers = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
        'Accept-Language' => 'vi-VN,vi;q=0.9,en-US;q=0.8,en;q=0.7',
        'Referer' => 'https://sangtacviet.com/',
    ];

    public function handle()
    {
        $url = $this->argument('url');
        $this->info("Đang phân tích link: $url");

        // Format link: https://sangtacviet.com/truyen/host/book_id/
        // Hoặc: https://sangtacviet.com/truyen/host/1/book_id/
        $hostStr = '';
        $bookId = '';
        $path = str_replace(['https://sangtacviet.com/truyen/', 'http://sangtacviet.com/truyen/'], '', $url);
        $parts = explode('/', trim($path, '/'));

        if (count($parts) < 2) {
            $this->error('Link không đúng định dạng. Ví dụ: https://sangtacviet.com/truyen/qidian/1/12345/');
            return 1;
        }

        $hostStr = $parts[0];
        $bookId = $parts[count($parts) - 1]; // Phần tử cuối cùng là ID.

        $this->info("Host nguồn: $hostStr | ID Truyện: $bookId");
        
        $this->warn("\nLƯU Ý: Tool đang gọi API SangTacViet. Có thể bị chặn bởi Cloudflare nếu gọi quá nhanh.");
        $this->info("Bat đầu lấy thông tin truyện (Book Info)...");

        // API của STV thường là GET request index.php
        $params = [
            'sajax' => 'getbookinfo',
            'host' => $hostStr,
            'id' => $bookId,
            'bookid' => $bookId,
            'nguon' => isset($parts[1]) && is_numeric($parts[1]) ? $parts[1] : 1, // Tham số phụ
        ];

        try {
            $response = Http::withHeaders($this->headers)
                ->timeout(15)
                ->get('https://sangtacviet.com/index.php', $params);

            if (!$response->successful()) {
                $this->error("Không thể kết nối đến SangTacViet (Status: " . $response->status() . ")");
                return 1;
            }

            $html = $response->body();
            
            $tenTruyen = null;

            // Xử lý chuỗi JSON nếu STV trả về JSON string
            if (Str::startsWith(trim($html), '{') || Str::startsWith(trim($html), '[')) {
                $data = json_decode($html, true);
                $tenTruyen = trim((string) ($data['name'] ?? '')) ?: null;
            }

            if (!$tenTruyen) {
                $this->error('Không đọc được tên truyện từ nguồn. Hủy để tránh tạo nội dung không xác thực.');
                return 1;
            }

            $this->info("=> Đã tìm thấy truyện: $tenTruyen");
            
            // Cài đặt tạm thông tin
            $tacGia = 'Đang cập nhật';
            $moTa = "Truyện được lấy tự động từ SangTacViet. Nguồn: $hostStr.";
            
            // Kiểm tra xem truyện đã tồn tại chưa
            $truyen = Truyen::where('tieu_de', $tenTruyen)->first();
            
            if (!$truyen) {
                if ($this->confirm("Bạn có muốn tạo mới truyện [$tenTruyen] và bắt đầu cào toàn bộ chương không?", true)) {
                    $slug = Str::slug($tenTruyen) . '-' . time();
                    $truyen = Truyen::create([
                        'tieu_de' => $tenTruyen,
                        'slug' => Str::slug($tenTruyen),
                        'tac_gia' => $tacGia,
                        'mo_ta_ngan' => $moTa,
                        'mo_ta_day_du' => $moTa,
                        'trang_thai' => 'dang_ra',
                        'tong_luot_xem' => 0,
                    ]);
                    $this->info("Đã tạo mới truyện trong CSDL!");
                } else {
                    $this->info("Đã hủy.");
                    return 0;
                }
            } else {
                $this->info("Truyện này đã có trong hệ thống, tự động bổ sung thêm chương mới...");
            }

            // Giai đoạn 2: Lấy danh sách chương
            $this->info("\n--- Bắt đầu lấy Danh Sách Chương ---");
            $paramsList = [
                'sajax' => 'getchapterlist',
                'host' => $hostStr,
                'id' => $bookId,
            ];

            $this->warn("Đang liên hệ API lấy danh sách... (Mất khoảng 2-5 giây)");
            $resList = Http::withHeaders($this->headers)->timeout(30)->get('https://sangtacviet.com/index.php', $paramsList);
            
            if (!$resList->successful()) {
                $this->error("Lỗi lấy danh sách chương!");
                return 1;
            }

            // Xử lý chuỗi JSON hoặc mạo danh JSON
            $listData = $resList->body();
            // Trong thực tế, chuỗi này có thể được nhúng bên trong JS function hoặc raw Text. 
            // Ở đây áp dụng Regex để bắt tất cả các thẻ hở chương <a href="..."> / JSON
            
            // Nếu không thể parse, ta dùng regex tìm pattern id chương.
            preg_match_all('/"chapid"\s*:\s*"?(\d+)"?|"id"\s*:\s*"?(\d+)"?/i', $listData, $matches);
            
            $danhSachChapIds = array_filter(array_merge($matches[1], $matches[2]));
            $danhSachChapIds = array_unique($danhSachChapIds);

            if (empty($danhSachChapIds)) {
                $this->error("Không tìm thấy chương nào trong phản hồi từ STV. API có thể đã thay đổi hoặc web chặn IP.");
                return 1;
            } else {
                $this->info("Thành công! Tìm thấy " . count($danhSachChapIds) . " chương.");
            }

            // Giai đoạn 3: Cào từng chương
            $bar = $this->output->createProgressBar(count($danhSachChapIds));
            $bar->start();

            $soChuongCurent = $truyen->chuong()->max('so_chuong') ?? 0;

            foreach ($danhSachChapIds as $chapId) {
                // Tránh lỗi do STV block IP => Delay 1s đến 2s ngẫu nhiên
                sleep(rand(1, 2));

                $chuongDaTonTai = Chuong::where('truyen_id', $truyen->id)->where('tieu_de', 'like', "%Chương " . ($soChuongCurent + 1) . "%")->exists();
                
                if ($chuongDaTonTai) {
                    $soChuongCurent++;
                    $bar->advance();
                    continue;
                }

                $noiDung = "";
                $tieuDeChuong = "";

                // Gọi API đọc nội dung thật
                $paramsRead = [
                    'sajax' => 'readchapter',
                    'host' => $hostStr,
                    'id' => $bookId,
                    'chap' => $chapId,
                ];
                $resRead = Http::withHeaders($this->headers)->timeout(15)->get('https://sangtacviet.com/index.php', $paramsRead);

                if ($resRead->successful()) {
                    $htmlChuong = $resRead->body();
                    $noiDung = trim(strip_tags($htmlChuong));
                    $tieuDeChuong = "Chương " . ($soChuongCurent + 1);
                } else {
                    $bar->advance();
                    continue;
                }

                if ($noiDung === '') {
                    $bar->advance();
                    continue;
                }

                $soChuongCurent++;
                $chuongSlug = Str::slug($tieuDeChuong) . "-$chapId-" . time();

                Chuong::create([
                    'truyen_id' => $truyen->id,
                    'so_chuong' => $soChuongCurent,
                    'tieu_de' => $tieuDeChuong,
                    'slug' => $chuongSlug,
                    'noi_dung' => $noiDung,
                    'so_tu' => str_word_count(strip_tags($noiDung)),
                    'trang_thai' => 'xuat_ban',
                    'luot_xem' => 0,
                    'published_at' => now(),
                ]);

                $bar->advance();
            }

            $bar->finish();
            $this->info("\n\n✅ Hoàn tất! Đã cào và lưu các chương thành công.");

        } catch (\Exception $e) {
            $this->error("\nĐã xảy ra lỗi: " . $e->getMessage());
            Log::error("Scraper Error: " . $e->getMessage());
        }

        return 0;
    }
}
