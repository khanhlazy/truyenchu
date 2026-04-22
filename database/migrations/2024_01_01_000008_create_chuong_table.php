<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chuong', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truyen_id')->constrained('truyen')->cascadeOnDelete();
            $table->unsignedInteger('so_chuong');
            $table->string('tieu_de', 255);
            $table->string('slug', 280);
            $table->longText('noi_dung');
            $table->unsignedInteger('so_tu')->default(0);
            $table->unsignedBigInteger('tong_luot_xem')->default(0);
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['truyen_id', 'so_chuong']);
            $table->unique(['truyen_id', 'slug']);
            $table->index(['truyen_id', 'is_published', 'so_chuong']);
            $table->index('tong_luot_xem');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chuong');
    }
};
