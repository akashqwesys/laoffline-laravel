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
            $table->id('commission_invoice_id');
            $table->integer('reference_id');
            $table->integer('customer_id');
            $table->integer('supplier_id');
            $table->integer('financial_year_id');
            $table->integer('generated_by');
            $table->string('bill_no',30);
            $table->date('bill_period_to')->nullable();
            $table->date('bill_period_from')->nullable();
            $table->date('bill_date')->nullable();
            $table->string('commission_amount',32)->nullable();
            $table->string('service_tax_amount',32)->nullable();
            $table->string('service_tax',32)->nullable();
            $table->string('other_amount',32)->nullable();
            $table->string('rounded_off',32)->nullable();
            $table->integer('tds_amount');
            $table->integer('final_amount');
            $table->integer('service_tax_flag');
            $table->tinyInteger('tds_flag');
            $table->tinyInteger('tax_class');
            $table->tinyInteger('with_without_gst');
            $table->string('cgst',10);
            $table->string('cgst_amount',10);
            $table->string('sgst',10);
            $table->string('sgst_amount',10);
            $table->string('igst',10);
            $table->string('igst_amount',10);
            $table->string('commission_percent');
            $table->integer('agent_id');
            $table->integer('done_outward');
            $table->tinyInteger('commission_status');
            $table->double('right_of_amount');
            $table->text('right_of_remark')->nullable();
            $table->tinyInteger('is_deleted');
            $table->dateTime('date_added');
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
