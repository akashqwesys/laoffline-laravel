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
        Schema::create('sale_bill_transports', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_bill_id')->default(0);
            $table->integer('financial_year_id')->default(0);
            $table->integer('transport_id')->default(0);
            $table->string('station')->nullable();
            $table->string('lr_mr_no')->nullable();
            $table->date('date')->nullable();
            $table->string('cases')->nullable();
            $table->integer('weight')->default(0);
            $table->integer('freight')->default(0);
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
        Schema::dropIfExists('sale_bill_transports');
    }
};
