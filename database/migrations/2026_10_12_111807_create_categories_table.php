<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('uscreen_id')->unique();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->timestamps();
        });

        Schema::create('category_collection', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['category_id', 'collection_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_collection');
        Schema::dropIfExists('categories');
    }
};