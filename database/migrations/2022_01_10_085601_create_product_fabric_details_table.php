<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFabricDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_fabric_details', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('product_id');
            $table->string('saree_fabric')->nullable();
            $table->float('saree_cut')->default('0');
            $table->string('blouse_fabric')->nullable();
            $table->float('blouse_cut')->default('0');
            $table->string('top_fabric')->nullable();
            $table->float('top_cut')->default('0');
            $table->string('bottom_fabric')->nullable();
            $table->float('bottom_cut')->default('0');
            $table->string('dupatta_fabric')->nullable();
            $table->float('dupatta_cut')->default('0');
            $table->string('inner_fabric')->nullable();
            $table->float('inner_cut')->default('0');
            $table->string('sleeves_fabric')->nullable();
            $table->float('sleeves_cut')->default('0');
            $table->string('choli_fabric')->nullable();
            $table->float('choli_cut')->default('0');
            $table->string('lehenga_fabric')->nullable();
            $table->float('lehenga_cut')->default('0');
            $table->string('lining_fabric')->nullable();
            $table->float('lining_cut')->default('0');
            $table->string('gown_fabric')->nullable();
            $table->float('gown_cut')->default('0');
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
        Schema::dropIfExists('product_fabric_details');
    }
}
