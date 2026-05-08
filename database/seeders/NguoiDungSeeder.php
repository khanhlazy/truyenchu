<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use App\Models\VaiTro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class NguoiDungSeeder extends Seeder
{
    public function run(): void
    {
        $admin = NguoiDung::create([
            'ten_dang_nhap' => 'admin',
            'email' => 'admin@truyenchu.test',
            'mat_khau' => Hash::make('password'),
            'ten_hien_thi' => 'Quản Trị Viên',
            'trang_thai' => 'hoat_dong',
        ]);
        $admin->vaiTro()->attach(VaiTro::where('ma', 'admin')->first()->id);
    }
}
