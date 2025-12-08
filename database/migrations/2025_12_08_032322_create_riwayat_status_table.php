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
        Schema::create('riwayat_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_cuti_id')
                ->constrained('pengajuan_cuti')
                ->cascadeOnDelete();

            $table->string('status_lama');
            $table->string('status_baru');
            $table->text('catatan')->nullable();
            $table->string('oleh')->nullable(); // nama admin
            $table->timestamp('tanggal')->useCurrent();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_status');
    }
};
