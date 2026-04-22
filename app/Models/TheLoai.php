<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TheLoai extends Model
{
    use HasFactory;

    protected $table = 'the_loai';

    protected $fillable = ['ten', 'slug', 'mo_ta', 'thu_tu'];

    // Tự động tạo slug
    protected static function booted(): void
    {
        static::creating(function (TheLoai $theLoai) {
            if (empty($theLoai->slug)) {
                $theLoai->slug = Str::slug($theLoai->ten);
            }
        });
    }

    public function truyen()
    {
        return $this->belongsToMany(Truyen::class, 'truyen_the_loai', 'the_loai_id', 'truyen_id');
    }

    // Scope: sắp xếp theo thứ tự
    public function scopeSapXep($query)
    {
        return $query->orderBy('thu_tu')->orderBy('ten');
    }
}
