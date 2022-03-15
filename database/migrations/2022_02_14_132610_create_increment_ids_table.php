<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncrementIdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('increment_ids', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('financial_year_id')->default('0');
            $table->integer('iuid')->default('0');
            $table->integer('ouid')->default('0');
            $table->integer('reference_id')->default('0');
            $table->integer('sale_bill_id')->default('0');
            $table->integer('payment_id')->default('0');
            $table->integer('commission_id')->default('0');
            $table->integer('goods_return_id')->default('0');
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
        Schema::dropIfExists('increment_ids');
    }
}
