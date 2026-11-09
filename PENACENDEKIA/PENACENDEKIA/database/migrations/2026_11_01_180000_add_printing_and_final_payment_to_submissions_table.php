<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE submissions MODIFY COLUMN status ENUM('pending_review','revision_required','waiting_payment','payment_verification','in_design','ready_to_print','printing','waiting_final_payment','completed','cancelled') NOT NULL DEFAULT 'pending_review'");
        }

        Schema::table('submissions', function (Blueprint $table) {
            $table->timestamp('printing_started_at')->nullable()->after('status');
            $table->timestamp('printed_at')->nullable()->after('printing_started_at');
            $table->foreignId('printed_by')->nullable()->after('printed_at')->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropForeign(['printed_by']);
            $table->dropColumn(['printed_by', 'printed_at', 'printing_started_at']);
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE submissions MODIFY COLUMN status ENUM('pending_review','revision_required','waiting_payment','payment_verification','in_design','ready_to_print','printing','completed','cancelled') NOT NULL DEFAULT 'pending_review'");
        }
    }
};
