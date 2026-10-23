<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('student_portal_lock', function (Blueprint $t) {
            $t->unsignedBigInteger('idstudent')->primary();
            $t->string('holder_type', 20)->nullable();   // STUDENT / ADMIN / SPECIALIST / COUNSELOR
            $t->unsignedBigInteger('holder_id')->nullable();
            $t->string('holder_name', 150)->nullable();
            $t->string('session_hash', 40)->nullable();
            $t->dateTime('last_heartbeat_at')->nullable();
            $t->dateTime('last_activity_at')->nullable();
        });

        Schema::create('student_portal_attempt', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('idstudent')->index();
            $t->string('who_type', 20);
            $t->string('who_name', 150)->nullable();
            $t->dateTime('created_at');
            $t->dateTime('seen_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('student_portal_attempt');
        Schema::dropIfExists('student_portal_lock');
    }
};
