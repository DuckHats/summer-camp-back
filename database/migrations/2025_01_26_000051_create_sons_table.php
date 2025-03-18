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
        Schema::create('sons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('dni')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('birth_date');
            $table->unsignedBigInteger('group_id');
            $table->string('profile_picture_url')->nullable();
            $table->text('profile_extra_info')->nullable();
            $table->string('gender', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sons');
    }
};
