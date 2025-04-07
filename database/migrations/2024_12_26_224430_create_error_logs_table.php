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
        Schema::create('error_logs', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->text('error');
            $table->json('parameters')->nullable();
            $table->string('path');
            $table->json('headers')->nullable();
            $table->string('ip')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->nullable();
            $table->integer('status_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('error_logs');
    }
};
