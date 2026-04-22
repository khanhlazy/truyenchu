<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhatKyKiemDuyet extends Model
{
    protected $table = 'nhat_ky_kiem_duyet';

    protected $fillable = [
        'nguoi_thuc_hien_id',
        'hanh_dong',
        'loai_doi_tuong',
        'doi_tuong_id',
        'du_lieu_bo_sung',
    ];

    protected function casts(): array
    {
        return ['du_lieu_bo_sung' => 'array'];
    }

    public function nguoiThucHien()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_thuc_hien_id');
    }

    // Ghi log kiểm duyệt
    public static function ghiLog(int $nguoiThucHienId, string $hanhDong, string $loaiDoiTuong, int $doiTuongId, ?array $duLieuBoSung = null): self
    {
        return self::create([
            'nguoi_thuc_hien_id' => $nguoiThucHienId,
            'hanh_dong' => $hanhDong,
            'loai_doi_tuong' => $loaiDoiTuong,
            'doi_tuong_id' => $doiTuongId,
            'du_lieu_bo_sung' => $duLieuBoSung,
        ]);
    }
}
