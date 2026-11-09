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
        Schema::create('featured_books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('author');
            $table->string('editor')->nullable();
            $table->string('category')->nullable();
            $table->string('isbn')->nullable();
            $table->string('year', 10)->nullable();
            $table->string('pages', 50)->nullable();
            $table->string('size', 50)->nullable();
            $table->text('synopsis')->nullable();
            $table->string('cover_image')->nullable();
            
            // Kolom Testimoni Penulis Terkait Buku
            $table->text('testimonial_quote')->nullable();
            $table->string('testimonial_author')->nullable();
            $table->string('testimonial_role')->nullable();
            $table->string('testimonial_photo')->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('featured_books');
    }
};
