<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhat_ky_kiem_duyet', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_thuc_hien_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->string('hanh_dong', 100);
            $table->string('loai_doi_tuong', 50);
            $table->unsignedBigInteger('doi_tuong_id');
            $table->json('du_lieu_bo_sung')->nullable();
            $table->timestamps();

            $table->index(['loai_doi_tuong', 'doi_tuong_id']);
            $table->index('nguoi_thuc_hien_id');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhat_ky_kiem_duyet');
    }
};
