<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vai_tro', function (Blueprint $table) {
            $table->id();
            $table->string('ma', 50)->unique();
            $table->string('ten', 100);
            $table->string('mo_ta', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vai_tro');
    }
};
