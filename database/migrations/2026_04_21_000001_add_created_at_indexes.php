<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm index created_at và updated_at cho các bảng lớn
     * để tăng tốc các truy vấn ORDER BY và WHERE trên các cột này.
     *
     * LƯU Ý: Trên database production có hàng triệu dòng,
     * migration này có thể mất vài phút. Nên chạy vào giờ thấp điểm.
     */
    public function up(): void
    {
        // Bảng chuong — hàng triệu dòng, dùng orderByDesc('created_at') nhiều nơi
        Schema::table('chuong', function (Blueprint $table) {
            $table->index('created_at', 'chuong_created_at_index');
        });

        // Bảng truyen — dùng orderByDesc('updated_at') ở scopeMoiCapNhat
        Schema::table('truyen', function (Blueprint $table) {
            $table->index('created_at', 'truyen_created_at_index');
            $table->index('updated_at', 'truyen_updated_at_index');
        });

        // Bảng nguoi_dung — orderByDesc('created_at') ở admin
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->index('created_at', 'nguoi_dung_created_at_index');
        });

        // Bảng tin_nhan_chat — whereDate('created_at') ở dashboard
        // Đã có composite index [phong_chat_id, created_at] nhưng cần standalone
        Schema::table('tin_nhan_chat', function (Blueprint $table) {
            $table->index('created_at', 'tin_nhan_chat_created_at_index');
        });

        // Bảng binh_luan — orderByDesc('created_at') ở admin + public
        // Đã có composite indexes nhưng cần standalone cho sort đơn thuần
        Schema::table('binh_luan', function (Blueprint $table) {
            $table->index('created_at', 'binh_luan_created_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('chuong', function (Blueprint $table) {
            $table->dropIndex('chuong_created_at_index');
        });

        Schema::table('truyen', function (Blueprint $table) {
            $table->dropIndex('truyen_created_at_index');
            $table->dropIndex('truyen_updated_at_index');
        });

        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropIndex('nguoi_dung_created_at_index');
        });

        Schema::table('tin_nhan_chat', function (Blueprint $table) {
            $table->dropIndex('tin_nhan_chat_created_at_index');
        });

        Schema::table('binh_luan', function (Blueprint $table) {
            $table->dropIndex('binh_luan_created_at_index');
        });
    }
};
