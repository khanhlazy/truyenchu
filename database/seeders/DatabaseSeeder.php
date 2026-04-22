<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            VaiTroSeeder::class,
            NguoiDungSeeder::class,
            TheLoaiSeeder::class,
            TruyenSeeder::class,
            ChuongSeeder::class,
            DuLieuMauSeeder::class,
        ]);
    }
}
