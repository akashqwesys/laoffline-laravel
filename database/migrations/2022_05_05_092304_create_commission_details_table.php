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
        Schema::create('commission_details', function (Blueprint $table) {
            $table->id();
            $table->integer('c_increment_id')->defalut('0');
            $table->integer('commission_id')->default('0');
            $table->integer('commission_invoice_id')->defalut('0');
            $table->integer('financial_year_id')->defalut('0');
            $table->integer('payment_id')->default('0');
            $table->date('bill_date')->nullable();
            $table->integer('deposite_bank')->defalut('0');
            $table->date('cheque_date')->nullable();
            $table->string('cheque_dd_no')->nullable();
            $table->integer('cheque_dd_bank')->defalut('0');
            $table->string('percentage')->nullable();
            $table->integer('bill_amount')->defalut('0');
            $table->integer('received_amount')->defalut('0');
            $table->integer('service_tax')->defalut('0');
            $table->integer('tds')->defalut('0');
            $table->integer('net_received_amount')->defalut('0');
            $table->integer('received_commission_amount')->defalut('0');
            $table->date('commission_date')->nullable();
            $table->integer('commission_account')->defalut('0');
            $table->integer('status')->defalut('1');
            $table->string('remark')->nullable();
            $table->integer('is_deleted')->defalut('0');
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
        Schema::dropIfExists('commission_details');
    }
};
