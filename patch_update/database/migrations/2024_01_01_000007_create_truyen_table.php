<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('truyen', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 255);
            $table->string('slug', 280)->unique();
            $table->string('tac_gia', 150);
            $table->string('mo_ta_ngan', 500)->nullable();
            $table->text('mo_ta_day_du')->nullable();
            $table->string('anh_bia')->nullable();
            $table->enum('trang_thai', ['dang_ra', 'hoan_thanh', 'tam_ngung'])->default('dang_ra');
            $table->unsignedBigInteger('tong_luot_xem')->default(0);
            $table->unsignedBigInteger('tong_luot_theo_doi')->default(0);
            $table->unsignedBigInteger('tong_luot_yeu_thich')->default(0);
            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('trang_thai');
            $table->index('is_published');
            $table->index('tong_luot_xem');
            $table->index('published_at');
            $table->fullText(['tieu_de', 'tac_gia', 'mo_ta_ngan'], 'truyen_fulltext');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('truyen');
    }
};
