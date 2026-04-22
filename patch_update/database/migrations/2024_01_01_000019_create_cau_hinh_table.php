<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cau_hinh', function (Blueprint $table) {
            $table->id();
            $table->string('khoa')->unique(); // key
            $table->text('gia_tri')->nullable(); // value
            $table->string('loai')->default('text'); // text, image, textarea, boolean
            $table->string('nhom')->default('chung'); // chung, giao_dien, seo
            $table->string('nhan')->nullable(); // label hiển thị
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cau_hinh');
    }
};
