<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaoCao extends Model
{
    protected $table = 'bao_cao';

    protected $fillable = [
        'nguoi_bao_cao_id',
        'loai_doi_tuong',
        'doi_tuong_id',
        'ly_do',
        'trang_thai',
    ];

    public function nguoiBaoCao()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_bao_cao_id');
    }

    // Lấy đối tượng bị báo cáo (polymorphic thủ công)
    public function doiTuong()
    {
        return match ($this->loai_doi_tuong) {
            'truyen' => Truyen::find($this->doi_tuong_id),
            'chuong' => Chuong::find($this->doi_tuong_id),
            'binh_luan' => BinhLuan::find($this->doi_tuong_id),
            'tin_nhan_chat' => TinNhanChat::find($this->doi_tuong_id),
            default => null,
        };
    }

    public function tenTrangThai(): string
    {
        return match ($this->trang_thai) {
            'cho_xu_ly' => 'Chờ xử lý',
            'da_xu_ly' => 'Đã xử lý',
            'tu_choi' => 'Từ chối',
            default => $this->trang_thai,
        };
    }
}
