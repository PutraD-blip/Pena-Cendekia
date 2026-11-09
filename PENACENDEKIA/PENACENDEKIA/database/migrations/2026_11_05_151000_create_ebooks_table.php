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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('author');
            $table->string('editor')->nullable();
            $table->string('publisher')->default('PT. Pena Cendekia Pustaka');
            $table->string('category')->nullable();
            $table->string('isbn', 30)->nullable();
            $table->string('edition', 50)->nullable();
            $table->unsignedSmallInteger('publication_year')->nullable();
            $table->unsignedInteger('pages')->nullable();
            $table->string('language', 50)->default('Indonesia');
            $table->text('synopsis')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path');
            $table->string('file_format', 10)->default('pdf');
            $table->string('file_size', 50)->nullable();
            $table->string('sample_file_path')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->boolean('is_free')->default(true);
            $table->string('access_type', 20)->default('public'); // public, registered, restricted
            $table->unsignedBigInteger('download_count')->default(0);
            $table->unsignedBigInteger('view_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
