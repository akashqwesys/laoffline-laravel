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
        Schema::create('invoice_payment_details', function (Blueprint $table) {
            $table->id();
            $table->integer('commission_invoice_id')->default(0);
            $table->integer('financial_year_id')->default(0);
            $table->integer('payment_id')->default(0);
            $table->date('payment_date')->nullable();
            $table->integer('company_id')->default(0);
            $table->double('received_amount')->default(0);
            $table->double('total_amount')->default(0);
            $table->tinyInteger('flag')->default(1)->comment('1-supplier and 2-customer');
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
        Schema::dropIfExists('invoice_payment_details');
    }
};
