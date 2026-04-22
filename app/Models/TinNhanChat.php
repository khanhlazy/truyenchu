<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TinNhanChat extends Model
{
    protected $table = 'tin_nhan_chat';

    protected $fillable = ['phong_chat_id', 'nguoi_dung_id', 'noi_dung'];

    public function phongChat()
    {
        return $this->belongsTo(PhongChat::class, 'phong_chat_id');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_dung_id');
    }
}
