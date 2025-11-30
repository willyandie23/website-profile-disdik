<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldIdToOrganizationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('image')->nullable();

            // Adding the 'field_id' column as a foreign key
            $table->unsignedBigInteger('field_id')->nullable();

            // Adding the foreign key constraint
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn('image');

            $table->dropForeign(['field_id']);
            $table->dropColumn('field_id');
        });
    }
};
