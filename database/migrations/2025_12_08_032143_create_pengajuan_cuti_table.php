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
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id();

            $table->string('kode_pengajuan')->unique(); // tracking kode

            // Data Diri (manual tanpa login)
            $table->string('nip')->nullable();
            $table->string('nama_lengkap');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->string('pangkat_golongan')->nullable();
            $table->string('jabatan');
            $table->string('unit_kerja');
            $table->string('nomor_hp');
            $table->text('alamat')->nullable();

            // Data Cuti
            $table->foreignId('jenis_cuti_id')->constrained('jenis_cuti');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->text('alasan_cuti');
            $table->string('alamat_selama_cuti');
            $table->string('kontak_selama_cuti');

            // Status Approval
            $table->enum('status', [
                'draft',
                'diajukan',
                'sedang_diproses',
                'disetujui',
                'ditolak',
                'selesai',
                'dibatalkan'
            ])->default('draft');

            $table->string('nomor_surat')->nullable();
            $table->string('final_pdf')->nullable();
            $table->text('catatan_penolakan')->nullable();

            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
};
