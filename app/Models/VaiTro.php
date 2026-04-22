<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VaiTro extends Model
{
    use HasFactory;

    protected $table = 'vai_tro';

    protected $fillable = ['ma', 'ten', 'mo_ta'];

    public function nguoiDung()
    {
        return $this->belongsToMany(NguoiDung::class, 'nguoi_dung_vai_tro', 'vai_tro_id', 'nguoi_dung_id');
    }

    public function quyenHan()
    {
        return $this->belongsToMany(QuyenHan::class, 'vai_tro_quyen_han', 'vai_tro_id', 'quyen_han_id');
    }
}
