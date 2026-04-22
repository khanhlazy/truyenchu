<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tin_nhan_chat', function (Blueprint $table) {
            $table->id();
            $table->foreignId('phong_chat_id')->constrained('phong_chat')->cascadeOnDelete();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->text('noi_dung');
            $table->timestamps();

            $table->index(['phong_chat_id', 'created_at']);
            $table->index('nguoi_dung_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tin_nhan_chat');
    }
};
