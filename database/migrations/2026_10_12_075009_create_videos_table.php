<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();

            $table->string('uscreen_id')->unique();
            $table->string('bunny_guid')->nullable()->unique();

            $table->string('title');
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->unsignedInteger('duration_seconds')->default(0);
            $table->dateTime('uploaded_at')->nullable();
            $table->string('author')->nullable();

            $table->string('thumbnail_url')->nullable();
            $table->string('thumbnail_path')->nullable();

            $table->string('section')->nullable();
            $table->string('access')->default('members');
            $table->string('status')->default('pending');
            $table->text('import_note')->nullable();

            $table->timestamps();

            $table->index('section');
            $table->index('access');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};