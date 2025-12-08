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
        Schema::create('berkas_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_cuti_id')
                ->constrained('pengajuan_cuti')
                ->cascadeOnDelete();

            $table->string('tipe_berkas');   // contoh: surat_permohonan, surat_dokter
            $table->string('nama_asli');
            $table->string('path');
            $table->string('mime_type');
            $table->integer('ukuran');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_pengajuan');
    }
};
