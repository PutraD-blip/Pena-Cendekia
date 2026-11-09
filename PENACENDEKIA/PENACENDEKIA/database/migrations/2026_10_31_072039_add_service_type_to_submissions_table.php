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
        Schema::table('submissions', function (Blueprint $table) {
            $table->string('service_type', 50)->default('penerbitan')->after('code');
            $table->string('editor_name', 100)->nullable()->change();
            $table->string('target_reader', 50)->nullable()->change();
            $table->string('cover_type', 50)->nullable()->change();
            $table->unsignedInteger('estimated_pages')->nullable()->change();
            $table->unsignedInteger('quantity')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn('service_type');
        });
    }
};
