<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('product_id')->default('0');
            $table->integer('catalogue_price')->default('0');
            $table->integer('average_price')->default('0');
            $table->integer('wholesale_discount')->default('0');
            $table->integer('wholesale_brokerage')->default('0');
            $table->integer('retail_discount')->default('0');
            $table->integer('retail_brokerage')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_details');
    }
}
