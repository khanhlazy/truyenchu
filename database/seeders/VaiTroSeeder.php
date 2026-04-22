<?php

namespace Database\Seeders;

use App\Models\VaiTro;
use App\Models\QuyenHan;
use Illuminate\Database\Seeder;

class VaiTroSeeder extends Seeder
{
    public function run(): void
    {
        $admin = VaiTro::create(['ma' => 'admin', 'ten' => 'Quản trị viên', 'mo_ta' => 'Toàn quyền quản lý hệ thống']);
        $user = VaiTro::create(['ma' => 'user', 'ten' => 'Người dùng', 'mo_ta' => 'Người dùng thông thường']);

        $quyenHans = [
            ['ma' => 'quan_ly_truyen', 'mo_ta' => 'Quản lý truyện'],
            ['ma' => 'quan_ly_chuong', 'mo_ta' => 'Quản lý chương'],
            ['ma' => 'quan_ly_the_loai', 'mo_ta' => 'Quản lý thể loại'],
            ['ma' => 'quan_ly_binh_luan', 'mo_ta' => 'Quản lý bình luận'],
            ['ma' => 'quan_ly_nguoi_dung', 'mo_ta' => 'Quản lý người dùng'],
            ['ma' => 'quan_ly_chat', 'mo_ta' => 'Quản lý chat'],
            ['ma' => 'quan_ly_bao_cao', 'mo_ta' => 'Quản lý báo cáo'],
            ['ma' => 'xem_truyen', 'mo_ta' => 'Xem truyện'],
            ['ma' => 'binh_luan', 'mo_ta' => 'Bình luận'],
            ['ma' => 'su_dung_chat', 'mo_ta' => 'Sử dụng chat'],
        ];

        foreach ($quyenHans as $qh) {
            QuyenHan::create($qh);
        }

        // Admin có tất cả quyền
        $admin->quyenHan()->attach(QuyenHan::pluck('id'));

        // User có quyền cơ bản
        $user->quyenHan()->attach(
            QuyenHan::whereIn('ma', ['xem_truyen', 'binh_luan', 'su_dung_chat'])->pluck('id')
        );
    }
}
