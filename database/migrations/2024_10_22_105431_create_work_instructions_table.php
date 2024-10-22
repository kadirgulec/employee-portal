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
        Schema::create('work_instructions', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('document')->nullable();
        });

        Schema::create('user_work_instruction', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->unsignedBigInteger('work_instruction_id');
            $table->unsignedBigInteger('user_id');
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('last_reminder_email_at')->nullable();

            //relations
            $table->foreign('work_instruction_id')->references('id')->on('work_instructions');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_work_instruction');
        Schema::dropIfExists('work_instructions');
    }
};
