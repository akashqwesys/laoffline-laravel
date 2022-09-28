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
        Schema::create('sample_details', function (Blueprint $table) {
            $table->integer('sample_details_id')->autoIncrement();
            $table->integer('sample_id');
            $table->integer('inward_sample_id');
            $table->integer('inward_id');
            $table->string('name');
            $table->string('image');
            $table->integer('price');
            $table->integer('qty');
            $table->integer('new_qty');
            $table->integer('remaining_qty');
            $table->integer('meters');
            $table->integer('new_meters');
            $table->integer('remaining_meters');
            $table->integer('is_deleted');
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
        Schema::dropIfExists('sample_details');
    }
};
