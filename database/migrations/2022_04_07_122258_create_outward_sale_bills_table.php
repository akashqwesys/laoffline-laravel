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
        Schema::create('outward_sale_bills', function (Blueprint $table) {
            $table->id();
            $table->integer('outward_id')->default(0);
            $table->integer('sale_bill_id')->default(0);
            $table->integer('payment_id')->default(0);
            $table->integer('commission_id')->default(0);
            $table->integer('commission_invoice_id')->default(0);
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
        Schema::dropIfExists('outward_sale_bills');
    }
};
