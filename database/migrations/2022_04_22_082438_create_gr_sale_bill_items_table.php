<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gr_sale_bill_items', function (Blueprint $table) {
            $table->id();
            $table->integer('gr_sale_bill_item_id')->default('0');
            $table->integer('gr_increment_id')->default('0');
            $table->integer('goods_return_id')->default('0');
            $table->integer('product_or_fabric_id')->default('0');
            $table->integer('peices')->default('0');
            $table->integer('meters')->default('0');
            $table->integer('peices_meters')->default('0');
            $table->integer('rate')->default('0');
            $table->double('discount_per')->default('0');
            $table->double('discount_amt')->default('0');
            $table->double('cgst_per')->default('0');
            $table->double('cgst_amt')->default('0');
            $table->double('sgst_per')->default('0');
            $table->double('sgst_amt')->default('0');
            $table->double('igst_per')->default('0');
            $table->double('igst_amt')->default('0');
            $table->integer('amount')->default('0');
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('gr_sale_bill_items');
    }
};
