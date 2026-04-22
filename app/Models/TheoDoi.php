<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TheoDoi extends Model
{
    protected $table = 'theo_doi';

    protected $fillable = ['nguoi_dung_id', 'truyen_id', 'chuong_cuoi_id'];

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function truyen()
    {
        return $this->belongsTo(Truyen::class, 'truyen_id');
    }

    public function chuongCuoi()
    {
        return $this->belongsTo(Chuong::class, 'chuong_cuoi_id');
    }
}
