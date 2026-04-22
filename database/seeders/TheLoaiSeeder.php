<?php

namespace Database\Seeders;

use App\Models\TheLoai;
use Illuminate\Database\Seeder;

class TheLoaiSeeder extends Seeder
{
    public function run(): void
    {
        $theLoais = [
            ['ten' => 'Tiên Hiệp', 'slug' => 'tien-hiep', 'mo_ta' => 'Truyện về tu tiên, thế giới tiên hiệp', 'thu_tu' => 1],
            ['ten' => 'Kiếm Hiệp', 'slug' => 'kiem-hiep', 'mo_ta' => 'Truyện kiếm hiệp, giang hồ, võ lâm', 'thu_tu' => 2],
            ['ten' => 'Huyền Huyễn', 'slug' => 'huyen-huyen', 'mo_ta' => 'Truyện huyền huyễn, thần thoại, phép thuật', 'thu_tu' => 3],
            ['ten' => 'Đô Thị', 'slug' => 'do-thi', 'mo_ta' => 'Truyện đô thị hiện đại, đời thường', 'thu_tu' => 4],
            ['ten' => 'Ngôn Tình', 'slug' => 'ngon-tinh', 'mo_ta' => 'Truyện tình cảm, lãng mạn', 'thu_tu' => 5],
            ['ten' => 'Xuyên Không', 'slug' => 'xuyen-khong', 'mo_ta' => 'Truyện xuyên không, chuyển sinh', 'thu_tu' => 6],
            ['ten' => 'Khoa Huyễn', 'slug' => 'khoa-huyen', 'mo_ta' => 'Truyện khoa học viễn tưởng', 'thu_tu' => 7],
            ['ten' => 'Trọng Sinh', 'slug' => 'trong-sinh', 'mo_ta' => 'Truyện trọng sinh, sống lại', 'thu_tu' => 8],
            ['ten' => 'Lịch Sử', 'slug' => 'lich-su', 'mo_ta' => 'Truyện lịch sử, cổ đại', 'thu_tu' => 9],
            ['ten' => 'Quân Sự', 'slug' => 'quan-su', 'mo_ta' => 'Truyện quân sự, chiến tranh', 'thu_tu' => 10],
            ['ten' => 'Trinh Thám', 'slug' => 'trinh-tham', 'mo_ta' => 'Truyện trinh thám, phá án', 'thu_tu' => 11],
            ['ten' => 'Kinh Dị', 'slug' => 'kinh-di', 'mo_ta' => 'Truyện kinh dị, ma quỷ, rùng rợn', 'thu_tu' => 12],
            ['ten' => 'Hài Hước', 'slug' => 'hai-huoc', 'mo_ta' => 'Truyện hài hước, giải trí', 'thu_tu' => 13],
            ['ten' => 'Đam Mỹ', 'slug' => 'dam-my', 'mo_ta' => 'Truyện đam mỹ', 'thu_tu' => 14],
            ['ten' => 'Hệ Thống', 'slug' => 'he-thong', 'mo_ta' => 'Truyện có hệ thống, game', 'thu_tu' => 15],
        ];

        foreach ($theLoais as $tl) {
            TheLoai::create($tl);
        }
    }
}
