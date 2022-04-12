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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_id');
            $table->integer('iuid');
            $table->integer('reference_id');
            $table->string('attachments')->nullable();
            $table->string('letter_attachment')->nullable();;
            $table->integer('financial_year_id');
            $table->string('reciept_mode')->nullable();;
            $table->string('slip_no')->nullable();;
            $table->date('date')->nullable();
            $table->integer('deposite_bank')->default('0');
            $table->date('cheque_date')->nullable();
            $table->string('cheque_dd_no')->nullable();
            $table->integer('cheque_dd_bank')->default('0');
            $table->integer('receipt_from')->default('0');
            $table->string('trns')->nullable();
            $table->integer('supplier_id')->default('0');
            $table->integer('customer_id')->default('0');
            $table->integer('receipt_amount')->default('0');
            $table->integer('total_amount')->default('0');
            $table->integer('tot_discount')->default('0');
            $table->integer('tot_vatav')->default('0');
            $table->integer('tot_agent_commission')->default('0');
            $table->integer('tot_bank_cpmmission')->default('0');
            $table->integer('tot_claim')->default('0');
            $table->integer('tot_good_returns')->default('0');
            $table->integer('tot_short')->default('0');
            $table->integer('tot_interest')->default('0');
            $table->integer('tot_rate_difference')->default('0');
            $table->integer('payment_ok_or_not')->default('0');
            $table->integer('old_commission_status')->default('0');
            $table->integer('customer_commission_status')->default('0');
            $table->double('right_of_amount')->default('0');
            $table->string('right_of_remark')->nullable();
            $table->integer('is_deleted')->default('0');
            $table->integer('done_outward')->default('0');
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
        Schema::dropIfExists('payments');
    }
};
