<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('yeu_thich', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nguoi_dung_id')->constrained('nguoi_dung')->cascadeOnDelete();
            $table->foreignId('truyen_id')->constrained('truyen')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['nguoi_dung_id', 'truyen_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('yeu_thich');
    }
};
