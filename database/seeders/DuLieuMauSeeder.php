<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use App\Models\Truyen;
use App\Models\Chuong;
use App\Models\BinhLuan;
use App\Models\LichSuDoc;
use App\Models\PhongChat;
use App\Models\TinNhanChat;
use Illuminate\Database\Seeder;

class DuLieuMauSeeder extends Seeder
{
    public function run(): void
    {
        $nguoiDungs = NguoiDung::where('ten_dang_nhap', '!=', 'admin')->get();
        $truyens = Truyen::all();

        // Yêu thích
        foreach ($nguoiDungs as $nd) {
            $yeuThichIds = $truyens->random(rand(2, 5))->pluck('id');
            foreach ($yeuThichIds as $truyenId) {
                $nd->yeuThich()->syncWithoutDetaching([$truyenId => ['created_at' => now()->subDays(rand(1, 30))]]);
                Truyen::where('id', $truyenId)->increment('tong_luot_yeu_thich');
            }
        }

        // Theo dõi
        foreach ($nguoiDungs as $nd) {
            $theoDoiIds = $truyens->random(rand(2, 4))->pluck('id');
            foreach ($theoDoiIds as $truyenId) {
                $chuong = Chuong::where('truyen_id', $truyenId)->inRandomOrder()->first();
                $nd->theoDoi()->syncWithoutDetaching([
                    $truyenId => [
                        'chuong_cuoi_id' => $chuong?->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                        'updated_at' => now()->subDays(rand(0, 5)),
                    ]
                ]);
                Truyen::where('id', $truyenId)->increment('tong_luot_theo_doi');
            }
        }

        // Lịch sử đọc
        foreach ($nguoiDungs as $nd) {
            $soLanDoc = rand(5, 15);
            for ($i = 0; $i < $soLanDoc; $i++) {
                $truyen = $truyens->random();
                $chuong = Chuong::where('truyen_id', $truyen->id)->inRandomOrder()->first();
                if ($chuong) {
                    LichSuDoc::create([
                        'nguoi_dung_id' => $nd->id,
                        'truyen_id' => $truyen->id,
                        'chuong_id' => $chuong->id,
                        'thoi_diem_doc_cuoi' => now()->subHours(rand(1, 720)),
                    ]);
                }
            }
        }

        // Bình luận
        $binhLuanMau = [
            'Truyện hay quá, đọc mê luôn!',
            'Chương này hấp dẫn thật sự.',
            'Cảm ơn tác giả, viết rất hay.',
            'Mong chờ chương tiếp theo!',
            'Nhân vật chính quá mạnh.',
            'Tình tiết bất ngờ quá!',
            'Đọc đi đọc lại không chán.',
            'Ai đọc đến đây rồi, quá đỉnh!',
            'Tác giả cập nhật chậm quá...',
            'Truyện này xứng đáng 10 điểm.',
        ];

        foreach ($truyens as $truyen) {
            $soBinhLuan = rand(3, 8);
            for ($i = 0; $i < $soBinhLuan; $i++) {
                $chuong = Chuong::where('truyen_id', $truyen->id)->inRandomOrder()->first();
                BinhLuan::create([
                    'nguoi_dung_id' => $nguoiDungs->random()->id,
                    'truyen_id' => $truyen->id,
                    'chuong_id' => $chuong?->id,
                    'noi_dung' => $binhLuanMau[array_rand($binhLuanMau)],
                    'trang_thai' => ['hien_thi', 'hien_thi', 'hien_thi', 'cho_duyet'][rand(0, 3)],
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }

        // Phòng chat và tin nhắn
        $phong = PhongChat::create(['ma' => 'chung', 'ten' => 'Phòng Chat Chung']);

        $chatMau = [
            'Xin chào mọi người!',
            'Hôm nay đọc truyện gì hay vậy?',
            'Truyện mới cập nhật rồi nè.',
            'Có ai online không?',
            'Giới thiệu truyện hay đi.',
            'Chúc mọi người ngày mới vui vẻ!',
            'Mình mới tham gia, xin chào.',
            'Truyện Đấu Phá Thương Khung hay quá!',
        ];

        foreach ($nguoiDungs as $nd) {
            $soTinNhan = rand(1, 4);
            for ($i = 0; $i < $soTinNhan; $i++) {
                TinNhanChat::create([
                    'phong_chat_id' => $phong->id,
                    'nguoi_dung_id' => $nd->id,
                    'noi_dung' => $chatMau[array_rand($chatMau)],
                    'created_at' => now()->subMinutes(rand(1, 1440)),
                ]);
            }
        }
    }
}
