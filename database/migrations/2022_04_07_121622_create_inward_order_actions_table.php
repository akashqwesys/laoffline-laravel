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
        Schema::create('inward_order_actions', function (Blueprint $table) {
            $table->id();
            $table->integer('inward_order_id')->default(0);
            $table->integer('action_flag')->default(0);
            $table->integer('inward_id')->default(0);
            $table->integer('order_for')->default(0);
            $table->integer('product_or_fabric_id')->default(0);
            $table->integer('sub_product_id')->default(0);
            $table->string('shade_no')->nullable();
            $table->integer('qty')->default(0);
            $table->integer('rate')->default(0);
            $table->integer('discount')->default(0);
            $table->integer('sale_bill_flag')->default(0);
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
        Schema::dropIfExists('inward_order_actions');
    }
};
