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

            //foreign keys
            $table->foreignIdFor(\App\Models\Customer::class);
            $table->foreignIdFor(\App\Models\User::class,'created_by');

        });

        Schema::create('positions', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->string('product_name');
            $table->float('product_price');
            $table->text('product_description')->nullable();
            $table->integer('quantity');


            //foreign keys
            $table->foreignIdFor(\App\Models\SPProduct::class);
            $table->foreignIdFor(\App\Models\Bill::class);
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
