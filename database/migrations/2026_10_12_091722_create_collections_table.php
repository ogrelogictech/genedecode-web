<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->string('uscreen_id')->unique();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('author_ids')->nullable();
            $table->json('subscription_plan_ids')->nullable();
            $table->json('category_ids')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->boolean('is_free')->default(false);
            $table->timestamps();
        });

        Schema::create('collection_video', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_id')->constrained()->cascadeOnDelete();
            $table->foreignId('video_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['collection_id', 'video_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collection_video');
        Schema::dropIfExists('collections');
    }
};