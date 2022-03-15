<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->integer('commission_id');
            $table->integer('iuid');
            $table->integer('reference_id');
            $table->integer('financial_year_id');
            $table->string('attachments')->nullable();
            $table->integer('payment_id');
            $table->integer('customer_id');
            $table->integer('supplier_id');
            $table->integer('bill_no');
            $table->date('bill_date')->nullable();
            $table->integer('deposite_bank');
            $table->date('cheque_date')->nullable();
            $table->integer('cheque_dd_no');
            $table->integer('cheque_dd_bank');
            $table->integer('bill_amount');
            $table->integer('received_amount');
            $table->integer('tds');
            $table->integer('net_received_amount');
            $table->integer('received_commission_amount');
            $table->date('commission_date')->nullable();
            $table->string('commission_account',50)->nullable();
            $table->string('remark')->nullable();
            $table->integer('required_followup');
            $table->string('commission_reciept_mode',32)->nullable();
            $table->date('commission_payment_date')->nullable();
            $table->integer('commission_deposite_bank');
            $table->date('commission_cheque_date')->nullable();
            $table->integer('commission_cheque_dd_no');
            $table->integer('commission_cheque_dd_bank');
            $table->integer('commission_payment_amount');
            $table->integer('done_outward');
            $table->string('service_tax_val',32)->nullable();
            $table->integer('normal_amt_flag');
            $table->tinyInteger('is_invoice');
            $table->dateTime('date_added');
            $table->integer('is_deleted');
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
        Schema::dropIfExists('commission');
    }
}
