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
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('code', 20)->unique();
            
            // Book Details
            $table->string('title', 200);
            $table->string('pen_name', 100)->nullable();
            $table->string('editor_name', 100);
            $table->foreignId('category_id')->constrained('book_categories')->onDelete('restrict');
            $table->enum('target_reader', ['children', 'teen', 'adult', 'general']);
            $table->text('synopsis');
            
            // Files
            $table->string('manuscript_file');
            $table->string('layout_file')->nullable();
            
            // Printing Specs
            $table->unsignedInteger('estimated_pages');
            $table->unsignedInteger('quantity');
            $table->enum('cover_type', ['soft', 'hard']);
            $table->text('notes')->nullable();
            
            // Pricing (set by Admin)
            $table->decimal('total_price', 12, 2)->nullable();
            $table->decimal('dp_amount', 12, 2)->nullable();
            $table->decimal('final_amount', 12, 2)->nullable();
            
            // Status Workflow
            $table->enum('status', [
                'pending_review',
                'revision_required',
                'waiting_payment',
                'payment_verification',
                'in_design',
                'ready_to_print',
                'printing',
                'completed',
                'cancelled',
            ])->default('pending_review');
            
            // Review
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();
            
            // Dates
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
