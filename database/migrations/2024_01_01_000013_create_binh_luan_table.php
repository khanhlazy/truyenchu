<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('binh_luan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('truyen_id')->nullable()->constrained('truyen')->cascadeOnDelete();
            $table->foreignId('chuong_id')->nullable()->constrained('chuong')->cascadeOnDelete();
            $table->foreignId('binh_luan_cha_id')->nullable()->constrained('binh_luan')->cascadeOnDelete();
            $table->text('noi_dung');
            $table->enum('trang_thai', ['cho_duyet', 'hien_thi', 'an', 'da_xoa'])->default('cho_duyet');
            $table->timestamps();

            $table->index(['chuong_id', 'trang_thai', 'created_at']);
            $table->index(['truyen_id', 'trang_thai', 'created_at']);
            $table->index(['nguoi_dung_id', 'created_at']);
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('binh_luan');
    }
};
