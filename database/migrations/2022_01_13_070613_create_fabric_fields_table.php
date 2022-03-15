<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFabricFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fabric_fields', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('product_fabric_id')->default('0');
            $table->string('name')->nullable();
            $table->integer('sort_order')->default('0');
            $table->integer('is_delete')->default('0');
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
        Schema::dropIfExists('fabric_fields');
    }
}
