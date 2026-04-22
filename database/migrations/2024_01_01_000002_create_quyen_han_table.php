<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quyen_han', function (Blueprint $table) {
            $table->id();
            $table->string('ma', 100)->unique();
            $table->string('mo_ta', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quyen_han');
    }
};
