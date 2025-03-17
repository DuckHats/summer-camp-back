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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('status')->default(1);
            $table->string('password')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->bigInteger('phone')->nullable();
            $table->timestamp('phone_verified')->nullable();
            $table->string('profile_picture_url')->nullable();
            $table->string('profile_short_description')->nullable();
            $table->text('profile_description')->nullable();
            $table->string('gender', 50)->nullable();
            $table->string('location')->nullable();
            $table->string('cv_path', 255)->nullable();
            $table->string('portfolio_url', 255)->nullable();
            $table->integer('level')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
