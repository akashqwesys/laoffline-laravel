<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsReturnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_returns', function (Blueprint $table) {
            $table->id();
            $table->integer('goods_return_id');
            $table->integer('p_increment_id');
            $table->integer('iuid');
            $table->integer('reference_id');
            $table->integer('financial_year_id');
            $table->integer('generated_by');
            $table->integer('sale_bill_id');
            $table->integer('sale_bill_for');
            $table->integer('company_id');
            $table->integer('supplier_id');
            $table->string('supp_invoice_no',32)->nullable();
            $table->text('multiple_attachment')->nullable();
            $table->integer('amount');
            $table->integer('adjust_amount');
            $table->integer('goods_return');
            $table->integer('tot_peices');
            $table->integer('tot_meters');
            $table->integer('tot_amount');
            $table->integer('is_deleted');
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
        Schema::dropIfExists('goods_return');
    }
}
