<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_su_doc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->nullable()->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('truyen_id')->constrained('truyen')->cascadeOnDelete();
            $table->foreignId('chuong_id')->constrained('chuong')->cascadeOnDelete();
            $table->string('ip', 45)->nullable();
            $table->string('session_id', 100)->nullable();
            $table->timestamp('thoi_diem_doc_cuoi')->useCurrent();
            $table->timestamps();

            $table->index(['nguoi_dung_id', 'updated_at']);
            $table->index(['truyen_id', 'nguoi_dung_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_doc');
    }
};
