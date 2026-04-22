<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinhLuan extends Model
{
    protected $table = 'binh_luan';

    protected $fillable = [
        'nguoi_dung_id',
        'truyen_id',
        'chuong_id',
        'binh_luan_cha_id',
        'noi_dung',
        'trang_thai',
    ];

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

    public function binhLuanCha()
    {
        return $this->belongsTo(BinhLuan::class, 'binh_luan_cha_id');
    }

    public function binhLuanCon()
    {
        return $this->hasMany(BinhLuan::class, 'binh_luan_cha_id');
    }

    // Scope: hiển thị
    public function scopeHienThi($query)
    {
        return $query->where('trang_thai', 'hien_thi');
    }

    // Scope: chờ duyệt
    public function scopeChoDuyet($query)
    {
        return $query->where('trang_thai', 'cho_duyet');
    }

    // Scope: bình luận gốc (không phải reply)
    public function scopeGoc($query)
    {
        return $query->whereNull('binh_luan_cha_id');
    }

    // Nhãn trạng thái tiếng Việt
    public function tenTrangThai(): string
    {
        return match ($this->trang_thai) {
            'cho_duyet' => 'Chờ duyệt',
            'hien_thi' => 'Hiển thị',
            'an' => 'Ẩn',
            'da_xoa' => 'Đã xóa',
            default => $this->trang_thai,
        };
    }
}
