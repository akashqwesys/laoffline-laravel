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
        Schema::create('inward_order_details', function (Blueprint $table) {
            $table->id();
            $table->integer('inward_id')->default(0);
            $table->string('order_for', 32)->nullable();
            $table->integer('packing_id')->default(0);
            $table->date('packing_date')->nullable();
            $table->integer('lump')->default(0);
            $table->integer('cut')->default(0);
            $table->integer('top')->default(0);
            $table->integer('bottom')->default(0);
            $table->integer('duppatta')->default(0);
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
        Schema::dropIfExists('inward_order_details');
    }
};
