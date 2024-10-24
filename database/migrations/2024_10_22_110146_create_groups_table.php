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
        Schema::create('groups', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->string('name');
            $table->string('description')->nullable();
        });

        Schema::create('group_work_instruction', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('work_instruction_id');

            //relations
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('work_instruction_id')->references('id')->on('work_instructions');
        });

        Schema::create('group_user', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');

            //relations
            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_user');
        Schema::dropIfExists('group_work_instruction');
        Schema::dropIfExists('groups');
    }
};
