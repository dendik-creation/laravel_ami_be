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
        Schema::create('detail_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('header_audit_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('judul_clausul_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('clausul_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_clausul_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('sub_departemen_id')->nullable()->constrained()->nullOnDelete();
            $table->date('tanggal_audit');
            $table->date('tanggal_target');
            $table->date('tanggal_realisasi')->nullable();
            $table->enum('kategori', ['mayor', 'minor', 'observasi']);
            $table->enum('jenis_temuan', ['new', 'repeat']);
            $table->enum('status', ['open', 'close']);
            $table->text('temuan')->nullable();
            $table->text('analisa')->nullable();
            $table->json('attachment')->nullable();
            $table->text('tindakan')->nullable();
            $table->json('static_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_audits');
    }
};
