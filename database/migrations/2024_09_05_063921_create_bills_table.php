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
        Schema::create('bills', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->date('date');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('created_by');

            //relations
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->foreign('created_by')->references('id')->on('users');

        });

        Schema::create('sp_products', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->string('name');
            $table->text('description')->nullable();
            $table->float('price');
        });

        Schema::create('positions', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->unsignedBigInteger('s_p_product_id');
            $table->unsignedBigInteger('bill_id');
            $table->string('product_name');
            $table->float('product_price');
            $table->text('product_description')->nullable();
            $table->integer('quantity');


            //foreign keys
            $table->foreign('s_p_product_id')->references('id')->on('sp_products');
            $table->foreign('bill_id')->references('id')->on('bills');
        });




    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('sp_products');
    }
};
