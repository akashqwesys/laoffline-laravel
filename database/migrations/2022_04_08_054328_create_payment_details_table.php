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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_details_id');
            $table->integer('payment_id')->default('0');
            $table->integer('p_increment_id')->default('0');
            $table->integer('financial_year_id')->default('0');
            $table->integer('sr_no')->default('0');
            $table->string('supplier_invoice_no')->nullable();
            $table->integer('amount')->default('0');
            $table->integer('adjust_amount')->default('0');
            $table->string('status')->nullable();
            $table->float('discount')->default('0');
            $table->integer('discount_amount')->default('0');
            $table->float('vatav')->default('0');
            $table->integer('agent_commission')->default('0');
            $table->integer('bank_commission')->default('0');
            $table->integer('claim')->default('0');
            $table->integer('goods_return')->default('0');
            $table->integer('short')->default('0');
            $table->integer('interest')->default('0');
            $table->string('rate_difference')->nullable();
            $table->string('remark')->nullable();
            $table->integer('flag_sale_bill_sr_no')->default('0');
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
        Schema::dropIfExists('payment_details');
    }
};
