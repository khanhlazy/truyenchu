<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuDoc extends Model
{
    protected $table = 'lich_su_doc';

    protected $fillable = [
        'nguoi_dung_id',
        'truyen_id',
        'chuong_id',
        'ip',
        'session_id',
        'thoi_diem_doc_cuoi',
    ];

    protected function casts(): array
    {
        return ['thoi_diem_doc_cuoi' => 'datetime'];
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function truyen()
    {
        return $this->belongsTo(Truyen::class, 'truyen_id');
    }

    public function chuong()
    {
        return $this->belongsTo(Chuong::class, 'chuong_id');
    }

    // Scope: theo người dùng, mới nhất trước
    public function scopeTheoNguoiDung($query, int $nguoiDungId)
    {
        return $query->where('nguoi_dung_id', $nguoiDungId)->orderByDesc('thoi_diem_doc_cuoi');
    }
}
