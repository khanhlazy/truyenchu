<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class NguoiDung extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'nguoi_dung';

    protected $fillable = [
        'ten_dang_nhap',
        'email',
        'mat_khau',
        'ten_hien_thi',
        'anh_dai_dien',
        'trang_thai',
        'da_xac_minh_email',
        'bi_cam_chat_den',
    ];

    protected $appends = ['url_anh_dai_dien'];

    protected $hidden = [
        'mat_khau',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'da_xac_minh_email' => 'datetime',
            'bi_cam_chat_den' => 'datetime',
            'mat_khau' => 'hashed',
        ];
    }

    // Override mặc định của Laravel Auth
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    // Quan hệ: vai trò
    public function vaiTro()
    {
        return $this->belongsToMany(VaiTro::class, 'nguoi_dung_vai_tro', 'nguoi_dung_id', 'vai_tro_id');
    }

    // Kiểm tra vai trò
    public function coVaiTro(string $ma): bool
    {
        return $this->vaiTro()->where('ma', $ma)->exists();
    }

    public function laAdmin(): bool
    {
        return $this->coVaiTro('admin');
    }

    // Kiểm tra bị cấm chat
    public function biCamChat(): bool
    {
        return $this->bi_cam_chat_den && $this->bi_cam_chat_den->isFuture();
    }

    // Quan hệ: yêu thích
    public function yeuThich()
    {
        return $this->belongsToMany(Truyen::class, 'yeu_thich', 'nguoi_dung_id', 'truyen_id')
            ->withPivot('created_at');
    }

    // Quan hệ: theo dõi
    public function theoDoi()
    {
        return $this->belongsToMany(Truyen::class, 'theo_doi', 'nguoi_dung_id', 'truyen_id')
            ->withPivot('chuong_cuoi_id', 'created_at', 'updated_at');
    }

    // Quan hệ: lịch sử đọc
    public function lichSuDoc()
    {
        return $this->hasMany(LichSuDoc::class, 'nguoi_dung_id');
    }

    // Quan hệ: bình luận
    public function binhLuan()
    {
        return $this->hasMany(BinhLuan::class, 'nguoi_dung_id');
    }

    // Quan hệ: tin nhắn chat
    public function tinNhanChat()
    {
        return $this->hasMany(TinNhanChat::class, 'nguoi_dung_id');
    }

    // Scope: đang hoạt động
    public function scopeHoatDong($query)
    {
        return $query->where('trang_thai', 'hoat_dong');
    }

    // Scope: tìm kiếm
    public function scopeTimKiem($query, ?string $tuKhoa)
    {
        if ($tuKhoa) {
            return $query->where(function ($q) use ($tuKhoa) {
                $q->where('ten_dang_nhap', 'like', "%{$tuKhoa}%")
                  ->orWhere('email', 'like', "%{$tuKhoa}%")
                  ->orWhere('ten_hien_thi', 'like', "%{$tuKhoa}%");
            });
        }
        return $query;
    }

    // Kiểm tra trạng thái hoạt động
    public function dangHoatDong(): bool
    {
        return $this->trang_thai === 'hoat_dong';
    }
    // Lấy URL ảnh đại diện (nếu không có thì dùng ảnh mặc định hoặc chữ cái đầu)
    public function urlAnhDaiDien(): string
    {
        if ($this->anh_dai_dien) {
            return asset('storage/' . $this->anh_dai_dien);
        }
        
        $name = urlencode($this->ten_hien_thi ?: $this->ten_dang_nhap);
        return "https://ui-avatars.com/api/?name={$name}&color=4f46e5&background=eef2ff&semibold=true";
    }

    public function getUrlAnhDaiDienAttribute(): string
    {
        return $this->urlAnhDaiDien();
    }
}
