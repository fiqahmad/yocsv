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
        Schema::table('csv_uploads', function (Blueprint $table) {
            $table->integer('total_rows')->nullable()->after('status');
            $table->integer('inserted_rows')->nullable()->after('total_rows');
            $table->integer('updated_rows')->nullable()->after('inserted_rows');
            $table->integer('error_rows')->nullable()->after('updated_rows');
            $table->text('error_messages')->nullable()->after('error_rows');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('csv_uploads', function (Blueprint $table) {
            $table->dropColumn(['total_rows', 'inserted_rows', 'updated_rows', 'error_rows', 'error_messages']);
        });
    }
};
