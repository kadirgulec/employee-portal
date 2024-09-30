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
        Schema::create('departments', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->string('name');
            $table->text('description')->nullable();
        });

        //Create the pivot Table between User-Department (m-n) relationship.
        Schema::create('department_user', function (Blueprint $table) {
            //Meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->boolean('leader')->default(false);
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('user_id');

            //relations
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('department_id')->references('id')->on('departments');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
        Schema::dropIfExists('department_user');
    }
};
