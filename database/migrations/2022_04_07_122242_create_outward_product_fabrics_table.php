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
        Schema::create('outward_product_fabrics', function (Blueprint $table) {
            $table->id();
            $table->integer('outward_id')->default(0);
            $table->integer('product_or_fabric_id')->default(0);
            $table->tinyInteger('product_or_fabric_flag')->default(0);
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
        Schema::dropIfExists('outward_product_fabrics');
    }
};
