<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('theo_doi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('truyen_id')->constrained('truyen')->cascadeOnDelete();
            $table->foreignId('chuong_cuoi_id')->nullable()->constrained('chuong')->nullOnDelete();
            $table->timestamps();

            $table->unique(['nguoi_dung_id', 'truyen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('theo_doi');
    }
};
