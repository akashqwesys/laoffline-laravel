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
        Schema::create('sale_bill_items', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_bill_id')->default(0);
            $table->integer('financial_year_id')->default(0);
            $table->integer('product_or_fabric_id')->default(0);
            $table->integer('sub_product_id')->default(0)->comment('Main ID of Product Table');
            $table->integer('pieces')->default(0);
            $table->integer('cut')->nullable()->default(0);
            $table->double('meters')->nullable()->default(0);
            $table->integer('pieces_meters')->nullable()->default(0);
            $table->double('rate')->default(0);
            $table->string('hsn_code')->nullable();
            $table->double('discount')->default(0);
            $table->double('discount_amount')->default(0);
            $table->double('cgst')->default(0);
            $table->double('cgst_amount')->default(0);
            $table->double('sgst')->default(0);
            $table->double('sgst_amount')->default(0);
            $table->double('igst')->default(0);
            $table->double('igst_amount')->default(0);
            $table->double('amount')->default(0);
            $table->tinyInteger('main_or_sub')->default(0);
            $table->integer('inward_order_action_id')->default(0);
            $table->tinyInteger('is_deleted')->default(0);
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
        Schema::dropIfExists('sale_bill_items');
    }
};
