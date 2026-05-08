<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DuLieuMauSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create('vi_VN');

        // 1. Seed Thể Loại
        $categories = [
            'Tiên Hiệp', 'Kiếm Hiệp', 'Ngôn Tình', 'Đô Thị', 'Khoa Huyễn', 
            'Võng Du', 'Dị Giới', 'Dị Năng', 'Huyền Huyễn', 'Xuyên Không'
        ];

        $categoryIds = [];
        $thuTu = 1;
        foreach ($categories as $catName) {
            $categoryIds[] = DB::table('the_loai')->insertGetId([
                'ten' => $catName,
                'slug' => Str::slug($catName),
                'mo_ta' => 'Mô tả cho thể loại ' . $catName,
                'thu_tu' => $thuTu++,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        // 2. Seed Truyện
        $trangThaiList = ['dang_ra', 'hoan_thanh', 'tam_ngung'];
        $truyenIds = [];

        for ($i = 1; $i <= 30; $i++) {
            $tieuDe = $faker->sentence(rand(3, 6));
            $tieuDe = str_replace('.', '', $tieuDe);
            
            $truyenId = DB::table('truyen')->insertGetId([
                'tieu_de' => $tieuDe,
                'slug' => Str::slug($tieuDe) . '-' . rand(1000, 9999),
                'tac_gia' => $faker->name,
                'mo_ta_ngan' => $faker->text(200),
                'mo_ta_day_du' => '<p>' . implode('</p><p>', $faker->paragraphs(5)) . '</p>',
                'anh_bia' => null, // Provide default in UI or null
                'trang_thai' => $trangThaiList[array_rand($trangThaiList)],
                'tong_luot_xem' => rand(100, 50000),
                'tong_luot_theo_doi' => rand(10, 5000),
                'tong_luot_yeu_thich' => rand(5, 2000),
                'is_published' => true,
                'published_at' => Carbon::now()->subDays(rand(1, 100)),
                'created_at' => Carbon::now()->subDays(rand(10, 150)),
                'updated_at' => Carbon::now(),
            ]);

            $truyenIds[] = $truyenId;

            // Seed Pivot Truyen_TheLoai
            $randCats = (array) array_rand(array_flip($categoryIds), rand(1, 3));
            foreach ($randCats as $catId) {
                DB::table('truyen_the_loai')->insert([
                    'truyen_id' => $truyenId,
                    'the_loai_id' => $catId,
                ]);
            }

            // 3. Seed Chương for this Truyện
            $numChuong = rand(5, 50);
            $chuongData = [];
            for ($c = 1; $c <= $numChuong; $c++) {
                $chuongTieuDe = 'Chương ' . $c . ': ' . $faker->sentence(rand(4, 8));
                $chuongTieuDe = str_replace('.', '', $chuongTieuDe);
                
                $chuongData[] = [
                    'truyen_id' => $truyenId,
                    'so_chuong' => $c,
                    'tieu_de' => $chuongTieuDe,
                    'slug' => Str::slug('chuong-' . $c),
                    'noi_dung' => '<p>' . implode('</p><p>', $faker->paragraphs(rand(10, 20))) . '</p>',
                    'so_tu' => rand(1500, 3000),
                    'tong_luot_xem' => rand(10, 1000),
                    'is_published' => true,
                    'published_at' => Carbon::now()->subDays(rand(1, 30))->addHours($c),
                    'created_at' => Carbon::now()->subDays(rand(1, 30))->addHours($c),
                    'updated_at' => Carbon::now()->subDays(rand(1, 30))->addHours($c),
                ];
            }
            
            // Insert chuongs in chunks to avoid large queries
            foreach (array_chunk($chuongData, 50) as $chunk) {
                DB::table('chuong')->insert($chunk);
            }
        }
    }
}
