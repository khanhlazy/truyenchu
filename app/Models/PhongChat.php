<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhongChat extends Model
{
    protected $table = 'phong_chat';

    protected $fillable = ['ma', 'ten'];

    public function tinNhan()
    {
        return $this->hasMany(TinNhanChat::class, 'phong_chat_id');
    }

    public function tinNhanMoiNhat()
    {
        return $this->hasMany(TinNhanChat::class, 'phong_chat_id')->orderByDesc('created_at');
    }
}
