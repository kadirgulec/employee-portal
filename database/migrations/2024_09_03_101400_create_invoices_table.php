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
        Schema::create('invoices', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->date('date');

            //foreign keys
            $table->foreignIdFor(\App\Models\Customer::class);

        });

        Schema::create('positions', function (Blueprint $table) {
            //meta
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            //fields
            $table->integer('quantity');

            //foreign keys
            $table->foreignIdFor(\App\Models\SPProduct::class);
            $table->foreignIdFor(\App\Models\Invoice::class);
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
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('sp_products');
    }
};
