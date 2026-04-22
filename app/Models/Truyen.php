<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Truyen extends Model
{
    use HasFactory;

    protected $table = 'truyen';

    protected $fillable = [
        'tieu_de',
        'slug',
        'tac_gia',
        'mo_ta_ngan',
        'mo_ta_day_du',
        'anh_bia',
        'trang_thai',
        'tong_luot_xem',
        'tong_luot_theo_doi',
        'tong_luot_yeu_thich',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'tong_luot_xem' => 'integer',
            'tong_luot_theo_doi' => 'integer',
            'tong_luot_yeu_thich' => 'integer',
        ];
    }

    // Tự động tạo slug
    protected static function booted(): void
    {
        static::creating(function (Truyen $truyen) {
            if (empty($truyen->slug)) {
                $truyen->slug = Str::slug($truyen->tieu_de);
            }
        });
    }

    // Quan hệ: thể loại
    public function theLoai()
    {
        return $this->belongsToMany(TheLoai::class, 'truyen_the_loai', 'truyen_id', 'the_loai_id');
    }

    // Quan hệ: chương
    public function chuong()
    {
        return $this->hasMany(Chuong::class, 'truyen_id');
    }

    // Chương đã xuất bản
    public function chuongDaXuatBan()
    {
        return $this->hasMany(Chuong::class, 'truyen_id')
            ->where('is_published', true)
            ->orderBy('so_chuong');
    }

    // Chương mới nhất
    public function chuongMoiNhat()
    {
        return $this->hasOne(Chuong::class, 'truyen_id')
            ->where('is_published', true)
            ->latestOfMany('so_chuong');
    }

    // Quan hệ: yêu thích
    public function nguoiYeuThich()
    {
        return $this->belongsToMany(NguoiDung::class, 'yeu_thich', 'truyen_id', 'nguoi_dung_id');
    }

    // Quan hệ: theo dõi
    public function nguoiTheoDoi()
    {
        return $this->belongsToMany(NguoiDung::class, 'theo_doi', 'truyen_id', 'nguoi_dung_id');
    }

    // Quan hệ: bình luận
    public function binhLuan()
    {
        return $this->hasMany(BinhLuan::class, 'truyen_id');
    }

    // Scope: đã xuất bản
    public function scopeDaXuatBan($query)
    {
        return $query->where('is_published', true);
    }

    // Scope: theo trạng thái
    public function scopeTrangThai($query, ?string $trangThai)
    {
        if ($trangThai) {
            return $query->where('trang_thai', $trangThai);
        }
        return $query;
    }

    // Scope: theo thể loại
    public function scopeTheoTheLoai($query, ?int $theLoaiId)
    {
        if ($theLoaiId) {
            return $query->whereHas('theLoai', fn($q) => $q->where('the_loai.id', $theLoaiId));
        }
        return $query;
    }

    // Scope: tìm kiếm fulltext
    public function scopeTimKiem($query, ?string $tuKhoa)
    {
        if ($tuKhoa) {
            // Thay vì dùng LIKE "%tuKhoa%" (gây Full Table Scan), 
            // Dùng whereFullText để tận dụng index FULLTEXT đã có trong Database
            return $query->whereFullText(['tieu_de', 'tac_gia', 'mo_ta_ngan'], $tuKhoa);
        }
        return $query;
    }

    // Scope: sắp xếp
    public function scopeSapXep($query, ?string $kieuSapXep)
    {
        return match ($kieuSapXep) {
            'xem_nhieu' => $query->orderByDesc('tong_luot_xem'),
            'ten_az' => $query->orderBy('tieu_de'),
            'ten_za' => $query->orderByDesc('tieu_de'),
            default => $query->orderByDesc('updated_at'),
        };
    }

    // Scope: truyện hot
    public function scopeHot($query)
    {
        return $query->daXuatBan()->orderByDesc('tong_luot_xem');
    }

    // Scope: mới cập nhật
    public function scopeMoiCapNhat($query)
    {
        return $query->daXuatBan()->orderByDesc('updated_at');
    }

    // Scope: hoàn thành
    public function scopeHoanThanh($query)
    {
        return $query->daXuatBan()->where('trang_thai', 'hoan_thanh');
    }

    // Tổng số chương đã xuất bản
    public function soChương(): int
    {
        return $this->chuong()->where('is_published', true)->count();
    }

    // Nhãn trạng thái tiếng Việt
    public function tenTrangThai(): string
    {
        return match ($this->trang_thai) {
            'dang_ra' => 'Đang ra',
            'hoan_thanh' => 'Hoàn thành',
            'tam_ngung' => 'Tạm ngưng',
            default => $this->trang_thai,
        };
    }

    // URL ảnh bìa
    public function urlAnhBia(): string
    {
        if ($this->anh_bia) {
            return asset('storage/' . $this->anh_bia);
        }
        return asset('images/default-cover.svg');
    }
}
