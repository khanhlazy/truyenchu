<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Chuong extends Model
{
    use HasFactory;

    protected $table = 'chuong';

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

    /**
     * Accessor: Ưu tiên đọc nội dung từ Redis -> File -> Database
     */
    public function getNoiDungAttribute($value)
    {
        $cacheKey = "chapter_content_{$this->truyen_id}_{$this->so_chuong}";

        // 1. Thử lấy từ Redis Cache
        $content = cache()->get($cacheKey);
        if ($content) {
            return $content;
        }

        // 2. Thử lấy từ File System
        $filePath = "chapters/{$this->truyen_id}/c_{$this->so_chuong}.txt";
        if (\Illuminate\Support\Facades\Storage::exists($filePath)) {
            $content = \Illuminate\Support\Facades\Storage::get($filePath);
            // Lưu lại vào Redis (7 ngày) để lần sau đọc nhanh hơn
            cache()->put($cacheKey, $content, now()->addDays(7));
            return $content;
        }

        // 3. Cuối cùng mới lấy từ DB (cho các truyện cũ chưa chuyển đổi)
        if ($value && $value !== '[FILE_STORAGE]') {
            return $value;
        }

        return "Nội dung đang được cập nhật...";
    }

    /**
     * Mutator: Tự động lưu vào File và Redis khi gán nội dung
     */
    public function setNoiDungAttribute($value)
    {
        if (empty($value)) return;

        // 1. Lưu vào File System
        $filePath = "chapters/{$this->truyen_id}/c_{$this->so_chuong}.txt";
        \Illuminate\Support\Facades\Storage::put($filePath, $value);

        // 2. Lưu vào Redis Cache (7 ngày)
        $cacheKey = "chapter_content_{$this->truyen_id}_{$this->so_chuong}";
        cache()->put($cacheKey, $value, now()->addDays(7));

        // 3. Để trống trong database để tiết kiệm dung lượng
        $this->attributes['noi_dung'] = '[FILE_STORAGE]';
    }

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
