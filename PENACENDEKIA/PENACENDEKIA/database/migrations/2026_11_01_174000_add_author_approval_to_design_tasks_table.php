<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('design_tasks', function (Blueprint $table) {
            $table->boolean('author_approved')->nullable()->after('admin_notes');
            $table->text('author_notes')->nullable()->after('author_approved');
            $table->timestamp('author_approved_at')->nullable()->after('author_notes');
        });
    }

    public function down(): void
    {
        Schema::table('design_tasks', function (Blueprint $table) {
            $table->dropColumn(['author_approved', 'author_notes', 'author_approved_at']);
        });
    }
};
