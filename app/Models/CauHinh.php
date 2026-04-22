<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CauHinh extends Model
{
    protected $table = 'cau_hinh';

    protected $fillable = ['khoa', 'gia_tri', 'loai', 'nhom', 'nhan'];

    // Lấy giá trị theo khóa, có cache
    public static function lay(string $khoa, $macDinh = null)
    {
        return Cache::remember("cau_hinh.{$khoa}", 3600, function () use ($khoa, $macDinh) {
            $cauHinh = self::where('khoa', $khoa)->first();
            return $cauHinh ? $cauHinh->gia_tri : $macDinh;
        });
    }

    // Cập nhật hoặc tạo mới
    public static function dat(string $khoa, $giaTri): void
    {
        self::updateOrCreate(
            ['khoa' => $khoa],
            ['gia_tri' => $giaTri]
        );
        Cache::forget("cau_hinh.{$khoa}");
    }

    // Lấy URL logo
    public static function urlLogo(): string
    {
        $logo = self::lay('logo');
        return $logo ? asset('storage/' . $logo) : '';
    }

    // Lấy URL banner
    public static function urlBanner(): string
    {
        $banner = self::lay('banner');
        return $banner ? asset('storage/' . $banner) : '';
    }

    // Xóa cache tất cả
    public static function xoaCache(): void
    {
        $keys = self::pluck('khoa');
        foreach ($keys as $khoa) {
            Cache::forget("cau_hinh.{$khoa}");
        }
    }
}
