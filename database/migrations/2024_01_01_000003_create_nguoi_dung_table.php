<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id();
            $table->string('ten_dang_nhap', 50)->unique();
            $table->string('email', 191)->unique();
            $table->string('mat_khau');
            $table->string('ten_hien_thi', 100);
            $table->string('anh_dai_dien')->nullable();
            $table->enum('trang_thai', ['hoat_dong', 'khoa'])->default('hoat_dong');
            $table->timestamp('da_xac_minh_email')->nullable();
            $table->timestamp('bi_cam_chat_den')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};
