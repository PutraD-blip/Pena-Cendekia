<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('book_reviews', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id')->nullable()->index();
            $table->string('book_title');
            $table->string('reviewer_name', 100);
            $table->unsignedTinyInteger('rating');
            $table->string('review_text', 500);
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_reviews');
    }
};
