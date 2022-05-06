<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommissionInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commission_invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('reference_id')->default(0);
            $table->integer('customer_id')->default(0);
            $table->integer('supplier_id')->default(0);
            $table->integer('financial_year_id')->default(0);
            $table->integer('generated_by')->default(0);
            $table->string('bill_no', 30);
            $table->date('bill_period_to')->nullable();
            $table->date('bill_period_from')->nullable();
            $table->date('bill_date')->nullable();
            $table->double('commission_amount')->default(0);
            $table->double('service_tax_amount')->default(0);
            $table->double('service_tax')->default(0);
            $table->double('other_amount')->default(0);
            $table->double('rounded_off')->default(0);
            $table->double('tds_amount')->default(0);
            $table->double('final_amount')->default(0);
            $table->tinyInteger('service_tax_flag')->default(0);
            $table->tinyInteger('tds_flag')->default(1);
            $table->tinyInteger('tax_class')->default(1);
            $table->tinyInteger('with_without_gst')->default(1)->comment('1=with gst, 2=without gst');
            $table->double('cgst')->default(0);
            $table->double('cgst_amount')->default(0);
            $table->double('sgst')->default(0);
            $table->double('sgst_amount')->default(0);
            $table->double('igst')->default(0);
            $table->double('igst_amount')->default(0);
            $table->double('commission_percent')->default(2);
            $table->integer('agent_id')->default(0);
            $table->double('total_payment_received_amount')->default(0);
            $table->integer('done_outward')->default(0)->comment('0-pending, 1-complete');
            $table->tinyInteger('commission_status')->default(0)->comment('0-none, 1-complete, 2-pending');
            $table->double('right_of_amount')->default(0);
            $table->text('right_of_remark')->nullable();
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
        Schema::dropIfExists('commission_invoice');
    }
}
