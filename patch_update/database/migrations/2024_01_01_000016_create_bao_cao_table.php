<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bao_cao', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_bao_cao_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->string('loai_doi_tuong', 50);
            $table->unsignedBigInteger('doi_tuong_id');
            $table->text('ly_do');
            $table->enum('trang_thai', ['cho_xu_ly', 'da_xu_ly', 'tu_choi'])->default('cho_xu_ly');
            $table->timestamps();

            $table->index(['loai_doi_tuong', 'doi_tuong_id']);
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bao_cao');
    }
};
