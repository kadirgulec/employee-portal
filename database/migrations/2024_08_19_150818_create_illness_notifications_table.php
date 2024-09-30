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
        Schema::create('illness_notifications', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('reported_to');
            $table->date('illness_notification_at')->nullable()->default(today());
            $table->date('doctor_visited_at')->nullable();
            $table->dateTime('report_time');
            $table->boolean('entgFG')->default(1);
            $table->string('incapacity_reason')->nullable();
            $table->string('doctor_certificate')->nullable();
            $table->text('note')->nullable();
            $table->date('sent_at')->nullable();
            $table->string('sent_to')->nullable();

            //relations
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('reported_to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('illness_notifications');
    }
};
