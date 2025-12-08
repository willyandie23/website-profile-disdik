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
        Schema::table('pengajuan_cuti', function (Blueprint $table) {
            // 1. Kolom utama proses approval
            $table->enum('level_approval', ['tu', 'kasubbag', 'selesai'])
                ->default('tu')
                ->after('status'); // tepat setelah kolom status

            // 2. Status revisi
            $table->enum('status_revisi', ['tidak_perlu', 'perlu_revisi', 'sudah_direvisi'])
                ->default('tidak_perlu')
                ->after('level_approval');

            // 3. Catatan revisi (dari TU atau Kasubbag)
            $table->text('catatan_revisi')
                ->nullable()
                ->after('status_revisi');

            // 4. Siapa yang minta revisi (NIP)
            $table->string('revisi_oleh', 20)
                ->nullable()
                ->after('catatan_revisi');

            // 5. Approval Admin TU
            $table->string('approved_by_tu', 20)->nullable()->after('revisi_oleh');
            $table->timestamp('approved_at_tu')->nullable()->after('approved_by_tu');

            // 6. Approval Kasubbag
            $table->string('approved_by_kasubbag', 20)->nullable()->after('approved_at_tu');
            $table->timestamp('approved_at_kasubbag')->nullable()->after('approved_by_kasubbag');

            // 7. (Opsional) Tanggal selesai cuti sebenarnya (untuk status 'selesai')
            $table->date('tanggal_kembali_kerja')->nullable()->after('approved_at_kasubbag');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_cuti', function (Blueprint $table) {
            $table->dropColumn([
                'level_approval',
                'status_revisi',
                'catatan_revisi',
                'revisi_oleh',
                'approved_by_tu',
                'approved_at_tu',
                'approved_by_kasubbag',
                'approved_at_kasubbag',
                'tanggal_kembali_kerja'
            ]);
        });
    }
};
