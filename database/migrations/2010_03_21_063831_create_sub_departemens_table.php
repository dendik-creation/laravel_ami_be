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
        Schema::create('sub_departemens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('departemen_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('nama_sub_departemen', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_departemens');
    }
};
