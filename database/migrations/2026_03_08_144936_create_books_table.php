<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('publisher');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('isbn')->unique();
            $table->integer('pages');
            $table->text('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->integer('stock')->default(0);
            $table->integer('available')->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
