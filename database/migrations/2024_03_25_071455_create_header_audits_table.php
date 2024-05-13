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
        Schema::create('header_audits', function (Blueprint $table) {
            $table->id();
            $table->string('no_plpp', 40);
            $table->boolean('is_responded')->default(false);
            $table->foreignId('grup_auditor_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('auditee_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('iso_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('temuan_audit', ['ada', 'tidak']);
            $table->foreignId('departemen_id')->nullable()->constrained()->nullOnDelete();
            $table->string('tahun', 4)->default(date('Y'));
            $table->string('periode', 1);
            $table->date('end_at')->nullable();
            $table->json('static_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('header_audits');
    }
};
