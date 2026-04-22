<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chuong extends Model
{
    use HasFactory;

    protected $table = 'chuong';

    // Cập nhật updated_at của bảng truyen bất cứ lúc nào chương thay đổi
    // Rất quan trọng để tính năng "Truyện mới cập nhật" (sắp xếp theo updated_at của truyện) hoạt động chuẩn xác
    protected $touches = ['truyen'];

    protected $fillable = [
        'truyen_id',
        'so_chuong',
        'tieu_de',
        'slug',
        'noi_dung',
        'so_tu',
        'tong_luot_xem',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
            'so_chuong' => 'integer',
            'so_tu' => 'integer',
            'tong_luot_xem' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Chuong $chuong) {
            if (empty($chuong->slug)) {
                $chuong->slug = Str::slug("chuong-{$chuong->so_chuong}");
            }
            if (empty($chuong->so_tu)) {
                $chuong->so_tu = str_word_count(strip_tags($chuong->noi_dung));
            }
        });

        static::updating(function (Chuong $chuong) {
            if ($chuong->isDirty('noi_dung')) {
                $chuong->so_tu = str_word_count(strip_tags($chuong->noi_dung));
            }
        });
    }

    // Quan hệ: truyện
    public function truyen()
    {
        return $this->belongsTo(Truyen::class, 'truyen_id');
    }

    // Quan hệ: bình luận
    public function binhLuan()
    {
        return $this->hasMany(BinhLuan::class, 'chuong_id');
    }

    // Chương trước
    public function chuongTruoc()
    {
        return Chuong::where('truyen_id', $this->truyen_id)
            ->where('is_published', true)
            ->where('so_chuong', '<', $this->so_chuong)
            ->orderByDesc('so_chuong')
            ->first();
    }

    // Chương sau
    public function chuongSau()
    {
        return Chuong::where('truyen_id', $this->truyen_id)
            ->where('is_published', true)
            ->where('so_chuong', '>', $this->so_chuong)
            ->orderBy('so_chuong')
            ->first();
    }

    // Scope: đã xuất bản
    public function scopeDaXuatBan($query)
    {
        return $query->where('is_published', true);
    }

    // Scope: sắp xếp theo số chương
    public function scopeSapXepChuong($query)
    {
        return $query->orderBy('so_chuong');
    }
}
