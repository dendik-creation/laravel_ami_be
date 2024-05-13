<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clausuls', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20);
            $table->foreignId('judul_clausul_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('nama_clausul', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clausuls');
    }
};
