<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('truyen_the_loai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truyen_id')->constrained('truyen')->cascadeOnDelete();
            $table->foreignId('the_loai_id')->constrained('the_loai')->cascadeOnDelete();

            $table->unique(['truyen_id', 'the_loai_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('truyen_the_loai');
    }
};
