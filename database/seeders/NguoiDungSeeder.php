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
        // Tài khoản admin mặc định
        $admin = NguoiDung::create([
            'ten_dang_nhap' => 'admin',
            'email' => 'admin@truyenchu.test',
            'mat_khau' => Hash::make('password'),
            'ten_hien_thi' => 'Quản Trị Viên',
            'trang_thai' => 'hoat_dong',
        ]);
        $admin->vaiTro()->attach(VaiTro::where('ma', 'admin')->first()->id);

        // Tài khoản người dùng mẫu
        $danhSachNguoiDung = [
            ['ten_dang_nhap' => 'nguyenvana', 'email' => 'vana@truyenchu.test', 'ten_hien_thi' => 'Nguyễn Văn A'],
            ['ten_dang_nhap' => 'tranthib', 'email' => 'thib@truyenchu.test', 'ten_hien_thi' => 'Trần Thị B'],
            ['ten_dang_nhap' => 'lehongc', 'email' => 'hongc@truyenchu.test', 'ten_hien_thi' => 'Lê Hồng C'],
            ['ten_dang_nhap' => 'phamvand', 'email' => 'vand@truyenchu.test', 'ten_hien_thi' => 'Phạm Văn D'],
            ['ten_dang_nhap' => 'hoangthie', 'email' => 'thie@truyenchu.test', 'ten_hien_thi' => 'Hoàng Thị E'],
        ];

        $vaiTroUser = VaiTro::where('ma', 'user')->first();

        foreach ($danhSachNguoiDung as $nd) {
            $nguoiDung = NguoiDung::create([
                'ten_dang_nhap' => $nd['ten_dang_nhap'],
                'email' => $nd['email'],
                'mat_khau' => Hash::make('password'),
                'ten_hien_thi' => $nd['ten_hien_thi'],
                'trang_thai' => 'hoat_dong',
            ]);
            $nguoiDung->vaiTro()->attach($vaiTroUser->id);
        }
    }
}
