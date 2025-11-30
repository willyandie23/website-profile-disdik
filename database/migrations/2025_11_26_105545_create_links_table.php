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
        // Gunakan Schema::create() untuk membuat tabel jika belum ada
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Gunakan Schema::dropIfExists() untuk menghapus tabel saat rollback
        Schema::dropIfExists('links');
    }
};
