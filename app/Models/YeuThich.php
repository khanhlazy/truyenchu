<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YeuThich extends Model
{
    protected $table = 'yeu_thich';
    public $timestamps = false;

    protected $fillable = ['nguoi_dung_id', 'truyen_id'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }

    public function truyen()
    {
        return $this->belongsTo(Truyen::class, 'truyen_id');
    }
}
